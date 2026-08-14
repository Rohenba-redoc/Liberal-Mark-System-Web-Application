<?php
include '../includes/config.php';
header('Content-Type: application/json'); 

$success = false;
$enroll_id = isset($_POST['enroll_id']) ? (int)$_POST['enroll_id'] : 0;
$studentId = isset($_POST['student_id']) ? $_POST['student_id'] : 0;
$courseCode = isset($_POST['course_code']) ? $_POST['course_code'] : '';
$newSemesterId = isset($_POST['new_semester']) ? (int)$_POST['new_semester'] : 0;
$newCombinationId = isset($_POST['new_combination']) ? (int)$_POST['new_combination'] : 0;
$newDate = isset($_POST['new_date']) ? $_POST['new_date'] : '';
$fee = isset($_POST['fee']) ? $_POST['fee'] : '';

if ($enroll_id <= 0 || $studentId <= 0 || empty($courseCode) || $newSemesterId <= 0 || $newCombinationId <= 0 || empty($newDate) || empty($fee)) {
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // Update the status of the current enrollment
    $queryUpdateEnroll = "UPDATE student_enroll SET status = 'Complete', completed_date = ? WHERE enroll_id = ?";
    $stmtUpdateEnroll = $conn->prepare($queryUpdateEnroll);
    $stmtUpdateEnroll->bind_param("si", $newDate, $enroll_id);
    if (!$stmtUpdateEnroll->execute()) {
        throw new Exception("Error updating student_enroll: " . $stmtUpdateEnroll->error);
    }

    // Insert a new row in student_enroll for the new semester
    $queryNewEnroll = "INSERT INTO student_enroll (student_id, course_code, semester_id, enroll_date, fee_status, status) 
                       VALUES (?, ?, ?, ?, ?, 'Incomplete')";
    $stmtNewEnroll = $conn->prepare($queryNewEnroll);
    $stmtNewEnroll->bind_param("ssiss", $studentId, $courseCode, $newSemesterId, $newDate, $fee);
    if (!$stmtNewEnroll->execute()) {
        throw new Exception("Error inserting new row into student_enroll: " . $stmtNewEnroll->error);
    }

    // Insert into students_course_combination for the new semester
    $queryNewCourseCombination = "INSERT INTO students_course_combination (student_id, subject_code, semester_id) 
                                  SELECT ?, cc.subject_code, ? 
                                  FROM course_combination AS cc 
                                  WHERE cc.combination_id = ?";
    $stmtNewCourseCombination = $conn->prepare($queryNewCourseCombination);
    $stmtNewCourseCombination->bind_param("sii", $studentId, $newSemesterId, $newCombinationId);
    if (!$stmtNewCourseCombination->execute()) {
        throw new Exception("Error inserting new row into students_course_combination: " . $stmtNewCourseCombination->error);
    }

    // Commit transaction
    $conn->commit();
    $_SESSION['form_submitted'] = true; // Set session variable
    $success = true;
    echo json_encode(['success' => true, 'message' => 'Student enrollment updated successfully.']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the statement and connection
$stmtUpdateEnroll->close();
$stmtNewEnroll->close();
$stmtNewCourseCombination->close();
$conn->close();
?>
