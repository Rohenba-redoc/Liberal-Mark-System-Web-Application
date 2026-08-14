<?php
// Include database connection file
include_once 'new.php';

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (isset($data['name'], $data['email'], $data['phoneNumber'], $data['address'], $data['student_id'])) {
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phoneNumber'];
        $address = $data['address'];
        $student_id=$data['student_id'];
       

        // Prepare the SQL queries
        $updateStudentQuery = "UPDATE students 
                               SET student_name = ?, student_email = ?, student_phone = ?, student_address = ?,modified_date=Now()
                               WHERE student_id = ?";

        $updateStudentCredentialsQuery = "UPDATE students_credentials 
                                          SET email = ?, phone = ?, modified_date = NOW()
                                          WHERE student_id = ?";

        // Prepare statements
        if ($stmt1 = $conn->prepare($updateStudentQuery)) {
            $stmt1->bind_param("sssss", $name, $email,$phone,$address, $student_id);
            $stmt1->execute();
            $stmt1->close();
        }

        if ($stmt2 = $conn->prepare($updateStudentCredentialsQuery)) {
            $stmt2->bind_param("sss", $email, $phone, $student_id);
            $stmt2->execute();
            $stmt2->close();
        }

        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'User details updated successfully']);
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
