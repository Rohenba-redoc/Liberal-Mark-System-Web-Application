<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $streamId = $data['stream_id'] ?? '';

    if (!$streamId) {
        throw new Exception('Invalid input');
    }

    // Perform deletion
    $stmt = $conn->prepare("DELETE FROM streams WHERE stream_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("i", $streamId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete stream');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('Stream not found or already deleted');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Stream deleted successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot delete stream. It is associated with course.']);
}
?>
