<?php include '../includes/header.php'; ?>
<style>
    .progress-bar {
        background-color: #007bff; /* Blue color */
        text-align: center;
        color: white;
        transition: width 0.3s ease-in-out; /* Smooth transition */
    }
    .error-message {
        color: red;
        margin-top: 10px;
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
        display: none; /* Hidden by default */
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- Header -->
            <div class="header mt-md-5">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">Import Marks</h6>
                            <!-- Title -->
                            <h1 class="header-title">Import New Marks</h1>
                        </div>
                        <div class="col-auto">

                            <!-- Button -->
                            <a href="dashboard.php" class="btn btn-primary lift">
                                Cancel
                            </a>

                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>
                <form id="uploadForm" action="../controller/process_upload.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $teacherId ?>" name="given_by" id="given_by">
                   <div style="display:flex;justify-content:space-between;">
                   <div class="form-group col-5">
                            <label for="course_code">Course <span class="text-red">*</span></label>
                            <select id="course" name="course_code" class="form-control mb-3" required>
                            <option value="">Select Course</option>
                            </select>
                    </div>
                    <div class="form-group col-5">
                            <label for="department">Department<span class="text-red">*</span></label>
                            <select id="department" name="department_id" class="form-control mb-3" disabled required >
                                <option value="" >Select Department</option>
                            </select>
                    </div>
                   </div>
                   <div style="display:flex;justify-content:space-between;">
                   <div class="form-group col-5">
                            <label for="semester_id">Semester<span class="text-red">*</span></label>
                            <select id="semester" name="semester_id" class="form-control mb-3" disabled required>
                            <option value="">Select Semester</option>
                            </select>
                    </div>
                    <div class="form-group col-5">
                        <label for="subject">Subject<span class="text-red">*</span></label>
                        <select id="subject" name="subject_code" class="form-control mb-3" disabled required>
                            <option value="">Select Subject</option>
                        </select>                     
                    </div>
                   </div>
                    
                    
                    <div class="form-group">
                            <label for="test_name">Test Name<span class="text-red">*</span></label>
                            <input type="text" class="form-control" id="test_name" name="test_name" required>
                    </div>
                   <div style="display:flex;justify-content:space-between;">
                        <div class="form-group col-5">
                            <label for="test_date">Test Date<span class="text-red">*</span></label>
                            <input type="date" class="form-control" id="test_date" name="test_date" required>
                        </div>
                        <div class="form-group col-5">
                            <label for="result_date">Result Date<span class="text-red">*</span></label>
                            <input type="date" class="form-control" id="result_date" name="result_date" required>
                        </div>
                   </div>
                   <div style="display:flex;justify-content:space-between;">

                         <div class="form-group col-5">
                            <label for="full_mark">Full Mark<span class="text-red">*</span></label>
                            <input type="number" class="form-control" id="full_mark" name="full_mark" required>
                         </div>
                         <div class="form-group col-5">
                            <label for="pass_mark">Pass Mark<span class="text-red">*</span></label>
                            <input type="number" class="form-control" id="pass_mark" name="pass_mark" required>
                         </div>
                    </div>
                    
                    <div class="form-group">
                            <label for="file">Upload Excel File<span class="text-red">*</span></label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls" required>
                    </div>
                    <div id="errorMessage" class="error-message"></div>

                            <button id="uploadButton" type="submit" class="btn btn-primary">Upload</button>
                </form>
                <div class="progress mt-3" style="width: 100%; display: none;">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
                </div>
        </div>
    </div>
</div>
<!-- Loading overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <dotlottie-player src="https://lottie.host/219ef7b9-9084-4b0f-a77d-f90bb9876d96/kaJBEaGBS3.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
</div>
<script>
        document.addEventListener('DOMContentLoaded', function() {
        
         // Populate courses on page load
         fetchCourses();

// Fetch and populate courses
        function fetchCourses() {
                fetch('../controller/get_courses.php')
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
            })
            .catch(error => console.error('Error:', error));
        }
         // Fetch years based on selected course
         document.getElementById('course').addEventListener('change', function() {
            const courseCode = this.value;
            if (courseCode) {
                fetch(`../controller/get_department.php`)
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
                        document.getElementById('semester').disabled = true;
                    })
                    .catch(error => console.error('Error:', error));
            }
            document.getElementById('department').value = '';
            document.getElementById('semester').value = '';
            document.getElementById('subject_code').value = '';
        });
         // Fetch semesters based on selected year
         document.getElementById('department').addEventListener('change', function() {
            const year = this.value;
            const courseCode = document.getElementById('course').value;
            if (year && courseCode) {
                fetch(`../controller/get_semesters.php?year=${year}&course_code=${courseCode}`)
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
                    })
                    .catch(error => console.error('Error:', error));
                }
        });
        document.getElementById('semester').addEventListener('change', function() {
            const semester = this.value;
            const department = document.getElementById('department').value;

            console.log('Semester:', semester, 'Department:', department); // Log to check values

                if (department && semester) {
                    fetch(`../controller/get_paper.php?semester=${semester}&department=${department}`)
                    .then(response => response.json())
                    .then(data => {
                        const subjectSelect = document.getElementById('subject');
                        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.subject_code;
                            option.textContent = `${subject.subject_code} (${subject.subject_name})`;
                            subjectSelect.appendChild(option);
                        });
                        subjectSelect.disabled = false;
                    })
                    .catch(error => console.error('Error:', error));
                }
        });
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Extract form values
    const courseCode = document.getElementById('course').value;
    const semesterId = document.getElementById('semester').value;
    const subjectCode = document.getElementById('subject').value;
    const test_name = document.getElementById('test_name').value;
    const test_date = document.getElementById('test_date').value;
    const full_mark = document.getElementById('full_mark').value;
    const pass_mark = document.getElementById('pass_mark').value;

    console.log("Course Code:", courseCode);
    console.log("Semester ID:", semesterId);
    console.log("Subject Code:", subjectCode);
    console.log("Test Name:", test_name);
    console.log("Test Date:", test_date);
    console.log("Full Mark:", full_mark);
    console.log("Pass Mark:", pass_mark);

    // Get form elements
    var fileInput = document.getElementById('file');
    var errorMessage = document.getElementById('errorMessage');
    var uploadButton = document.getElementById('uploadButton');
    var loadingOverlay = document.getElementById('loadingOverlay');
    var progressBar = document.getElementById('progressBar');
    var progressContainer = document.querySelector('.progress');

    // Validate the file
    if (!fileInput.files.length) {
        errorMessage.textContent = 'Please select a file.';
        return;
    }

    var file = fileInput.files[0];
    var allowedExtensions = /(\.xlsx|\.xls)$/i;

    if (!allowedExtensions.exec(file.name)) {
        errorMessage.textContent = 'Invalid file type. Please upload an Excel file (.xlsx, .xls).';
        return;
    } else {
        errorMessage.textContent = ''; // Clear any previous error messages
    }

    // Create FormData object with form data
    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();

    // Show the progress bar and loading overlay
    progressContainer.style.display = 'block';
    loadingOverlay.style.display = 'flex';
    uploadButton.disabled = true; // Disable the upload button

    // Initialize variables for simulated progress
    let simulatedProgress = 0;
    const maxSimulatedProgress = 20; // Maximum simulated progress percentage before actual upload takes over

    // Function to update simulated progress slowly
    function updateSimulatedProgress() {
        if (simulatedProgress < maxSimulatedProgress) {
            // Increment simulated progress by a small random value
            simulatedProgress += Math.floor(Math.random() * 3) + 1; // Random value between 1 and 3
            simulatedProgress = Math.min(simulatedProgress, maxSimulatedProgress); // Ensure it doesn't exceed maxSimulatedProgress

            progressBar.style.width = simulatedProgress + '%';
            progressBar.innerHTML = simulatedProgress + '%';

            // Call the function again after a longer random delay between 500ms to 1200ms
            setTimeout(updateSimulatedProgress, Math.floor(Math.random() * 700) + 500);
        }
    }

    // Start simulated progress
    updateSimulatedProgress();

    // Event listener for real upload progress
    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            var actualProgress = Math.round((event.loaded / event.total) * 100);

            // Update the progress bar with the actual progress if it's greater than the simulated progress
            if (actualProgress > simulatedProgress) {
                progressBar.style.width = actualProgress + '%';
                progressBar.innerHTML = actualProgress + '%';
            }
        }
    };

    // Event listener for upload completion
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Successfully uploaded the Marks');
            window.location.href = 'dashboard.php';
        } else {
            alert('Upload failed. Please try again.');
        }
        resetProgress();
    };

    // Handle upload error
    xhr.onerror = function() {
        alert('An error occurred while uploading the file.');
        resetProgress();
    };

    // Function to reset progress and UI elements
    function resetProgress() {
        progressContainer.style.display = 'none';
        progressBar.style.width = '0%';
        progressBar.innerHTML = '0%';
        loadingOverlay.style.display = 'none';
        uploadButton.disabled = false;
    }

    // Open and send the request with form data
    xhr.open('POST', document.getElementById('uploadForm').action, true);
    xhr.send(formData);
});

        });
</script>

<?php include '../includes/footer.php'; ?>
