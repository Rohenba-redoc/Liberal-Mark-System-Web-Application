<?php
// Database connection
include '../includes/config.php'; // Ensure you have your DB connection


$semesterId = $_GET['semester_id'];
// Fetch subjects from the database
$subjectsQuery = "SELECT s.subject_code, s.subject_name, d.department_name 
                  FROM subject s 
                  JOIN department d ON s.department_id = d.department_id 
                  WHERE s.semester_id = ?";
$stmt = $conn->prepare($subjectsQuery);
$stmt->bind_param("i", $semesterId);
$stmt->execute();
$result = $stmt->get_result();

$subjectsByDepartment = [];
while ($row = $result->fetch_assoc()) {
    $subjectsByDepartment[$row['department_name']][] = [
        'subject_code' => $row['subject_code'],
        'subject_name' => $row['subject_name']
    ];
}

header('Content-Type: application/json');
echo json_encode($subjectsByDepartment);

$stmt->close();
$conn->close();
?>
