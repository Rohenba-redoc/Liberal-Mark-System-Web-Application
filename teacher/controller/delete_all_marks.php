<?php
header('Content-Type: application/json');
include '../../includes/config.php'; // Include your database connection

$response = ['success' => false, 'message' => 'An error occurred.'];

try {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['markIds']) || !is_array($data['markIds']) || empty($data['markIds'])) {
        throw new Exception("No marks to delete.");
    }

    $conn->begin_transaction(); // Start a transaction

    // Prepare the SQL statement for deletion
    $stmt = $conn->prepare("DELETE FROM unit_test_marks WHERE mark_id = ?");
    if ($stmt === false) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    // Delete each mark based on mark_id
    foreach ($data['markIds'] as $markId) {
        $stmt->bind_param('i', $markId);
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete mark_id $markId: " . $stmt->error);
        }
    }

    $conn->commit(); // Commit the transaction

    $response['success'] = true;
    $response['message'] = 'All marks deleted successfully!';
} catch (Exception $e) {
    $conn->rollback(); // Rollback the transaction on error
    $response['message'] = $e->getMessage();
}

// Send JSON response
echo json_encode($response);
?>
