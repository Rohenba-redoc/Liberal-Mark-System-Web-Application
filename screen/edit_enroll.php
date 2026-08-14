<?php 
include '../includes/header.php';
include '../includes/config.php';
if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true) {
    header('Location: enrolled_student.php');
    exit;
}
unset($_SESSION['form_submitted']);

// Get the enroll_id from the URL
$enroll_id = isset($_GET['enroll_id']) ? $_GET['enroll_id'] : null;

if ($enroll_id) {
    try {
        // Fetch the enrollment details
        $sql = "SELECT se.enroll_id, s.student_id, s.student_name,c.course_code, c.course_name, sem.semester_name, 
                GROUP_CONCAT(CONCAT(sub.subject_name, ' - ', sub.subject_code) SEPARATOR ', ') AS subjects,
                se.enroll_date, se.fee_status, se.status
                FROM student_enroll se
                JOIN students s ON se.student_id = s.student_id
                JOIN course c ON se.course_code = c.course_code
                JOIN semester sem ON se.semester_id = sem.semester_id
                JOIN students_course_combination scc ON se.student_id = scc.student_id AND se.semester_id = scc.semester_id
                JOIN subject sub ON scc.subject_code = sub.subject_code
                WHERE se.enroll_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $enroll_id);
        $stmt->execute();
        $stmt->bind_result($enroll_id, $student_id, $student_name,$course_code, $course_name, $semester_name, $subjects, $enroll_date, $fee_status, $status);
        $stmt->fetch();
        $stmt->close();

        // Fetch available semesters for the student
        $sql_new_semester = "SELECT semester_id, semester_name
                             FROM semester
                             WHERE semester_id NOT IN (
                                SELECT DISTINCT semester_id
                                FROM student_enroll
                                WHERE student_id = ? 
                             )
                             
                             ";
        $stmt_new_semester = $conn->prepare($sql_new_semester);
        $stmt_new_semester->bind_param("s", $student_id);
        $stmt_new_semester->execute();
        $new_semesters = $stmt_new_semester->get_result();
        $stmt_new_semester->close();
        
        // Fetch available years from course_combination
        $sql_years = "SELECT DISTINCT year FROM course_combination";
        $stmt_years = $conn->prepare($sql_years);
        $stmt_years->execute();
        $result_years = $stmt_years->get_result();
        $stmt_years->close();

    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>
    alert('No enrollment ID provided!');
    window.location.href = 'enrolled_student.php';
  </script>";
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="header">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Title -->
                            <h1 class="header-title">
                                Upgrade Semester ( <?php echo $student_name; ?> - <?php echo $student_id; ?> )
                            </h1>
                           
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="enrolled_student.php" class="btn btn-primary lift">Cancel</a>
                           
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Form -->
            <form id="updateEnrollmentForm" action="../functions/update_enroll.php" method="POST">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="enroll_id" value="<?php echo $enroll_id; ?>">
                        <input type="hidden" class="form-control" id="student_id" name="student_id" value="<?php echo $student_id; ?>" readonly>

                        <input type="hidden" class="form-control" id="student_name" name="student_name" value="<?php echo $student_name; ?>" readonly>
                        <div style="display:flex;justify-content:center;margin-bottom:10px;">
                        <span style="font-size:24px;">Current Semester</span>
                        </div>
                        <div style="display:flex; justify-content:space-between">
                            <div class="form-group col-3">
                                <label for="course_name">Course</label>
                                <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo $course_name; ?>" readonly>
                                <input type="hidden" class="form-control" id="course_code" name="course_code" value="<?php echo $course_code; ?>" readonly>
                            </div>
                            <div class="form-group col-3">
                                <label for="semester_name">Current Semester</label>
                                <input type="text" class="form-control" id="semester_name" name="semester_name" value="<?php echo $semester_name; ?>" readonly>
                            </div>
                            <div class="form-group col-3">
                                <label for="enroll_date">Enroll Date</label>
                                <input type="text" class="form-control" id="enroll_date" readonly name="enroll_date" value="<?php echo $enroll_date; ?>">
                            </div>
                        </div>

                        <div style="display:flex;justify-content:space-between">
                            <div class="form-group col-12">
                                <label for="subjects">Subjects</label>
                                <input type="text" class="form-control" id="subjects" name="subjects" readonly value="<?php echo $subjects; ?>">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div style="display:flex;justify-content:center;margin-bottom:10px;">
                        <span style="font-size:24px;">New  Semester</span>
                        </div>

                       <div style="display:flex;justify-content:space-between;">
                            <div class="col-5 mb-3">
                                <label for="year">Year</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">Select Year</option>
                                    <?php while ($row = $result_years->fetch_assoc()): ?>
                                        <option value="<?php echo $row['year']; ?>"><?php echo $row['year']; ?> Year</option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="new_semester">New Semester</label>
                                <select name="new_semester" id="new_semester" class="form-control">
                                    <?php while ($row = $new_semesters->fetch_assoc()): ?>
                                        <option value="<?php echo $row['semester_id']; ?>"><?php echo $row['semester_name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                       </div>
                        <div style="display:flex;justify-content:space-between">
                                <div class="col-5">
                                    <label for="new_combination">New Subject Combination</label>
                                    <select name="new_combination" id="new_combination" class="form-control">
                                        <option value="">Select Combination</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label for="new_date">Enroll Date</label>
                                    <input type="date" name="new_date" id="new_date" class="form-control">
                                </div>
                        </div>
                        <div class="col-5">
                                    <label for="fee">Fee Status</label>
                                    <select name="fee" id="fee" class="form-control">
                                        <option value="Paid">Paid</option>
                                        <option value="Not_Paid">Not Paid</option>
                                        </select>
                                </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Enrollment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
document.getElementById('year').addEventListener('change', function() {
    var year = this.value;
    var courseCode = document.getElementById('course_code').value; 

    if (year && courseCode) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../functions/get_combinations.php?year=' + year + '&course_code=' + encodeURIComponent(courseCode), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var combinations = JSON.parse(xhr.responseText);
                var combinationSelect = document.getElementById('new_combination');
                combinationSelect.innerHTML = '<option value="">Select Combination</option>';
                
                combinations.forEach(function(combination) {
                    var option = document.createElement('option');
                    option.value = combination.combination_id;
                    option.text = combination.subject_info;
                    combinationSelect.add(option);
                });
            }
        };
        xhr.send();
    }
});
</script>
<script>
document.getElementById('updateEnrollmentForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // Show the success message

            // Redirect to the enrolled student list and replace the current page in history
            window.location.replace('enrolled_student.php');
        } else {
            alert('Error: ' + data.error); // Show the error message
        }
    })
    .catch(error => {
        alert('An unexpected error occurred: ' + error.message);
    });
});
</script>

