<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get the POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $subjectCodes = $data['subject_codes'];

    if (!is_array($subjectCodes) || empty($subjectCodes)) {
        throw new Exception('Invalid input');
    }

    // Prepare the SQL statement
    $placeholders = implode(',', array_fill(0, count($subjectCodes), '?'));
    $sql = "DELETE FROM subject WHERE subject_code IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }

    if (!$stmt->execute($subjectCodes)) {
        throw new Exception('Failed to delete subjects');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('Subjects not found or already deleted');
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Subjects deleted successfully']);

} catch (Exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
    echo json_encode(['success' => false, 'message' => 'Cannot delete subjects. They might be associated with students or teachers.']);
}
?>
