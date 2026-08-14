<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id = $_POST['modalid']; 
    $action =$_POST['action'];

    // Update the teacher's status to "Inactive"
    $query = "UPDATE teacher_credentials SET status = '$action' WHERE Id = '$Id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect or return a success message
        echo "<script>alert('Teacher Account has been set to " . $action . "'); window.location.href='../screen/teacher.php';</script>";
    } else {
        // Handle the error
        echo "<script>alert('Error updating teacher status'); window.location.href='../screen/teacher.php';</script>";
    }
}
?>
