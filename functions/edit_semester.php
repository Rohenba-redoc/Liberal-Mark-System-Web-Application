<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['semester']) || !isset($data['semester']['id']) || !isset($data['semester']['name'])) {
        throw new Exception('Invalid input');
    }

    $id = $data['semester']['id'];
    $name = $data['semester']['name'];

    $stmt = $conn->prepare("UPDATE semester SET semester_name = ? WHERE semester_id = ?");

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param('si', $name, $id);

    if (!$stmt->execute()) {
        throw new Exception('Failed to update semester');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Semester updated successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
