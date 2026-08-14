<?php
include '../../includes/config.php';

$query = "SELECT department_id, department_name FROM department ";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$departments = [];

while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);
?>
