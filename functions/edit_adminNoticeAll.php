<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $noticeId = $data['id'] ?? '';
    $noticeTitle = $data['title'] ?? '';

    $noticeMessage = $data['message'] ?? '';
    $noticeCreated_At = $data['created_at'] ?? '';

    if (!$noticeId || !$noticeTitle || !$noticeMessage || !$noticeCreated_At) {
        throw new Exception('Invalid input');
    }

    $stmt = $conn->prepare("UPDATE admin_notice SET title = ?, message = ?, created_at=? WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("sssi", $noticeTitle,$noticeMessage,$noticeCreated_At, $noticeId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update stream');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No changes made');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Notice updated successfully']);

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
