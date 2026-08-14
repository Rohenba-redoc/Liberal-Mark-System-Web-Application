<?php
require '../vendor/autoload.php';
include '../includes/config.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
    $fileTmpPath = $_FILES['excel_file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Skip header row
        array_shift($data);

        // Prepare statements
        $stmtStudentInsert = $conn->prepare("
            INSERT INTO students (student_id, student_name, student_email, student_phone, student_address, MU_Roll_No, Registration_no, Abc_id, status, enroll)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active', 0)
        ");
        
        $stmtStudentUpdate = $conn->prepare("
            UPDATE students
            SET student_name = ?, student_email = ?, student_phone = ?, student_address = ?, MU_Roll_No = ?, Registration_no = ?, Abc_id = ?, status = 'active'
            WHERE student_id = ?
        ");

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
            $id = $row[0];
            $name = $row[1];
            $email = $row[2];
            $phone = $row[3];
            $address = $row[4];
            $mu = $row[5];
            $registration = $row[6];
            $abc = $row[7];

            // Check if student already exists
            $stmtCheckStudent = $conn->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
            $stmtCheckStudent->bind_param('s', $id);
            $stmtCheckStudent->execute();
            $stmtCheckStudent->bind_result($studentCount);
            $stmtCheckStudent->fetch();
            $stmtCheckStudent->close();

            if ($studentCount > 0) {
                // Update existing student record
                $stmtStudentUpdate->bind_param('ssssssss', $name, $email, $phone, $address, $mu, $registration, $abc, $id);
                $stmtStudentUpdate->execute();
            } else {
                // Insert new student record
                $stmtStudentInsert->bind_param('ssssssss', $id, $name, $email, $phone, $address, $mu, $registration, $abc);
                $stmtStudentInsert->execute();
            }

            // Check if student credentials already exist
            $stmtCheckCredentials->bind_param('s', $id);
            $stmtCheckCredentials->execute();
            $stmtCheckCredentials->bind_result($credentialsCount);
            $stmtCheckCredentials->fetch();
            $stmtCheckCredentials->close();

            if ($credentialsCount > 0) {
                // Update existing credentials
                $stmtUpdateCredentials->bind_param('sss', $email, $phone, $id);
                $stmtUpdateCredentials->execute();
            } else {
                // Insert new credentials
                $stmtInsertCredentials->bind_param('sss', $id, $email, $phone);
                $stmtInsertCredentials->execute();
            }
        }

        echo json_encode(['success' => true, 'message' => 'Data successfully inserted/updated!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error processing the file: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or file upload error.']);
}
?>
