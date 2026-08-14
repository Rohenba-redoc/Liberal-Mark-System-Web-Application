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


// Function to fetch the expo_push_token for the student
function fetchExpoToken($conn, $student_id) {
    $stmt = $conn->prepare("SELECT expo_push_token FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row ? $row['expo_push_token'] : null;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (isset($data['student_id'], $data['mark_score'], $data['mark_id'])) {
        $student_id = $data['student_id'];
        $mark_score = $data['mark_score'];
        $mark_id = $data['mark_id'];

        // Prepare the SQL query for updating the mark
        $updateStudentQuery = "UPDATE unit_test_marks 
                               SET mark_score = ?, modified_date = NOW()
                               WHERE mark_id = ?";

        // Prepare statement and execute
        if ($stmt1 = $conn->prepare($updateStudentQuery)) {
            $stmt1->bind_param("si", $mark_score, $mark_id);
            if ($stmt1->execute()) {
                $stmt1->close();

                // Fetch the expo_push_token for the student
                $expoPushToken = fetchExpoToken($conn, $student_id);

                // Send notification if token is available
                if ($expoPushToken) {
                    $keyFilePath = 'liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json'; // Path to Firebase service account key
                    $accessToken = getAccessToken($keyFilePath);

                    $title = "Mark Updated";
                    $body = "Your mark for the recent test has been updated to " . $mark_score . ".";

                    $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);

                    // Return the response including the notification status
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Mark details updated successfully',
                        'notification' => $notificationResponse,
                    ]);
                } else {
                    echo json_encode(['status' => 'success', 'message' => 'Mark updated, but no notification sent (token not found)']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update the mark']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the query']);
        }
    } else {
        // Return error response if required fields are missing
        echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
    }
} else {
    // Return error response for invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
