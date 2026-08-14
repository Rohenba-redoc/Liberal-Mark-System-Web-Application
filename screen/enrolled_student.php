<?php 
include '../includes/header.php';
include '../includes/config.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="header">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">
                                Overview
                            </h6>
                            <!-- Title -->
                            <h1 class="header-title">
                                Enrolled Students
                            </h1>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Filters -->
            <div class="filters mb-3">
                <form id="filterForm" method="GET" >
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="courseFilter" class="form-label">Course</label>
                            <select id="courseFilter" name="course" class="form-select">
                                <option value="">All Courses</option>
                                <?php
                                try {
                                    $sql = "SELECT DISTINCT course_name FROM course";
                                    $result = $conn->query($sql);

                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['course_name']) . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                                    }
                                } catch (mysqli_sql_exception $e) {
                                    echo "<option value=''>Error fetching courses</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="semesterFilter" class="form-label">Semester</label>
                            <select id="semesterFilter" name="semester" class="form-select">
                                <option value="">All Semesters</option>
                                <?php
                                try {
                                    $sql = "SELECT DISTINCT semester_name FROM semester";
                                    $result = $conn->query($sql);

                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['semester_name']) . "'>" . htmlspecialchars($row['semester_name']) . "</option>";
                                    }
                                } catch (mysqli_sql_exception $e) {
                                    echo "<option value=''>Error fetching semesters</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary filter">Filter</button>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#infoModal">Upgrade Students</button>                            

                        </div>
                    </div>
                </form>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["student-id", "student-name", "student-course", "student-semester", "student-subject","student-date"]}'>
                <div class="card-header">
                    <!-- Search -->
                    <form>
                        <div class="input-group input-group-flush input-group-merge input-group-reverse">
                            <input class="form-control list-search" type="search" placeholder="Search">
                            <span class="input-group-text">
                                <i class="fe fe-search"></i>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap card-table">
                        <thead>
                            <tr>
                                <th><a href="#" class="text-body-secondary list-sort" data-sort="student-id">Unique_ID</a></th>
                                <th><a href="#" class="text-body-secondary list-sort" data-sort="student-name">Student_Name</a></th>
                                <th><a href="#" class="text-body-secondary list-sort" data-sort="student-course">Discipline</a></th>
                                <th><a href="#" class="text-body-secondary list-sort" data-sort="student-subject">Subject-Combination</a></th>
                                <th><a href="#" class="text-body-secondary list-sort" data-sort="student-fee">Admission_Fee<br>Exam_Fee</a></th>
                                <th><a href="#" class="text-body-secondary list-sort" data-sort="student-date">Enroll_Date</a></th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php
                            // Apply filters if set
                            $courseFilter = isset($_GET['course']) ? $_GET['course'] : '';
                            $semesterFilter = isset($_GET['semester']) ? $_GET['semester'] : '';

                            try {
                                $sql = "
                                SELECT 
                                    se.enroll_id,
                                    s.student_id,
                                    s.student_name,
                                    c.course_name,
                                    c.duration,
                                    sem.semester_name,
                                    GROUP_CONCAT(CONCAT(sub.subject_code, '(', sub.subject_name,')') SEPARATOR '<br>') AS subjects,
                                    se.enroll_date,
                                    se.fee_status,
                                    se.exam_fee,
                                    se.status,
                                    COUNT(se.enroll_id) AS enroll_count
                                FROM 
                                    student_enroll se
                                JOIN 
                                    students s ON se.student_id = s.student_id
                                JOIN 
                                    course c ON se.course_code = c.course_code
                                JOIN 
                                    semester sem ON se.semester_id = sem.semester_id
                                JOIN 
                                    students_course_combination scc ON se.student_id = scc.student_id AND se.semester_id = scc.semester_id
                                JOIN 
                                    subject sub ON scc.subject_code = sub.subject_code
                                WHERE 
                                    se.status = 'Incomplete'
                                ";

                                // Add filtering conditions
                                if ($courseFilter) {
                                    $sql .= " AND c.course_name = ?";
                                }
                                if ($semesterFilter) {
                                    $sql .= " AND sem.semester_name = ?";
                                }

                                $sql .= "
                                GROUP BY 
                                    se.enroll_id, s.student_name, c.course_name, sem.semester_name, se.enroll_date, se.fee_status,se.exam_fee, se.status, c.duration
                                ORDER BY 
                                    se.enroll_date DESC";

                                $stmt = $conn->prepare($sql);

                                // Bind parameters for filtering
                                $params = [];
                                if ($courseFilter) {
                                    $params[] = $courseFilter;
                                }
                                if ($semesterFilter) {
                                    $params[] = $semesterFilter;
                                }

                                if ($params) {
                                    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                                }

                                $stmt->execute();
                                $stmt->bind_result($enroll_id, $student_id, $student_name, $course_name, $duration, $semester_name, $subjects, $enroll_date, $fee_status,$exam_fee, $status, $enroll_count);

                                while ($stmt->fetch()) {
                                    $expectedSemesterLimit = $duration * 2;
                                    $showCompletedButton = $semester_name >= $expectedSemesterLimit;
                                   

                                    echo "<tr>";
                                    echo "<td class='student-id'>" . $student_id . "</td>";
                                    echo "<td class='student-name'>" . $student_name . "</td>";
                                    echo "<td class='student-course'>" . $course_name . " - " . $semester_name . "</td>";
                                    echo "<td class='student-subject'>" . $subjects . "</td>";
                                    echo "<td class='student-fee'>" . $fee_status . " <br> " .$exam_fee. "</td>";
                                    echo "<td class='student-date'>" . $enroll_date . "</td>";
                                    echo "<td>";
                                    if ($showCompletedButton) {
                                        echo "<a href='#' data-bs-toggle='modal' data-bs-target='#completeModal' data-enroll-id='" . htmlspecialchars($enroll_id) . "' class='btn btn-primary lift'>
                                            Mark as Completed
                                        </a>";
                                    }
                                    
                                    
                                    else {
                                        // echo "<a href='edit_enroll.php?enroll_id=" . $enroll_id . "' class='btn btn-secondary lift'>
                                        //     Upgrade
                                        // </a>";
                                        if ($fee_status == "Not_Paid") {
                                            echo "<a href='update_fee_status.php?enroll_id=" . $enroll_id . "' class='btn btn-success lift pay-button mx-1'>
                                                Admission Fee
                                            </a>";
                                        }
                                        if ($exam_fee == "Not_Paid") {
                                            echo "<a href='update_exam_fee.php?enroll_id=" . $enroll_id . "' class='btn btn-success lift pay-buttonn mx-1'>
                                                Exam Fee
                                            </a>";
                                        }

                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }

                            } catch (mysqli_sql_exception $e) {
                                echo "Error: " . $e->getMessage();
                            }

                            $stmt->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Template -->
<!-- Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel">Mark as Completed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../functions/update_status.php">
                    <input type="hidden" id="modalEnrollId" name="enroll_id">
                    <div class="mb-3">
                        <label for="completionDate" class="form-label">Completion Date</label>
                        <input type="date" class="form-control" id="completionDate" name="completion_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Upgrade Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="upgradeform" action="upgrade.php" method="get">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5">
                            <label for="course">Course:</label>
                            <select id="course" name="course_code" required class="form-control mb-3">
                                <option value="">Select Course</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="semester">Semester:</label>
                            <select id="semester" name="semester_id" required class="form-control mb-3">
                                <option value="">Select Semester</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upgrade</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pay-button').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior

            const url = this.href; // Get the URL from the href attribute

            if (confirm("Are you sure you want to update the fee status to 'Paid'?")) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'action': 'update_fee_status'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Fee status updated successfully.');
                        window.location.reload(); // Reload page to reflect changes
                    } else {
                        alert('Failed to update fee status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });
    document.querySelectorAll('.pay-buttonn').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior

            const url = this.href; // Get the URL from the href attribute

            if (confirm("Are you sure you want to update the Exam fee status to 'Paid'?")) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'action': 'update_exam_fee'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Exam Fee status updated successfully.');
                        window.location.reload(); // Reload page to reflect changes
                    } else {
                        alert('Failed to update fee status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });
   
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (event) {
        if (event.target.matches('[data-bs-toggle="modal"]')) {
            const enrollId = event.target.getAttribute('data-enroll-id');
            document.getElementById('modalEnrollId').value = enrollId;
            const modal = new bootstrap.Modal(document.getElementById('completeModal'));
            modal.show();
        }
    });
    document.getElementById('completeModal').querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Student Mark As Completed.');
                window.location.href = 'enrolled_student.php'; // Redirect to the enrolled students page
            } else {
                alert('Failed to update status. ' + (data.error || ''));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    });
});

</script>

<script>
    
      function populateCourses() {
        fetch(`../functions/get_courses.php`)
            .then(response => response.json())
            .then(data => {
                const courseSelect = document.getElementById('course');
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.course_code;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
                courseSelect.disabled = false;
            });
    }

    // Function to populate semesters
    function populateSemesters() {
        fetch(`../functions/get_semesters.php`)
            .then(response => response.json())
            .then(data => {
                const semesterSelect = document.getElementById('semester');
                semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                data.forEach(semester => {
                    const option = document.createElement('option');
                    option.value = semester.semester_id;
                    option.textContent = semester.semester_name;
                    semesterSelect.appendChild(option);
                });
            });
    }

    // Call the functions to populate the dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        populateCourses();
        populateSemesters();
    });
</script>



<?php include '../includes/footer.php'; ?>
