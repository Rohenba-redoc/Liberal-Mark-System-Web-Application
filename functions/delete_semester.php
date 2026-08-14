<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        throw new Exception('Invalid input');
    }

    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM semester WHERE semester_id = ?");

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param('i', $id);

    if (!$stmt->execute()) {
        throw new Exception('Failed to delete semester');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Semester deleted successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot delete subject. It is associated with students.']);
}
?>
