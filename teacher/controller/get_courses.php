<?php
include '../../includes/config.php';

$query = "SELECT course_code, course_name FROM course ";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);
?>
