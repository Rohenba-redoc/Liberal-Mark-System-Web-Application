<?php 
header('Content-Type: application/json'); 
include '../../includes/config.php'; 
require '../../_api/vendor/autoload.php'; // For Google and Guzzle clients

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
$response = array();
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validate students data
        if (!isset($_POST['students']) || !is_array($_POST['students'])) {
            throw new Exception("Invalid students data.");
        }

        // Extract POST data
        $given_by = $_POST['given_by'];
        $name = $_POST['name'];
        $tdate = $_POST['tdate'];
        $rdate = $_POST['rdate'];
        $fmark = $_POST['fmark'];
        $pmark = $_POST['pmark'];
        $course = $_POST['course'];
        $semester = $_POST['semester'];
        $subject = $_POST['subject'];

        // Path to Firebase service account key
        $keyFilePath = '../../_api/liberalcollege-88dd5-firebase-adminsdk-95quv-08ff6c741c.json';

        // Get the access token
        $accessToken = getAccessToken($keyFilePath);

        $insertQuery = "INSERT INTO unit_test_marks (student_id, test_name, test_date, result_date, full_mark, pass_mark, course_code, semester_id, subject_code, mark_score, given_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = $conn->prepare($insertQuery)) {
            // Collect student IDs for notifications
            $studentIds = array_keys($_POST['students']); 

            // Fetch tokens for all students
            $tokens = fetchExpoTokens($conn, $studentIds);

            // Iterate through all students and insert data
            foreach ($_POST['students'] as $studentId => $markScore) {
                // Bind parameters (ensure types match your DB schema)
                $stmt->bind_param('ssssssiissi', $studentId, $name, $tdate, $rdate, $fmark, $pmark, $course, $semester, $subject, $markScore, $given_by);

                // Execute the statement
                if (!$stmt->execute()) {
                    $response['error'][] = "Error inserting record for student ID $studentId: " . $stmt->error;
                } else {
                    // Send notifications if token exists for student
                    if (isset($tokens[$studentId])) {
                        $expoPushToken = $tokens[$studentId];
                        $title = "Marks Added";
                        $body = "$name for $subject has been released";
                        $notificationResponse = sendNotification($accessToken, $expoPushToken, $title, $body);
                        $response['notifications'][$studentId] = $notificationResponse;
                    }
                }
            }

            // Close the statement
            $stmt->close();

            // Redirect or display success message
            $response['success'] = "Marks inserted successfully.";
        } else {
            $response['error'] = "Error preparing statement: " . $conn->error;
        }
    }
} catch (Exception $e) {
    $response['error'] = "An error occurred: " . $e->getMessage();
}

// Close the database connection
$conn->close();

// Output the response as JSON
echo json_encode($response);


?>

