<?php
include '../includes/config.php'; 
header('Content-Type: application/json');

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check if data was decoded successfully
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid  data.']);
    exit;
}

// Get the data, checking for null values
$teacherId = $data['teacherId'] ?? null;
$teacherName = $data['teacherName'] ?? null;
$teacherAddress = $data['teacherAddress'] ?? null;
$teacherPhone = $data['teacherPhone'] ?? null;
$teacherEmail = $data['teacherEmail'] ?? null;
$desgination = $data['Desgination'] ?? null;
$dob = $data['DateOfBirth'] ?? null;
$department = $data['department'] ?? null;

// Check if any required fields are missing
if ($teacherId === null || $teacherName === null || $teacherAddress === null || $teacherPhone === null || $teacherEmail === null || $desgination === null || $dob === null) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Prepare the SQL query
$sql = "UPDATE teacher SET 
            teacher_name = ?, 
            teacher_address = ?, 
            teacher_phone = ?, 
            teacher_email = ?, 
            desgination = ?, 
            dob = ? 
        WHERE teacher_id = ?";

$stmt = $conn->prepare($sql);

// Execute the query and check the result
$result = $stmt->execute([$teacherName, $teacherAddress, $teacherPhone, $teacherEmail, $desgination, $dob, $teacherId]);

if ($result) {
    $sql2 = "UPDATE teacher_department SET department_id = ? WHERE teacher_id = ?";
    $stmmt = $conn->prepare($sql2);
    $resultt = $stmmt->execute([$department, $teacherId]);
    if($resultt){
        echo json_encode(['success' => true, 'message' => 'Teacher updated successfully.']);

    }
    else {
        echo json_encode(['success' => false, 'message' => 'Failed to update teacher.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update teacher.']);
}
?>
