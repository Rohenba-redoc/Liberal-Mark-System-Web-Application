<?php
// Database connection
include '../includes/config.php'; // Ensure you have your DB connection

$semester_id = $_GET['semester_id'];


    $stmt = $conn->prepare('SELECT subject_code, subject_name FROM subject where semester_id=? ');
    $stmt->bind_param("i", $semester_id);

    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    echo json_encode($subjects);

?>
