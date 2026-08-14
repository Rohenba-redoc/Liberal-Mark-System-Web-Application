<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $Id = $data['id'] ?? '';

    if (!$Id) {
        throw new Exception('Invalid input');
    }

    // Start a transaction to ensure both deletes happen or neither
    $conn->begin_transaction();

    // Delete from admin_notice_type first
    $stmt = $conn->prepare("DELETE FROM admin_notice_type WHERE admin_notice_id = ?");
    if (!$stmt) {
        throw new Exception('Database error (admin_notice_type): ' . $conn->error);
    }
    
    $stmt->bind_param("i", $Id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from admin_notice_type');
    }

    $stmt->close();

    // Delete from admin_notice
    $stmt = $conn->prepare("DELETE FROM admin_notice WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Database error (admin_notice): ' . $conn->error);
    }

    $stmt->bind_param("i", $Id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete admin_notice');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('Notice not found or already deleted');
    }

    // Commit the transaction
    $conn->commit();
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Notice and associated records deleted successfully']);

} catch (Exception $e) {
    // Rollback the transaction if an error occurred
    if (isset($conn) && $conn->in_transaction) {
        $conn->rollback();
    }

    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }

    echo json_encode(['success' => false, 'message' => 'Cannot delete notice. It is associated with a course or has other issues.']);
}
?>
