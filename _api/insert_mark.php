<?php
header('Content-Type: application/json');
require 'vendor/autoload.php'; // For Google and Guzzle clients
require 'new.php';

use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

// Function to get access token from Firebase service account key
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

// Function to send notification via FCM
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

// Function to fetch expo_push_token for each student from the database
function fetchExpoTokens($conn, $student_ids) {
    $placeholders = rtrim(str_repeat('?,', count($student_ids)), ','); // Create placeholders for prepared statement
    $stmt = $conn->prepare("SELECT student_id, expo_push_token FROM students WHERE student_id IN ($placeholders)");
    $stmt->bind_param(str_repeat('s', count($student_ids)), ...$student_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $tokens = [];
    while ($row = $result->fetch_assoc()) {
        $tokens[$row['student_id']] = $row['expo_push_token']; // Store token based on student_id
    }

    return $tokens;
}



// Main script: Insert marks and send notifications
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marks_data = json_decode(file_get_contents('php://input'), true);

    if (isset($marks_data['marks'])) {
        $conn->begin_transaction();
        try {
            // Insert marks
            $stmt = $conn->prepare("INSERT INTO unit_test_marks (student_id, course_code, semester_id, subject_code, test_name, test_date, mark_score, full_mark, pass_mark, result_date, given_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $student_ids = [];
            foreach ($marks_data['marks'] as $mark) {
                $stmt->bind_param(
                    "siisssssssi",
                    $mark['student_id'],
                    $marks_data['course_id'],
                    $marks_data['semester'],
                    $marks_data['option'],
                    $marks_data['test_name'],
                    $marks_data['test_date'],
                    $mark['mark_score'],
                    $marks_data['full_mark'],
                    $marks_data['pass_mark'],
                    $marks_data['mark_date'],
                    $marks_data['given_by']
                );
                $stmt->execute();
                $student_ids[] = $mark['student_id']; // Collect student IDs for notification
            }

            $conn->commit();
            $response['success'] = true;
            $response['message'] = "Marks inserted successfully.";

            // Get expo_push_tokens of the students
            $tokens = fetchExpoTokens($conn, $student_ids);

            // Prepare to send notifications
            $keyFilePath = 'liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json';
            $accessToken = getAccessToken($keyFilePath);

            foreach ($marks_data['marks'] as $mark) {
                $student_id = $mark['student_id'];
                if (isset($tokens[$student_id])) {
                    $expoPushToken = $tokens[$student_id];
                    $title = "Marks Added";
                    $body = "{$marks_data['test_name']} for {$marks_data['option']} Has been Out";

                    $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                    $response['notifications'][$student_id] = $notificationResponse;
                }
            }
        } catch (Exception $e) {
            $conn->rollback();
            $response['success'] = false;
            $response['message'] = "Error: " . $e->getMessage();
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid input data.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
