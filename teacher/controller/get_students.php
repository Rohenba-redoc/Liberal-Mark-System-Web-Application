<?php include '../../includes/config.php'; 


$semester = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';
$courseCode = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$subject_code = isset($_GET['subject_code']) ? $_GET['subject_code'] : '';

if( empty($semester) || empty($courseCode) || empty($subject_code))
{
    echo json_encode(['error' => 'All the Field Required']);
    exit;
}
if($courseCode && $semester && $subject_code) {
    $query = "
        SELECT 
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
            AND scc.semester_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind parameters to the query
    $stmt->bind_param('iisi', $courseCode, $semester, $subject_code, $semester);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the data and output as JSON
    $students = array();
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    // Output the student data as JSON
    echo json_encode($students);

} else {
    echo json_encode(array('error' => 'Invalid parameters'));
}

// Close the connection
$conn->close();
?>

