<?php
include '../includes/config.php';

// Check if form is submitted and selected_students is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_students']) && isset($_POST['date'])) {

    $date = $_POST['date'];

    // Validate the date field (optional, based on the format you expect)
    if (empty($date)) {
        echo "<script>alert('Date field is required. Please try again.'); window.history.back();</script>";
        exit;
    }

    // Loop through the selected students
    foreach ($_POST['selected_students'] as $studentId) {
        // Check if enroll_id exists for the current studentId
        if (isset($_POST['enroll_id'][$studentId])) {
            $enrollId = $_POST['enroll_id'][$studentId]; // Get enroll_id from the form

            // Update exam fee status
            $enrollSql = "UPDATE student_enroll SET status = 'Complete', completed_date = ? WHERE enroll_id = ?";
            if ($enrollStmt = $conn->prepare($enrollSql)) {
                $enrollStmt->bind_param("ss", $date, $enrollId);
                if ($enrollStmt->execute()) {
                    // Success: Optional logging or further actions can be added here
                } else {
                    echo "<script>alert('Error updating student with ID: {$studentId}. Please try again.'); window.history.back();</script>";
                    exit;
                }
                $enrollStmt->close();
            } else {
                echo "<script>alert('Error preparing the SQL statement. Please try again.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Enroll ID not found for student with ID: {$studentId}.'); window.history.back();</script>";
            exit;
        }
    }

    // Success message and redirect after all updates
    echo "<script>alert('Selected students have been upgraded successfully.'); window.location.href = 'enrolled_student.php';</script>";
} else {
    // Redirect if no students were selected or date not set
    echo "<script>alert('No students selected or missing date. Please go back and try again.'); window.history.back();</script>";
}
?>
