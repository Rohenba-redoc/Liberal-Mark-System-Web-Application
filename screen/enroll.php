<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include '../includes/header.php';
include '../includes/config.php';
?>
<?php
if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true) {
    header('Location: student.php');
    exit;
}
unset($_SESSION['form_submitted']);

$studentId = isset($_GET['student_id']) ? $_GET['student_id'] : 0;

// Validate the student_id
if ($studentId <= 0) {
    echo 'Invalid student ID.';
    exit;
}
$query = "SELECT student_id, student_name FROM students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo 'Student not found.';
    exit;
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
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">Overview</h6>
                            <!-- Title -->
                            <h1 class="header-title">Enrolled - <?php echo htmlspecialchars($student['student_name'], ENT_QUOTES, 'UTF-8'); ?>(<?php echo htmlspecialchars($student['student_id'], ENT_QUOTES, 'UTF-8'); ?>)</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="student.php" class="btn btn-primary lift">Cancel</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <div class="col-12">
                
            <form id="enrollForm" action="../functions/enroll_student.php" method="post">
               
                    <input type="hidden" name="student_id"  value="<?php echo htmlspecialchars($student['student_id']); ?>" class="form-control">              
                    


            <div style="display:flex;justify-content:space-between;">
                <div class="col-5">
                    <!-- Course Dropdown -->
                    <label for="course" >Course:</label>
                    <select id="course" name="course_code" required disabled class="form-control mb-3">
                    <option value="">Select Course</option>
                    <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="col-5">
                         <!-- Years Dropdown -->
                        <label for="department">Department:</label>
                        <select id="department" name="department" required disabled class="form-control mb-3">
                        <option value="">Select Department</option>
                        <!-- Options will be populated dynamically -->
                        </select>
                    </div>
            </div>
              
               <div style="display:flex;justify-content:space-between;">
                     

                    <div class="col-5">
                         <!-- Semester Dropdown -->
                        <label for="semester">Semester:</label>
                        <select id="semester" name="semester_id" required disabled class="form-control mb-3">
                        <option value="">Select Semester</option>
                        <!-- Options will be populated dynamically -->
                        </select>
                     </div>
               </div>
               <div class="col-12" id="subject-group">
                            <label for="subjects">Subjects:</label>
                            <div class="multi-select">
                                <input type="text" id="search" placeholder="Subjects" class="form-control mb-2">
                                <div id="subject-options" class="options">
                                    <input type="text" id="option-search" placeholder="Filter subjects inside options..." class="form-control mb-2">
                                    <!-- Options will be populated dynamically -->
                                </div>
                            </div>
                                <input type="hidden" name="selected_subjects" id="selected_subjects">
                </div>
               


               
                <div style="display:flex;justify-content:space-between;">
                    <div class="col-5">
                    <label for="date">Enrolled Date:</label>
                    <input type="date" name="date" id="date" class="form-control mb-3">
                    </div>
                    <div class="col-5">
                    <label for="fee">Admission-Fee Status</label>
                    <select id="fee" name="fee" required  class="form-control mb-3">
                    <option value="Paid">Paid</option>
                    <option value="Not_Paid">Not Paid</option>
                    
                    </select>
                   </div>
                    </div>
                </div>
               <div style="display:flex;justify-content:space-between;">
                <div class="col-5">
                    <label for="examfee">Exam-Fee Status</label>
                    <select id="examfee" name="examfee" required  class="form-control mb-3">
                    <option value="Paid">Paid</option>
                    <option value="Not_Paid">Not Paid</option>
                    
                    </select>
                   </div>

                        <div class="col-5 mt-4">
                        <input type="submit" value="Enroll" class="btn btn-success">

                        </div>               
                </div>
                </form>
            </div>

            
        </div>
    </div>
</div>

