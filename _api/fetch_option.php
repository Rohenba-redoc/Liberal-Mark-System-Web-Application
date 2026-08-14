<?php
include 'new.php';

$course_code = $_GET['course_code'];
$semester = $_GET['semester'];
$department = $_GET['department'];

// Check if course_code and year are provided
if (empty($course_code) || empty($semester) || empty($department)) {
    // Return an error response
    header('Content-Type: application/json');
    echo json_encode([
        'error' => true,
        'message' => 'course_code and year are required'
    ]);
    exit(); // Stop further execution
}

// Sanitize input to prevent SQL injection
$course_code = mysqli_real_escape_string($conn, $course_code);
$semester = mysqli_real_escape_string($conn, $semester);
$department = mysqli_real_escape_string($conn, $department);

// Prepare SQL statement
$sql = "SELECT subject_code, subject_name
          FROM subject
          WHERE department_id = ? AND semester_id = ?";

// Prepare and bind the query
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $department, $semester); // 'ss' for two strings
$stmt->execute();

// Fetch results
$result = $stmt->get_result();
$options = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($options);
?>
