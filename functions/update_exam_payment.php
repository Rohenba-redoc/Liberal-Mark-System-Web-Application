<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enroll_id = $_POST['enroll_id'];
    $status = $_POST['status'];

    // Update student_enroll status
    $update_query = "UPDATE student_enroll SET exam_fee = ? WHERE enroll_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('si', $status, $enroll_id);

    if ($update_stmt->execute()) {
        echo '<script>alert("Fee Status Has been Marked as Paid Successfully");</script>';
    } else {
        echo '<script>alert("Error while updating");</script>';
    }

    $update_stmt->close();
}

$conn->close();
?>
