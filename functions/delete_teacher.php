<?php
ob_start(); // Start output buffering

include '../includes/config.php';

header('Content-Type: application/json'); // Set content type to JSON

// Retrieve input data
$data = json_decode(file_get_contents('php://input'), true);
$teacherId = $data['teacher_id'] ?? '';

try {
    // Validate input
    if (!$teacherId) {
        throw new Exception('Invalid input');
    }

    // Begin a transaction
    $conn->begin_transaction();

    // Delete from teacher_department
    $stmt = $conn->prepare("DELETE FROM teacher_department WHERE teacher_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("i", $teacherId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from teacher_department');
    }
    $stmt->close(); // Close the statement

    // Delete from teacher_credentials
    $stmt = $conn->prepare("DELETE FROM teacher_credentials WHERE teacher_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("i", $teacherId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from teacher_credentials');
    }
    $stmt->close(); // Close the statement

    // Delete from teacher
    $stmt = $conn->prepare("DELETE FROM teacher WHERE teacher_id = ?");
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    $stmt->bind_param("i", $teacherId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete from teacher');
    }

    // Check if rows were affected
    if ($stmt->affected_rows === 0) {
        throw new Exception('Teacher not found or already deleted');
    }
    $stmt->close(); // Close the statement

    // Commit transaction
    $conn->commit();

    // Return success message
    echo json_encode(['success' => true, 'message' => 'Teacher deleted successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    // Return error message
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Ensure statement and connection are closed
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}

ob_end_flush(); // Flush the output buffer
