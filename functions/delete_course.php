<?php
// Include configuration file for database connection
include '../includes/config.php';

// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Get the data from the POST request
    $courseCode = isset($_POST['course_code']) ? htmlspecialchars(trim($_POST['course_code'])) : '';

    // Validate input
    if (empty($courseCode)) {
        throw new Exception('Invalid input');
    }

    // Prepare SQL statement to delete the course
    $stmt = $conn->prepare("DELETE FROM course WHERE course_code = ?");

    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param('s', $courseCode);

    if (!$stmt->execute()) {
        throw new Exception('Failed to delete Discipline: ' . $stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Discipline deleted successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot delete Discipline. It is associated with students.']);
}
?>
