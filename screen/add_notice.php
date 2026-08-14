<?php 
include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            <!-- Header -->
            <div class="header mt-md-5">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">
                                New Notice
                            </h6>

                            <!-- Title -->
                            <h1 class="header-title">
                                Add new notice
                            </h1>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Form -->
            <form class="mb-4" id="noticeForm">

                <!-- Type -->
                <div class="form-group">
                    <label class="form-label">
                        Type<span class="text-red">*</span>
                    </label>
                    <select id="type" class="form-control" name="type">
                        <option >Select Type</option>
                        <option value="all">All</option>
                        <option value="filter">Filter</option>
                    </select>
                </div>

                <div style="display:flex;justify-content:space-between;">
                    <div class="col-5" id="stream-group">
                        <label for="stream">Stream<span class="text-red">*</span></label>
                        <select id="stream" name="stream_id" class="form-control mb-3">
                            <option value="">Select Stream</option>
                        </select>
                    </div>

                    <div class="col-5" id="course-group">
                        <label for="course">Course<span class="text-red">*</span></label>
                        <select id="course" name="course_code" class="form-control mb-3" disabled>
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>
              
                <div style="display:flex;justify-content:space-between;">
                    <div class="col-5" id="semester-group">
                        <label for="semester">Semester<span class="text-red">*</span></label>
                        <select id="semester" name="semester_id" class="form-control mb-3" disabled>
                            <option value="">Select Semester</option>
                        </select>
                    </div>
                    <div class="col-5" id="department-group">
                        <label for="department">Department<span class="text-red">*</span></label>
                        <select id="department" name="department_id" class="form-control mb-3" disabled>
                            <option value="">Select Department</option>
                        </select>
                    </div>
                </div>
                <div id="checkbox" style="margin:20px">
                    <input type="checkbox" name="check" id="check" onclick="toggleTextbox()">
                    <b>Add Subject for the sending the Notification
                </div>

                <!-- Multi-select for subjects -->
                <!-- Multi-select for subjects -->
                   <div class="form-group" id="subject-group">
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


                <!-- Notice Title -->
                <div class="form-group" id="title-box">
                    <label class="form-label">
                        Notice Title<span class="text-red">*</span>
                    </label>
                    <input type="text" class="form-control" name="title" required>
                </div>

                <!-- Notice Description -->
                <div class="form-group" id="body-box">
                    <label class="form-label mb-1">
                        Notice Description<span class="text-red">*</span>
                    </label>
                    <textarea name="body" id="body" class="form-control"></textarea>
                </div>

                <!-- Divider -->
                <hr class="mt-4 mb-5">

                <!-- Buttons -->
                <button type="submit" class="btn w-100 btn-primary" id="add">
                    Add Notice
                </button>
                <a href="notice.php" class="btn w-100 btn-link text-body-secondary mt-2" id="back">
                    Back
                </a>
            </form>
        </div>
    </div> <!-- / .row -->
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.8.0/ckeditor.js"></script>
<script>
    var expanded = false;

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        checkboxes.style.display = expanded ? "none" : "block";
        expanded = !expanded;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor
        CKEDITOR.replace('body');

        // Toggle visibility of form sections based on type selection
        document.getElementById('type').addEventListener('change', function() {
            var value = this.value;
            var subjectGroup = document.getElementById('subject-group');
            var courseGroup = document.getElementById('course-group');
            var semesterGroup = document.getElementById('semester-group');
            var streamGroup = document.getElementById('stream-group');
            var departmentGroup = document.getElementById('department-group');
            var titlebox = document.getElementById('title-box');
            var bodybox = document.getElementById('body-box');
            var add = document.getElementById('add');
            var back = document.getElementById('back');
            var checkbox = document.getElementById('checkbox');
            var check = document.getElementById("check");

            // Hide all initially
            subjectGroup.classList.add('d-none');
            courseGroup.classList.add('d-none');
            semesterGroup.classList.add('d-none');
            streamGroup.classList.add('d-none');
            departmentGroup.classList.add('d-none');
            titlebox.classList.add('d-none');
            bodybox.classList.add('d-none');
            add.classList.add('d-none');
            back.classList.add('d-none');
            checkbox.classList.add('d-none');

            if (value === 'all') {
                titlebox.classList.remove('d-none');
                bodybox.classList.remove('d-none');
                add.classList.remove('d-none');
                back.classList.remove('d-none');
            } else if (value === 'filter') {
                courseGroup.classList.remove('d-none');
                semesterGroup.classList.remove('d-none');
                streamGroup.classList.remove('d-none');
                departmentGroup.classList.remove('d-none');
                

                // Toggle subjectGroup based on the checkbox state
                if (check.checked) {
                    subjectGroup.classList.remove('d-none');
                } else {
                    subjectGroup.classList.add('d-none');
                }
            }
        });

