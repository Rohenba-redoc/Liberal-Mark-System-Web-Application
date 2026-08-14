<?php include '../includes/header.php'; 
include '../includes/config.php'; ?>
<?php 

$student_id = $_GET['id']; 
$semester_sql = "
    SELECT DISTINCT semester_id 
    FROM unit_test_marks 
    WHERE student_id = ? 
    ORDER BY semester_id";
$semester_stmt = $conn->prepare($semester_sql);
$semester_stmt->bind_param("s", $student_id);
$semester_stmt->execute();
$semester_result = $semester_stmt->get_result();

$semesters = [];
if ($semester_result->num_rows > 0) {
    while ($row = $semester_result->fetch_assoc()) {
        $semester = $row['semester_id'];
        $semesters[$semester] = [];
    }
}

// Fetching test details grouped by semester and test name
$marks_sql = "
    SELECT u.semester_id, u.test_name, u.mark_id, u.subject_code, u.test_date, u.result_date, u.full_mark, u.pass_mark, u.mark_score, u.given_by, s.subject_name, ss.semester_name, t.teacher_name
    FROM unit_test_marks u
    JOIN subject s ON u.subject_code = s.subject_code
    JOIN semester ss ON u.semester_id = ss.semester_id
    JOIN teacher t ON u.given_by = t.teacher_id
    WHERE student_id = ? 
    ORDER BY semester_id, test_name, subject_code";
$marks_stmt = $conn->prepare($marks_sql);
$marks_stmt->bind_param("s", $student_id);
$marks_stmt->execute();
$marks_result = $marks_stmt->get_result();

