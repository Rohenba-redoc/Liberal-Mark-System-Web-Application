<?php
session_start(); // Start the session
include '../../includes/config.php';


$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ../index.php');
exit();
?>
