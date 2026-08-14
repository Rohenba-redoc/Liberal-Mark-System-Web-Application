<?php
include_once 'new.php';

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (isset($data['id'])) {
        $id = $data['id'];
       
       

        // Prepare the SQL queries
        
        $DeleteStudentQuery = "DELETE FROM teacher_notice WHERE id = ?";

        
        // Prepare statements
        if ($stmt1 = $conn->prepare($DeleteStudentQuery)) {
            $stmt1->bind_param("i", $id);
            $stmt1->execute();
            $stmt1->close();
        }


        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Notice details Deleted successfully']);
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
