<?php
include '../includes/config.php';
header('Content-Type: application/json'); 
$success = false;

// Get form inputs
$studentId = isset($_POST['student_id']) ? $_POST['student_id'] : 0;
$courseCode = isset($_POST['course_code']) ? $_POST['course_code'] : '';
$fee = isset($_POST['fee']) ? $_POST['fee'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$semesterId = isset($_POST['semester_id']) ? (int)$_POST['semester_id'] : 0;
$departmentId = isset($_POST['department']) ? (int)$_POST['department'] : 0;
$examFee = isset($_POST['examfee']) ? $_POST['examfee'] : '';
$selectedSubjects = isset($_POST['selected_subjects']) ? explode(',', $_POST['selected_subjects']) : [];

// Validate the inputs
if ($studentId <= 0 || empty($courseCode) || empty($fee) || empty($date) || $semesterId <= 0 || empty($selectedSubjects) || $departmentId <= 0) {
    echo json_encode(['error' => 'Invalid input.']);
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // Insert into student_enroll
    $queryEnroll = "INSERT INTO student_enroll (student_id, course_code, semester_id, department_id, enroll_date, fee_status, exam_fee, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Incomplete')";
    $stmtEnroll = $conn->prepare($queryEnroll);
    $stmtEnroll->bind_param("ssiisss", $studentId, $courseCode, $semesterId, $departmentId, $date, $fee, $examFee);
    if (!$stmtEnroll->execute()) {
        throw new Exception("Error inserting into student_enroll: " . $stmtEnroll->error);
    }

    // Insert selected subjects into student_subjects table
    $querySubject = "INSERT INTO students_course_combination (student_id, subject_code, semester_id) VALUES (?, ?, ?)";
    $stmtSubject = $conn->prepare($querySubject);

    foreach ($selectedSubjects as $subjectCode) {
        $stmtSubject->bind_param("ssi", $studentId, $subjectCode, $semesterId);
        if (!$stmtSubject->execute()) {
            throw new Exception("Error inserting subject $subjectCode: " . $stmtSubject->error);
        }
    }

    // Update student's enroll status
    $queryStudent = "UPDATE students SET enroll = 1 WHERE student_id = ?";
    $stmtStudent = $conn->prepare($queryStudent);
    $stmtStudent->bind_param("s", $studentId);
    if (!$stmtStudent->execute()) {
        throw new Exception("Error updating student status: " . $stmtStudent->error);
    }

    // Commit transaction
    $conn->commit();
    $_SESSION['form_submitted'] = true; // Set session variable

    $success = true;
    echo json_encode(['success' => 'Student has been Enrolled Successfully.']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the statement and connection
$stmtEnroll->close();
$stmtSubject->close();
$stmtStudent->close();
$conn->close();
?>
