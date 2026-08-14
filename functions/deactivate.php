<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $Id = $data['id'] ?? '';

    if (!$Id) {
        throw new Exception('Invalid input');
    }

    // Perform deletion
    $stmt = $conn->prepare("UPDATE admin_credentials set status='Inactive' WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("i", $Id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to De-Activate User');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('User not found or already deleted');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'User De-Activate successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot De-Activate User. It is associated with Some Data.']);
}
?>
