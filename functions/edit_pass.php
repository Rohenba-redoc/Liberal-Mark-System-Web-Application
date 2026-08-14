<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $Id = $data['id'] ?? '';
    $key = $data['key'] ?? '';

    

    if (!$Id || !$key) {
        throw new Exception('Invalid input');
    }

    $stmt = $conn->prepare("UPDATE passkeys SET passkey = ? WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("si", $key,$Id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update PassKey');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No changes made');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Pass Key updated successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
