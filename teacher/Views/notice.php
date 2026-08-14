<?php
include '../includes/header.php'; 
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
                                Notice
                            </h1>

                        </div>
                        <div class="col-auto">

                            <!-- Button -->
                            <a href="add_notice.php" class="btn btn-primary lift">
                                New Notice 
                            </a>

                        </div>
                    </div> <!-- / .row -->
                    <div class="row align-items-center">
                        <div class="col">

                            <!-- Nav -->
                            <ul class="nav nav-tabs nav-overflow header-tabs">
                                <li class="nav-item">
                                    <a href="#!" class="nav-link active" id="tab-all">
                                      All <span class="badge rounded-pill text-bg-secondary-subtle"></span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["notice-title", "notice-message", "notice-date", "notice-course", "notice-subject", "notice-semester"]}'>
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
                    <table class="table table-sm table-nowrap card-table tab-content" id="content-filter">
                        <thead>
                            <tr>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-title">
                                        Title
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-message">
                                        Description
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-course">
                                        Course
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-semester">
                                        Semester
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-subject">
                                        Paper-code
                                    </a>
                                </th>
                                <th colspan="2">
                                    <a href="#" class="text-body-secondary list-sort" data-sort="notice-date">
                                        Annouced_Date
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php 
                            include '../../includes/config.php';

                            // Query to fetch filtered notices
                            $sql = "SELECT a.id,
                                    a.title,
                                    a.message,
                                    a.course_code,
                                    a.semester_id,
                                    a.subject_code,
                                    a.created_at,
                                    se.semester_name,
                                    co.course_name,
                                    GROUP_CONCAT(CONCAT(s.subject_name, '-', s.subject_code) SEPARATOR ', ') AS subject_info
                                    FROM teacher_notice AS a
                                    JOIN subject AS s ON a.subject_code = s.subject_code
                                    JOIN semester AS se ON a.semester_id = se.semester_id
                                    JOIN course AS co ON a.course_code = co.course_code
                                    WHERE a.add_by = $teacherId
                                    GROUP BY a.id, a.title, a.message, a.created_at, se.semester_name, co.course_name
                                    ORDER BY a.created_at DESC";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    // Define variables from the row
                                    $id = $row['id'];
                                    $title = $row['title'];  
                                    $message = $row['message'];  
                                    $created_at = $row['created_at'];  
                                    $subject = $row['subject_info'];
                                    $semester = $row['semester_name'];
                                    $course = $row['course_name'];

                                    echo '
                                    <tr>
                                        <td class="notice-title">
                                            ' . htmlspecialchars($title) . '
                                        </td>
                                        <td class="notice-message">
                                            ' . $message . '
                                        </td>
                                        <td class="notice-course">
                                            ' . htmlspecialchars($course) . '
                                        </td>
                                        <td class="notice-semester">
                                            ' . htmlspecialchars($semester) . '
                                        </td>
                                        <td class="notice-subject">
                                            ' . htmlspecialchars($subject) . '
                                        </td>
                                        <td class="notice-date">';
                                            $date = new DateTime($created_at);
                                            echo htmlspecialchars($date->format('d-m-Y'));
                                    echo '</td>
                                        <td class="text-end">
                                            <!-- Dropdown -->
                                            <div class="dropdown" style="position:absolute">
                                                <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                  
                                                    <a href="#" class="dropdown-item" onclick="confirmDelete(' . $id . ')">
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="7">No notices found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div> <!-- / .row -->
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.8.0/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {


    // Handle deletion
    window.confirmDelete = function(Id) {
        if (confirm('Are you sure you want to delete this Notice?')) {
            fetch('../controller/deletenotice.php', {
                method: 'POST',
                body: JSON.stringify({ id: Id }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
        }
    };
});
</script>

<?php
include '../includes/footer.php'; 
?>
