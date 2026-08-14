<?php
include '../../includes/config.php';  // Database connection
require '../../_api/vendor/autoload.php'; // For Google and Guzzle clients
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => 'An error occurred.'];

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['course_code']) || empty($_POST['semester_id']) || empty($_POST['title']) || empty($_POST['body']) || empty($_POST['teacher_id']) || !isset($_POST['subject_code']) || empty($_POST['department_id'])) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }

    // Sanitize inputs
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $semester_id = mysqli_real_escape_string($conn, $_POST['semester_id']);
    $department = mysqli_real_escape_string($conn, $_POST['department_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['body']);
    $add_by = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $subject_code = mysqli_real_escape_string($conn, $_POST['subject_code']);

    

    $errors = [];
    $response['notifications'] = []; // Initialize notifications array


        // Insert into the 'teacher_notice' table
        $insert_query = "INSERT INTO teacher_notice (course_code, semester_id, subject_code, title, message, created_at, add_by, department_id) 
                         VALUES ('$course_code', '$semester_id', '$subject_code', '$title', '$message', NOW(), '$add_by', '$department')";

        if (!mysqli_query($conn, $insert_query)) {
            $errors[] = "Error adding notice for subject code $subject_code: " . mysqli_error($conn);
        }

        // Fetch the students based on course_code, semester_id, and subject_code
        $selectStudentsQuery = "SELECT s.student_id FROM students s
                                INNER JOIN student_enroll se ON s.student_id = se.student_id
                                INNER JOIN students_course_combination scc ON s.student_id = scc.student_id
                                WHERE se.course_code = ? AND se.semester_id = ? AND se.status = 'Incomplete' AND scc.subject_code = ? AND scc.semester_id = ?";

        $stmt2 = $conn->prepare($selectStudentsQuery);
        $stmt2->bind_param("iisi", $course_code, $semester_id, $subject_code, $semester_id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        
        // Send notifications to each student
        $keyFilePath = '../../_api/liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json';
        $accessToken = getAccessToken($keyFilePath);

        while ($student = $result->fetch_assoc()) {
            // Fetch expo_push_token for the student
            $expoPushToken = fetchExpoToken($conn, $student['student_id']);
            
            // If push token exists, send notification
            if ($expoPushToken) {
                $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $message);

                // Log success or failure of notification
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
    

    if (empty($errors)) {
        $response['success'] = true;
        $response['message'] = "Notice inserted successfully and notifications sent.";
    } else {
        $response['success'] = false;
        $response['message'] = implode(' | ', $errors);
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();
echo json_encode($response);

// Helper Functions

// Function to get Firebase access token
function getAccessToken($keyFilePath) {
    $client = new GoogleClient();
    $client->setAuthConfig($keyFilePath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->useApplicationDefaultCredentials();
    
    $token = $client->fetchAccessTokenWithAssertion();
    return $token['access_token'];
}

// Function to fetch Expo token for a student
function fetchExpoToken($conn, $student_id) {
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

// Function to send notification via FCM
function sendNotification($accessToken, $expoPushToken, $title, $body) {
    $client = new GuzzleClient();
    $cleanTitle = strip_tags($title);
    $cleanBody = strip_tags($body);
    try {
        $response = $client->post('https://fcm.googleapis.com/v1/projects/liberalcollege-88dd5/messages:send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'token' => $expoPushToken,
                    'notification' => [
                        'title' => $cleanTitle,
                        'body' => $cleanBody,
                    ],
                ],
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return ['status' => 'success', 'message' => 'Notification sent'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to send notification'];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()];
    }
}
?>
