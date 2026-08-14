<?php
include '../includes/header.php';
include '../../includes/config.php';

// Assuming $teacherId is retrieved from the session or elsewhere
$teacherId = $_SESSION['teacher']['teacher_id'] ?? null; // Replace with actual session value retrieval

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
                            <h1 class="header-title">Results</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="add_marks.php" class="btn btn-primary lift">Add Marks</a>
                            <a href="import_mark.php" class="btn btn-secondary lift">Import Marks</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Filters -->
            <div style="display:flex;justify-content:space-between">
                <div class="form-group col-3">
                    <label for="course_code">Course <span class="text-red">*</span></label>
                    <select id="course" name="course_code" class="form-control mb-3">
                        <option value="">Select Course</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="semester_id">Semester<span class="text-red">*</span></label>
                    <select id="semester" name="semester_id" class="form-control mb-3">
                        <option value="">Select Semester</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="subject_code">Paper<span class="text-red">*</span></label>
                    <select id="subject_code" name="subject_code" class="form-control mb-3">
                        <option value="">Select Paper</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between">
                <div class="form-group col-3">
                    <label for="test">Test Name<span class="text-red">*</span></label>
                    <select id="test" name="test" class="form-control mb-3">
                        <option value="">Select Test</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="date">Year<span class="text-red">*</span></label>
                    <select id="date" name="date" class="form-control mb-3">
                        <option value="">Select Year</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <input type="hidden" value="<?php echo $teacherId; ?>" name="given_by" id="given_by">
                    <button id="filter-btn" class="btn btn-primary lift mb-5" style="display:none">Filter</button>
                </div>
            </div>

            <!-- Container to Display the Results -->
            <div id="results-container">
    <div class="result-info">
       
    </div>
    <table class="table table-striped" id="table" style="display:none;">
        
        
        <!-- More rows as needed -->
    </table>
</div>

        </div>
    </div>
</div>
<!-- Modal Structure -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="closeModal" class="close" style="cursor: pointer;">&times;</span>
        <h2 style="text-align:center;color:black;">Edit Mark</h2>
        <form id="editForm">
            <input type="hidden" id="mark_id" name="mark_id">
            <div>
                <label for="student_name" style="color:black">Student Name:</label>
                <input type="text" id="student_name" name="student_name" disabled class="form-control">
            </div>
            <div style="margin-top:10px;">
                <label for="mark_score" style="color:black">Marks Obtained:</label>
                <input type="number" id="mark_score" name="mark_score" required class="form-control">
            </div>
            <button type="submit" class="btn btn-success" style="margin-top:10px;">Save Changes</button>
        </form>
    </div>
</div>
<!-- Edit All Modal Structure -->
<div id="editAllModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="closeEditAllModal" class="close" style="cursor: pointer;">&times;</span>
        <h2 style="text-align:center;color:black;">Edit All Marks</h2>
        <form id="editAllForm">
            <div id="editAllFields"></div> <!-- Container for all mark fields -->
            <button type="submit" class="btn btn-success">Save Changes</button>
        </form>
    </div>
</div>


<style>
    .table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
}

.table th {
    text-align: left;
}
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); /* Black background with transparency */
}

.modal-content {
    background-color: lightgrey; /* Black background with transparency */
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}


