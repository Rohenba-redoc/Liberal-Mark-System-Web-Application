<?php
include '../includes/config.php';

// Check if form is submitted and selected_students is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_students'])) {

    $date = $_POST['date'];

    // Loop through the selected students
    foreach ($_POST['selected_students'] as $studentId) {
        // Get associated data for the selected student
        $enrollId = $_POST['enroll_id'][$studentId]; // Get enroll_id from the form
        $courseCode = $_POST['course_code'][$studentId]; // Get course_code from the form
        $departmentId = $_POST['department_id'][$studentId];
        $feeStatusDate = $_POST['fee_status'][$studentId];

        // Update exam fee status
        $enrollSql = "UPDATE student_enroll SET status = 'Complete', completed_date = ? WHERE enroll_id = ?";
        if ($enrollStmt = $conn->prepare($enrollSql)) {
            $enrollStmt->bind_param("ss", $date, $enrollId);
            $enrollStmt->execute();
            $enrollStmt->close();
        }

        // Insert new enrollment record
        $insertStudent = "INSERT INTO student_enroll (student_id, course_code, department_id, semester_id, enroll_date, fee_status, exam_fee, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $nextSemesterId = $_POST['semester_id']; // Get the next semester ID from the form
        $status = 'Incomplete'; // Set the status as 'Incomplete'
        $examfee = 'Not_Paid';
        if ($insertStmt = $conn->prepare($insertStudent)) {
            $insertStmt->bind_param("ssisssss", $studentId, $courseCode, $departmentId, $nextSemesterId, $date, $feeStatusDate, $examfee, $status);
            $insertStmt->execute();
            $insertStmt->close();
        }

        // Process the subjects for the selected student
        if (isset($_POST['subjects'][$studentId])) {
            $subjects = $_POST['subjects'][$studentId];

            // Split the subjects into an array
            $subjectCodes = explode(',', $subjects[0]);

            // Insert into student_course_combination
            foreach ($subjectCodes as $subjectCode) {
                // Trim any whitespace from the subject code
                $subjectCode = trim($subjectCode);

                // Check if the subject code exists
                $subjectCheckSql = "SELECT COUNT(*) FROM subject WHERE subject_code = ?";
                if ($checkStmt = $conn->prepare($subjectCheckSql)) {
                    $checkStmt->bind_param("s", $subjectCode);
                    $checkStmt->execute();
                    $checkStmt->bind_result($count);
                    $checkStmt->fetch();
                    $checkStmt->close();

                    if ($count > 0) {
                        // Insert into student_course_combination if the subject exists
                        $insertSql = "INSERT INTO students_course_combination (student_id, subject_code, semester_id) VALUES (?, ?, ?)";
                        if ($insertStmt = $conn->prepare($insertSql)) {
                            $insertStmt->bind_param("ssi", $studentId, $subjectCode, $nextSemesterId);
                            $insertStmt->execute();
                            $insertStmt->close();
                        }
                    } else {
                        echo "<script>alert('Error: Subject code $subjectCode does not exist.');</script>";
                    }
                }
            }
        }
    }
    
    echo "<script>alert('Selected students have been upgraded successfully.'); window.location.href = 'enrolled_student.php';</script>";
} else {
    // Redirect if no students were selected
    echo "<script>alert('No students selected. Please go back and try again.'); window.history.back();</script>";
}
?>
