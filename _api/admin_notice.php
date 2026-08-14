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

// Fetch notices for type='all'
$sqlAllNotices = "SELECT id, title, message, created_at FROM admin_notice WHERE type='all'";
$resultAllNotices = $conn->query($sqlAllNotices);

$allNotices = [];
if ($resultAllNotices->num_rows > 0) {
    while ($row = $resultAllNotices->fetch_assoc()) {
        // Strip HTML tags from the message field
        $row['message'] = strip_tags($row['message']);
        $allNotices[] = $row;
    }
}

// Fetch unique combinations for the student
$sqlCombinations = "SELECT se.semester_id, se.course_code, se.department_id, scc.subject_code
                    FROM student_enroll se
                    LEFT JOIN students_course_combination scc ON se.student_id = scc.student_id AND se.semester_id = scc.semester_id
                    WHERE se.student_id = '$student_id' AND se.status = 'Incomplete'";

$resultCombinations = $conn->query($sqlCombinations);

$studentCombinations = [];

if ($resultCombinations->num_rows > 0) {
    while ($row = $resultCombinations->fetch_assoc()) {
        $studentCombinations[] = [
            'semester_id' => $row['semester_id'],
            'course_code' => $row['course_code'],
            'department_id' => $row['department_id'],
            'subject_code' => $row['subject_code']
        ];
    }
}

// Fetch notices with type='filter'
$sqlFilterNotices = "SELECT an.id AS notice_id, an.title, an.message, an.created_at, ant.department_id, ant.subject_code, ant.admin_notice_id, ant.semester_id, ant.course_code
                     FROM admin_notice an
                     JOIN admin_notice_type ant ON an.id = ant.admin_notice_id
                     WHERE an.type = 'filter'";

$resultFilterNotices = $conn->query($sqlFilterNotices);

$filteredNotices = [];

if ($resultFilterNotices->num_rows > 0) {
    while ($row = $resultFilterNotices->fetch_assoc()) {
        // Ensure all keys are set to avoid undefined index warnings
        $row['semester_id'] = $row['semester_id'] ?? null;
        $row['course_code'] = $row['course_code'] ?? null;

        // Loop through student combinations and match against the notice
        foreach ($studentCombinations as $studentCombination) {
            $semesterIdMatch = isset($studentCombination['semester_id']) && $studentCombination['semester_id'] == $row['semester_id'];
            $departmentIdMatch = isset($studentCombination['department_id']) && $studentCombination['department_id'] == $row['department_id'];
            $courseCodeMatch = isset($studentCombination['course_code']) && $studentCombination['course_code'] == $row['course_code'];
            $subjectCodeMatch = isset($studentCombination['subject_code']) && $studentCombination['subject_code'] == $row['subject_code'];

            // Check condition based on whether subject_code is null or not
            if ($row['subject_code'] === NULL) {
                // Check only for department and semester match when subject_code is null
                if ($semesterIdMatch && $departmentIdMatch) {
                    // Avoid duplicate notices with the same admin_notice_id
                    if (!in_array($row['admin_notice_id'], array_column($filteredNotices, 'admin_notice_id'))) {
                        $filteredNotices[] = [
                            'id' => $row['notice_id'],
                            'title' => $row['title'],
                            'message' => strip_tags($row['message']),
                            'created_at' => $row['created_at'],
                            'admin_notice_id' => $row['admin_notice_id']
                        ];
                    }
                }
            } else {
                // Check for full match including subject_code and course_code
                if ($semesterIdMatch && $departmentIdMatch && $courseCodeMatch && $subjectCodeMatch) {
                    // Avoid duplicate notices with the same admin_notice_id
                    if (!in_array($row['admin_notice_id'], array_column($filteredNotices, 'admin_notice_id'))) {
                        $filteredNotices[] = [
                            'id' => $row['notice_id'],
                            'title' => $row['title'],
                            'message' => strip_tags($row['message']),
                            'created_at' => $row['created_at'],
                            'admin_notice_id' => $row['admin_notice_id']
                        ];
                    }
                }
            }
        }
    }
}

// Combine all notices and filtered notices into a single array
$combinedNotices = array_merge($allNotices, $filteredNotices);

// Remove duplicates based on 'id'
$distinctNotices = [];
foreach ($combinedNotices as $notice) {
    if (!isset($distinctNotices[$notice['id']])) {
        $distinctNotices[$notice['id']] = $notice;
    }
}

// Close the database connection
$conn->close();

// Create a response array that includes the combined notices
$response = [
    'combinedNotices' => array_values($distinctNotices)
];

// Encode the results as JSON and output it
echo json_encode($response);
?>
