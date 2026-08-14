<?php 
include '../../includes/config.php';

$sql = "SELECT DISTINCT year FROM course_combination";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$years = [];

// Fetch the results
while ($row = $result->fetch_assoc()) {
    $years[] = $row;
}

// Output the results as JSON
echo json_encode($years);
?>
