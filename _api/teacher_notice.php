<?php
header('Content-Type: application/json');

include 'new.php';

// Get the student_id from the query string
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Check if student_id is provided
if (empty($student_id)) {
    echo json_encode(['error' => 'Student ID is required']);
    exit;
}

// Fetch unique combinations from the student_enroll and students_course_combination tables
$sql1 = "SELECT se.semester_id, se.course_code, scc.subject_code
         FROM student_enroll se
         JOIN students_course_combination scc ON se.student_id = scc.student_id
         WHERE se.student_id = '$student_id' AND se.status = 'Incomplete'";

$result1 = $conn->query($sql1);

$combinations = [];

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $combinations[] = [
            'semester_id' => $row['semester_id'],
            'course_code' => $row['course_code'],
            'subject_code' => $row['subject_code']
        ];
    }
}

// Prepare a placeholder for the final notices
$matchedNotices = [];

if (!empty($combinations)) {
    // Build the query dynamically based on the combinations
    $conditions = [];
    foreach ($combinations as $combination) {
        $semester_id = $combination['semester_id'];
        $course_code = $combination['course_code'];
        $subject_code = $combination['subject_code'];

        $conditions[] = "(semester_id = '$semester_id' AND course_code = '$course_code' AND subject_code = '$subject_code')";
    }

    // Join the conditions with OR to check any of the combinations
    $conditionsString = implode(' OR ', $conditions);

    // Fetch notices that match any of the combinations
    $sql2 = "SELECT id, title, message, created_at 
             FROM teacher_notice 
             WHERE $conditionsString";

    $result2 = $conn->query($sql2);

    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            // Strip HTML tags from the message field
            $row['message'] = strip_tags($row['message']);
            $matchedNotices[] = $row;
        }
    }
}

// Close the database connection
$conn->close();

// Create a response array that includes the matched notices
$response = [
    'notices' => $matchedNotices
];

// Encode the results as JSON and output it
echo json_encode($response);
?>