</style>
<script>
   const givenBy = document.getElementById('given_by').value;
        document.addEventListener('DOMContentLoaded', function () {
            const givenBy = document.getElementById('given_by').value;
            if (givenBy) {
             fetchFilters(givenBy);
            } else {
             console.error("Given by value is empty.");
            }
        });

            // Fetch filters based on the given_by value
            function fetchFilters(givenBy) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/fetch_filters.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                                       const data = JSON.parse(xhr.responseText);
                        
                            // Populate dropdowns with fetched options
                            populateDropdown('course', data.course_codes, data.course_names);
                            populateDropdown('semester', data.semesters, data.semester_names);
                            populateSubjectDropdown('subject_code', data.subjects);
                            populateDropdown('test', data.test_names, data.test_names); // Test names dropdown
                            populateDropdown('date', data.years, data.years); // Years dropdown
                       } catch (e) {
                            console.error("JSON parsing error:", e);
                        }
                    } else {
                        console.error("Request failed. Status:", xhr.status, "Response:", xhr.responseText);
                    }
                };

                // Send the given_by value to the server
                xhr.send(`given_by=${encodeURIComponent(givenBy)}`);
            }

            // Populate dropdown options for non-subject dropdowns
            function populateDropdown(id, values, names) {
                const select = document.getElementById(id);
                select.innerHTML = ''; // Clear existing options

                // Add a "Select" option at the top
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select';
                select.appendChild(defaultOption);

                // Check if values are present and populate the dropdown
                if (values && values.length > 0) {
                    values.forEach(function (value, index) {
                        const option = document.createElement('option');
                        option.value = value; // Use the corresponding code/ID as value
                        option.textContent = names ? names[index] : value; // Use names if provided
                        select.appendChild(option);
                    });
                } else {
                    console.error(`No values found for ${id}`);
                }
            }

            // Populate dropdown options specifically for subjects (code and name)
            function populateSubjectDropdown(id, subjects) {
                const select = document.getElementById(id);
                select.innerHTML = ''; // Clear existing options

                // Add a "Select" option at the top
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
               defaultOption.textContent = 'Select';
                select.appendChild(defaultOption);

                // Populate the dropdown with subject codes and names
                if (subjects && subjects.length > 0) {
                    subjects.forEach(function (subject) {
                        const option = document.createElement('option');
                        option.value = subject.code; // Use subject code as value
                        option.textContent = `${subject.code} (${subject.name})`; // Display code and name
                        select.appendChild(option);
                    });
                } else {
                    console.error("No subjects found.");
                }
            }

            document.getElementById('date').addEventListener('change', function() {
                    const filterButton = document.getElementById('filter-btn');
    
                    // Check if a date is selected (not an empty value)
                    if (this.value) {
                       filterButton.style.display = 'block'; // Show the filter button
                    } else {
                        filterButton.style.display = 'none'; // Hide the filter button if no date is selected
                        }
            });
            document.getElementById('filter-btn').addEventListener('click', function() {
        const table = document.getElementById('table');
        table.style.display = 'block'; // Show the table
    });




    // Handle the filter button click to fetch filtered results
    document.getElementById('filter-btn').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Collect filter values
        const courseCode = document.getElementById('course').value;
        const semesterId = document.getElementById('semester').value;
        const subjectCode = document.getElementById('subject_code').value;
        const testName = document.getElementById('test').value;
        const year = document.getElementById('date').value;

        console.log("course", courseCode);
        console.log("semester", semesterId);
        console.log("subjectCode", subjectCode);
        console.log("testName", testName);
        console.log("year", year);


        fetchFilteredResults(givenBy, courseCode, semesterId, subjectCode, testName, year);
    });

    // Fetch filtered results
    function fetchFilteredResults(givenBy, courseCode, semesterId, subjectCode, testName, year) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../controller/fetch_marks.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                const results = JSON.parse(xhr.responseText);
                displayResults(results);
            }
        };

        // Send the selected filter values
        xhr.send(`given_by=${givenBy}&course_code=${courseCode}&semester_id=${semesterId}&subject_code=${subjectCode}&test_name=${testName}&year=${year}`);
    }
    function formatDate(dateString) {
    // Split the date string (assumes it is in 'yyyy-mm-dd' format)
    const [year, month, day] = dateString.split('-');
    
    // Return the date in 'dd-mm-yyyy' format
    return `${day}-${month}-${year}`;
}

    // Display results in the results container
    function displayResults(results) {
    const container = document.getElementById('results-container');
    container.innerHTML = ''; // Clear previous results

    if (results.length > 0) {
        const infoDiv = document.createElement('div');
        infoDiv.className = 'result-info';

        const { test_name, test_date, result_date, full_mark, pass_mark } = results[0];

        infoDiv.innerHTML = `
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid grey; padding:5px 10px;margin-bottom:5px;">
                <div class="col-3"><p><strong>Test Name:</strong><br> ${test_name}</p></div>
                <div class="col-3"><p><strong>Test Date:</strong><br> ${formatDate(test_date)}</p></div>
                <div class="col-3"><p><strong>Result Date:</strong><br> ${formatDate(result_date)}</p></div>
            </div>
            <div style="display:flex;justify-content:space-between;border-bottom:1px solid grey; padding:5px 10px;margin-bottom:10px;">
                <div class="col-3"><p><strong>Full Marks:</strong><br> ${full_mark}</p></div>
                <div class="col-3"><p><strong>Pass Marks:</strong><br> ${pass_mark}</p></div>
            </div>
        `;

        container.appendChild(infoDiv);

        const table = document.createElement('table');
        table.className = 'table table-striped';

        const headerRow = document.createElement('tr');
        headerRow.innerHTML = `<th>Unique Id</th><th>Student Name</th><th>Marks Obtained</th>
        <th><button id="edit-all" class="btn btn-secondary lift edit-all">Edit All</button>
        <button id="delete-all" class="btn btn-danger lift delete-all">Delete All</button>
        </th>`;
        table.appendChild(headerRow);

        results.forEach(function (result) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${result.student_id}</td>
                <td>${result.student_name}</td>
                <td>${result.mark_score}</td>
                <td><button class="btn btn-primary edit-btn" data-markid="${result.mark_id}">Edit</button>
                <button class="btn btn-danger" onclick="confirmDelete(${result.mark_id})">Delete</button></td>
            `;
            table.appendChild(row);
        });

        container.appendChild(table);

        // Add event listeners to Edit buttons
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function() {
                const markId = this.getAttribute('data-markid');
                const studentName = this.closest('tr').cells[1].textContent;
                const markScore = this.closest('tr').cells[2].textContent;

                openEditModal(markId, studentName, markScore);
            });
        });
        const editAllButton = document.querySelectorAll('.edit-all');
        editAllButton.forEach(function (button) {
            button.addEventListener('click', function() {
                openEditAllModal(results);
            });
        });
        const deleteAllButton = document.querySelectorAll('.delete-all');
    deleteAllButton.forEach(function (button) {
        button.addEventListener('click', function() {
            // Call the function to delete all marks
            deleteAllMarks(results);
        });
    });
       
    } else {
        container.innerHTML = '<p style="text-align:center;color:red;font-size:20px;">No results found.</p>';
    }
}
function deleteAllMarks(results) {
    // Ask for confirmation before deleting
    const confirmDelete = confirm("Are you sure you want to delete all marks? This action cannot be undone.");
    if (!confirmDelete) return;

    // Create an array of mark_ids to delete
    const markIds = results.map(result => result.mark_id);

    // Send the request to the server to delete all marks
    fetch('../controller/delete_all_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ markIds }), // Send markIds as the request body
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('All marks deleted successfully!');
            location.reload();
        } else {
            alert('Failed to delete marks: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting all marks.');
    });
}

function openEditModal(markId, studentName, markScore) {
    document.getElementById('mark_id').value = markId;
    document.getElementById('student_name').value = studentName;
    document.getElementById('mark_score').value = markScore;

    document.getElementById('editModal').style.display = 'block'; // Show the modal
}

// Close the modal when the close button is clicked
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none'; // Hide the modal
});
function refreshTable() {
    document.getElementById('filter-btn').click();
}
// Handle form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form submission

    const markId = document.getElementById('mark_id').value;
    const newMarkScore = document.getElementById('mark_score').value;
    fetch('../controller/update_student_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            mark_id: markId,
            mark_score: newMarkScore
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Marks updated successfully');
            document.getElementById('editModal').style.display = 'none'; // Hide the modal after submission
            // Refresh the table
            refreshTable();
        } else {
            alert('Failed to update marks');
        }
    })
    .catch(error => console.error('Error:', error));
});
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
    function openEditAllModal(results) {
    const editAllFields = document.getElementById('editAllFields');
    editAllFields.innerHTML = ''; // Clear previous fields

    // Assuming the first result has the test information
    const { test_name, test_date, result_date, full_mark, pass_mark } = results[0];

    // Create a div for test information with appropriate input types
    const testInfoDiv = document.createElement('div');
    testInfoDiv.innerHTML = `
        <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
            <div class="col-3">
                <label for="test_name" style="color:black">Test Name:</label>
                <input type="text" id="test_name" value="${test_name}" required class="form-control">
            </div>
            <div class="col-3">
                <label for="test_date" style="color:black">Test Date:</label>
                <input type="date" id="test_date" value="${test_date}" required class="form-control">
            </div>
            <div class="col-3">
                <label for="result_date" style="color:black">Result Date:</label>
                <input type="date" id="result_date" value="${result_date}" required class="form-control">
            </div>
        </div>
        <div style="display:flex;justify-content:space-between">
            <div class="col-4">
                <label for="full_mark" style="color:black">Full Marks:</label>
                <input type="number" id="full_mark" value="${full_mark}" required class="form-control">    
            </div>
            <div class="col-4">
                <label for="pass_mark" style="color:black">Pass Marks:</label>
                <input type="number" id="pass_mark" value="${pass_mark}" required class="form-control">
            </div>
        </div>
        <hr>
    `;
    editAllFields.appendChild(testInfoDiv);

    // Create the table to display student marks
    const table = document.createElement('table');
    table.className = 'table table-striped';

    // Create the table header
    const headerRow = document.createElement('tr');
    headerRow.innerHTML = `
        <th style="color:black">Student ID</th>
        <th style="color:black">Student Name</th>
        <th style="color:black">Marks Obtained</th>
    `;
    table.appendChild(headerRow);

    // Add each student's marks to the table
    results.forEach(function (result) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="color:black">${result.student_id}</td>
            <td style="color:black">${result.student_name}</td>
            <td >
                <input type="number" id="mark_score_${result.mark_id}" name="mark_score_${result.mark_id}" value="${result.mark_score}" required class="form-control" style="color:red">
            </td>
        `;
        table.appendChild(row);
    });

    // Append the table to the editAllFields container
    editAllFields.appendChild(table);

    document.getElementById('editAllModal').style.display = 'block'; // Show the modal
}



