<?php
require '../vendor/autoload.php'; // Include Composer's autoloader

use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection
include '../includes/config.php'; // Assuming you have a file with your DB connection

// Check if a file was uploaded
if ($_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['excel_file']['tmp_name'];

    // Load the spreadsheet
    $spreadsheet = IOFactory::load($fileTmpPath);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = $worksheet->toArray();

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Prepare statement for inserting students table
        $stmtStudentInsert = $conn->prepare("
            INSERT INTO students (student_id, student_name, student_email, student_phone, student_address, MU_Roll_No, Registration_no, Abc_id, status, enroll)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', 0)
        ");
        
        // Prepare statement for updating students table
        $stmtStudentUpdate = $conn->prepare("
            UPDATE students
            SET student_name = ?, student_email = ?, student_phone = ?, student_address = ?, MU_Roll_No = ?, Registration_no = ?, Abc_id = ?, status = 'active'
            WHERE student_id = ?
        ");

        // Prepare statements for students_credentials table
        $stmtCheckCredentials = $conn->prepare("SELECT COUNT(*) FROM students_credentials WHERE student_id = ?");
        $stmtInsertCredentials = $conn->prepare("
            INSERT INTO students_credentials (student_id, email, phone, status)
            VALUES (?, ?, ?, 'active')
        ");
        $stmtUpdateCredentials = $conn->prepare("
            UPDATE students_credentials
            SET email = ?, phone = ?, status = 'active'
            WHERE student_id = ?
        ");

        foreach ($data as $row) {
            // Skip header row (optional)
            if ($row[0] === 'student_id') {
                continue;
            }

            // Assuming the Excel columns are in the same order as your database fields
            list($student_id, $student_name, $student_email, $student_phone, $student_address, $MU_Roll_No, $Registration_no, $Abc_id) = $row;

            // Handle NULL values
            $MU_Roll_No = !empty($MU_Roll_No) ? $MU_Roll_No : null;
            $Registration_no = !empty($Registration_no) ? $Registration_no : null;
            $Abc_id = !empty($Abc_id) ? $Abc_id : null;

            // Check if the student_id exists
            $stmtCheckCredentials->bind_param('s', $student_id);
            $stmtCheckCredentials->execute();
            $stmtCheckCredentials->bind_result($count);
            $stmtCheckCredentials->fetch();
            $stmtCheckCredentials->free_result(); // Free the result set

            if ($count > 0) {
                // Update existing student
                $stmtStudentUpdate->bind_param(
                    'ssssssss',
                    $student_name,
                    $student_email,
                    $student_phone,
                    $student_address,
                    $MU_Roll_No,
                    $Registration_no,
                    $Abc_id,
                    $student_id
                );
                $stmtStudentUpdate->execute();
            } else {
                // Insert new student
                $stmtStudentInsert->bind_param(
                    'ssssssss',
                    $student_id,
                    $student_name,
                    $student_email,
                    $student_phone,
                    $student_address,
                    $MU_Roll_No,
                    $Registration_no,
                    $Abc_id
                );
                $stmtStudentInsert->execute();
            }

            // Check if the student_id exists in students_credentials
            $stmtCheckCredentials->bind_param('s', $student_id);
            $stmtCheckCredentials->execute();
            $stmtCheckCredentials->bind_result($count);
            $stmtCheckCredentials->fetch();
            $stmtCheckCredentials->free_result(); // Free the result set

            if ($count > 0) {
                // Update existing credentials
                $stmtUpdateCredentials->bind_param('sss', $student_email, $student_phone, $student_id);
                $stmtUpdateCredentials->execute();
            } else {
                // Insert new credentials
                $stmtInsertCredentials->bind_param('sss', $student_id, $student_email, $student_phone);
                $stmtInsertCredentials->execute();
            }
        }

        // Commit transaction
        $conn->commit();
        echo 'Data has been successfully imported.';

    } catch (Exception $e) {
        $conn->rollback();
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'File upload failed. Please try again.';
}
?>
