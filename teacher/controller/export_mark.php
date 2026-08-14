<?php 
include '../../includes/config.php';

$subject = isset($_GET['subject_code']) ? $_GET['subject_code'] : '';
$course = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$semester = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';

$error = '';

if(empty($subject)) {
    $error = 'Subject is required.';
} elseif(empty($course)) {
    $error = 'Course is required.';
} elseif(empty($semester)) {
    $error = 'Semester is required.';
}

if ($error) {
    echo json_encode(['error' => $error]);
    exit;
}

// Prepare SQL query to get the mean of the top 2 marks for each student
$query = "
    SELECT s.student_id AS unique_id, s.student_name AS name, s.MU_Roll_No AS mu_roll,
           AVG(t.mark_score) AS Mark_score_mean
    FROM (
        SELECT utm.student_id, CAST(utm.mark_score AS UNSIGNED) AS mark_score,
               ROW_NUMBER() OVER (PARTITION BY utm.student_id ORDER BY CAST(utm.mark_score AS UNSIGNED) DESC) AS rank
        FROM unit_test_marks utm
        JOIN students_course_combination scc ON utm.student_id = scc.student_id
        WHERE scc.subject_code = ? 
        AND utm.subject_code = ?
        AND utm.semester_id = ?
        AND scc.semester_id = ?
        AND utm.course_code = ?
    ) t
    JOIN students s ON t.student_id = s.student_id
    JOIN student_enroll se ON s.student_id = se.student_id
    WHERE t.rank <= 2 AND se.status = 'Incomplete'
    GROUP BY t.student_id, s.student_name, s.MU_Roll_No
    ORDER BY t.student_id
";

// Prepare and bind parameters
$stmt = $conn->prepare($query);
$stmt->bind_param("sssii", $subject, $subject, $semester, $semester, $course);

// Execute and fetch results
$stmt->execute();
$result = $stmt->get_result();
$students = [];

while ($row = $result->fetch_assoc()) {
    // Calculate the mean and format it
    $mean_score = floatval($row['Mark_score_mean']);
    $integer_part = floor($mean_score);
    $decimal_part = $mean_score - $integer_part;

    if ($decimal_part == 0.5) {
        $formatted_score = $integer_part . ' <small>1/2</small>';
    } else {
        $formatted_score = (string)$integer_part;
    }

    $students[] = [
        'unique_id' => $row['unique_id'],
        'name' => $row['name'],
        'mu_roll' => $row['mu_roll'],
        'Mark_score_mean' => $formatted_score
    ];
}

// Return results as JSON
echo json_encode($students, JSON_UNESCAPED_SLASHES);
?>
