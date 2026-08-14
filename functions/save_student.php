<?php
session_start();
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $is_save = ($action == 'save') ? 'active' : 'draft';

    $student_ids = $_POST['student_id'];
    $student_names = $_POST['student_name'];
    $student_emails = $_POST['student_email'];
    $student_phones = $_POST['student_phone'];
    $student_addresses = $_POST['student_address'];
    $mu_roll_nos = $_POST['mu_roll_no'];
    $registration_nos = $_POST['registration_no'];
    $abc_ids = $_POST['abc_id'];

    $form_data = [];
    $errors = []; 

    if (isset($_POST['student_id'])) {
        $num_rows = count($_POST['student_id']);
        for ($i = 0; $i < $num_rows; $i++) {
            $form_data[] = [
                'student_id' => $_POST['student_id'][$i],
                'student_name' => $_POST['student_name'][$i],
                'student_email' => $_POST['student_email'][$i],
                'student_phone' => $_POST['student_phone'][$i],
                'student_address' => $_POST['student_address'][$i],
                'mu_roll_no' => $_POST['mu_roll_no'][$i],
                'registration_no' => $_POST['registration_no'][$i],
                'abc_id' => $_POST['abc_id'][$i]
            ];
        }
    }

    $_SESSION['form_data'] = $form_data;

    // Example validation
    foreach ($form_data as $data) {
        if (empty($data['student_id']) || empty($data['student_name'])) {
            $errors[] = 'Unique-Id and Student Name are required fields.';
            break;
        }
        if ($is_save === 'active' && (empty($data['student_phone']) || empty($data['student_email']))) {
            $errors[] = "Phone and email are required for student ID: " . $data['student_id'];
        }
    }

    if (empty($errors)) {
        foreach ($form_data as $data) {
            $student_id = $data['student_id'];
            $student_name = $data['student_name'];
            $student_email = $data['student_email'];
            $student_phone = $data['student_phone'];
            $student_address = $data['student_address'];
            $mu_roll_no = $data['mu_roll_no'];
            $registration_no = $data['registration_no'];
            $abc_id = $data['abc_id'];

            // Check if student_id already exists
            $stmt_check = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
            $stmt_check->bind_param("s", $student_id);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                // student_id exists, so update the record
                $stmt_student = $conn->prepare("UPDATE students SET student_name = ?, student_email = ?, student_phone = ?, student_address = ?, mu_roll_no = ?, registration_no = ?, abc_id = ?, status = ? WHERE student_id = ?");
                $stmt_student->bind_param("sssssssss", $student_name, $student_email, $student_phone, $student_address, $mu_roll_no, $registration_no, $abc_id, $is_save, $student_id);
            } else {
                // student_id does not exist, so insert a new record
                $stmt_student = $conn->prepare("INSERT INTO students (student_id, student_name, student_email, student_phone, student_address, mu_roll_no, registration_no, abc_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_student->bind_param("sssssssss", $student_id, $student_name, $student_email, $student_phone, $student_address, $mu_roll_no, $registration_no, $abc_id, $is_save);
            }

            if (!$stmt_student->execute()) {
                $errors[] = "Failed to save student ID: $student_id. Error: " . $stmt_student->error;
            }

            if ($is_save === 'active') {
                // Validate phone and email for save action
                if (empty($student_phone) || empty($student_email)) {
                    $errors[] = "Phone and email are required for student ID: $student_id";
                } else {
                    $stmt_credentials = $conn->prepare("INSERT INTO students_credentials (student_id, phone, email, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE phone=VALUES(phone), email=VALUES(email), status=VALUES(status)");
                    $stmt_credentials->bind_param("ssss", $student_id, $student_phone, $student_email, $is_save);
                    if (!$stmt_credentials->execute()) {
                        $errors[] = "Failed to update credentials for student ID: $student_id. Error: " . $stmt_credentials->error;
                    }
                }
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => $errors
        ];
        header("Location: ../screen/add_student.php");
        exit();
    } $_SESSION['toast'] = [
        'type' => 'success',
        'message' => ["Student(s) saved successfully!"]
    ];
    $_SESSION['successful_save'] = true; 
    header("Location: ../screen/student.php");
    exit();

    $conn->close();
}
?>
