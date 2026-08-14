<?php
include 'new.php';

// Set the content type header to JSON
header('Content-Type: application/json');

if (isset($_GET['mark_id'])) {
    $mark_id = $_GET['mark_id'];

   
    $mark_id = mysqli_real_escape_string($conn, $mark_id);
$query = "SELECT 
            utm.mark_id,
            utm.student_id,
            s.student_name AS student_name,
            c.course_name AS course,
            sem.semester_name AS semester,
            sub.subject_name AS option,
            utm.subject_code AS subject_code,
            utm.test_name AS test_name,
            utm.test_date AS test_date,
            utm.mark_score AS mark_score,
            utm.full_mark AS full_mark,
            utm.pass_mark AS pass_mark,
            utm.result_date AS mark_date,
            utm.given_by AS given_by
        FROM 
            unit_test_marks utm
        JOIN 
            semester sem ON utm.semester_id = sem.semester_id
        JOIN
            course c ON utm.course_code = c.course_code
        JOIN 
            subject sub ON utm.subject_code = sub.subject_code
        JOIN
            students s ON utm.student_id = s.student_id
        WHERE utm.mark_id = $mark_id";

// Execute the query
$result = mysqli_query($conn, $query);

if ($result) {
    // Initialize an array to store the results
    $markArray = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $markArray[] = $row;
    }

    if (!empty($markArray)) {
        echo json_encode($markArray);
    } else {
        echo json_encode(['error' => 'No Mark found']);
    }

    mysqli_free_result($result);
} else {
    error_log("Failed to execute query: " . mysqli_error($conn));
    echo json_encode(['error' => 'Failed to execute query']);
}
} else {
echo json_encode(['error' => 'Missing MarkId parameter']);
}

mysqli_close($conn);
?>

