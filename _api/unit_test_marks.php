<?php
header('Content-Type: application/json');

include 'new.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

    if (empty($student_id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing student_id parameter'));
        exit;
    }

    $sql = "SELECT 
                u.mark_id,
                u.student_id,
                u.subject_code,
                u.semester_id,
                u.course_code,
                TRIM(REPLACE(u.test_name, ' ', '')) AS normalized_test_name,
                 u.test_date,
                u.result_date,
                u.full_mark,
                u.pass_mark,
                u.mark_score,
                s.semester_name,
                c.course_name,
                sub.subject_name
            FROM unit_test_marks u
            JOIN semester s ON u.semester_id = s.semester_id
            JOIN course c ON u.course_code = c.course_code
            JOIN subject sub ON u.subject_code = sub.subject_code
            WHERE u.student_id = ?";

    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error: ' . $conn->error));
        exit;
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("s", $student_id);
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($mark_id, $student_id, $subject_code, $semester_id, $course_code, $test_name, $test_date, $result_date, $full_mark, $pass_mark, $mark_score, $semester_name, $course_name, $subject_name);

    // Fetch results into an array
    $results = array();
    while ($stmt->fetch()) {
        $results[] = array(
            'mark_id' => $mark_id,
            'student_id' => $student_id,
            'subject_code' => $subject_code,
            'semester_id' => $semester_id,
            'course_code' => $course_code,
            'test_name' => $test_name,
            'test_date' => $test_date,
            'result_date' => $result_date,
            'full_mark' => $full_mark,
            'pass_mark' => $pass_mark,
            'mark_score' => $mark_score,
            'semester_name' => $semester_name,
            'course_name' => $course_name,
            'subject_name' => $subject_name,
        );
    }

    // Close statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Check if results were found
    if (!empty($results)) {
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'No records found'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}
?>
