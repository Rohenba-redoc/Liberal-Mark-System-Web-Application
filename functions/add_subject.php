<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/config.php';

// Decode the JSON data sent via the form submission
$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Get the subjects, department, and semester from the request
$subjects = $data['subjects'] ?? [];
$department_id = $data['department'] ?? null; // Allow department to be null
$semester_id = $data['semester'] ?? null;

if (empty($subjects) || empty($semester_id)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Subjects or Semester missing']);
    exit;
}

// Prepare a statement to check for duplicate subject codes
$checkStmt = $conn->prepare("SELECT COUNT(*) FROM subject WHERE subject_code = ?");
if (!$checkStmt) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

// Check for duplicates for each subject
foreach ($subjects as $subject) {
    $checkStmt->bind_param("s", $subject['code']);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();

    if ($count > 0) {
        $checkStmt->close();
        $conn->close();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Duplicate subject code: ' . $subject['code']]);
        exit;
    }
}

// If no duplicates found, proceed with the insertion
$checkStmt->close(); // Close the duplicate check statement

// Prepare the insert statement
$stmt = $conn->prepare("INSERT INTO subject (subject_code, subject_name, department_id, semester_id, type) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

// Insert each subject into the database
foreach ($subjects as $subject) {
    $subject_code = $subject['code'];
    $subject_name = $subject['name'];
    $type = $subject['type']; // "core" or "optional"

    // Bind department_id as null if it's not provided
    if ($department_id === null) {
        $stmt->bind_param("ssiss", $subject_code, $subject_name, $department_id, $semester_id, $type);
    } else {
        $stmt->bind_param("ssiis", $subject_code, $subject_name, $department_id, $semester_id, $type);
    }

    if (!$stmt->execute()) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        exit;
    }
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();

// Return success message
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Subjects added successfully']);
?>
