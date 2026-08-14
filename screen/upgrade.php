<?php
include '../includes/header.php';
include '../includes/config.php';

// Check if the GET parameters are set
if (isset($_GET['course_code']) && isset($_GET['semester_id'])) {
    $courseCode = htmlspecialchars($_GET['course_code']);
    $currentSemesterId = htmlspecialchars($_GET['semester_id']);

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
                        $lastsql = "SELECT se.enroll_id, se.student_id, se.department_id, s.student_name, d.department_name, se.course_code
                                    FROM student_enroll se
                                    JOIN students s ON se.student_id = s.student_id
                                    JOIN department d ON se.department_id = d.department_id
                                    WHERE se.course_code = ? AND se.semester_id = ? AND se.status = 'Incomplete'";
                                    
                        if ($stmmt = $conn->prepare($lastsql)) {
                            $stmmt->bind_param("ss", $courseCode, $currentSemesterId);
                            $stmmt->execute();
                            $resulttt = $stmmt->get_result(); // Corrected: using $stmmt
                    
                            // Check if any students were found
                            if ($resulttt->num_rows > 0) {
                                // Display students in a table
                                echo '<form action="process_Complete.php" method="POST">';
                                echo '<table class="table">';
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th>UPGRADE STUDENT</th>';
                                echo '<th>Completed Date <input type="date" name="date" class="form-control" required></th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                    
                                
                    
                                while ($row = $resulttt->fetch_assoc()) {
                                    echo '<tr>';
                                  
                                    echo '<td colspan="2">
                                                   
                                                     <label class="custom-checkbox">
                                                        <input type="checkbox" name="selected_students[]" value="' . htmlspecialchars($row['student_id']) . '">
                                                        <span class="checkmark"></span>
                                                    </label>
                    
                                        ' . htmlspecialchars($row['student_id']) . ' - ' . htmlspecialchars($row['student_name']) . '<br>&emsp;&emsp;
                                        ' . htmlspecialchars($row['department_name']) . '
                                        <input type="hidden" name="enroll_id[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['enroll_id']) . '">
                                           
                                        ';
                                  
                    
                                    echo '</td>';
                                    echo '</tr>';
                                }
                    
                                echo '</tbody>';
                                echo '</table>';
                                echo '<button type="submit" class="btn btn-primary" style="margin-left:20px;">Set As Completed</button>';
                                echo '</form>';
                            } else {
                                echo "<script>alert('No students found for the selected course and semester.'); window.location.href = 'enrolled_student.php';</script>";
                            }
                    
                            // Close the statement
                            $stmmt->close(); // Corrected: closing $stmmt
                        } else {
                            echo "<p>Error preparing the SQL statement.</p>";
                        }
                    }
                     else {
                        $sql = "SELECT se.enroll_id, se.student_id, se.department_id, s.student_name, d.department_name, se.course_code
                        FROM student_enroll se
                        JOIN students s ON se.student_id = s.student_id
                        JOIN department d ON se.department_id = d.department_id
                        WHERE se.course_code = ? AND se.semester_id = ? AND se.status = 'Incomplete'";
            
                // Prepare and execute the statement
                if ($stmt = $conn->prepare($sql)) {
                    // Bind the parameters
                    $stmt->bind_param("ss", $courseCode, $currentSemesterId);
                    $stmt->execute();
                    $result = $stmt->get_result();
            
                    // Check if any students were found
                   
                    // Assuming this part is inside the code that displays the table and subjects
                    
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
                        $nextSemesterId = $currentSemesterId + 1; // Increment semester ID
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
                    
                        // Adding a search box for subjects above the table
                        
                        // Display student rows and subjects
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td colspan="2">';
                            echo '<label class="custom-checkbox">';

                            echo '<input type="checkbox" name="selected_students[]" value="' . htmlspecialchars($row['student_id']) . '">';
                            echo '<span class="checkmark"></span>';
                            echo '</label>';
                    
                            echo htmlspecialchars($row['student_id']) . ' - ' . htmlspecialchars($row['student_name']) . '<br>&emsp;&emsp;';
                            echo htmlspecialchars($row['department_name']);
                            echo '<input type="hidden" name="enroll_id[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['enroll_id']) . '">';
                            echo '<input type="hidden" name="course_code[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['course_code']) . '">';
                            echo '<input type="hidden" name="department_id[' . htmlspecialchars($row['student_id']) . ']" value="' . htmlspecialchars($row['department_id']) . '">';
                            echo '<input type="hidden" name="semester_id" value=' . $nextSemesterId . '>';
                            echo '<label for="fee_status_' . htmlspecialchars($row['student_id']) . '" style="float:right;">Admission Fee:</label><br>';
                            echo '<select name="fee_status[' . htmlspecialchars($row['student_id']) . ']" id="fee_status_' . htmlspecialchars($row['student_id']) . '" style="float:right; margin-bottom:10px; padding:8px; border: 1px solid #ccc; border-radius: 4px;">';
                            echo '<option value="Not_Paid">Not Paid</option>';
                            echo '<option value="Paid">Paid</option>';
                            echo '</select>';
                    
                            // Create a multi-select box for all subjects with search functionality
                            $departmentId = $row['department_id'];
                            echo '<div class="multi-select">';
                            echo '<input type="text" id="search-subjects-' . htmlspecialchars($row['student_id']) . '" placeholder="Select subjects..." class="form-control mb-2" readonly>';

                            echo '<div class="options" style="display: none;">';
                            echo '<input type="text" id="subjectSearchInput" class="form-control mb-3" placeholder="Search subjects..." style="width: 300px;">';

                            echo '<div class="options-list" id="subjectOptions">';
                    
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
                    
                                        // Search functionality for filtering subjects
                                        const subjectSearchInput = document.getElementById("subjectSearchInput");
                                        const subjectOptions = document.getElementById("subjectOptions");
                                        subjectSearchInput.addEventListener("keyup", function() {
                                            const searchValue = subjectSearchInput.value.toLowerCase();
                                            const options = subjectOptions.getElementsByClassName("option");
                                            Array.from(options).forEach(function(option) {
                                                const subjectText = option.textContent.toLowerCase();
                                                if (subjectText.includes(searchValue)) {
                                                    option.style.display = "";
                                                } else {
                                                    option.style.display = "none";
                                                }
                                            });
                                        });
                                    });
                                  </script>';
                    
                            echo '</td>';
                            echo '</tr>';
                        }
                    
                        echo '</tbody>';
                        echo '</table>';
                        echo '<button type="submit" class="btn btn-primary" style="margin-left:20px;">Save Changes</button>';
                        echo '</form>';
                    } else {
                        echo "<script>alert('No students found for the selected course and semester.'); window.location.href = 'enrolled_student.php';</script>";
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
<style>
    .custom-checkbox {
    display: inline-block;
    position: relative;
    padding-left: 30px; /* Space for the custom checkbox */
    cursor: pointer;
    user-select: none; /* Prevent text selection */
}

.custom-checkbox input {
    position: absolute;
    opacity: 0; /* Hide the default checkbox */
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px; /* Height of the custom checkbox */
    width: 20px; /* Width of the custom checkbox */
    background-color: #eee; /* Background color when unchecked */
    border-radius: 4px; /* Rounded corners */
    border: 1px solid #ccc; /* Border */
}

.custom-checkbox input:checked ~ .checkmark {
    background-color: #4caf50; /* Background color when checked */
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.custom-checkbox input:checked ~ .checkmark:after {
    display: block;
}

.custom-checkbox .checkmark:after {
    left: 7px;
    top: 3px;
    width: 5px;
    height: 10px;
    border: solid white; /* Checkmark color */
    border-width: 0 2px 2px 0; /* Create a checkmark */
    transform: rotate(45deg);
}

</style>
<script>
// The JavaScript for the multi-select
document.addEventListener("DOMContentLoaded", function() {
    // console.log("JavaScript is running");

    const multiSelects = document.querySelectorAll('.multi-select');

    multiSelects.forEach(multiSelect => {
        const searchInput = multiSelect.querySelector('input[type="text"]');
        const optionsDiv = multiSelect.querySelector('.options');
        const optionsList = multiSelect.querySelector('.options-list');
        const selectedSubjectsInput = multiSelect.querySelector('input[type="hidden"]');
        let coreSubjects = selectedSubjectsInput.value.split(',');

        const updateSelectedDisplay = () => {
            const selectedOptions = Array.from(optionsList.querySelectorAll('.option input:checked'))
                .map(option => option.closest('.option').querySelector('label').textContent.trim());
            searchInput.value = selectedOptions.join(', ') || 'Select subjects...';
        };

        searchInput.addEventListener("click", function() {
            optionsDiv.style.display = optionsDiv.style.display === "none" ? "block" : "none";
        });

        optionsList.addEventListener("change", function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'checkbox') {
                const selectedCode = e.target.value;
                let selectedValues = selectedSubjectsInput.value.split(',');

                if (e.target.checked) {
                    if (!selectedValues.includes(selectedCode)) {
                        selectedValues.push(selectedCode);
                    }
                } else {
                    if (!coreSubjects.includes(selectedCode)) {
                        selectedValues = selectedValues.filter(code => code !== selectedCode);
                    } else {
                        e.target.checked = true;
                    }
                }

                selectedSubjectsInput.value = selectedValues.join(',');
                updateSelectedDisplay();
            }
        });

        searchInput.addEventListener("input", function() {
            const filter = this.value.toLowerCase();
            optionsList.querySelectorAll('.option').forEach(option => {
                option.style.display = option.textContent.toLowerCase().includes(filter) ? "block" : "none";
            });
        });

        updateSelectedDisplay();
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        const allHiddenInputs = document.querySelectorAll('input[type="hidden"]');
        let output = '';

        allHiddenInputs.forEach(input => {
            output += `Student ID: ${input.name}<br>Selected subjects: ${input.value}<br>`;
        });

        // Create a div to display the output
        const outputDiv = document.createElement('div');
        outputDiv.style.position = 'fixed';
        outputDiv.style.top = '10px';
        outputDiv.style.right = '10px';
        outputDiv.style.backgroundColor = 'white';
        outputDiv.style.border = '1px solid #ccc';
        outputDiv.style.padding = '10px';
        outputDiv.style.zIndex = '1000';
        outputDiv.innerHTML = output;

        document.body.appendChild(outputDiv);

        // Optionally remove the output after a few seconds
        setTimeout(() => {
            document.body.removeChild(outputDiv);
        }, 5000);
    });
});
</script>


