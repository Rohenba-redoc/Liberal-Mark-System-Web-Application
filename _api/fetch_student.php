<?php
header('Content-Type: application/json');

include 'new.php';

// Retrieve parameters from query string
$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$semester_id = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';
$subject_code = isset($_GET['subject_code']) ? $_GET['subject_code'] : '';

// Validate parameters
if (empty($course_code) || empty($semester_id) || empty($subject_code)) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

// Prepare the SQL query
$sql = "SELECT 
            s.student_id, 
            s.student_name,
            se.enroll_id
        FROM 
            students s
        INNER JOIN 
            student_enroll se ON s.student_id = se.student_id
        INNER JOIN 
            students_course_combination scc ON s.student_id = scc.student_id
        WHERE 
            se.course_code = ? 
            AND se.semester_id = ? 
            AND se.status = 'Incomplete'
            AND scc.subject_code = ?
            AND scc.semester_id = ? ";

$stmt = $conn->prepare($sql);

// Check if preparation is successful
if ($stmt === false) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param('iisi', $course_code, $semester_id, $subject_code, $semester_id);

// Execute the query
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
    exit;
}

// Get the result
$result = $stmt->get_result();

// Check if any records were found
if ($result->num_rows === 0) {
    echo json_encode(['message' => 'No records found']);
    exit;
}

// Fetch data
$students = array();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Close statement and connection
$stmt->close();
$conn->close();

// Output JSON
echo json_encode($students);
?>
