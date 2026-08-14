<?php
include '../includes/config.php'; // Include your database connection file
require '../_api/vendor/autoload.php'; 
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

$response = array('success' => false, 'error' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $check = isset($_POST['check']) ? $_POST['check'] : null;

    $course_code = isset($_POST['course_code']) ? $_POST['course_code'] : null;
    $semester_id = isset($_POST['semester_id']) ? intval($_POST['semester_id']) : null;
    $subjects = isset($_POST['selected_subjects']) ? $_POST['selected_subjects'] : array();
    $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null;

    if (!empty($title) && !empty($body)) {
        // Insert the notice
        $sql = "INSERT INTO admin_notice (type, title, message, created_at) VALUES (?, ?, ?, NOW())";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sss', $type, $title, $body);

            if ($stmt->execute()) {
                $admin_notice_id = $stmt->insert_id;
                $response['success'] = true;

                // Get Access Token once
                $keyFilePath = '../_api/liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json';
                $accessToken = getAccessToken($keyFilePath);

                if ($type === 'all') {
                    // Step 2: Send Notifications to Students
                    $selectStudentsQuery = "SELECT student_id FROM students";
                    $stmt2 = $conn->prepare($selectStudentsQuery);
                    $stmt2->execute();
                    $result = $stmt2->get_result();

                    while ($student = $result->fetch_assoc()) {
                        $expoPushToken = fetchStudentExpoToken($conn, $student['student_id']);
                        if ($expoPushToken) {
                            $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                            $response['notifications'][] = [
                                'student_id' => $student['student_id'],
                                'status' => $notificationResponse['status'],
                                'message' => $notificationResponse['message']
                            ];
                        } else {
                            $response['notifications'][] = [
                                'student_id' => $student['student_id'],
                                'status' => 'error',
                                'message' => 'No push token found'
                            ];
                        }
                    }
                    $stmt2->close();

                    // Step 3: Send Notifications to Teachers
                    $selectTeacherQuery = "SELECT teacher_id FROM teacher";
                    $stmt3 = $conn->prepare($selectTeacherQuery);
                    $stmt3->execute();
                    $result = $stmt3->get_result();

                    while ($teacher = $result->fetch_assoc()) {
                        $expoPushToken = fetchTeacherExpoToken($conn, $teacher['teacher_id']);
                        if ($expoPushToken) {
                            $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                            $response['notifications'][] = [
                                'teacher_id' => $teacher['teacher_id'],
                                'status' => $notificationResponse['status'],
                                'message' => $notificationResponse['message']
                            ];
                        } else {
                            $response['notifications'][] = [
                                'teacher_id' => $teacher['teacher_id'],
                                'status' => 'error',
                                'message' => 'No push token found'
                            ];
                        }
                    }
                    $stmt3->close();
                }
            } else {
                $response['error'] = 'Failed to add notice: ' . $stmt->error;
                $response['success'] = false;
            }

            $stmt->close();
        } else {
            $response['error'] = 'Failed to prepare the SQL statement: ' . $conn->error;
            $response['success'] = false;
        }

        // Additional insertions if type is 'filter'
        if ($response['success'] && $type === 'filter') {
            if ($check === 'on') {
                $subjects_array = explode(',', $subjects);
                
                // Insert notice types for each subject
                foreach ($subjects_array as $subject_code) {
                    $sql = "INSERT INTO admin_notice_type (course_code, department_id, subject_code, admin_notice_id, semester_id)
                            VALUES (?, ?, ?, ?, ?)";
            
                    if ($stmt = $conn->prepare($sql)) {
                        if (empty($course_code) || empty($department_id) || empty($subject_code) || empty($admin_notice_id) || empty($semester_id)) {
                            $response['error'] = "One of the required fields is empty.";
                            break;
                        }
                        $stmt->bind_param('sssii', $course_code, $department_id, $subject_code, $admin_notice_id, $semester_id);
                        if (!$stmt->execute()) {
                            $response['error'] = 'Failed to add notice type for subject ' . $subject_code . ': ' . $stmt->error;
                            $response['success'] = false;
                            break;
                        }
                    } else {
                        $response['error'] = 'Failed to prepare the SQL statement: ' . $conn->error;
                        $response['success'] = false;
                        break;
                    }
                }
            
                // Prepare the IN clause for subject_code
                $placeholders = implode(',', array_fill(0, count($subjects_array), '?'));
                
                // Count number of subjects the student is enrolled in
                $selectStudentQuery = "SELECT s.student_id, COUNT(DISTINCT scc.subject_code) AS subjects_count
                                        FROM students s
                                        INNER JOIN student_enroll se ON s.student_id = se.student_id
                                        INNER JOIN students_course_combination scc ON s.student_id = scc.student_id
                                        WHERE se.course_code = ? AND se.semester_id = ? AND se.status = 'Incomplete' 
                                        AND scc.subject_code IN ($placeholders) AND scc.semester_id = ?
                                        GROUP BY s.student_id
                                        HAVING subjects_count = ?";
                
                // Prepare the statement
                if ($stmt2 = $conn->prepare($selectStudentQuery)) {
                    // Prepare the types for bind_param
                    $types = 'si' . str_repeat('s', count($subjects_array)) . 'ii';
                    
                    // Create the array of parameters, and add the number of subjects at the end
                    $params = array_merge([$course_code, $semester_id], $subjects_array, [$semester_id, count($subjects_array)]);
            
                    // Bind the parameters to the query
                    $stmt2->bind_param($types, ...$params);
                    
                    // Execute the query
                    $stmt2->execute();
                    $result = $stmt2->get_result();
            
                    while ($student = $result->fetch_assoc()) {
                        $expoPushToken = fetchStudentExpoToken($conn, $student['student_id']);
                        if ($expoPushToken) {
                            $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                            $response['notifications'][] = [
                                'student_id' => $student['student_id'],
                                'status' => $notificationResponse['status'],
                                'message' => $notificationResponse['message']
                            ];
                        } else {
                            $response['notifications'][] = [
                                'student_id' => $student['student_id'],
                                'status' => 'error',
                                'message' => 'No push token found'
                            ];
                        }
                    }
                    $stmt2->close();
                } else {
                    $response['error'] = 'Failed to prepare the SQL statement for selecting students: ' . $conn->error;
                    $response['success'] = false;
                }
            }
            
            
             else {
                $sql = "INSERT INTO admin_notice_type (course_code, department_id, admin_notice_id, semester_id)
                VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssii', $course_code, $department_id, $admin_notice_id, $semester_id);
            if (!$stmt->execute()) {
                $response['error'] = 'Failed to add notice type: ' . $stmt->error;
                $response['success'] = false;
            } else {
                // Select students based on department_id and semester_id where status is Incomplete
                $selectStudentQuery = "SELECT s.student_id 
                                       FROM students s
                                       INNER JOIN student_enroll se ON s.student_id = se.student_id
                                       WHERE se.department_id = ? AND se.semester_id = ? AND se.course_code = ?  AND se.status = 'Incomplete'";
                
                if ($stmt2 = $conn->prepare($selectStudentQuery)) {
                    // Bind parameters for department_id and semester_id
                    $stmt2->bind_param('sii', $department_id, $semester_id, $course_code);
        
                    // Execute the query
                    $stmt2->execute();
                    $result = $stmt2->get_result();
        
                    // Loop through students and send notifications
                    while ($student = $result->fetch_assoc()) {
                        $expoPushToken = fetchStudentExpoToken($conn, $student['student_id']);
                        if ($expoPushToken) {
                            $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                            $response['notifications'][] = [
                                'student_id' => $student['student_id'],
                                'status' => $notificationResponse['status'],
                                'message' => $notificationResponse['message']
                            ];
                        } else {
                            $response['notifications'][] = [
                                'student_id' => $student['student_id'],
                                'status' => 'error',
                                'message' => 'No push token found'
                            ];
                        }
                    }
                    $stmt2->close();
                } else {
                    $response['error'] = 'Failed to prepare the SQL statement for selecting students: ' . $conn->error;
                    $response['success'] = false;
                }
            }
        } else {
            $response['error'] = 'Failed to prepare the SQL statement: ' . $conn->error;
            $response['success'] = false;
        }
        
            }
        }
    } else {
        $response['error'] = 'Title and body are required fields.';
        $response['success'] = false;
    }
} else {
    $response['error'] = 'Invalid request method.';
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();

// Function to get Firebase access token
function getAccessToken($keyFilePath) {
    $client = new GoogleClient();
    $client->setAuthConfig($keyFilePath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->useApplicationDefaultCredentials();
    $token = $client->fetchAccessTokenWithAssertion();
    return $token['access_token'];
}

// Function to fetch Expo token for students
function fetchStudentExpoToken($conn, $student_id) {
    $query = "SELECT expo_push_token FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['expo_push_token'];
    }
    return false;
}

// Function to fetch Expo token for teachers
function fetchTeacherExpoToken($conn, $teacher_id) {
    $query = "SELECT fcm_token FROM teacher WHERE teacher_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['fcm_token'];
    }
    return false;
}

// Function to send notifications
function sendNotification($accessToken, $expoPushToken, $title, $body) {
    $url = 'https://fcm.googleapis.com/v1/projects/liberalcollege-88dd5/messages:send';
    $client = new GuzzleClient();
    $cleanTitle = strip_tags($title);
    $cleanBody = strip_tags($body);
    $data = [
        'message' => [
            'token' => $expoPushToken,
            'notification' => [
                'title' => $cleanTitle,
                'body' => $cleanBody,
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => $cleanTitle,
                'body' => $cleanBody,
                'status' => 'done'
            ]
        ]
    ];

    try {
        $response = $client->post($url, [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            RequestOptions::JSON => $data
        ]);
        return ['status' => 'success', 'message' => 'Notification sent successfully'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Notification failed: ' . $e->getMessage()];
    }
}
?>
