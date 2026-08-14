<?php
header('Content-Type: application/json');

require 'new.php';
require 'vendor/autoload.php'; // For Google and Guzzle clients
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marks_data = json_decode(file_get_contents('php://input'), true);

    if (isset($marks_data)) {
        $conn->begin_transaction();
        try {
            // Insert the notice into the teacher_notice table
            $stmt = $conn->prepare("INSERT INTO teacher_notice (course_code,department_id, semester_id, subject_code, title, message, created_at, add_by) VALUES (?,?, ?, ?, ?, ?, NOW(), ?)");
            $stmt->bind_param(
                "iiisssi",
                $marks_data['course_id'],
                $marks_data['department'],
                $marks_data['semester'],
                $marks_data['option'],
                $marks_data['title'],
                $marks_data['body'],
                $marks_data['add_by']
            );
            $stmt->execute();
            $stmt->close();

            // Fetch the students based on course_code, semester_id, and subject_code
            $selectStudentsQuery = "SELECT s.student_id FROM students s
                INNER JOIN student_enroll se ON s.student_id = se.student_id
                INNER JOIN students_course_combination scc ON s.student_id = scc.student_id
                WHERE se.course_code = ? AND se.semester_id = ? AND se.status = 'Incomplete' AND scc.subject_code = ? AND scc.semester_id = ?";
            
            $stmt2 = $conn->prepare($selectStudentsQuery);
            $stmt2->bind_param("iisi", $marks_data['course_id'], $marks_data['semester'], $marks_data['option'], $marks_data['semester']);
            $stmt2->execute();
            $result = $stmt2->get_result();

            // Send notifications to each student
            $keyFilePath = 'liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json'; // Path to Firebase service account key
            $accessToken = getAccessToken($keyFilePath);

            while ($student = $result->fetch_assoc()) {
                // Fetch expo_push_token for the student
                $expoPushToken = fetchExpoToken($conn, $student['student_id']);
                
                // If push token exists, send notification
                if ($expoPushToken) {
                    $title = "New Notice: " . $marks_data['title'];
                    $body = $marks_data['body'];
                    $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);

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

            // Commit the transaction
            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Notice inserted successfully and notifications sent.";
        } catch (Exception $e) {
            $conn->rollback();
            $response['success'] = false;
            $response['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid input data.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Close the database connection
$conn->close();

// Return the response as JSON
echo json_encode($response);

// Functions for access token, notification sending, and token fetching (same as in your previous code)

function getAccessToken($keyFilePath) {
    $client = new GoogleClient();
    $client->setAuthConfig($keyFilePath); // Path to the Firebase service account key JSON file
    $client->setScopes(['https://www.googleapis.com/auth/firebase.messaging']);
    $token = $client->fetchAccessTokenWithAssertion();
    if (isset($token['error'])) {
        throw new Exception('Error fetching the access token: ' . $token['error']);
    }
    return $token['access_token'];
}

function sendNotification($accessToken, $expoPushToken, $title, $body) {
    $client = new GuzzleClient();
    $message = [
        'message' => [
            'token' => $expoPushToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
               
            ],
        ],
    ];

    try {
        $response = $client->post('https://fcm.googleapis.com/v1/projects/liberalcollege-88dd5/messages:send', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            RequestOptions::JSON => $message,
        ]);

        return [
            'status' => 'success',
            'response' => json_decode($response->getBody()->getContents(), true),
        ];
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body',
        ];
    }
}

function fetchExpoToken($conn, $student_id) {
    $stmt = $conn->prepare("SELECT expo_push_token FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row ? $row['expo_push_token'] : null;
}
?>
