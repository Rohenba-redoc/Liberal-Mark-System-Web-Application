<?php
include '../includes/config.php'; // Ensure you include your database connection
session_start(); // Start the session to use session variables

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Retrieve the IDs of the students to update
        $student_ids = isset($_POST['student_ids']) ? $_POST['student_ids'] : [];

        if (!empty($student_ids)) {
            // Store selected student IDs in session to display later

            foreach ($student_ids as $student_id) {
                // Fetch individual data from POST
                $name = isset($_POST["student_name"][$student_id]) ? $_POST["student_name"][$student_id] : '';
                $email = isset($_POST["student_email"][$student_id]) ? $_POST["student_email"][$student_id] : '';
                $phone = isset($_POST["student_phone"][$student_id]) ? $_POST["student_phone"][$student_id] : '';
                $address = isset($_POST["student_address"][$student_id]) ? $_POST["student_address"][$student_id] : '';
                $mu_roll_no = isset($_POST["MU_Roll_No"][$student_id]) ? $_POST["MU_Roll_No"][$student_id] : '';
                $registration_no = isset($_POST["Registration_no"][$student_id]) ? $_POST["Registration_no"][$student_id] : '';
                $abc_id = isset($_POST["Abc_id"][$student_id]) ? $_POST["Abc_id"][$student_id] : '';

                // Log data for debugging
                error_log("Updating student ID $student_id with data: " . print_r([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'mu_roll_no' => $mu_roll_no,
                    'registration_no' => $registration_no,
                    'abc_id' => $abc_id
                ], true));

                // Validate email and phone
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid email format for student Name =' . $name);
                }
                
                if (empty($phone) || strlen($phone) < 10 || strlen($phone) > 10) {
                    throw new Exception('Invalid phone number format for student name = ' . $name);
                }

                // Prepare the update statement for students table
                $stmt = $conn->prepare("
                    UPDATE students 
                    SET student_name = ?, 
                        student_email = ?, 
                        student_phone = ?, 
                        student_address = ?, 
                        MU_Roll_No = ?, 
                        Registration_no = ?, 
                        Abc_id = ?, 
                        status = 'active' 
                    WHERE student_id = ?
                ");

                // Bind parameters
                $stmt->bind_param(
                    'ssssssss', 
                    $name,
                    $email,
                    $phone,
                    $address,
                    $mu_roll_no,
                    $registration_no,
                    $abc_id,
                    $student_id
                );

                // Execute the update
                if (!$stmt->execute()) {
                    throw new Exception('Update failed for student ID ' . $student_id . ': ' . $stmt->error);
                }

                // Prepare the insert statement for students_credentials table
                $stmt = $conn->prepare("
                    INSERT INTO students_credentials (student_id, email, phone, status) 
                    VALUES (?, ?, ?, 'active')
                    ON DUPLICATE KEY UPDATE 
                    email = VALUES(email), 
                    phone = VALUES(phone), 
                    status = 'active'
                ");

                // Bind parameters
                $stmt->bind_param(
                    'sss', 
                    $student_id,
                    $email,
                    $phone
                );

                // Execute the insert
                if (!$stmt->execute()) {
                    throw new Exception('Insert into credentials failed for student ID ' . $student_id . ': ' . $stmt->error);
                }
            }

            // Commit the transaction
            $conn->commit();
            
            // Redirect or show a success message
            header('Location: ../screen/edit_draft_students.php?status=success');
            exit;

        } else {
            // Redirect back to the form page with an error message
            $_SESSION['error_message'] = 'No students selected for update.';
            header('Location: ../screen/edit_draft_students.php');
            exit;
        }

    } catch (Exception $e) {
        $conn->rollback();

        // Store the form data and error message in the session
        $_SESSION['form_data'] = $_POST;
        $_SESSION['error_message'] = $e->getMessage();

        // Redirect to the form page with an error message
        header('Location: ../screen/edit_draft_students.php');
        exit;
    }
}
?>
