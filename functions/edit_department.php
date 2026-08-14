<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $departmentId = $data['department_id'] ?? '';
    $departmentName = $data['department_name'] ?? '';

    if (!$departmentId || !$departmentName) {
        throw new Exception('Invalid input');
    }

    $stmt = $conn->prepare("UPDATE department SET department_name = ? WHERE department_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param("si", $departmentName, $departmentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update Department');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No changes made');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Department updated successfully']);

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
