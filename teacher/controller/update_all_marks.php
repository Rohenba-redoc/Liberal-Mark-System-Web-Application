<?php
header('Content-Type: application/json');
include '../../includes/config.php'; // Include your database connection

$response = ['success' => false, 'message' => 'An error occurred.'];

try {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validate if both test details and marks are provided
    if (!isset($data['testDetails']) || !is_array($data['testDetails']) || empty($data['testDetails'])) {
        throw new Exception("No test details provided. Parsed data: " . print_r($data, true));
    }

    if (!isset($data['updatedMarks']) || !is_array($data['updatedMarks']) || empty($data['updatedMarks'])) {
        throw new Exception("No marks provided. Parsed data: " . print_r($data, true));
    }

    $testDetails = $data['testDetails']; // Extract test details from the data

    $conn->begin_transaction(); // Start a transaction

    // Update marks for each student
    $stmt = $conn->prepare("UPDATE unit_test_marks SET test_name = ?, test_date = ?, result_date = ?, full_mark = ?, pass_mark = ?, mark_score = ? WHERE mark_id = ?");
    if ($stmt === false) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    foreach ($data['updatedMarks'] as $markId => $markScore) {
        // Bind the parameters with test details and the individual mark
        $stmt->bind_param(
            'ssssssi', 
            $testDetails['test_name'], 
            $testDetails['test_date'], 
            $testDetails['result_date'], 
            $testDetails['full_mark'], 
            $testDetails['pass_mark'], 
            $markScore, 
            $markId
        );
        
        // Execute the statement for each student mark
        if (!$stmt->execute()) {
            throw new Exception("Failed to update mark_id $markId: " . $stmt->error);
        }
    }

    $conn->commit(); // Commit the transaction

    $response['success'] = true;
    $response['message'] = 'Marks and details updated successfully!';
} catch (Exception $e) {
    $conn->rollback(); // Rollback the transaction on error
    $response['message'] = $e->getMessage();
}

// Send JSON response
echo json_encode($response);
?>
