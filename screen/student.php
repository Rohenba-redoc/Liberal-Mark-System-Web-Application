<?php 
include '../functions/display.php';
include '../includes/header.php';
$students = fetchStudents();
?>

<style>
   .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    .left-align, .left-aligns , .left-alignss {
    text-align: left;
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
                            <h6 class="header-pretitle">Overview</h6>
                            <!-- Title -->
                            <h1 class="header-title">Student</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="add_student.php" class="btn btn-primary lift">New Student</a>
                            <a href="edit_draft_students.php" class="btn btn-info lift">Manage Draft Student</a>
                            <a href="upload_student.php" class="btn btn-secondary lift">Import From Excel</a>
                        </div>
                    </div> <!-- / .row -->
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Nav -->
                            <ul class="nav nav-tabs nav-overflow header-tabs">
                                <li class="nav-item">
                                    <a href="#!" class="nav-link active" id="showAll">All <span class="badge rounded-pill text-bg-secondary-subtle" id="studentCount"><?php echo count($students); ?></span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="#!" class="nav-link" id="filterDrafts">Filter <span class="badge rounded-pill text-bg-secondary-subtle">Drafts</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" data-list='{"valueNames": ["students-name", "students-phone", "students-email"]}'>
                <div class="card-header">
                    <!-- Search -->
                    <form>
                        <div class="input-group input-group-flush input-group-merge input-group-reverse">
                            <input class="form-control list-search" type="search" placeholder="Search">
                            <span class="input-group-text"><i class="fe fe-search"></i></span>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-nowrap card-table">
                        <thead>
                            <tr>
                              
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="students-id">Unique-ID</a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="students-name">Name</a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="students-email">Email</a>
                                </th>
                               
                               
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="students-mu_roll_no">MU_Roll_No</a>
                                </th>
                                <th >
                                    <a href="#" class="text-body-secondary list-sort" data-sort="students-registration">Registration_No.</a>
                                </th>
                                <th> </th>
                                <th> </th>
                               
                                
                             
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $student): ?>
                                    <tr  data-student-Id="<?php echo htmlspecialchars($student['student_id']); ?>">
                                        
                                        <td class="students-id"><?php echo htmlspecialchars($student['student_id']); ?></td>
                                        <td class="students-name"><?php echo htmlspecialchars($student['student_name']); ?></td>
                                        <td class="students-email"><?php echo htmlspecialchars($student['student_email']); ?></td>
                                        <td class="students-phone hidden-column"><?php echo htmlspecialchars($student['student_phone']); ?></td>
                                        <td class="students-address hidden-column"><?php echo htmlspecialchars($student['student_address']); ?></td>
                                        
                                        <td class="students-mu_roll_no">
                                                                <?php echo htmlspecialchars($student['MU_Roll_No']); ?>
                                                                               </td>
                                        <td class="students-registration">
                                            <?php echo htmlspecialchars($student['Registration_no']); ?>
                                        </td>
                                        <td class="students-status hidden-column"><?php echo htmlspecialchars($student['status']); ?></td>
                                        <td class="students-abc hidden-column"><?php echo htmlspecialchars($student['Abc_id']); ?></td>
                                        <td class="text-end">
                                            <!-- Dropdown -->
                                            <div class="dropdown" style="position:absolute">
                                                <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal" data-student-Id="<?php echo htmlspecialchars($student['student_id']); ?>">Edit</a>
                                                    <a href="#" class="dropdown-item" onclick="confirmDelete('<?php echo htmlspecialchars($student['student_id']); ?>')">Delete</a>
                                                    <a href="student_details.php?id=<?php echo htmlspecialchars($student['student_id']); ?>" class="dropdown-item">Details</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                        <?php 
                                            if ($student['enroll'] === 0 && $student['status'] === 'active') {
                                                echo '<a href="enroll.php?student_id=' . htmlspecialchars($student['student_id'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-warning lift">Enroll</a>';
                                            }
                                            ?>

                                        
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">No students found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStudentForm">
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="editStudentId">
                    <div class="mb-3">
                        <label for="editStudentName" class="form-label">Name<span class="text-red">*</span></label>
                        <input type="text" class="form-control" id="editStudentName" name="student_name" required>
                    </div>
                   <div style="display:flex;justify-content:space-between;">
                   <div class="mb-3 col-6">
                        <label for="editStudentEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editStudentEmail" name="student_email">
                    </div>
                    <div class="mb-3 col-5">
                        <label for="editStudentPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="editStudentPhone" name="student_phone">
                    </div>
                   </div>
                    <div class="mb-3">
                        <label for="editStudentAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="editStudentAddress" name="student_address">
                    </div>
                    <div class="mb-3">
                        <label for="editMuRollNo" class="form-label">MU Roll No</label>
                        <input type="text" class="form-control left-alignss" id="editMuRollNo" name="mu_roll_no" style="text-align:left;">

                    </div>
                    <div class="mb-3 ">
                        <label for="editAbcId" class="form-label">ABC ID</label>
                        <input type="text" class="form-control left-align" id="editAbcId" name="abc_id" style="text-align:left;">
                    </div>
                    <div class="mb-3">
                        <label for="editRegistrationNo" class="form-label">Registration No</label>
                        <input type="text" class="form-control left-aligns" id="editRegistrationNo" name="registration_no" style="text-align:left;">
                    </div>
                    <div class="mb-3 hidden-column">
                        <label for="editStatus" class="form-label">Status</label>
                        <input type="text" class="form-control" id="editStatus" name="status">
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   <!-- Save changes button -->
                    <button type="submit" class="btn btn-success" id="saveChangesButton">Save changes</button>

                    <!-- Save as Draft button -->
                    <button type="button" class="btn btn-primary" id="saveAsDraftButton" style="display:none;">Save As Draft</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.getElementById('filterDrafts').addEventListener('click', function() {
    fetch('filter_students.php?status=draft')
        .then(response => response.json())
        .then(data => {
            updateStudentTable(data);
        });
});

document.getElementById('showAll').addEventListener('click', function() {
    fetch('filter_students.php')
        .then(response => response.json())
        .then(data => {
            updateStudentTable(data);
        });
});

function updateStudentTable(data) {
    const tbody = document.querySelector('tbody.list');
    tbody.innerHTML = '';
    data.forEach(student => {
        let enrollButton = '';
        
        if (Number(student.enroll) == 0 && student.status === 'active') {
            enrollButton = '<a href="add_student.php" class="btn btn-warning lift">Enroll</a>';
        }
        tbody.innerHTML += `
            <tr data-student-Id="${student.student_id}">
                
                <td class="students-id">${student.student_id}</td>
                <td class="students-name">${student.student_name}</td>
                <td class="students-email">${student.student_email}</td>
                <td class="students-phone hidden-column">${student.student_phone}</td>
                <td class="students-address hidden-column">${student.student_address}</td> 
                <td class="students-mu_roll_no">${student.MU_Roll_No}</td>
                <td class="students-registration">${student.Registration_no}</td>
                <td class="students-status hidden-column">${student.status}</td>
                <td class="students-abc hidden-column">${student.Abc_id}</td>
                <td class="text-end">
                    <div class="dropdown" style="position:absolute">
                        <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe fe-more-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal"  data-student-Id="${student.student_id}">Edit</a>
                            <a href="#" class="dropdown-item" onclick="confirmDelete('${student.student_id}')">Delete</a>
                                                    <a href="student_details.php?id=${student.student_id}" class="dropdown-item">Details</a>
                  </div>
                </td>
                <td>
                     ${enrollButton}
                
                                        
                </td>
            </tr>
        `;
    });
}
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('edit-btn')) {
        const studentId = event.target.getAttribute('data-student-Id');
        const row = event.target.closest('tr');
        console.log(studentId);
        console.log(row);
        
        document.getElementById('editStudentId').value = studentId;
        document.getElementById('editStudentName').value = row.querySelector('.students-name').textContent;
        document.getElementById('editStudentEmail').value = row.querySelector('.students-email').textContent;
        document.getElementById('editStudentPhone').value = row.querySelector('.students-phone').textContent;
        document.getElementById('editStudentAddress').value = row.querySelector('.students-address').textContent;
        document.getElementById('editMuRollNo').value = row.querySelector('.students-mu_roll_no').textContent;
        document.getElementById('editRegistrationNo').value = row.querySelector('.students-registration').textContent;
        document.getElementById('editAbcId').value = row.querySelector('.students-abc').textContent;
        document.getElementById('editStatus').value = row.querySelector('.students-status').textContent;
        if (row.querySelector('.students-status').textContent === 'draft') {
            document.getElementById('saveAsDraftButton').style.display = 'inline-block';
        } else {
            document.getElementById('saveAsDraftButton').style.display = 'none';
        }
    }
});


