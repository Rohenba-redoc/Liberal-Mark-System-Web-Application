<?php
include '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

$subjectCode = $data['subject_code'] ?? '';
$subjectName = $data['subject_name'] ?? '';
$semester = $data['semester'] ?? '';
$department = $data['department'] ?? '';
$type = $data['type'] ?? '';

if (!$subjectCode || !$subjectName || !$semester || !$department || !$type) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$stmt = $conn->prepare("UPDATE subject SET subject_name = ?, semester_id = ?, department_id = ?, type = ?  WHERE subject_code = ?");
if (!$stmt) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("siiss", $subjectName,$semester,$department,$type, $subjectCode);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Subject updated successfully']);
} else {
    $stmt->close();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to update subject']);
}
?>
