<?php
include '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

$teacher_name = $data['teacher_name'];
$teacher_address = $data['teacher_address'];
$teacher_phone = $data['teacher_phone'];
$teacher_email = $data['teacher_email'];
$desgination = $data['desgination'];
$dob = $data['dob'];
$department = $data['department']; // This should be department_id

// Start a transaction
$conn->begin_transaction();

try {
    // Insert into the teacher table
    $query = "INSERT INTO teacher (teacher_name, teacher_address, teacher_phone, teacher_email, desgination, dob) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssss', $teacher_name, $teacher_address, $teacher_phone, $teacher_email, $desgination, $dob);
    $stmt->execute();
    
    // Get the last inserted teacher_id
    $teacher_id = $conn->insert_id;

    // Insert into the teacher_credentials table
    $status = 'active'; // or whatever status is appropriate
    $sql = "INSERT INTO teacher_credentials (teacher_id, phone, email, password, status) VALUES (?, ?, ?, ?, ?)";
    $stmtt = $conn->prepare($sql);
    $stmtt->bind_param('issss', $teacher_id, $teacher_phone, $teacher_email, $teacher_phone, $status);
    $stmtt->execute();

    // Insert into the teacher_department table
    $sql2 = "INSERT INTO teacher_department (teacher_id, department_id) VALUES (?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ii', $teacher_id, $department);
    $stmt2->execute();

    // Commit the transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Teacher added successfully']);

} catch (Exception $e) {
    // Rollback the transaction if something failed
    $conn->rollback();

    echo json_encode(['success' => false, 'message' => 'Failed to add teacher']);
}

$stmt->close();
$stmtt->close();
$stmt2->close();
$conn->close();
?>
