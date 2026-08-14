<?php
include_once 'new.php';

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (isset($data['id'], $data['title'], $data['message'])) {
        $id = $data['id'];
        $title = $data['title'];
        $message = $data['message'];
       
       

        // Prepare the SQL queries
        $updateStudentQuery = "UPDATE teacher_notice 
                               SET title = ?,message=?
                               WHERE id = ?";

        
        // Prepare statements
        if ($stmt1 = $conn->prepare($updateStudentQuery)) {
            $stmt1->bind_param("ssi", $title, $message, $id);
            $stmt1->execute();
            $stmt1->close();
        }


        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Notice has been updated successfully']);
    } else {
        // Return error response if required fields are missing
        echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
    }
} else {
    // Return error response for invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
