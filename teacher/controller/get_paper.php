<?php
include '../../includes/config.php'; 

// Validate and sanitize the inputs
$department = isset($_GET['department']) ? (int)$_GET['department'] : 0;
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';

if (empty($department) || empty($semester)) {
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

// Modified SQL query to fetch each subject as a separate row
$query = "SELECT subject_code, subject_name
          FROM subject
          WHERE department_id = ? AND semester_id = ?";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'Database query preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $department, $semester);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

// Output the results as JSON
echo json_encode($subjects);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
