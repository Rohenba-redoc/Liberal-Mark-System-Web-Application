<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $subjectCode = $data['subject_code'] ?? '';

    if (!$subjectCode) {
        throw new Exception('Invalid input');
    }

    $stmt = $conn->prepare("DELETE FROM subject WHERE subject_code = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("s", $subjectCode);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete subject');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('Subject not found or already deleted');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Subject deleted successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot delete subject. It is associated with students or teachers.']);
}
?>
