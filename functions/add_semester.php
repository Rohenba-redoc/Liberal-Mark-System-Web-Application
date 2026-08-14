<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['semester']) || !isset($data['semester']['name'])) {
        throw new Exception('Invalid input');
    }

    $name = $data['semester']['name'];

    $stmt = $conn->prepare("INSERT INTO semester (semester_name) VALUES (?)");

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    $stmt->bind_param('s', $name);

    if (!$stmt->execute()) {
        throw new Exception('Failed to add semester');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Semester added successfully']);

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
