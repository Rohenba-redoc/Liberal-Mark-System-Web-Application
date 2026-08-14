<?php
header('Content-Type: application/json');

include 'new.php';

$student_id = $_GET['student_id'] ?? '';

if (!$student_id) {
    echo json_encode(["error" => "Missing required parameters."]);
    exit;
}

$sql = "SELECT DISTINCT s.semester, utm.option, utm.test_name
        FROM semester s
        JOIN unit_test_marks utm ON s.id = utm.semester
        WHERE utm.student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}


$stmt->close();
$conn->close();
echo json_encode($data);

?>
