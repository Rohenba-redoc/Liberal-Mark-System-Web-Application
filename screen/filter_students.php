<?php
include '../includes/config.php';

$status = isset($_GET['status']) ? $_GET['status'] : null;

function fetchStudents($status = null) {
    global $conn;

    $query = "SELECT * FROM students";
    
    if ($status === 'draft') {
        $query .= " WHERE status = 'draft'";
    }

    $result = $conn->query($query);

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    return $students;
}

$students = fetchStudents($status);

header('Content-Type: application/json');
echo json_encode($students);
?>
