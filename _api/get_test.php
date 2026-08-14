<?php 

include 'new.php';

// Retrieve the GET parameters
$given_by = isset($_GET['given_by']) ? $_GET['given_by'] : '';
$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$subject_code = isset($_GET['subject_code']) ? $_GET['subject_code'] : '';
$semester_id = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';
$year = isset($_GET['test_date']) ? $_GET['test_date'] : ''; 

// Validate the input parameters
if (empty($given_by) || empty($course_code) || empty($subject_code) || empty($semester_id) || empty($year)) {
    echo json_encode(['error' => 'No Test Available']);
    exit;
}

// Prepare the SQL statement to prevent SQL injection
$sql = "SELECT DISTINCT test_name FROM unit_test_marks WHERE given_by = ? AND course_code = ? AND subject_code = ? AND semester_id=? AND DATE_FORMAT(STR_TO_DATE(test_date, '%Y-%m-%d'), '%Y') = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

// Bind the parameters to the SQL query
$stmt->bind_param('sssss', $given_by, $course_code, $subject_code, $semester_id,$year);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to hold the test names
$test = [];

// Fetch the results
while ($row = $result->fetch_assoc()) {
    $test[] = $row;
}

// Set the content type to JSON
header('Content-Type: application/json');

// Output the results as JSON
echo json_encode($test);

$stmt->close();
$conn->close();

?>
