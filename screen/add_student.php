<?php
include '../includes/header.php';
include '../includes/config.php'; // Include your database connection

$status = '';
$message = '';
$form_data = [
    'unique_id' => '',
    'student_name' => '',
    'student_email' => '',
    'student_phone' => '',
    'student_address' => '',
    'mu_roll_no' => '',
    'registration_no' => '',
    'abc_id' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data['unique_id'] = $_POST['unique_id'];
    $form_data['student_name'] = $_POST['student_name'];
    $form_data['student_email'] = $_POST['student_email'];
    $form_data['student_phone'] = $_POST['student_phone'];
    $form_data['student_address'] = $_POST['student_address'];
    $form_data['mu_roll_no'] = $_POST['mu_roll_no'];
    $form_data['registration_no'] = $_POST['registration_no'];
    $form_data['abc_id'] = $_POST['abc_id'];
    $status = $_POST['status'];

    // Validate phone and email based on status
    if ($status == 'active') {
        if (empty($form_data['student_phone']) || strlen($form_data['student_phone']) < 10 ) {
            $message = 'Phone number must be 10 digits Number to save as active but can be saved as draft.';
        } elseif (empty($form_data['student_email'])) {
            $message = 'Email is required to save as active but can be saved as draft.';
        } else {
            // Check if student ID already exists
            $query = "SELECT * FROM students WHERE student_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $form_data['unique_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = 'Student ID already exists.';
            } else {
                // Insert new student record
                $query = "INSERT INTO students (student_id, student_name, student_email, student_phone, student_address, MU_Roll_No, Registration_no, Abc_id, status, enroll) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,0)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('sssssssss', $form_data['unique_id'], $form_data['student_name'], $form_data['student_email'], $form_data['student_phone'], $form_data['student_address'], $form_data['mu_roll_no'], $form_data['registration_no'], $form_data['abc_id'], $status);
                if ($stmt->execute()) {
                    // Insert into students_credentials table
                    $query = "INSERT INTO students_credentials (student_id, email, phone, status) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ssss', $form_data['unique_id'], $form_data['student_email'], $form_data['student_phone'], $status);
                    if ($stmt->execute()) {
                        $message = 'Student added successfully.';
                    } else {
                        $message = 'Failed to add student credentials.';
                    }
                    $form_data = array_fill_keys(array_keys($form_data), ''); // Clear form data if success
                } else {
                    $message = 'Failed to add student.';
                }
            }
        }
    } else {
        if (strlen($form_data['student_phone']) < 10 ) {
            $message = 'Phone number must be 10 digits Number to save as active but can be saved as draft.';
        }
        // Save as draft
        // Check if student ID already exists
        $query = "SELECT * FROM students WHERE student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $form_data['unique_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = 'Student ID already exists.';
        } else {
            // Insert new student record
            $query = "INSERT INTO students (student_id, student_name, student_email, student_phone, student_address, MU_Roll_No, Registration_no, Abc_id, status,enroll) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,0)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssssss', $form_data['unique_id'], $form_data['student_name'], $form_data['student_email'], $form_data['student_phone'], $form_data['student_address'], $form_data['mu_roll_no'], $form_data['registration_no'], $form_data['abc_id'], $status);
            if ($stmt->execute()) {
                $message = 'Student saved as draft.';
                $form_data = array_fill_keys(array_keys($form_data), ''); // Clear form data if success
            } else {
                $message = 'Failed to save student as draft.';
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-12">
            <!-- Header -->
            <div class="header mt-md-5">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">
                                New Student
                            </h6>
                            <!-- Title -->
                            <h1 class="header-title">
                                Add new Student
                            </h1>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Form -->
            <form class="mb-4" method="POST">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <label class="form-label">
                                Unique_Id <span class="text-red">*</span>
                            </label>
                            <input type="text" name="unique_id" class="form-control" value="<?php echo htmlspecialchars($form_data['unique_id']); ?>" required>
                        </td>
                        <td>
                            <label class="form-label">
                                Student_Name <span class="text-red">*</span>
                            </label>
                            <input type="text" name="student_name" class="form-control" value="<?php echo htmlspecialchars($form_data['student_name']); ?>" required>
                        </td>
                        <td>
                            <label class="form-label">
                                Student_email
                            </label>
                            <input type="email" name="student_email" class="form-control" value="<?php echo htmlspecialchars($form_data['student_email']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">
                                Student_Phone
                            </label>
                            <input type="text" name="student_phone" class="form-control" value="<?php echo htmlspecialchars($form_data['student_phone']); ?>">
                        </td>
                        <td>
                            <label class="form-label">
                                Student_address
                            </label>
                            <input type="text" name="student_address" class="form-control" value="<?php echo htmlspecialchars($form_data['student_address']); ?>">
                        </td>
                        <td>
                            <label class="form-label">
                                MU_Roll_No
                            </label>
                            <input type="text" name="mu_roll_no" class="form-control" value="<?php echo htmlspecialchars($form_data['mu_roll_no']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="form-label">
                                Registration_No
                            </label>
                            <input type="text" name="registration_no" class="form-control" value="<?php echo htmlspecialchars($form_data['registration_no']); ?>">
                        </td>
                        <td>
                            <label class="form-label">
                                ABC_ID
                            </label>
                            <input type="text" name="abc_id" class="form-control" value="<?php echo htmlspecialchars($form_data['abc_id']); ?>">
                        </td>
                        <td>
                            <button type="submit" name="status" value="active" class="btn w-30 btn-primary">Save</button>
                            <button type="submit" name="status" value="draft" class="btn w-30 btn-secondary">Save As Draft</button>
                            <a href="student.php" class="btn w-30 btn-link text-body-secondary mt-2">Back </a>
                        </td>
                    </tr>
                </table>

                <?php if ($message) : ?>
                    <div id="message" class="alert alert-info mt-2"><?php echo $message; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div> <!-- / .row -->
</div>

<?php 
include '../includes/footer.php';
?>

<script>
    // Hide the message after 2 seconds
    setTimeout(() => {
        const message = document.getElementById('message');
        if (message) {
            message.style.display = 'none';
        }
    }, 2000);
</script>
