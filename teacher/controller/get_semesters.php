<?php
include '../../includes/config.php'; // Ensure you have your DB connection

$query = "SELECT semester_id, semester_name FROM semester ";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$semesters = [];

while ($row = $result->fetch_assoc()) {
    $semesters[] = $row;
}

echo json_encode($semesters);
?>
