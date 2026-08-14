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
    if (isset($data['password'], $data['NewPassword'], $data['teacher_id'], $data['ConfirmPassword'])) {
        $currentPassword = $data['password'];
        $newPassword = $data['NewPassword'];
        $confirmPassword = $data['ConfirmPassword'];
        $teacher_id = $data['teacher_id'];

        // Validate new password and confirm password
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'New password and confirm password do not match']);
            exit;
        }

        // Fetch the user from the database
        $stmt = $conn->prepare("SELECT password FROM teacher_credentials WHERE teacher_id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Teacher not found']);
            exit;
        }

        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the current password
        if ($currentPassword !== $hashedPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
            exit;
        }

        // Hash the new password

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE teacher_credentials SET password = ? WHERE teacher_id = ?");
        $stmt->bind_param("si", $newPassword, $teacher_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
        }
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
