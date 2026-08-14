<?php
include '../includes/config.php';  // Ensure this file exists and is correctly included

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check if data was decoded successfully
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data.']);
    exit;
}

// Get teacherId
$teacherId = $data['teacher_id'] ?? null;

// Check if teacherId is provided
if ($teacherId === null) {
    echo json_encode(['success' => false, 'message' => 'Missing teacher ID.']);
    exit;
}
// Prepare the SQL query
$sql = "SELECT Id,email, phone, password, status FROM teacher_credentials WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'SQL prepare failed: ' . $conn->error]);
    exit;
}

// Bind the parameter
$stmt->bind_param("s", $teacherId);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$credentials = $result->fetch_assoc();

// Check if credentials are found
if ($credentials) {
    echo json_encode(['success' => true, 'credentials' => $credentials]);
} else {
    echo json_encode(['success' => false, 'message' => 'Credentials not found.']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
