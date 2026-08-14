<?php
session_start(); // Start the session
include '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user']['id'])) {
    // Redirect to login page if no session exists
    header('Location: ../screen/sign-in.php');
    exit();
}

// Retrieve the user ID from session
$userId = $_SESSION['user']['id'];

// Prepare and execute SQL statement to delete the token for the user
$sql = "DELETE FROM tokens WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ../screen/sign-in.php');
exit();
?>
