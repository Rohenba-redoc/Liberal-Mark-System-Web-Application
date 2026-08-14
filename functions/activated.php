<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credentials_id = $_POST['credentials_id'];
    $status = $_POST['status'];

    // Update student_enroll status
    $update_query = "UPDATE students_credentials SET status = ? WHERE credentials_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('si', $status, $credentials_id);

    if ($update_stmt->execute()) {
        echo '<script>alert("Account has been De-Activate Successfully");</script>';
    } else {
        echo '<script>alert("Error while updating");</script>';
    }

    $update_stmt->close();
}

$conn->close();
?>
