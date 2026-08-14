<?php
include '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['name']) && isset($data['level'])) {
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $level = $data['level'];

    // Prepare SQL update statement
    $sql = "UPDATE admin_credentials SET username = ?,email= ?, level = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    
        $stmt->bind_param("sssi", $name,$email, $level, $id);
    

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>
