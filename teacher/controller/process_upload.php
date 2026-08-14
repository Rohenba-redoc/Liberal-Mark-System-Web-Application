<?php
include '../../includes/config.php';
require '../../vendor/autoload.php'; // Path to PhpSpreadsheet library
require '../../_api/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // UI fields
    $subject_code = $_POST['subject_code'];
    $semester_id = $_POST['semester_id'];
    $course_code = $_POST['course_code'];
    $test_name = $_POST['test_name'];
    $test_date = $_POST['test_date'];
    $result_date = $_POST['result_date'];
    $full_mark = $_POST['full_mark'];
    $pass_mark = $_POST['pass_mark'];
    $given_by = $_POST['given_by'];

    // File upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileTmpPath = $_FILES['file']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            // Skip header row
            array_shift($data);

            $insertStmt = $conn->prepare("INSERT INTO unit_test_marks (student_id, subject_code, semester_id, course_code, test_name, test_date, result_date, full_mark, pass_mark, mark_score, given_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $student_ids = []; // To store student IDs for fetching tokens later

            foreach ($data as $row) {
                $student_id = $row[0]; // Assuming student_id is in the first column
                $mark_score = $row[1]; // Assuming mark_score is in the second column
                $insertStmt->bind_param('ssiissssssi', $student_id, $subject_code, $semester_id, $course_code, $test_name, $test_date, $result_date, $full_mark, $pass_mark, $mark_score, $given_by);
                $insertStmt->execute();
                
                // Store student_id for notification
                $student_ids[] = $student_id;
            }

            // Fetch expo_push_tokens for the students
            $tokens = fetchExpoTokens($conn, $student_ids);

            // Prepare to send notifications
            $keyFilePath = '../../_api/liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json';
            $accessToken = getAccessToken($keyFilePath);

            foreach ($student_ids as $student_id) {
                if (isset($tokens[$student_id])) {
                    $expoPushToken = $tokens[$student_id];
                    $title = "Marks Added";
                    $body = "$test_name for $subject_code has been uploaded.";
                    $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                    $notificationStatus[$student_id] = $notificationResponse;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data successfully inserted and notifications sent!',
                'notifications' => $notificationStatus ?? []
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error processing the file: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or file upload error.']);
    }
}
?>
