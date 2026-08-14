<?php
include 'new.php'; // Make sure this includes the database connection

// Set the content type header to JSON
header('Content-Type: application/json');

// Check if 'student_id' parameter is set
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Escape the student_id to prevent SQL injection
    $student_id = mysqli_real_escape_string($conn, $student_id);

    // Define the query to group subjects by semester_id
    $query = "SELECT 
                scc.semester_id, 
                s.semester_name,
                GROUP_CONCAT(DISTINCT CONCAT(ss.subject_name, ' (', ss.subject_code, ')') ORDER BY ss.subject_name ASC SEPARATOR '\n') AS subjects
              FROM 
                students_course_combination scc
              JOIN 
                semester s ON scc.semester_id = s.semester_id
              JOIN 
                subject ss ON scc.subject_code = ss.subject_code
              WHERE 
                scc.student_id = '$student_id'
              GROUP BY 
                scc.semester_id, s.semester_name";

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
            echo json_encode(['error' => 'No marks found']);
        }

        mysqli_free_result($result);
    } else {
        error_log("Failed to execute query: " . mysqli_error($conn));
        echo json_encode(['error' => 'Failed to execute query']);
    }
} else {
    echo json_encode(['error' => 'Missing student_id parameter']);
}

// Close the database connection
mysqli_close($conn);
?>
