<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $departmentId = $data['department_id'] ?? '';

    if (!$departmentId) {
        throw new Exception('Invalid input');
    }

    // Perform deletion
    $stmt = $conn->prepare("DELETE FROM department WHERE department_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("i", $departmentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete department');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('department not found or already deleted');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Department deleted successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot delete department. It is associated with course.']);
}
?>
