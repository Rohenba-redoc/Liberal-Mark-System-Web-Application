<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- Header -->
            <div class="header mt-md-5">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">New Notice</h6>
                            <!-- Title -->
                            <h1 class="header-title">Add new notice</h1>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Form -->
            <form class="mb-4" id="noticeForm">
                <input type="hidden" value="<?php echo $teacherId?>" name="teacher_id">
                <!-- Course -->
                <div style="display:flex;justify-content:space-between;">
                    <div class="col-5" id="course-group">
                        <label for="course">Discipline<span class="text-red">*</span></label>
                        <select id="course" name="course_code" class="form-control mb-3" required>
                            <option value="">Select Discipline</option>
                        </select>
                    </div>
                    <div class="col-5" id="department-group">
                        <label for="department">Department<span class="text-red">*</span></label>
                        <select id="department" name="department_id" class="form-control mb-3" disabled required>
                            <option value="">Select Department</option>
                        </select>
                    </div>
                </div>
              
                <!-- Year and Semester -->
                <div style="display:flex;justify-content:space-between;">
                    <div class="col-5" id="semester-group">
                        <label for="semester">Semester<span class="text-red">*</span></label>
                        <select id="semester" name="semester_id" class="form-control mb-3" disabled required>
                            <option value="">Select Semester</option>
                        </select>
                    </div>
                    <div class="col-5" id="subject-group">
                        <label for="subject">Subject<span class="text-red">*</span></label>
                        <select id="subject" name="subject_code" class="form-control mb-3" disabled required>
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                </div>

                

                <!-- Notice Title -->
                <div class="form-group">
                    <label class="form-label">Notice Title<span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="title" required required>
                </div>

                <!-- Notice Description -->
                <div class="form-group">
                    <label class="form-label mb-1">Notice Description<span class="text-red">*</span></label>
                    <textarea name="body" id="body" class="form-control" ></textarea>
                </div>

                <!-- Divider -->
                <hr class="mt-4 mb-5">

                <!-- Buttons -->
                <button type="submit" class="btn w-100 btn-primary">Add Notice</button>
                <a href="notice.php" class="btn w-100 btn-link text-body-secondary mt-2">Back</a>
            </form>
        </div>
    </div> <!-- / .row -->
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.8.0/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor
        CKEDITOR.replace('body');

        // Populate courses on page load
        fetchCourses();

        // Fetch and populate courses
        function fetchCourses() {
            fetch('../controller/get_courses.php')
                .then(response => response.json())
                .then(data => {
                    const courseSelect = document.getElementById('course');
                    courseSelect.innerHTML = '<option value="">Select Discipline</option>';
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
            document.getElementById('combinations-list').innerHTML = '';
        });

        // Fetch semesters based on selected year
        document.getElementById('department').addEventListener('change', function() {
            const department = this.value;
            if (department ) {
                fetch(`../controller/get_semesters.php`)
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


        

      

        // Form submission handler
        document.getElementById('noticeForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Step 1: Prepare Form Data
    const formData = new FormData(this); // Collect all form data
    const body = CKEDITOR.instances.body.getData(); // Get CKEditor content
    
    // Remove the existing 'body' field before appending CKEditor content
    formData.delete('body');
    
    // Append CKEditor content to 'body'
    formData.append('body', body);

    // Step 2: Log the FormData content
    console.log('FormData content:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    // Step 3: Send Data Using Fetch
    fetch('../controller/add_notice.php', { // URL to send the data
        method: 'POST', // Specify the HTTP method
        body: formData // Send the form data
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse the response body as JSON
    })
    .then(data => {
        console.log('Response Data:', data); // Log the data for debugging

        if (data.success) {
            alert('Notice added successfully!'); // Notify the user of success
            document.getElementById('noticeForm').reset(); // Reset the form
            CKEDITOR.instances.body.setData(''); // Clear CKEditor content
        } else {
            alert('Error adding notice: ' + data.error); // Show an error message
        }
    })
    .catch(error => {
        console.error('Error:', error); // Log the error to the console
        alert('There was a problem with the submission: ' + error.message); // Show a generic error message
    });
});

    });
</script>

<?php include '../includes/footer.php'; ?>