<style>
    

    .multi-select {
        position: relative;
        width: 100%;
        margin-bottom: 20px; /* Space below the multi-select */
    }

    .selectBox {
        position: relative;
        display: flex;
        align-items: center; /* Center-align items */
        justify-content: space-between; /* Space between text and arrow */
        padding: 10px 15px; /* Add padding for better touch targets */
        border: 1px solid #ccc; /* Border color */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor */
        transition: border 0.3s; /* Transition effect for border */
    }

    .selectBox:hover {
        border-color: #7e4483; /* Change border color on hover */
    }

    .checkboxes {
        display: none; /* Hidden by default */
        border: 1px solid #dadada; /* Border for the options */
        max-height: 200px; /* Limit height */
        overflow-y: auto; /* Scroll if needed */
        border-radius: 5px; /* Rounded corners */
        z-index: 100; /* Ensure it appears above other elements */
    }

    .checkboxes label {
        display: flex; /* Flex for better alignment */
        align-items: center; /* Center-align checkbox and text */
        padding: 10px; /* Padding around label */
        transition: background-color 0.2s; /* Smooth transition for background */
    }

    .checkboxes label:hover {
        background-color: #f1f1f1; /* Light background on hover */
    }

    .options {
        border: 1px solid #ccc; /* Border for options */
        border-radius: 5px; /* Rounded corners */
        max-height: 600px; /* Fixed height */
        overflow-y: auto; /* Scroll if needed */
        display: none; /* Hidden by default */
        position: absolute; /* Absolute positioning */
        width: 100%; /* Full width */
        z-index: 1000; /* Ensure it appears above other elements */
        //box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow for options */
        background:#063970;
        min-height:500px;
    }

    .options .group {
        cursor: pointer; /* Pointer cursor */
        font-weight: bold; /* Bold font */
        margin: 10px 0; /* Space around groups */
        padding: 10px; /* Padding for groups */
        //background: #f4f4f4; /* Light background for group headers */
        border-radius: 5px; /* Rounded corners */
        transition: background-color 0.2s; /* Smooth transition */
        background:#783820;
    }

    .options .group:hover {
        background-color: #7e4483; /* Change background color on hover */
        color: #fff; /* White text on hover */
    }

    .options label {
        display: block; /* Block display */
        padding: 10px; /* Padding for labels */
        transition: background-color 0.2s; /* Smooth transition for background */
    }

    .options label:hover {
        background-color: #7e4483; /* Change background color on hover */
    }

    /* Style for the search input */
    #search {
        border: 1px solid #ccc; /* Border for the search input */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Padding for input */
        width: 100%; /* Full width */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Shadow for input */
        transition: border 0.3s; /* Transition for border */
    }

    #search:focus {
        border-color: #7e4483; /* Change border color on focus */
        outline: none; /* Remove default outline */
    }

    /* Style for the filter input in options */
    #option-search {
        border: 1px solid #ccc; /* Border for the option search input */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Padding for input */
        width: 100%; /* Full width */
        margin-bottom: 10px; /* Space below */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Shadow for input */
        transition: border 0.3s; /* Transition for border */
    }

    #option-search:focus {
        border-color: #7e4483; /* Change border color on focus */
        outline: none; /* Remove default outline */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Populate Stream Dropdown
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

    // Event listener for Stream Dropdown
    document.getElementById('course').addEventListener('change', function() {
        const streamId = this.value;
        fetch(`../functions/get_department.php`)
            .then(response => response.json())
            .then(data => {
                const departmentSelect = document.getElementById('department');
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.department_id;
                    option.textContent = department.department_name;
                    departmentSelect.appendChild(option);
                });
                departmentSelect.disabled = false;
            });

        // Reset and disable subsequent dropdowns
        document.getElementById('semester').disabled = true;
    });

    // Event listener for Course Dropdown
    document.getElementById('department').addEventListener('change', function() {
        const courseCode = this.value;
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
                semesterSelect.disabled = false;
            });

        
    });
    // Event listener for Semester Dropdown
    const searchInput = document.getElementById('search');
    const optionsContainer = document.getElementById('subject-options');
    const selectedSubjectsInput = document.getElementById('selected_subjects');

   // Fetch subjects when semester is selected
