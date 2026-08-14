<?php
include '../includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $studentId = $data['student_id'] ?? '';

    if (!$studentId) {
        throw new Exception('Invalid input');
    }

    // Begin a transaction
    $conn->begin_transaction();

    // Delete from teacher_credentials
    $stmt = $conn->prepare("DELETE FROM students_credentials WHERE student_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("s", $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from student_credentials');
    }

    $stmt = $conn->prepare("DELETE FROM students_course_combination WHERE student_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("s", $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from student_credentials');
    }

    $stmt = $conn->prepare("DELETE FROM student_enroll WHERE student_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("s", $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from student_credentials');
    }

    $stmt = $conn->prepare("DELETE FROM unit_test_marks WHERE student_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("s", $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from student_credentials');
    }

    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("s", $studentId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from student');
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('student not found or already deleted');
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }


    echo json_encode(['success' => false, 'message' => 'Cannot delete Student. It is associated with some Information .']);
}
?>
