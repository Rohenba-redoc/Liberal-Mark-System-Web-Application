<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $streamId = $data['stream_id'] ?? '';
    $streamTitle = $data['stream_title'] ?? '';

    if (!$streamId || !$streamTitle) {
        throw new Exception('Invalid input');
    }

    $stmt = $conn->prepare("UPDATE streams SET stream_title = ? WHERE stream_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("si", $streamTitle, $streamId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update stream');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No changes made');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Stream updated successfully']);

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
