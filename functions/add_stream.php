<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['streams']) || !is_array($data['streams'])) {
        throw new Exception('Invalid input');
    }

    $streams = $data['streams'];

    $stmt = $conn->prepare("INSERT INTO streams (stream_title) VALUES (?)");

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    // Begin transaction
    $conn->begin_transaction();

    foreach ($streams as $stream) {
        if (!isset($stream['title']) || empty($stream['title'])) {
            throw new Exception('Stream title is required');
        }

        $title = $stream['title'];
        $stmt->bind_param('s', $title);

        if (!$stmt->execute()) {
            throw new Exception('Failed to add stream');
        }
    }

    // Commit transaction
    $conn->commit();

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Stream(s) added successfully']);

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
