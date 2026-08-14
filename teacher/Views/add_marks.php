<?php include '../includes/header.php'?>
<style>
    .req {
        color: red;
    }
    .kan {
        display: flex;
        justify-content: space-between;
    }
    .kane {
        padding: 10px;
        padding-top: 50px;
        padding-bottom: 50px;
        border-radius: 10px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        margin-bottom: 20px;
    }
    .kaneee {
        padding: 10px;
        padding-bottom: 50px;
        border-radius: 10px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        margin-bottom: 20px;
    }
    .kanee {
        padding: 10px;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        margin-bottom: 20px;
    }
    .progress-bar {
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 20px;
        position: relative;
    }
    .progress {
        background-color: lightseagreen;
        height: 10px;
        width: 0;
        position: absolute;
    }
    .just {
        background-color: lightgray;
        height: 10px;
        width: 100%;
        position: absolute;
    }
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
    button {
        background-color: #4caf50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
    button.prev {
        background-color: #bbb;
    }
    button.prev:hover {
        background-color: #aaa;
    }
    #progress-num {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        justify-content: space-between;
    }
    #progress-num::before {
        content: "";
        background-color: lightgray;
        position: absolute;
        top: 50%;
        left: 0;
        height: 5px;
        width: 100%;
        z-index: -1;
    }
    #progress-num .steps {
        border: 3px solid lightgray;
        border-radius: 100%;
        width: 25px;
        height: 25px;
        line-height: 25px;
        text-align: center;
        background-color: #fff;
        font-family: sans-serif;
        font-size: 14px;
        position: relative;
        z-index: 1;
        color: black;
    }
    #progress-num .steps.active {
        border-color: lightseagreen;
        background-color: lightseagreen;
        color: #fff;
    }
    .ins {
        display: grid;
        background: black;
        margin: 40px;
        border-radius: 10px;
        width: 90%;
        height: 60%;
        align-items: center;
        gap: 30px;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        margin-bottom: 20px;
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
                            <h6 class="header-pretitle">
                                Overview
                            </h6>
                            <!-- Title -->
                            <h1 class="header-title">
                                Add Marks
                            </h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="dashboard.php" class="btn btn-primary lift">
                                Cancel    
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-0">
                <div class="progress-bar">
                    <div class="just"></div>
                    <div class="progress"></div>
                    <ul id="progress-num">
                        <li class="steps active">1</li>
                        <li class="steps">2</li>
                        <li class="steps">3</li>
                        <li class="steps">4</li>
                    </ul>
                </div>

                <form id="multiStepForm" action="" method="post">
                <input type="hidden" value="<?php echo $teacherId ?>" name="given_by" id="given_by">
                <!-- Start Step 1 -->
                    <div class="step">
                        <div class="kane">
                            <div class="kan">
                                <div class="form-group" style="width:48%">
                                    <label for="name">Test Name <span class="req">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" autocomplete="off" required>
                                </div>
                                <div class="form-group" style="width:48%">
                                    <label for="tdate">Test Date <span class="req">*</span></label>
                                    <input type="date" name="tdate" id="tdate" class="form-control" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="kan">
                                <div class="form-group" style="width:48%">
                                    <label for="rdate">Result Date <span class="req">*</span></label>
                                    <input type="date" name="rdate" id="rdate" class="form-control" autocomplete="off" required>
                                </div>
                                <div class="form-group" style="width:48%">
                                    <label for="fmark">Full Marks <span class="req">*</span></label>
                                    <input type="number" name="fmark" id="fmark" class="form-control" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="kan">
                                <div class="form-group" style="width:48%">
                                    <label for="pmark">Pass Marks <span class="req">*</span></label>
                                    <input type="number" name="pmark" id="pmark" class="form-control" autocomplete="off" required maxlength="10">
                                </div>
                                <div class="form-group" style="width:48%; padding-top:25px; text-align:center;">
                                    <button type="button" class="next">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Step 1 -->

                    <!-- Start Step 2 -->
                    <div class="step">
                        <div class="kane">
                            <div class="kan">
                                <div class="form-group" style="width:48%">
                                    <label for="course_code">Course <span class="text-red">*</span></label>
                                    <select id="course" name="course_code" class="form-control mb-3" required>
                                    <option value="">Select Course</option>
                                    </select>
                                    
                                </div>
                                <div class="form-group" style="width:48%">
                                        <label for="department">Department<span class="text-red">*</span></label>
                                        <select id="department" name="department_id" class="form-control mb-3" disabled required >
                                            <option value="" >Select Department</option>
                                        </select>
                                </div>
                            </div>
                            <div class="kan">
                                <div class="form-group" style="width:48%">
                                        <label for="semester_id">Semester<span class="text-red">*</span></label>
                                        <select id="semester" name="semester_id" class="form-control mb-3" disabled required>
                                            <option value="">Select Semester</option>
                                        </select>
                                    
                                </div>
                                <div class="form-group" style="width:48%">
                                <label for="subject">Subject<span class="text-red">*</span></label>
                        <select id="subject" name="subject_code" class="form-control mb-3" disabled required>
                            <option value="">Select Subject</option>
                        </select> 
                                </div>
                            </div>
                            <div class="kan">
                                <button type="button" class="prev">Previous</button>
                                <button type="button" class="next sel" >Next</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Step 2 -->

                    <!-- Start Step 3 -->
                    <div class="step">
                        <div class="kane">
                            <div class="table-responsive">

                                <table class="table table-sm table-nowrap card-table">
                               <thead>
                               <tr>
                                    <th>Unique-ID</th>
                                    <th>Student_Name</th>
                                    <th>Mark score</th>
                                    <th></th>
                                </tr>
                               </thead>
                               <tbody>

                               </tbody>
                                </table>
                            </div>
                            
                            <div class="kan">
                                <button type="button" class="prev">Previous</button>
                                <button type="button" class="next">Next</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Step 3 -->

                    <!-- Start Step 4 -->
                    <div class="step">
                    <h3>Review</h3>

                                <div id="summary" >
                                    <!-- Summary will be dynamically populated here -->
                                </div>
                            <div class="kan" style="margin-top:10px;">
                                    <button type="button" class="prev">Previous</button>
                                    <button type="submit" class="submit">Submit</button>
                            </div>
                    </div>
                    <!-- End Step 4 -->
                </form>
            </div>
        </div>
    </div>
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
        document.querySelector('.sel').addEventListener('click', function() {
            const courseCode = document.getElementById('course').value;
            const semesterId = document.getElementById('semester').value;
            const subjectCode = document.getElementById('subject').value;
            if (courseCode && semesterId && subjectCode) {
                fetch(`../controller/get_students.php?course_code=${courseCode}&semester_id=${semesterId}&subject_code=${subjectCode}`)
                .then(response => response.json())
                .then(data => {
                console.log(data);
                
                const tableBody = document.querySelector('table tbody');
                tableBody.innerHTML = ''; // Clear existing rows

                    if (Array.isArray(data)) {
                        
                            if (data.length === 0) {
                                // No students found
                                const row = document.createElement('tr');
                                row.innerHTML = '<td colspan="4" style="text-align:center">No Student Found</td>'; // colspan to span all columns
                                tableBody.appendChild(row);
                            }
                            else{
                                data.forEach(student => {
                                    const row = document.createElement('tr');
                                row.innerHTML = `
                                <td>${student.student_id}</td>
                                <td>${student.student_name}</td>
                                <td><input type="number" name="marks[${student.student_id}]" id="mark_score_${student.student_id}" class="form-control" ></td>
                                <td> <input type="checkbox" name="student_checkbox" value="${student.student_id}" data-student-name="${student.student_name}"> </td>
                                                          `;
                                    tableBody.appendChild(row);
                                });
                            }
                           
                            
                    } else {
                    console.error('Expected an array but got:', data);
                    }

                })
                    .catch(error => console.error('Error:', error));
            } else {
                alert('Please fill in all required fields.');
            }
        });




    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 0;
    const nextButtons = Array.from(document.querySelectorAll('.next'));
    const prevButtons = Array.from(document.querySelectorAll('.prev'));
    const steps = Array.from(document.querySelectorAll('.step'));
    const progress = document.querySelector('.progress');
    const progressNum = document.querySelectorAll('.steps');
    const form = document.getElementById('multiStepForm');
    const formData = {};

    
    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
            progressNum[index].classList.toggle('active', index <= stepIndex);
        });

        const progressWidth = ((stepIndex + 1) / steps.length) * 100;
        progress.style.width = progressWidth + '%';
        if (stepIndex === steps.length - 1) {
            updateSummary();
        }
    }
   

    function updateSummary() {
    const name = document.getElementById('name').value;
    const tdate = document.getElementById('tdate').value;
    const rdate = document.getElementById('rdate').value;
    const fmark = document.getElementById('fmark').value;
    const pmark = document.getElementById('pmark').value;
    const course = document.getElementById('course').selectedOptions[0]?.text || '';
    const semester = document.getElementById('semester').selectedOptions[0]?.text || '';
    const subject = document.getElementById('subject').selectedOptions[0]?.text || '';
    
    // Get all checked students
    const students = Array.from(document.querySelectorAll('input[name="student_checkbox"]:checked')).map(checkbox => {
        const studentId = checkbox.value;
        const studentName = checkbox.getAttribute('data-student-name');
        const markScore = document.getElementById(`mark_score_${studentId}`).value;
        return { studentId, studentName, markScore };
    });

    const data = {
        name: name,
        tdate: tdate,
        rdate: rdate,
        fmark: fmark,
        pmark: pmark,
        course: course,
        semester: semester,
        subject: subject,
        students: students // Added to data object
    };

    displaySummary(data);
    }



    function displaySummary(data) {
    const summaryDiv = document.getElementById('summary');
    const studentsDetails = data.students.map(student => `
        <tr>
            <td>${student.studentId}</td>
            <td>${student.studentName}</td>
            <td>${student.markScore}</td>
        </tr>
    `).join('');
    function formatDate(dateString) {
    var date = new Date(dateString);
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

// Format dates
var formattedTestDate = formatDate(data.tdate);
var formattedResultDate = formatDate(data.rdate);
    summaryDiv.innerHTML = `
        <div style="width:100%; padding:15px; border-radius:10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">
            <table style="width:100%;margin-bottom:20px;" class="table table-sm table-nowrap card-table" >
                <tr>
                    <td><strong>Test Name:</strong><br> ${data.name}</td>
                    <td><strong>Test Date:</strong><br> ${formattedTestDate}</td>
                    <td><strong>Result Date:</strong><br> ${formattedResultDate}</td>
                </tr>
                <tr>
                    <td><strong>Course:</strong><br> ${data.course}</td>
                    <td><strong>Semester:</strong><br> ${data.semester}</td>
                    <td><strong>Paper:</strong><br> ${data.subject}</td>
                </tr>
                <tr>
                    <td><strong>Full Mark:</strong><br> ${data.fmark}</td>
                    <td colspan="2"><strong>Pass Mark:</strong><br> ${data.pmark}</td>
                </tr>
               
            </table>
            <table style="width:100%; border:none;" class="table table-sm table-nowrap card-table">
                <h2 style="text-align:center">Students Marks</h2>
             <tr>
                                <th>UNIQUE ID</th>
                                <th>Student Name</th>
                                <th>Mark Score</th>
                            </tr>
                            ${studentsDetails}
            </table>
        </div>
    `;
    }


    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                currentStep++;
                if (currentStep >= steps.length) {
                    currentStep = steps.length - 1; // Prevent stepping beyond the last step
                }
                showStep(currentStep);
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            currentStep--;
            if (currentStep < 0) {
                currentStep = 0; // Prevent stepping before the first step
            }
            showStep(currentStep);
        });
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        if (validateStep(currentStep)) {
            alert('Form submitted successfully!');
            console.log('Submitted Data:', formData); // For debugging
        }
    });

    function validateStep(stepIndex) {
        const inputs = steps[stepIndex].querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = 'red';
            } else {
                input.style.borderColor = '';
            }
        });

        return isValid;
    }

    showStep(currentStep);
});
</script>
<script>
    document.querySelector('.submit').addEventListener('click', function(event) {
    event.preventDefault(); 

    const given_by = document.getElementById('given_by').value;
    const name = document.getElementById('name').value;
    const tdate = document.getElementById('tdate').value;
    const rdate = document.getElementById('rdate').value;
    const fmark = document.getElementById('fmark').value;
    const pmark = document.getElementById('pmark').value;
    const course = document.getElementById('course').selectedOptions[0]?.value || '';
    const semester = document.getElementById('semester').selectedOptions[0]?.value || '';
    const subject = document.getElementById('subject').selectedOptions[0]?.value || '';
    
    // Get all checked students
    const students = Array.from(document.querySelectorAll('input[name="student_checkbox"]:checked')).map(checkbox => {
        const studentId = checkbox.value;
        const markScore = document.getElementById(`mark_score_${studentId}`).value;
        return { studentId, markScore };
    });

    // Prepare the data to send to the server
    const formData = new FormData();
    formData.append('given_by', given_by);
    formData.append('name', name);
    formData.append('tdate', tdate);
    formData.append('rdate', rdate);
    formData.append('fmark', fmark);
    formData.append('pmark', pmark);
    formData.append('course', course);
    formData.append('semester', semester);
    formData.append('subject', subject);

    students.forEach(student => {
        formData.append(`students[${student.studentId}]`, student.markScore);
    });

    // Send the data using fetch or any other method
    fetch('../controller/add_marks.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(result => {
        if (result.success) {
        // Handle success
        console.log(result.success);
        alert(result.success);
        window.location.href = 'dashboard.php';

    } else if (result.error) {
        // Handle error
        console.error(result.error);
        alert(result.error);
    }
      }).catch(error => {
          console.error('Error:', error); // Handle the error
      });
});

</script>
<?php include '../includes/footer.php'?>
