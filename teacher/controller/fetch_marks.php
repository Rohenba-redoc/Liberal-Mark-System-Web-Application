<?php
include '../../includes/config.php';

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $given_by = isset($_POST['given_by']) ? htmlspecialchars($_POST['given_by']) : '';
    $course_code = isset($_POST['course_code']) ? htmlspecialchars($_POST['course_code']) : '';
    $semester_id = isset($_POST['semester_id']) ? htmlspecialchars($_POST['semester_id']) : '';
    $subject_code = isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : '';
    $year = isset($_POST['year']) ? filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT) : '';

    // Base SQL query to fetch data
    $query = "SELECT u.mark_id, u.subject_code, u.semester_id, u.course_code, u.test_name, u.test_date, u.result_date, u.full_mark, u.pass_mark, u.student_id, u.mark_score, s.student_name 
              FROM unit_test_marks u
              JOIN 
              students s ON u.student_id = s.student_id
              WHERE given_by = ?";
    $params = [$given_by];

    // Dynamically build the query based on provided filters
    if (!empty($course_code)) {
        $query .= " AND course_code = ?";
        $params[] = $course_code;
    }
    if (!empty($semester_id)) {
        $query .= " AND semester_id = ?";
        $params[] = $semester_id;
    }
    if (!empty($subject_code)) {
        $query .= " AND subject_code = ?";
        $params[] = $subject_code;
    }
    
    if (!empty($year)) {
        $query .= " AND YEAR(test_date) = ?";
        $params[] = $year;
    }

    // Prepare and execute the query using mysqli
    $stmt = $conn->prepare($query);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }

    // Bind parameters dynamically, assuming most are strings, but adjust as needed
    $types = str_repeat('s', count($params)); // Assuming all are strings; adjust if necessary
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // Use get_result to fetch the results
        $result = $stmt->get_result();
        $results = [];

        // Fetch all results
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        // Return the results as JSON
        echo json_encode($results);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Return an error if not a POST request
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are allowed.']);
}
?>