document.getElementById('saveChangesButton').addEventListener('click', function(event) {
    event.preventDefault();
    updateStudentStatus('active');
});

document.getElementById('saveAsDraftButton').addEventListener('click', function(event) {
    event.preventDefault();
    updateStudentStatus('draft');
});


function updateStudentStatus(status) {
    const studentId = document.getElementById('editStudentId').value;
    const studentName = document.getElementById('editStudentName').value;
    const studentEmail = document.getElementById('editStudentEmail').value;
    const studentPhone = document.getElementById('editStudentPhone').value;
    const studentAddress = document.getElementById('editStudentAddress').value;
    const muRollNo = document.getElementById('editMuRollNo').value;
    const registrationNo = document.getElementById('editRegistrationNo').value;
    const abcId = document.getElementById('editAbcId').value;
    const studentStatus = status;

    // Email and Phone number validation
    if (studentStatus === 'active') {
        if (!studentEmail.includes('@gmail.com')) {
            alert('Email must contain "@gmail.com".');
            return;
        }
        const phoneRegex = /^\d{10}$/;
        if (!phoneRegex.test(studentPhone)) {
            alert('Phone number must be exactly 10 digits.');
            return;
        }
    }

    const formData = new FormData();
    formData.append('student_id', studentId);
    formData.append('student_name', studentName);
    formData.append('student_email', studentEmail);
    formData.append('student_phone', studentPhone);
    formData.append('student_address', studentAddress);
    formData.append('mu_roll_no', muRollNo);
    formData.append('registration_no', registrationNo);
    formData.append('abc_id', abcId);
    formData.append('status', studentStatus);

    fetch('../functions/update_student.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Student updated successfully.');
            location.reload();
        } else {
            alert('Failed to update student.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the student.');
    });
}


window.confirmDelete = function(Id) {
    if (confirm('Are you sure you want to delete this Student? All the Related Information with this Student will be deleted and cannot be recover Again! ( UNIQUE_ID - ' + Id +')')) {
        fetch('../functions/delete_student.php', {
            method: 'POST',
            body: JSON.stringify({ student_id: Id }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.text()) // Read response as text
        .then(text => {
            try {
                const data = JSON.parse(text); // Parse JSON manually
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Invalid JSON response:', text);
                alert('Error parsing server response.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
};



</script>

<?php include '../includes/footer.php'; ?>