// Close the modal when the close button is clicked
document.getElementById('closeEditAllModal').addEventListener('click', function() {
    document.getElementById('editAllModal').style.display = 'none'; // Hide the modal
});

// Handle form submission for Edit All
document.getElementById('editAllForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form submission

    // Capture test information from the modal inputs
    const testDetails = {
        test_name: document.getElementById('test_name').value,
        test_date: document.getElementById('test_date').value,
        result_date: document.getElementById('result_date').value,
        full_mark: document.getElementById('full_mark').value,
        pass_mark: document.getElementById('pass_mark').value
    };

    // Capture updated marks for each student
    const updatedMarks = {};
    const results = document.querySelectorAll('[id^="mark_score_"]');
    results.forEach(function (input) {
        const markId = input.id.split('_')[2]; // Extract mark_id from the input's ID
        updatedMarks[markId] = input.value; // Store the updated score
    });

    // Combine both test details and updated marks into a single object
    const data = {
        testDetails: testDetails,
        updatedMarks: updatedMarks
    };

    // Send the data to the PHP backend using fetch
    fetch('../controller/update_all_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data), // Convert data to JSON string before sending
    })
        .then(response => response.json()) // Parse the JSON response from the server
        .then(data => {
            if (data.success) {
                console.log('Server Response:', data);
                alert('Marks and details updated successfully!');
                document.getElementById('editAllModal').style.display = 'none'; // Hide the modal after successful update
                
                // Optionally, refresh the main table to show the updated data
                document.getElementById('filter-btn').click();
            } else {
                alert('Failed to update marks: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating marks.');
        });
});



</script>

<?php include '../includes/footer.php'; ?>