$marks = [];
if ($marks_result->num_rows > 0) {
    while ($row = $marks_result->fetch_assoc()) {
        $semester = $row['semester_id'];
        $semester_name = $row['semester_name'];
        $testName = $row['test_name'];
        $mark_id = $row['mark_id'];

        if (!isset($marks[$semester])) {
            $marks[$semester] = [
                'semester_name' => $semester_name,
                'tests' => []
            ];
        }

        if (!isset($marks[$semester]['tests'][$testName])) {
            $marks[$semester]['tests'][$testName] = [];
        }

        $marks[$semester]['tests'][$testName][] = [
            'mark_id' => $mark_id,
            'subject_code' => $row['subject_code'],
            'subject_name' => $row['subject_name'],
            'test_date' => $row['test_date'],
            'result_date' => $row['result_date'],
            'full_mark' => $row['full_mark'],
            'pass_mark' => $row['pass_mark'],
            'mark_score' => $row['mark_score'],
            'given_by' => $row['given_by'],
            'teacher' => $row['teacher_name'],
        ];
    }
}
?>


    <?php
        $student_id = $_GET['id'];
        $sql = "SELECT * from students where student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

            // Check if a student is found
        if ($student = $result->fetch_assoc()) {
    ?>
<style>
      details {
            background-color: #2196F3;
            border-radius: 5px;
            margin-bottom: 10px;
            color: white;
        }

        details[open] {
            background-color: #850b63;
            padding: 20px;
        }
        summary {
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
        }
        .sum{
            background: #2196F3;

        }
        .summarya {
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            background: #dbaece;
            margin-bottom: 5px;
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
                            <h1 class="header-title">Student - <?php echo $student['student_name']; ?>(<?php echo $student['student_id']; ?>)</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="student.php" class="btn btn-primary lift">Back</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Tab navigation -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="login-tab" data-bs-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="false">Login Credentails</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="discipline-tab" data-bs-toggle="tab" href="#discipline" role="tab" aria-controls="discipline" aria-selected="false">Discipline</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="result-tab" data-bs-toggle="tab" href="#result" role="tab" aria-controls="result" aria-selected="false">Test_Results</a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- Profile content -->
                     
                    <div class="table-responsive">

                            <table class="table table-sm table-nowrap card-table">
                                <thead>
                                <tr>
                                        <td>Unique-Id: <p class="p"><?php echo $student['student_id']; ?></p></td>
                                        <td>Name: <p class="p"><?php echo $student['student_name']; ?></p></td>
                                        <td>Email: <p class="p"><?php echo isset($student['student_email']) && !empty($student['student_email'])? $student['student_email']: 'Email not available'; ?></p></td>
                                </tr>
                                 <tr>
                                     <td>Phone_Number: <p class="p"><?php echo isset($student['student_phone']) && !empty($student['student_phone'])? $student['student_phone']: 'Phone number not available'; ?></p></td>
                                     <td colspan="2">Address: <p class="p"><?php echo isset($student['student_address']) && !empty($student['student_address'])? $student['student_address']: 'Address not available'; ?></p></td>

                                 </tr>
                                 <tr>
                                     <td>Registration_No.: <p class="p"><?php echo isset($student['Registration_no']) && !empty($student['Registration_no'])? $student['Registration_no']: 'Registration No not available'; ?></p></td>
                                     <td>MU_Roll-No.: <p class="p"><?php echo isset($student['MU_Roll_No']) && !empty($student['MU_Roll_No'])? $student['MU_Roll_No']: 'MU Roll No not available'; ?></p></td>
                                     <td>Abc_id: <p class="p"><?php echo isset($student['Abc_id']) && !empty($student['Abc_id'])? $student['Abc_id']: 'ABC Id not available'; ?></p></td>

                                 </tr>
                                </thead>
                       
                            </table>
                    </div>


                </div>
                <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
                    <!-- Login content -->
                     <?php $query="SELECT * from students_credentials where student_id=?";
                     $stmtt = $conn->prepare($query);
                     $stmtt->bind_param('s', $student_id);
                     $stmtt->execute();
                     $resultt = $stmtt->get_result();
                     if ($login = $resultt->fetch_assoc()) {
                        ?>
                    
                     
                    <div class="table-responsive">

                        <table class="table table-sm table-nowrap card-table">
                            <thead>
                                <tr>
                                <td>Phone_Number: <p class="p"><?php echo isset($login['phone']) && !empty($login['phone'])? $login['phone']: 'Phone Number not available'; ?></p></td>
                                <td>Email: <p class="p"><?php echo isset($login['email']) && !empty($login['email'])? $login['email']: 'Email not available'; ?></p></td>
                                <td>Status: <p class="p"><?php echo isset($login['status']) && !empty($login['status'])? $login['status']: 'InActive'; ?></p></td>
                                </tr>
                                <tr>
                                <td colspan="3" style="text-align:right">
                                <?php if($login['status'] == "active"){?>
                                    <button class="btn btn-danger lift" onclick="deactivate('<?php echo $login['credentials_id']; ?>')">De-Activate</button>

                                <?php } else { ?>
                                    <button class="btn btn-success lift" onclick="activate('<?php echo $login['credentials_id']; ?>')">Activate</button>

                                <?php } ?>


                                </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <?php
                    } else {
                        echo "<p style='text-align:center;padding:20px'>No Login Credentials found for this Student.</p>";
                    }
                    ?>
                </div>
                <div class="tab-pane fade" id="discipline" role="tabpanel" aria-labelledby="discipline-tab">
                    <!-- Discipline content -->
                    <div class="table-responsive">
                        <table class="table table-sm table-nowrap card-table">
                            <thead>
                                <tr>
                                    <td style="font-weight:bold;color:red;font-size:16px;">Discipline</td>
                                    <td style="font-weight:bold;color:red;font-size:16px;">Semester</td>
                                    <td style="font-weight:bold;color:red;font-size:16px;">Subject</td>
                                    <td style="font-weight:bold;color:red;font-size:16px;">Enroll_date</td>
                                    <td style="font-weight:bold;color:red;font-size:16px;">Completed_date</td>
                                    <td style="font-weight:bold;color:red;font-size:16px;">Admission_Fee <br><hr> Exam_Fee</td>
                                    <td> </td>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                        // Fetch discipline details with subject combinations
                                        $discipline_query = "
                                            SELECT 
                                                se.enroll_id, 
                                                se.enroll_date, 
                                                se.completed_date, 
                                                se.fee_status,
                                                se.exam_fee, 
                                                c.course_name, 
                                                sc.semester_name,
                                               GROUP_CONCAT(CONCAT(s.subject_code, '(', s.subject_name,')') SEPARATOR '<br>') AS subject_combination
                                            FROM 
                                                student_enroll se
                                            JOIN 
                                                course c ON se.course_code = c.course_code
                                            JOIN 
                                                semester sc ON se.semester_id = sc.semester_id
                                            JOIN 
                                                students_course_combination scc ON se.semester_id = scc.semester_id AND se.student_id = scc.student_id
                                            JOIN 
                                                subject s ON scc.subject_code = s.subject_code
                                            WHERE 
                                                se.student_id = ?
                                            GROUP BY 
                                                se.enroll_id, c.course_name, sc.semester_name, se.enroll_date, se.completed_date, se.fee_status
                                            ORDER BY 
                                                se.semester_id, se.enroll_date";
        
                                            $discipline_stmt = $conn->prepare($discipline_query);
                                            $discipline_stmt->bind_param('s', $student_id);
                                            $discipline_stmt->execute();
                                            $discipline_result = $discipline_stmt->get_result();

                                            // Loop through the result and display rows
                                            while ($discipline = $discipline_result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($discipline['course_name']); ?></td>
                                            <td><?php echo htmlspecialchars($discipline['semester_name']); ?></td>
                                            <td><?php echo htmlspecialchars_decode($discipline['subject_combination']); ?></td>
                                            <td>
                                            <?php 
                                            $date = new DateTime($discipline['enroll_date']);
                                            echo htmlspecialchars($date->format('d-m-Y')); ?></td>
                                            <td><?php
                                                if (!empty($discipline['completed_date'])) {
                                                 $datee = new DateTime($discipline['completed_date']);
                                                    echo htmlspecialchars($datee->format('d-m-Y'));
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($discipline['fee_status']); ?>
                                                <br><hr>
                                                <?php echo htmlspecialchars($discipline['exam_fee']); ?>
                                                </td>
                                                <td> 
                                            <?php 
                                            if($discipline['fee_status'] == "Paid"){?>
                                               
                                           <?php }
                                            else{?>
                                            <button class="btn btn-success lift" onclick="confirmPayment('<?php echo $discipline['enroll_id']; ?>')">Admission Fee</button><?php  }
                                            ?>
                                              <?php 
                                            if($discipline['exam_fee'] == "Paid"){?>
                                               
                                           <?php }
                                            else{?>
                                            <br><br>
                                            <button class="btn btn-success lift" onclick="confirmExamPayment('<?php echo $discipline['enroll_id']; ?>')">Exam Fee</button><?php  }
                                            ?>
                                           </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                            </tbody>



                        </table>

                    </div>
                </div>
                <div class="tab-pane fade" id="result" role="tabpanel" aria-labelledby="result-tab">
                <?php if (!empty($marks)): ?>
                    <?php foreach ($marks as $semesterId => $semesterData): ?>
                        <details>
                            <summary class="sum"><?php echo htmlspecialchars($semesterData['semester_name']); ?> </summary>
                            <?php foreach ($semesterData['tests'] as $testName => $testDetails): ?>
                                <details>
                                    <summary class="summarya"><?php echo htmlspecialchars($testName); ?></summary>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-nowrap card-table tab-content">
                                            <thead>
                                                <tr>
                                                    <td>Subject</td>
                                                    <td>Test-Date</td>
                                                    <td>Result-Date</td>
                                                    <td>Full Mark</td>
                                                    <td>Pass Mark</td>
                                                    <td>Mark Score</td>
                                                    <td>Given By</td>
                                                </tr>
                                            </thead>
                                        <tbody>
                                            <?php foreach ($testDetails as $detail): ?>
                                            <article>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($detail['subject_name']); ?>(<?php echo htmlspecialchars($detail['subject_code']); ?>)</td>
                                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($detail['test_date']))); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($detail['result_date']))); ?></td>
                                                    <td> <?php echo htmlspecialchars($detail['full_mark']); ?></td>
                                                    <td><?php echo htmlspecialchars($detail['pass_mark']); ?></td>
                                                    <td><?php echo htmlspecialchars($detail['mark_score']); ?></td>
                                                    <td><?php echo htmlspecialchars($detail['teacher']); ?></td>
                                                </tr>
                                    
                                            </article>
                                            <?php endforeach; ?>
                                        </tbody>
                                        </table>
                                    </div>
                                    
                                </details>
                            <?php endforeach; ?>
                        </details>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No test marks found.</p>
                <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmPayment(enrollId) {
    // Display confirmation dialog
    if (confirm("Are you sure you want to set Admission Fee as Paid?")) {
        // Send AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../functions/update_payment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert("An error occurred while updating the status.");
            }
        };
        xhr.send("enroll_id=" + encodeURIComponent(enrollId) + "&status=Paid");
    }
}
function confirmExamPayment(enrollId) {
    // Display confirmation dialog
    if (confirm("Are you sure you want to set Exam fee as Paid?")) {
        // Send AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../functions/update_exam_payment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert("An error occurred while updating the status.");
            }
        };
        xhr.send("enroll_id=" + encodeURIComponent(enrollId) + "&status=Paid");
    }
}
function deactivate(credentials_id) {
    // Display confirmation dialog
    if (confirm("Are you sure you want to De-Activate this Student?")) {
        // Send AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../functions/deactivated.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert("An error occurred while updating the status.");
            }
        };
        xhr.send("credentials_id=" + encodeURIComponent(credentials_id) + "&status=Inactive");
    }
}
function activate(credentials_id) {
    // Display confirmation dialog
    if (confirm("Are you sure you want to Activate this Student?")) {
        // Send AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../functions/activated.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert("An error occurred while updating the status.");
            }
        };
        xhr.send("credentials_id=" + encodeURIComponent(credentials_id) + "&status=active");
    }
}
</script>

<?php
} else {
    echo "<p>Student Details not found for this Student.</p>";
    echo'<script>window.location.href="student.php";</script>';
}

include '../includes/footer.php'; 
?>
