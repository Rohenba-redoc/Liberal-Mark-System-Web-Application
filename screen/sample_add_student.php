<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<?php 
session_start();
include '../includes/header.php'; 
// Check if the flag is set
$successful_save = isset($_SESSION['successful_save']) ? $_SESSION['successful_save'] : false;

if ($successful_save) {
    // Clear the flag
    unset($_SESSION['successful_save']);
    // Redirect to student.php or another page
    header("Location:student.php");
    exit();
}

?>

<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table-bordered {
        width: 100%;
        border-collapse: collapse;
    }

    .table-bordered th,
    .table-bordered td {
        padding: 2px;
        text-align: left;
    }

    .table-bordered th {
        padding: 8px;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .table-bordered td input {
        width: 100%;
        box-sizing: border-box;
        border: none;
    }

    .text-red {
        color: #b30000;
    }

    .deleteRowBtn {
        border: none;
        color: red;
    }

    #addRowBtn {
        margin-bottom: 5px;
    }

    .last {
        margin-top: 5px;
        float: right;
        margin: 10px;
    }
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    /* Toast message */
    .toast {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 0.25rem;
        padding: 10px 20px;
        margin-bottom: 10px;
        box-shadow: 0 0 0.5rem rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        width: 500px;
    }

    /* Toast visible */
    .toast.show {
        opacity: 1;
    }

    /* Toast close button */
    .toast .close-btn {
        background: transparent;
        border: none;
        color: #721c24;
        font-size: 1.2rem;
        cursor: pointer;
        position: absolute;
        top: 5px;
        right: 10px;
    }
</style>
<div id="toast-container" class="toast-container position-fixed bottom-0 right-0 p-3">
    <!-- Toast for errors -->
    <div id="error-toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="mr-auto">Error Phone and email cannot be null for</strong>
            <small class="text-muted">just now</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <!-- Error messages will be inserted here -->
        </div>
    </div>

    <!-- Toast for success -->
    <div id="success-toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="mr-auto">Success</strong>
            <small class="text-muted">just now</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <!-- Success messages will be inserted here -->
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="header-pretitle">Student</h6>
                            <h1 class="header-title">Add Student</h1>
                           

                        </div>
                        <div class="col-auto">
                            <a href="student.php" class="btn btn-danger lift">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            <form id="studentForm" action="../functions/save_student.php" method="POST">
                <div class="table-responsive">
                    <table class="table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Unique-Id<span class="text-red">*</span></th>
                                <th>Student_Name<span class="text-red">*</span></th>
                                <th>Email<span class="text-red">*</span></th>
                                <th>Phone<span class="text-red">*</span></th>
                                <th>Address<span class="text-red">*</span></th>
                                <th>MU_Roll_No</th>
                                <th>Registration_No.</th>
                                <th>ABC_ID</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="studentTable">
                        <?php
                            // Repopulate form fields with previously submitted data
                            if (isset($_SESSION['form_data'])) {
                                foreach ($_SESSION['form_data'] as $data) {
                                    echo '<tr>';
                                    foreach ($data as $key => $value) {
                                        echo '<td><input type="text" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($value) . '"></td>';
                                    }
                                    echo '<td><button type="button" class="deleteRowBtn"><i class="fe fe-trash"></i></button></td>';
                                    echo '</tr>';
                                }
                                unset($_SESSION['form_data']);
                            } else {
                                echo '<tr>
                                    <td><input type="text" name="student_id[]"></td>
                                    <td><input type="text" name="student_name[]"></td>
                                    <td><input type="text" name="student_email[]"></td>
                                    <td><input type="number" name="student_phone[]"></td>
                                    <td><input type="text" name="student_address[]"></td>
                                    <td><input type="text" name="mu_roll_no[]"></td>
                                    <td><input type="text" name="registration_no[]"></td>
                                    <td><input type="text" name="abc_id[]"></td>
                                    <td><button type="button" class="deleteRowBtn"><i class="fe fe-trash"></i></button></td>
                                </tr>';
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

                <button type="submit" name="action" value="draft" class="btn btn-secondary last">Save as Draft</button>
                <button type="submit" name="action" value="save" class="btn btn-success last">Save</button>
                <button type="button" id="addRowBtn" class="btn btn-primary last">Add Row</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addRowBtn').addEventListener('click', function () {
        const table = document.getElementById('studentTable');
        const newRow = table.insertRow();

        newRow.innerHTML = `
            <td><input type="text" name="student_id[]"></td>
            <td><input type="text" name="student_name[]"></td>
            <td><input type="text" name="student_email[]"></td>
            <td><input type="number" name="student_phone[]"></td>
            <td><input type="text" name="student_address[]"></td>
            <td><input type="text" name="mu_roll_no[]"></td>
            <td><input type="text" name="registration_no[]"></td>
            <td><input type="text" name="abc_id[]"></td>
            <td><button type="button" class="deleteRowBtn"><i class="fe fe-trash"></i></button></td>
        `;

        newRow.querySelector('.deleteRowBtn').addEventListener('click', function () {
            table.deleteRow(newRow.rowIndex - 1);
        });
    });

    document.querySelectorAll('.deleteRowBtn').forEach(button => {
        button.addEventListener('click', function () {
            const row = button.closest('tr');
            row.remove();
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastContainer = document.getElementById('toast-container');

    <?php if (isset($_SESSION['toast'])): ?>
        const toastType = '<?php echo $_SESSION['toast']['type']; ?>';
        const toastMessage = '<?php echo addslashes(implode('<br>', $_SESSION['toast']['message'])); ?>';

        const toastElement = document.getElementById(toastType + '-toast');
        const toastBody = toastElement.querySelector('.toast-body');
        toastBody.innerHTML = toastMessage;

        $(toastElement).toast({ delay: 5000 });
        $(toastElement).toast('show');

        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>
});
</script>


<?php include '../includes/footer.php'; ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Popper.js (for Bootstrap tooltips and popovers) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
