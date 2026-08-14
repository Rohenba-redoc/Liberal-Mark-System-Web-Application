<?php
include '../includes/header.php';
include '../includes/config.php';

// Check if the GET parameters are set
if (isset($_GET['course_code']) && isset($_GET['semester_id'])) {
    $courseCode = htmlspecialchars($_GET['course_code']);
    $currentSemesterId = intval($_GET['semester_id']);

    // Fetch the course duration from the course table
    $query = "SELECT duration FROM course WHERE course_code = ?";
    if ($stmtt = $conn->prepare($query)) {
        $stmtt->bind_param("s", $courseCode);
        $stmtt->execute();
        $resultt = $stmtt->get_result();

        if ($resultt->num_rows > 0) {
            $row = $resultt->fetch_assoc();
            $courseDuration = $row['duration'];

            // Calculate the total number of semesters (duration * 2)
            $totalSemesters = $courseDuration * 2;

            // Query to fetch the last semester_id from the `semester` table, limited by the total number of semesters
            $semesterQuery = "SELECT semester_id FROM semester ORDER BY semester_id DESC LIMIT ?";
            if ($stmtSemester = $conn->prepare($semesterQuery)) {
                $stmtSemester->bind_param("i", $totalSemesters);
                $stmtSemester->execute();
                $semesterResult = $stmtSemester->get_result();

                if ($semesterResult->num_rows > 0) {
                    // Get the last semester from the limited result
                    $lastSemester = $semesterResult->fetch_assoc()['semester_id'];

                    // Check if the current semester is the last one
                    if ($currentSemesterId == $lastSemester) {
                        // Display "Mark as Completed" for the last semester
                        echo '<p>This is the last semester. Please mark the course as completed.</p>';
                        // Add any additional logic for the last semester here (e.g., updating status to "completed").
                    } else {
                        $sql = "SELECT se.enroll_id, se.student_id, se.department_id, s.student_name, d.department_name, se.course_code
                        FROM student_enroll se
                        JOIN students s ON se.student_id = s.student_id
                        JOIN department d ON se.department_id = d.department_id
                        WHERE se.course_code = ? AND se.semester_id = ? AND se.status = 'Incomplete'";
            
                // Prepare and execute the statement
                if ($stmt = $conn->prepare($sql)) {
                    // Bind the parameters
                    $stmt->bind_param("ss", $courseCode, $semesterId);
                    $stmt->execute();
                    $result = $stmt->get_result();
            
                    // Check if any students were found
                    if ($result->num_rows > 0) {
                        // Display students in a table
                        echo '<form action="process_enrollment.php" method="POST">';
                        echo '<table class="table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>UPGRADE STUDENT</th>';
                        echo '<th>Completed Date <input type="date" name="date" class="form-control" required></th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
            
                        // Fetch all subjects for the next semester
                        $allSubjects = [];
                        $coreSubjects = [];
                        $subjectSql = "SELECT subject_code, subject_name, department_id, type FROM subject WHERE semester_id = ?";
                        $nextSemesterId = $semesterId + 1; // Increment semester ID
                        if ($subjectStmt = $conn->prepare($subjectSql)) {
                            $subjectStmt->bind_param("s", $nextSemesterId);
                            $subjectStmt->execute();
                            $subjectResult = $subjectStmt->get_result();
            
                            while ($subjectRow = $subjectResult->fetch_assoc()) {
                                $allSubjects[] = $subjectRow;
                                if ($subjectRow['type'] === 'core') {
                                    $coreSubjects[$subjectRow['department_id']][] = $subjectRow;
                                }
                            }
                            $subjectStmt->close();
                        }
            
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                          
                            echo '<td colspan="2">
                                           
                                             <label class="custom-checkbox">
                                                <input type="checkbox" name="selected_students[]" value="' . htmlspecialchars($row['student_id']) . '">
                                                <span class="checkmark"></span>
                                            </label>
            
                                ' . htmlspecialchars($row['student_id']) . ' - ' . htmlspecialchars($row['student_name']) . '<br>&emsp;&emsp;
                                ' . htmlspecialchars($row['department_name']) . '
                                <input type="hidden" name="enroll_id[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['enroll_id']) . '">
                                <input type="hidden" name="course_code[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['course_code']) . '">
                                <input type="hidden" name="department_id[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['department_id']) . '">
                                <input type="hidden" name="semester_id" value=' .$nextSemesterId. '>
                                    <label for="fee_status_' . htmlspecialchars($row['student_id']) . '" style="float:right;">Admission Fee:</label><br>
                                    <select name="fee_status[' . htmlspecialchars($row['student_id']) . ']" id="fee_status_' . htmlspecialchars($row['student_id']) . '" style="float:right; margin-bottom:10px; padding:8px; border: 1px solid #ccc; border-radius: 4px;">
                                    <option value="Not_Paid">Not Paid</option>
                                    <option value="Paid">Paid</option>
                                    </select>
                                ';
            
                            // Create a multi-select box for all subjects
                            $departmentId = $row['department_id'];
                            echo '<div class="multi-select">';
                            echo '<input type="text" id="search-subjects-' . htmlspecialchars($row['student_id']) . '" placeholder="Select subjects..." class="form-control mb-2" readonly>';
                            echo '<div class="options" style="display: none;">';
                            echo '<div class="options-list">';
            
                            // Add all subjects with checkboxes
                            foreach ($allSubjects as $subject) {
                                $isCoreSubject = in_array($subject, $coreSubjects[$departmentId] ?? []);
                                echo '<div class="option" data-code="' . htmlspecialchars($subject['subject_code']) . '" style="padding:10px">';
                                echo '<label>';
                                echo '<input type="checkbox" value="' . htmlspecialchars($subject['subject_code']) . '" ' . ($isCoreSubject ? 'checked' : '') . '> ';
                                echo htmlspecialchars($subject['subject_code']) . ' (' . htmlspecialchars($subject['subject_name']) . ')';
                                echo '</label>';
                                echo '</div>';
                            }
            
                            echo '</div>'; // Close options-list
                            echo '</div>'; // Close options
            
                            // Pre-select core subjects
                            $selectedCoreSubjects = [];
                            if (isset($coreSubjects[$departmentId])) {
                                foreach ($coreSubjects[$departmentId] as $coreSubject) {
                                    $selectedCoreSubjects[] = htmlspecialchars($coreSubject['subject_code']);
                                }
                            }
            
                            echo '<input type="hidden" name="subjects[' . htmlspecialchars($row['student_id']) . '][]" id="selected-subjects-' . htmlspecialchars($row['student_id']) . '" value="' . implode(',', $selectedCoreSubjects) . '">';
                            echo '</div>'; // Close multi-select
            
                            // Update the search input with pre-selected core subjects
                            echo '<script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const searchInput = document.getElementById("search-subjects-' . htmlspecialchars($row['student_id']) . '");
                                        const coreSubjects = "' . implode(', ', array_map('htmlspecialchars', $selectedCoreSubjects)) . '";
                                        searchInput.value = coreSubjects;
                                        console.log("Core subjects for student ' . htmlspecialchars($row['student_id']) . ':", coreSubjects);
                                    });
                                  </script>';
            
                            echo '</td>';
                            echo '</tr>';
                        }
            
                        echo '</tbody>';
                        echo '</table>';
                        echo '<button type="submit" class="btn btn-primary" style="margin-left:20px;">Submit Selected Subjects</button>';
                        echo '</form>';
                    } else {
                        echo "<p>No students found for the selected course and semester.</p>";
                    }
            
                    // Close the statement
                    $stmt->close();
                } else {
                    echo "<p>Error preparing the SQL statement.</p>";
                }
                    }
                } else {
                    echo '<p>No semesters found in the database.</p>';
                }

                $stmtSemester->close();
            } else {
                echo '<p>Error preparing the SQL statement to fetch semesters.</p>';
            }
        } else {
            echo '<p>No course found with the given course code.</p>';
        }

        $stmtt->close();
    } else {
        echo '<p>Error preparing the SQL statement to fetch course duration.</p>';
    }
} else {
    echo "<p>No course or semester selected. Please go back and try again.</p>";
}

include '../includes/footer.php';
?>
