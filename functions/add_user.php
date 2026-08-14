<?php 
include '../includes/config.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$email = $data['email'];
$pass = $data['pass'];
$level = $data['level'];

if(empty($name) || empty($pass) || empty($level)){
    echo json_encode(['success' => false, 'message' => 'All fields are required and must be valid.']);
        exit;
}
$query="INSERT INTO admin_credentials(username,email,password,level,status) VALUES(?,?, ?, ?,'active')";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssss', $name,$email, $pass, $level);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User added successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add User: ' . $stmt->error]);
}

?>