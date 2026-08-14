<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['departments']) || !is_array($data['departments'])) {
        throw new Exception('Invalid input');
    }

    $departments = $data['departments'];

    $stmt = $conn->prepare("INSERT INTO department (department_name) VALUES (?)");

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    // Begin transaction
    $conn->begin_transaction();

    foreach ($departments as $department) {
        if (!isset($department['name']) || empty($department['name'])) {
            throw new Exception('Department Name is required');
        }

        $name = $department['name'];
        $stmt->bind_param('s', $name);

        if (!$stmt->execute()) {
            throw new Exception('Failed to add stream');
        }
    }

    // Commit transaction
    $conn->commit();

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Department(s) added successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->rollback();  // Rollback transaction on error
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
