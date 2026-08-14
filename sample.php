<?php include '../includes/header.php'; ?>
<style>
    /* Modal styling */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1000; /* Sit on top */
  left: 0;
  top: 0;
  
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

.modal-content {
  margin: 5% auto; /* 10% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 50%; /* Could be more or less, depending on screen size */
  border-radius: 8px;
}


.close {
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  text-decoration: none;
  cursor: pointer;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
}

.btnn{
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  margin: 10px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  width: 100%;
}
 .edit-btn{
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  margin: 10px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}
.delete-btn{
  background-color: red;
  color: white;
  padding: 10px 20px;
  margin: 10px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

.btn:hover {
  background-color: #45a049;
}

 .edit-btn:hover {
  background-color: #45a049;
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
                                Results
                            </h1>

                        </div>
                        <div class="col-auto">

                            <!-- Button -->
                            <a href="add_marks.php" class="btn btn-primary lift">
                                Add Marks 
                            </a>
                            <a href="import_mark.php" class="btn btn-secondary lift">
                                Import Marks 
                            </a>

                        </div>
                    </div> <!-- / .row -->
                   
                </div>
            </div>

            <div style="display:flex;justify-content:space-between">
                <div class="form-group col-3" >
                    <label for="course_code">Course <span class="text-red">*</span></label>
                    <select id="course" name="course_code" class="form-control mb-3" required>
                        <option value="">Select Course</option>
                    </select>
                </div>
                    
                <div class="form-group col-3" >
                    <label for="year">Years<span class="text-red">*</span></label>
                    <select id="year" name="year" class="form-control mb-3" disabled required>
                        <option value="">Select Years</option>
                    </select>
                </div>
                <div class="form-group col-3" >
                    <label for="semester_id">Semester<span class="text-red">*</span></label>
                    <select id="semester" name="semester_id" class="form-control mb-3" disabled required>
                        <option value="">Select Semester</option>
                    </select>
                </div>
                
            </div>
            <div style="display:flex;justify-content:space-between">
                 <div class="form-group col-3" >
                    <label for="subject_code">Paper<span class="text-red">*</span></label>
                    <select id="subject_code" name="subject_code" class="form-control mb-3" disabled required>
                        <option value="">Select Paper</option>
                    </select>  
                </div>
                <div class="form-group col-3" >
                    <label for="test">Test Name<span class="text-red">*</span></label>
                    <select id="test" name="test" class="form-control mb-3" disabled required>
                        <option value="">Select Test</option>
                    </select>  
                </div>
                <div class="form-group col-3" >
                    <label for="date">Year<span class="text-red">*</span></label>
                    <select id="date" name="date" class="form-control mb-3">
                        <option value="">Select Year</option>
                        <!-- Options will be populated here -->
                    </select>
                </div>
                
            </div>
            <input type="hidden" value=<?php echo $teacherId ?> name="given_by" id="given_by">
            <button id="filter-btn" class="btn btn-primary lift mb-5">Filter</button>
            <!-- Card -->
                <div class="card" data-list='{"valueNames": ["student-id", "student-name"]}'>
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
                        <table id="results-table" class="table table-sm table-nowrap card-table" >
                            <thead>
                                <tr >
                                    <th> <a href="#" class="text-body-secondary list-sort" data-sort="student-id">
                                        Unique ID
                                    </a></th>
                                    <th> <a href="#" class="text-body-secondary list-sort" data-sort="student-name">
                                        Student Name
                                    </a></th>
                                    <th>Marks</th>
                                    <th style="display:none;" id="btnn"><button id="edit-all" class="btn btn-secondary lift ">Edit All</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be dynamically inserted here -->
                            </tbody>
                        </table>
                        <div id="no-student-msg" style="display: none; text-align:center;color:red;fontsize:25px;">No Results Found</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Edit Student Marks</h2>
    <form id="edit-form">
      <input type="hidden" id="edit-mark-id">
      <input type="hidden" id="edit-date">
      <div class="form-group">
        <label for="edit-student-name">Student Name:</label>
        <input type="text" id="edit-student-name" class="form-control" disabled>
      </div>
      <div class="form-group">
        <label for="edit-mark-score">Marks:</label>
        <input type="number" id="edit-mark-score" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary btnn">Save Changes</button>
    </form>
  </div>
</div>
<!-- Edit All Modal -->
<div id="editAllModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Edit All Student's Marks and Details</h2>
    <form id="edit-all-form">
       <div style="display:flex;justify-content:space-between">
            <div class="form-group col-3">
                <label for="edit_course_code">Course Code</label>
                <select id="edit_course_code" name="edit_course_code" class="form-control">
                   <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="form-group col-3">
                <label for="edit_year">Years</label>
                <select id="edit_year" name="edit_year" class="form-control">
                  <!-- Options will be populated dynamically -->
                </select>
            </div>
            <div class="form-group col-3">
                <label for="edit_semester_id">Semester</label>
                <select id="edit_semester_id" name="edit_semester_id" class="form-control">
                  <!-- Options will be populated dynamically -->
                </select>
            </div>
       </div>
        
        <div class="form-group">
            <label for="edit_subject_code">Subject:</label>
            <select id="edit_subject_code" name="edit_subject_code" class="form-control">
                <!-- Options will be populated dynamically -->
            </select>
        </div>
      
      
     <div style="display:flex;justify-content:space-between">
            <div class="form-group col-3">
                <label for="edit_test_name">Test Name</label>
                <input type="text" id="edit_test_name" name="edit_test_name" required class="form-control">
            </div>
            <div class="form-group col-3">
                <label for="edit_test_date">Test Date</label>
                <input type="date" id="edit_test_date" name="edit_test_date" required class="form-control">
            </div>
            <div class="form-group col-3">
                <label for="edit_result_date">Result Date</label>
                <input type="date" id="edit_result_date" name="edit_result_date" required class="form-control">
            </div>
     </div>

      <div style="display:flex;justify-content:space-between">
            <div class="form-group col-5">
                <label for="edit_full_mark">Full Mark</label>
                <input type="number" id="edit_full_mark" name="edit_full_mark" required class="form-control">
            </div>
            <div class="form-group col-5">
                <label for="edit_pass_mark">Pass Mark</label>
                <input type="number" id="edit_pass_mark" name="edit_pass_mark" required class="form-control">
            </div>
      </div>
      <div class="card" data-list='{"valueNames": ["student-id", "student-name"]}'>
            <div class="card-header">

                <!-- Search -->
                <form>
                    <div class="input-group input-group-flush input-group-merge input-group-reverse">
                        <input class="form-control list-searchs" type="search" placeholder="Search">
                        <span class="input-group-text">
                        <i class="fe fe-search"></i>
                        </span>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                    <table id="edit-all-table" class="table table-sm table-nowrap card-table">
                        <thead>
                            <tr>
                                    <th> <a href="#" class="text-body-secondary list-sort" data-sort="student-id">
                                        Unique ID
                                    </a></th>
                                    <th> <a href="#" class="text-body-secondary list-sort" data-sort="student-name">
                                        Student Name
                                    </a></th>
                                <th>Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically added here -->
                        </tbody>
                    </table>
                    <div id="no-student" style="display: none; text-align:center;color:red;fontsize:25px;">No Results Found</div>
            </div>
        </div>

      
      <button type="submit" class="btn btn-primary btnn">Save Changes</button>
    </form>
  </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Populate courses on page load
        fetchCourses();

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

        document.getElementById('course').addEventListener('change', function() {
            const courseCode = this.value;
            if (courseCode) {
                fetch(`../controller/get_years.php?course_code=${courseCode}`)
                    .then(response => response.json())
                    .then(data => {
                        const yearSelect = document.getElementById('year');
                        yearSelect.innerHTML = '<option value="">Select Years</option>';
                        data.forEach(year => {
                            const option = document.createElement('option');
                            option.value = year.year;
                            option.textContent = year.year + ' Years';
                            yearSelect.appendChild(option);
                        });
                        yearSelect.disabled = false;
                        document.getElementById('semester').disabled = true;
                    })
                    .catch(error => console.error('Error:', error));
            }
            resetFilters();
        });

        document.getElementById('year').addEventListener('change', function() {
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
            const year = document.getElementById('year').value;
            const courseCode = document.getElementById('course').value;
            if (year && courseCode) {
                fetch(`../controller/get_paper.php?year=${year}&course_code=${courseCode}`)
                    .then(response => response.json())
                    .then(data => {
                        const subjectSelect = document.getElementById('subject_code');
                        subjectSelect.innerHTML = '<option value="">Select Paper</option>';
                        data.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.subject_code;
                            option.textContent = `${subject.subject_name} (${subject.subject_code})`;
                            subjectSelect.appendChild(option);
                        });
                        subjectSelect.disabled = false;
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
        document.getElementById('subject_code').addEventListener('change', function() {
            
            const given_by = document.getElementById('given_by').value;

                fetch(`../controller/get_test.php?given_by=${given_by}`)
                    .then(response => response.json())
                    .then(data => {
                        const testSelect = document.getElementById('test');
                        testSelect.innerHTML = '<option value="">Select Test</option>';
                        data.forEach(test => {
                            const option = document.createElement('option');
                            option.value = test.test_name;
                            option.textContent = `${test.test_name}`;
                            testSelect.appendChild(option);
                        });
                        testSelect.disabled = false;
                    })
                    .catch(error => console.error('Error:', error));
            
        });

        document.getElementById('filter-btn').addEventListener('click', function() {
                const courseCode = document.getElementById('course').value;
                const year = document.getElementById('year').value;
                const semesterId = document.getElementById('semester').value;
                const subjectCode = document.getElementById('subject_code').value;
                const test = document.getElementById('test').value;
                const date = document.getElementById('date').value;
                const given_by = document.getElementById('given_by').value;
                const heading = document.getElementById('btnn');
    
    
                   



    if (courseCode && year && semesterId && subjectCode && test && date) {
        heading.style.display='block';

        fetch(`../controller/get_students_marks.php?course_code=${courseCode}&semester_id=${semesterId}&subject_code=${subjectCode}&date=${date}&given_by=${given_by}&test=${test}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#results-table tbody');
                tableBody.innerHTML = ''; // Clear existing rows
                const noStudentMsg = document.getElementById('no-student-msg');

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="student-id">${student.student_id}</td>
                            <td class="student-name">${student.student_name}</td>
                            <td>${student.mark_score}</td>
                            <td>
                                <button class="edit-btn" data-id="${student.mark_id}" data-name="${student.student_name}" data-score="${student.mark_score}" data-date="${student.test_date}">Edit</button>
                            
                            <a href="#" class="delete-btn" onclick="confirmDelete(${student.mark_id})">
                                                        Delete
                                                    </a>
                            </td>

                        `;
                        tableBody.appendChild(row);
                    });
                    noStudentMsg.style.display = 'none'; // Hide "No Student Found" message

                    // Add event listeners to the Edit buttons
                    document.querySelectorAll('.edit-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const markId = this.getAttribute('data-id');
                            const studentName = this.getAttribute('data-name');
                            const markScore = this.getAttribute('data-score');
                            const testDate = this.getAttribute('data-date');

                            // Populate the modal with the student details
                            document.getElementById('edit-mark-id').value = markId;
                            document.getElementById('edit-student-name').value = studentName;
                            document.getElementById('edit-mark-score').value = markScore;
                            document.getElementById('edit-date').value = testDate;

                            // Open the modal
                            document.getElementById('editModal').style.display = 'block';
                        });
                    });
                } else {
                    noStudentMsg.style.display = 'block'; // Show "No Student Found" message
                    heading.style.display='block';

                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('no-student-msg').style.display = 'block'; // Show "No Student Found" message on error
            });
    } else {
        alert('Please Select in all required fields to filter.');
    }
});