// Listen for checkbox changes separately
document.getElementById('check').addEventListener('change', function() {
    var subjectGroup = document.getElementById('subject-group');
    if (this.checked) {
        subjectGroup.classList.remove('d-none');
    } else {
        subjectGroup.classList.add('d-none');
    }
});

       
        // Initialize type selection to apply changes on page load
        document.getElementById('type').dispatchEvent(new Event('change'));

        // Fetch streams
        fetch('../functions/get_streams.php')
            .then(response => response.json())
            .then(data => {
                const streamSelect = document.getElementById('stream');
                data.forEach(stream => {
                    const option = document.createElement('option');
                    option.value = stream.stream_id;
                    option.textContent = stream.stream_title;
                    streamSelect.appendChild(option);
                });
            });

        // Fetch courses based on selected stream
        document.getElementById('stream').addEventListener('change', function() {
            const streamId = this.value;
            fetch(`../functions/get_courses.php?stream_id=${streamId}`)
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

            // Disable subsequent fields
            document.getElementById('course').disabled = true;
            document.getElementById('department').disabled = true;
            document.getElementById('semester').disabled = true;
        });

        // Fetch semesters based on selected course
        document.getElementById('course').addEventListener('change', function() {
            fetch('../functions/get_semesters.php')
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

        // Fetch departments based on selected semester
        document.getElementById('semester').addEventListener('change', function() {
            fetch('../functions/get_department.php')
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
        });

        // Fetch subjects based on selected course and semester
        document.getElementById('department').addEventListener('change', function() {
            const departmentId = this.value;
            const semesterId = document.getElementById('semester').value;
            const courseCode = document.getElementById('course').value;
            var titlebox = document.getElementById('title-box');
            var bodybox = document.getElementById('body-box');
            var add = document.getElementById('add');
            var back = document.getElementById('back');
            var checkbox = document.getElementById('checkbox');

            fetch(`../functions/get_subjects.php?semester_id=${semesterId}`)
            .then(response => response.json())
                .then(data => {
                    const subjectSelect = document.getElementById('subject');
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.subject_code;
                        option.textContent = `${subject.subject_name} (${subject.subject_code})`;
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;

                });
                checkbox.classList.remove('d-none');
                titlebox.classList.remove('d-none');
                bodybox.classList.remove('d-none');
                add.classList.remove('d-none');
                back.classList.remove('d-none');

        });
 // Event listener for Semester Dropdown
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

// Handle form submission
document.getElementById('noticeForm').addEventListener('submit', function(e) {
    e.preventDefault();
   const formData = new FormData(this);
        const body = CKEDITOR.instances.body.getData();
        formData.append('body', body);
        const checkBox = document.getElementById('check');
        const isChecked = checkBox.checked ? 'on' : 'off'; // Add 'on' or 'off' depending on whether it's checked
        formData.append('check', isChecked);
        fetch('../functions/add_admin_notice.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notice added successfully!');
                document.getElementById('noticeForm').reset();
            } else {
                alert('Error adding notice: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    
});


   
        // Handle form submission
       
    });
</script>

<?php include '../includes/footer.php'; ?>
