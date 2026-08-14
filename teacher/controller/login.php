<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

include '../../includes/config.php'; 

header('Content-Type: application/json');

// Get POST data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password required']);
    exit();
}

// Query to check credentials
$query = "SELECT 
  tc.Id AS Id, 
  tc.teacher_id AS teacher_id, 
  tc.phone AS phone, 
  tc.email AS email, 
  tc.password AS password, 
  tc.status AS status, 
  t.teacher_name AS name, 
  t.teacher_address AS address, 
  t.desgination AS desgination, 
  t.dob AS dob, 
  d.department_name, 
  d.department_id
FROM teacher_credentials tc
JOIN teacher t ON tc.teacher_id = t.teacher_id
JOIN teacher_department td ON tc.teacher_id = td.teacher_id
JOIN department d ON td.department_id = d.department_id
WHERE tc.email = ?;
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit();
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

// Check if user exists and password matches
if ($teacher) {
    if ($teacher && $password == $teacher['password']) { // Assuming passwords are hashed
        // Check if the account status is active
        if ($teacher['status'] === 'active') {
            // Set session variables
            $_SESSION['teacher'] = [
                'Id' => $teacher['Id'],
                'teacher_id' => $teacher['teacher_id'],
                'email' => $teacher['email'],
                'status' => $teacher['status'],
                'phone' => $teacher['phone'],
                'name' => $teacher['name'],
                'address' => $teacher['address'],
                'desgination' => $teacher['desgination'],
                'dob' => $teacher['dob'],
                'department' => $teacher['department_name'],
            ];

            echo json_encode([
                'status' => 'success',
                'teacher' => $_SESSION['teacher']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Account is not active! Contact Admin to Activate the Account']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
}

$stmt->close();
$conn->close();
?>
