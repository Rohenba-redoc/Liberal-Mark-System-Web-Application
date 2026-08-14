<?php
// Include configuration file for database connection
include '../includes/config.php';

// Check if form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize it
    $courseCode = isset($_POST['course_code']) ? htmlspecialchars(trim($_POST['course_code'])) : '';
    $courseName = isset($_POST['course_name']) ? htmlspecialchars(trim($_POST['course_name'])) : '';
    $courseDuration = isset($_POST['duration']) ? htmlspecialchars(trim($_POST['duration'])) : '';
    $streamId = isset($_POST['stream_id']) ? intval($_POST['stream_id']) : 0;

    // Validate input
    if (empty($courseCode) || empty($courseName) || empty($courseDuration) || $streamId <= 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required and must be valid.']);
        exit;
    }

    // Prepare SQL statement to update the course
    $sql = "UPDATE course SET course_name = ?, duration = ?, stream_id = ? WHERE course_code = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    // Bind parameters and execute the statement
    $stmt->bind_param('ssis', $courseName, $courseDuration, $streamId, $courseCode);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update course: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not POST, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
