<?php
include '../includes/config.php'; // Ensure you have your DB connection

// Validate and sanitize the inputs
$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;
$courseCode = isset($_GET['course_code']) ? $_GET['course_code'] : '';

if ($year <= 0 || empty($courseCode)) {
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

// Modified SQL query to aggregate subject information
$query = "SELECT c.combination_id, 
                 GROUP_CONCAT(CONCAT(s.subject_name, '-', s.subject_code) SEPARATOR ', ') AS subject_info
          FROM combination AS c
          JOIN course_combination AS cc ON c.combination_id = cc.combination_id
          JOIN subject AS s ON cc.subject_code = s.subject_code
          WHERE cc.year = ? AND cc.course_code = ?
          GROUP BY c.combination_id";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'Database query preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $year, $courseCode);
$stmt->execute();
$result = $stmt->get_result();

$combinations = [];
while ($row = $result->fetch_assoc()) {
    $combinations[] = $row;
}

// Output the results as JSON
echo json_encode($combinations);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
