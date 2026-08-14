<?php
header('Content-Type: application/json');
include 'new.php';
require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+';
    $max = strlen($characters) - 1;
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $max)];
    }
    
    return $randomString;
}

$secretKey = generateRandomString(64); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['student_email']) && isset($input['student_phone'])) {
        $student_email = $conn->real_escape_string($input['student_email']);
        $student_phone = $conn->real_escape_string($input['student_phone']);

        // Modify the SQL query to include status check
        $sql = "SELECT s.student_id, s.student_name, s.student_phone, s.student_address, s.student_email,
                       s.MU_Roll_No, s.Registration_no, s.Abc_id, s.status AS student_status, sc.phone, sc.email, sc.status AS credentials_status
                FROM students_credentials sc
                JOIN students s ON sc.student_id = s.student_id
                WHERE sc.email = ? AND sc.phone = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $student_email, $student_phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if both student status and credentials status are active
            if ($row['student_status'] === 'active' && $row['credentials_status'] === 'active') {
                $tokenPayload = [
                    'iat' => time(), 
                    'exp' => time() + 3600, 
                    'data' => [
                        'student_id' => $row['student_id'],
                        'student_email' => $row['student_email'],
                        'student_phone' => $row['student_phone'],
                        'student_name' => $row['student_name'],
                        'student_address' => $row['student_address'],
                        'MU_Roll_No' => $row['MU_Roll_No'],
                        'Registration_no' => $row['Registration_no'],
                        'Abc_id' => $row['Abc_id']
                    ]
                ];

                $jwt = JWT::encode($tokenPayload, $secretKey, 'HS256');
                $response = [
                    'status' => 'success',
                    'token' => $jwt,
                    'student_id' => $row['student_id'],
                    'student_email' => $row['student_email'],
                    'student_phone' => $row['student_phone'],
                    'student_name' => $row['student_name'],
                    'student_address' => $row['student_address'],
                    'MU_Roll_No' => $row['MU_Roll_No'],
                    'Registration_no' => $row['Registration_no'],
                    'Abc_id' => $row['Abc_id']
                ];

                echo json_encode($response);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Account is In De-Activate Mode Contact Admin to Activate it']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or phone']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
