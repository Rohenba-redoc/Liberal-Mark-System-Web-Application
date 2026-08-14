<?php
include '../../includes/config.php';

// Get raw POST data
$input = json_decode(file_get_contents('php://input'), true);

// Log input data for debugging
file_put_contents('debug.log', print_r($input, true), FILE_APPEND);

$mark_id = isset($input['mark_id']) ? (int)$input['mark_id'] : '';
$mark_score = isset($input['mark_score']) ? (int)$input['mark_score'] : '';

if (empty($mark_id) || empty($mark_score)) {
    echo json_encode(['error' => 'Invalid Field']);
    exit;
}

// Prepare the update query
$query = "UPDATE unit_test_marks SET mark_score = ?, modified_date = NOW() WHERE mark_id = ?";

// Prepare the statement
if ($stmt = $conn->prepare($query)) {
    // Bind the parameters
    $stmt->bind_param("si", $mark_score, $mark_id);
    
    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Marks updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update marks: ' . $stmt->error]);
    }
    
    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Failed to prepare the query: ' . $conn->error]);
}

// Close the connection
$conn->close();
?>
