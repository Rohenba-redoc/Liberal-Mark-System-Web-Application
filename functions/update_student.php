<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_phone = $_POST['student_phone'];
    $student_address = $_POST['student_address'];
    $mu_roll_no = $_POST['mu_roll_no'];
    $registration_no = $_POST['registration_no'];
    $abc_id = $_POST['abc_id'];
    $status = $_POST['status'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the students table
        $query = "UPDATE students SET student_name = ?, student_email = ?, student_phone = ?, student_address = ?, MU_Roll_No = ?, Registration_no = ?, Abc_id = ?, status = ? WHERE student_id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param('sssssssss', $student_name, $student_email, $student_phone, $student_address, $mu_roll_no, $registration_no, $abc_id, $status, $student_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to update students table: ' . $stmt->error);
        }

        // If status is active, insert or update the students_credentials table
        if ($status === 'active') {
            // Check if the student_id already exists in students_credentials
            $query = "SELECT COUNT(*) FROM students_credentials WHERE student_id = ?";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . $conn->error);
            }
            $stmt->bind_param('s', $student_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute SELECT query: ' . $stmt->error);
            }
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                // If student_id exists, update the existing record
                $query = "UPDATE students_credentials SET email = ?, phone = ?, status = ? WHERE student_id = ?";
                $stmt = $conn->prepare($query);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $conn->error);
                }
                $stmt->bind_param('ssss', $student_email, $student_phone, $status, $student_id);
            } else {
                // If student_id does not exist, insert a new record
                $query = "INSERT INTO students_credentials (student_id, email, phone, status) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                if ($stmt === false) {
                    throw new Exception('Prepare failed: ' . $conn->error);
                }
                $stmt->bind_param('ssss', $student_id, $student_email, $student_phone, $status);
            }

            if (!$stmt->execute()) {
                throw new Exception('Failed to insert or update students_credentials table: ' . $stmt->error);
            }
        }

        // Commit the transaction
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