document.getElementById('semester').addEventListener('change', function() {
    const semesterId = this.value;
    fetch(`../functions/getdsubject.php?semester_id=${semesterId}`)
        .then(response => response.json())
        .then(data => {
            optionsContainer.innerHTML = ''; // Clear previous options
            const optionSearchInput = document.createElement('input');
            optionSearchInput.setAttribute('type', 'text');
            optionSearchInput.setAttribute('id', 'option-search');
            optionSearchInput.setAttribute('placeholder', 'Filter subjects inside options...');
            optionSearchInput.classList.add('form-control', 'mb-2');

            optionsContainer.appendChild(optionSearchInput); // Add new search input to optionsContainer
            
            // Create collapsible groups
            for (const [department, subjects] of Object.entries(data)) {
                const groupLabel = document.createElement('div');
                groupLabel.classList.add('group');
                groupLabel.innerHTML = `<strong>${department}</strong>`;
                optionsContainer.appendChild(groupLabel);

                const subjectList = document.createElement('div');
                subjectList.classList.add('options');
                
                subjects.forEach(subject => {
                    const label = document.createElement('label');
                    label.innerHTML = `<input type="checkbox" value="${subject.subject_code}"> ${subject.subject_code} (${subject.subject_name})`;
                    subjectList.appendChild(label);
                });

                optionsContainer.appendChild(subjectList);

                // Add click event to toggle visibility
                groupLabel.addEventListener('click', function() {
                    subjectList.style.display = subjectList.style.display === 'block' ? 'none' : 'block';
                });
            }

            optionsContainer.style.display = 'block'; // Show options

            // Attach search functionality for filtering inside options
            optionSearchInput.addEventListener('input', function() {
                const filter = optionSearchInput.value.toLowerCase();
                const labels = optionsContainer.getElementsByTagName('label');

                for (let i = 0; i < labels.length; i++) {
                    const text = labels[i].textContent.toLowerCase();
                    labels[i].style.display = text.includes(filter) ? '' : 'none';
                }
            });
        });
});

// Show options when input is focused
searchInput.addEventListener('focus', function() {
    optionsContainer.style.display = 'block';
});

// Hide options when clicking outside
document.addEventListener('click', function(event) {
    if (!searchInput.contains(event.target) && !optionsContainer.contains(event.target)) {
        optionsContainer.style.display = 'none';
    }
});

// Checkbox handling
optionsContainer.addEventListener('change', function(event) {
    if (event.target.tagName === 'INPUT' && event.target.type === 'checkbox') {
        const selectedOptions = Array.from(optionsContainer.querySelectorAll('input:checked'))
            .map(checkbox => checkbox.parentNode.textContent.trim());
        
        searchInput.value = selectedOptions.join(', '); // Display selected options in the input
        selectedSubjectsInput.value = Array.from(optionsContainer.querySelectorAll('input:checked'))
            .map(checkbox => checkbox.value).join(','); // Store selected subjects
    }
});

});


</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('enrollForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the default way

        const formData = new FormData(this); // Create FormData from the form
        console.log('Form data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        fetch('../functions/enroll_student.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Check if the response status is OK
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text(); // Read response as text
        })
        .then(text => {
            console.log('Raw response:', text); // Log the raw response to inspect it

            try {
                const data = JSON.parse(text); // Parse the response text as JSON

                if (data.success) {
                    alert(data.success); // Show success message
                    setTimeout(() => {
                        window.location.href = 'student.php'; // Redirect after 2 seconds
                    }, 1000);
                } else {
                    alert(data.error); // Show error message
                }
            } catch (e) {
                console.error('Error parsing JSON:', e); // Log JSON parsing errors
                alert('An error occurred while processing the response.'); // User-friendly message
            }
        })
        .catch(error => {
            console.error('Fetch error:', error); // Log fetch errors
            alert('An error occurred: ' + error); // User-friendly message
        });
    });
});
</script>




<?php include '../includes/footer.php';?>