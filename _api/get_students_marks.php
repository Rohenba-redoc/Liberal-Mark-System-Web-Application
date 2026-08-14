<?php
include 'new.php';

// Get parameters from the request
$semester = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';
$courseCode = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$subject_code = isset($_GET['subject_code']) ? $_GET['subject_code'] : '';
$test = isset($_GET['test']) ? $_GET['test'] : '';
$given_by = isset($_GET['given_by']) ? $_GET['given_by'] : '';
$year = isset($_GET['date']) ? $_GET['date'] : ''; // Assuming 'date' contains only the year (YYYY)

if (empty($semester) || empty($courseCode) || empty($subject_code) || empty($year) || empty($given_by) || empty($test)) {
    echo json_encode(['error' => 'All the fields are required']);
    exit;
}

if ($semester && $courseCode && $subject_code && $year) {
    // Modify the query to check only the year part of the test_date
    $query = "
        SELECT 
            utm.*, 
            s.student_name 
        FROM 
            unit_test_marks utm
        JOIN 
            students s 
        ON 
            utm.student_id = s.student_id 
        WHERE 
            utm.course_code = ? 
            AND utm.subject_code = ? 
            AND utm.semester_id = ? 
            AND DATE_FORMAT(STR_TO_DATE(utm.test_date, '%Y-%m-%d'), '%Y') = ?  
            AND utm.given_by = ? 
            AND utm.test_name = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters
        $stmt->bind_param("ssssss", $courseCode, $subject_code, $semester, $year, $given_by, $test);
        
        // Execute the query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            // Check if any rows were returned
            if ($result->num_rows > 0) {
                $students = [];
                
                // Fetch all rows
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row;
                }
                
                // Return the results as JSON
                echo json_encode($students);
            } else {
                echo json_encode(['message' => 'No student found']);
            }
        } else {
            echo json_encode(['error' => 'Query execution failed']);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare the query']);
    }
} else {
    echo json_encode(['error' => 'Invalid parameters']);
}

// Close the connection
$conn->close();
?>
