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

    
    if (isset($input['teacher_email']) && isset($input['password'])) {
        $teacher_email = $conn->real_escape_string($input['teacher_email']);
        $password = $conn->real_escape_string($input['password']);

       
        $sql = "SELECT t.teacher_id,t.teacher_name,t.teacher_phone,t.teacher_email,t.teacher_address,
        t.desgination,t.dob,tc.password,tc.status,tc.email,d.department_name, 
  d.department_id
                FROM teacher_credentials tc
                JOIN teacher t ON tc.teacher_id = t.teacher_id
                JOIN teacher_department td ON tc.teacher_id = td.teacher_id
JOIN department d ON td.department_id = d.department_id
                WHERE tc.email= ? AND tc.password = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $teacher_email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if($row['status'] === 'active'){
                $tokenPayload = [
                
                    'iat' => time(), 
                    'exp' => time() + 3600, 
                    'data' => [
                        'teacher_id' => $row['teacher_id'],
                        'teacher_email' => $row['teacher_email'],
                        'teacher_phone' => $row['teacher_phone'],
                        'teacher_name' => $row['teacher_name'],
                        'password' => $row['password'],
                        'desgination' => $row['desgination'],
                        'dob' => $row['dob'],
                        'teacher_address' => $row['teacher_address'],
                        'department_name' => $row['department_name']
                    ]
                ];
               
    
                $jwt = JWT::encode($tokenPayload, $secretKey, 'HS256');
                $response = [
                    'status' => 'success',
                    'token' => $jwt,
                   'teacher_id' => $row['teacher_id'],
                        'teacher_email' => $row['teacher_email'],
                        'teacher_phone' => $row['teacher_phone'],
                        'teacher_name' => $row['teacher_name'],
                        'password' => $row['password'],
                        'desgination' => $row['desgination'],
                        'dob' => $row['dob'],
                        'teacher_address' => $row['teacher_address'],
                        'department_name' => $row['department_name']

                ];
    
                echo json_encode($response);
            }
            else{
                echo json_encode(['status' => 'error', 'message' => 'Account is In De-Activate Mode Contact Admin to Activate it']);
            }
           
            
         
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
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