// Close the modal when the user clicks the close button or outside the modal
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
});

window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        document.getElementById('editModal').style.display = 'none';
    }
};
// Function to refresh the table
function refreshTable() {
    document.getElementById('filter-btn').click();
}

// Event listener for the form submission
document.getElementById('edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const markId = document.getElementById('edit-mark-id').value;
    const newScore = document.getElementById('edit-mark-score').value;

    // Call your PHP backend to update the student's marks using fetch
    fetch('../controller/update_student_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            mark_id: markId,
            mark_score: newScore
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Marks updated successfully');
            document.getElementById('editModal').style.display = 'none';
            // Refresh the table
            refreshTable();
        } else {
            alert('Failed to update marks');
        }
    })
    .catch(error => console.error('Error:', error));
});





        function resetFilters() {
            document.getElementById('year').value = '';
            document.getElementById('semester').value = '';
            document.getElementById('subject_code').value = '';
            document.querySelector('#results-table tbody').innerHTML = ''; // Clear table
            document.getElementById('no-student-msg').style.display = 'none'; // Hide "No Student Found" message
        }
        
         // Handle deletion
    window.confirmDelete = function(Id) {
        if (confirm('Are you sure you want to delete this Students Result It will Premanently Delete?')) {
            fetch('../controller/deletemark.php', {
                method: 'POST',
                body: JSON.stringify({ id: Id }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        refreshTable();
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('date');
        const currentYear = new Date().getFullYear();
        const startYear = 2000;
        const endYear = currentYear;

        for (let year = endYear; year >= startYear; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Existing code...

    const editAllBtn = document.getElementById('edit-all');
    const editAllModal = document.getElementById('editAllModal');
    const editAllForm = document.getElementById('edit-all-form');
    const editAllTableBody = document.querySelector('#edit-all-table tbody');
    const closeEditAll = editAllModal.querySelector('.close');

    editAllBtn.addEventListener('click', function() {
        editAllModal.style.display = 'block';
        populateEditAllModal(); // Populate the modal with student data
    });

    closeEditAll.addEventListener('click', function() {
        editAllModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == editAllModal) {
            editAllModal.style.display = 'none';
        }
    });

    function populateEditAllModal() {
        const courseCode = document.getElementById('course').value;
        const year = document.getElementById('year').value;
        const semesterId = document.getElementById('semester').value;
        const subjectCode = document.getElementById('subject_code').value;
        const test = document.getElementById('test').value;
        const date = document.getElementById('date').value;
        const given_by = document.getElementById('given_by').value;

        // Populate the additional fields
        document.getElementById('edit_test_name').value = test;

        fetchCourses(courseCode);

            function fetchCourses(selectedCourseCode) {
                fetch('../controller/get_courses.php')
                    .then(response => response.json())
                    .then(courses => {
                    const courseSelect = document.getElementById('edit_course_code');
                    courseSelect.innerHTML = ''; // Clear existing options

                    courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.course_code;
                        option.textContent = course.course_name;
                
                        courseSelect.appendChild(option);
                    });
                    courseSelect.value = selectedCourseCode;
                })
                .catch(error => console.error('Error:', error));
            }
        fetchYears(year);
            function fetchYears(selectedYear){
                fetch('../controller/get_years.php?course_code=${courseCode}')
                    .then(response => response.json())
                    .then(years => {
                    const yearSelect = document.getElementById('edit_year');
                    yearSelect.innerHTML = ''; // Clear existing options

                    years.forEach(year => {
                        const option = document.createElement('option');
                        option.value = year.year;
                            option.textContent = year.year + ' Years';
                
                            yearSelect.appendChild(option);
                    });
                    yearSelect.value = selectedYear;
                })
                .catch(error => console.error('Error:', error));
            }
        fetchSemester(semesterId);
        function fetchSemester(selectedSemester){
                fetch('../controller/get_semesters.php?year=${year}&course_code=${courseCode}')
                    .then(response => response.json())
                    .then(semesters => {
                    const semesterSelect = document.getElementById('edit_semester_id');
                    semesterSelect.innerHTML = ''; // Clear existing options

                    semesters.forEach(semester => {
                        const option = document.createElement('option');
                        option.value = semester.semester_id;
                        option.textContent = semester.semester_name;
                
                        semesterSelect.appendChild(option);
                    });
                    semesterSelect.value = selectedSemester;
                })
                .catch(error => console.error('Error:', error));
        }
        fetchPaper(subjectCode);
        function fetchPaper(selectedSubjectCode) {
            fetch(`../controller/get_paper.php?course_code=${courseCode}&year=${year}`)
            .then(response => response.json())
            .then(papers => {

           

                    const paperSelect = document.getElementById('edit_subject_code');
                    paperSelect.innerHTML = ''; // Clear existing options

                    papers.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.subject_code;
                option.textContent = `${subject.subject_name} (${subject.subject_code})`;
                paperSelect.appendChild(option);
                    });

                    // Set the default value after options are added
                    paperSelect.value = selectedSubjectCode;
            })
            .catch(error => console.error('Error:', error));
        }

            

       

        fetch(`../controller/get_students_marks.php?course_code=${courseCode}&semester_id=${semesterId}&subject_code=${subjectCode}&date=${date}&given_by=${given_by}&test=${test}`)
            .then(response => response.json())
            .then(data => {
                editAllTableBody.innerHTML = ''; // Clear existing rows

                if (Array.isArray(data) && data.length > 0) {
                    const { full_mark, pass_mark, test_date, result_date} = data[0];
                    document.getElementById('edit_full_mark').value = full_mark;
                    document.getElementById('edit_pass_mark').value = pass_mark;
                    document.getElementById('edit_test_date').value = test_date;
                    document.getElementById('edit_result_date').value = result_date;
                    data.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="student-id">${student.student_id}</td>
                            <td class="student-name">${student.student_name}</td>
                            <td><input type="number" name="marks[${student.mark_id}]" value="${student.mark_score}" required class="form-control"></td>
                        `;
                        editAllTableBody.appendChild(row);
                    });
                } else {
                    editAllTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No Students Found</td></tr>';
                }
            })
            .catch(error => console.error('Error:', error));
    }
    editAllForm.addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(editAllForm);
    const data = {};

    // Manually serialize the form data to ensure correct format
    formData.forEach((value, key) => {
        if (key.startsWith('marks[')) {
            // Handle marks array separately
            const markId = key.match(/marks\[(\d+)\]/)[1];
            if (!data['marks']) {
                data['marks'] = {};
            }
            data['marks'][markId] = value;
        } else {
            // Handle other fields
            data[key] = value;
        }
    });

    console.log('Form Data:', data);

    fetch('../controller/update_all_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Server Response:', data);
            alert('Marks and details updated successfully!');
            editAllModal.style.display = 'none';
            // Optionally refresh the main table
            document.getElementById('filter-btn').click();
        } else {
            alert('Failed to update marks: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});


});

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.list-search');
    const tableBody = document.querySelector('#results-table tbody');
    const noStudentMsg = document.getElementById('no-student-msg');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        let hasVisibleRows = false;

        rows.forEach(row => {
            const studentName = row.querySelector('.student-name').textContent.toLowerCase();
            const studentId = row.querySelector('.student-id').textContent.toLowerCase();

            if (studentName.includes(searchTerm) || studentId.includes(searchTerm)) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        noStudentMsg.style.display = hasVisibleRows ? 'none' : 'block';
    });
});



    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.list-searchs');
    const tableBody = document.querySelector('#edit-all-table tbody');
    const noStudentMsg = document.getElementById('no-student');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        let hasVisibleRows = false;

        rows.forEach(row => {
            const studentName = row.querySelector('.student-name').textContent.toLowerCase();
            const studentId = row.querySelector('.student-id').textContent.toLowerCase();

            if (studentName.includes(searchTerm) || studentId.includes(searchTerm)) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        noStudentMsg.style.display = hasVisibleRows ? 'none' : 'block';
    });
});



    </script>


<?php include '../includes/footer.php'; ?>
