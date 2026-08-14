<?php
// Database connection
include '../includes/config.php'; // Adjust this to your database connection file

try {
    // SQL query
    $sql = "
        SELECT c.course_name, COUNT(DISTINCT e.student_id) AS num_students
        FROM course c
        INNER JOIN student_enroll e ON c.course_code = e.course_code
        GROUP BY c.course_code;
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    $results = array();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    // Close the database connection
    $conn->close();

    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
