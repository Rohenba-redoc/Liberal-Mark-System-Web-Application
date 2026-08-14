<?php
session_start(); // Start the session
include '../../includes/config.php'; // Include your database connection file

$response = array('success' => false, 'error' => '');

// Get the logged-in user ID from the session
$userId = $_SESSION['teacher']['Id'];

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Password change logic
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Fetch current password from the database
        $query = "SELECT password FROM teacher_credentials WHERE Id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if the current password matches
        if ($user && $current_password === $user['password']) {
            // Check if the new password and confirm password match
            if ($new_password === $confirm_password) {
                // Update the password in the database
                $updateQuery = "UPDATE teacher_credentials SET password = ? WHERE Id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('si', $new_password, $userId);
                if ($updateStmt->execute()) {
                    $_SESSION['teacher']['password'] = $new_password;     
                    $response['success'] = true;
                    $response['message']=' Password Updated Successfully';
                    $response['redirect'] = '../Views/profile.php';
                } else {
                    $response['error'] = 'Failed to update the password.';
                }
            } else {
                $response['error'] = 'New password and confirm password do not match.';
            }
        } else {
            $response['error'] = 'Current password is incorrect.';
        }

        $stmt->close();
    }
} else {
    $response['error'] = 'Invalid request method.';
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
