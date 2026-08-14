<?php
include '../functions/display.php';
include '../includes/header.php';

// Fetch draft students
$students = fetchStudentsByStatus('draft');

// Start session to check for error messages and form data
$errors = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
$selected_ids = isset($form_data['student_ids']) ? $form_data['student_ids'] : [];
unset($_SESSION['error_message']);
unset($_SESSION['form_data']);
$selected_student_ids = isset($_SESSION['selected_student_ids']) ? $_SESSION['selected_student_ids'] : [];
unset($_SESSION['selected_student_ids']);
?>

<style>
   .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    
   .submit{
    margin:10px;
    float:right;
   }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">

            <!-- Header -->
            <div class="header">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">Overview</h6>
                            <!-- Title -->
                            <h1 class="header-title">Edit Draft Students</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="student.php" class="btn btn-primary lift">Back to Students</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errors); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <form method="post" action="../functions/update_students.php">
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap card-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>MU Roll No</th>
                                    <th>Registration No</th>
                                    <th>ABC ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($students)): ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <!-- Checkbox Input -->
                                            <td>
                                                <input 
                                                    type="checkbox" 
                                                    name="student_ids[]" 
                                                    value="<?php echo htmlspecialchars($student['student_id']); ?>"
                                                    <?php echo in_array($student['student_id'], $selected_ids) ? 'checked' : ''; ?>
                                                >
                                            </td>
                                            
                                            <td><input type="text" name="student_name[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["student_name"][$student['student_id']]) ? htmlspecialchars($form_data["student_name"][$student['student_id']]) : htmlspecialchars($student['student_name']); ?>" ></td>
                                            <td><input type="email" name="student_email[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["student_email"][$student['student_id']]) ? htmlspecialchars($form_data["student_email"][$student['student_id']]) : htmlspecialchars($student['student_email']); ?>"></td>
                                            <td><input type="text" name="student_phone[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["student_phone"][$student['student_id']]) ? htmlspecialchars($form_data["student_phone"][$student['student_id']]) : htmlspecialchars($student['student_phone']); ?>"></td>
                                            <td><input type="text" name="student_address[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["student_address"][$student['student_id']]) ? htmlspecialchars($form_data["student_address"][$student['student_id']]) : htmlspecialchars($student['student_address']); ?>"></td>
                                            <td><input type="text" name="MU_Roll_No[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["MU_Roll_No"][$student['student_id']]) ? htmlspecialchars($form_data["MU_Roll_No"][$student['student_id']]) : htmlspecialchars($student['MU_Roll_No']); ?>"></td>
                                            <td><input type="text" name="Registration_no[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["Registration_no"][$student['student_id']]) ? htmlspecialchars($form_data["Registration_no"][$student['student_id']]) : htmlspecialchars($student['Registration_no']); ?>"></td>
                                            <td><input type="text" name="Abc_id[<?php echo htmlspecialchars($student['student_id']); ?>]" class="form-control" value="<?php echo isset($form_data["Abc_id"][$student['student_id']]) ? htmlspecialchars($form_data["Abc_id"][$student['student_id']]) : htmlspecialchars($student['Abc_id']); ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No students found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary submit">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>



<?php include '../includes/footer.php'; ?>
