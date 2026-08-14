<?php 
include '../includes/header.php';
include '../functions/display.php';

$teachers = fetchTeacher();

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
                            <h6 class="header-pretitle">
                                Overview
                            </h6>

                            <!-- Title -->
                            <h1 class="header-title">
                                Teacher
                            </h1>

                        </div>
                        <div class="col-auto">

                            <!-- Button -->
                            <a href="#" class="btn btn-primary lift" data-bs-toggle="modal" data-bs-target="#addModal">
                                New Teacher
                            </a>

                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col">

                            <!-- Nav -->
                            <ul class="nav nav-tabs nav-overflow header-tabs">
                                <li class="nav-item">
                                    <a href="#!" class="nav-link active">
                                        All <span class="badge rounded-pill text-bg-secondary-subtle"><?php echo count($teachers); ?></span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["teacher-name", "teacher-phone", "teacher-email", "teacher-address", "teacher-desgination", "teacher-dob"]}'>
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
                    <table class="table table-sm table-nowrap card-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="teacher-name">
                                        Name
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="teacher-phone">
                                        Phone_No.
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="teacher-email">
                                        Email
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="teacher-address">
                                        Address
                                    </a>
                                </th>
                                <th>
                                    <a href="#" class="text-body-secondary list-sort" data-sort="teacher-desgination">
                                        Desgination <br><hr> Department
                                    </a>
                                </th>
                                <th colspan="2">
                                    <a href="#" class="text-body-secondary list-sort" data-sort="teacher-dob">
                                        DOB
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($teachers)): ?>
                                <?php foreach($teachers as $teacher): ?>
                                    <tr>
                                        <td class="teacher-name">
                                            <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                                        </td>
                                        <td class="teacher-phone">
                                            <?php echo htmlspecialchars($teacher['teacher_phone']); ?>
                                        </td>
                                        <td class="teacher-email">
                                            <?php echo htmlspecialchars($teacher['teacher_email']); ?>
                                        </td>
                                        <td class="teacher-address">
                                            <?php echo htmlspecialchars($teacher['teacher_address']); ?>
                                        </td>
                                        <td class="teacher-desgination">
                                            <?php echo htmlspecialchars($teacher['desgination']); ?><br><hr>
                                            <?php echo htmlspecialchars($teacher['department_name']); ?>
                                        </td>
                                        <td class="teacher-dob">
                                            <?php 
                                             $date = new DateTime($teacher['dob']);
                                             echo htmlspecialchars($date->format('d-m-Y'));
                                            
                                            //echo htmlspecialchars($teacher['dob']); ?>
                                        </td>
                                        <td class="text-end">
                                            <!-- Dropdown -->
                                            <div class="dropdown" style="position:absolute">
                                                <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                <a href="#" class="dropdown-item edit-teacher" 
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#editModal" 
                                                  data-id="<?php echo $teacher['teacher_id']; ?>"
                                                 data-teacher-name="<?php echo htmlspecialchars($teacher['teacher_name']); ?>"
                                                 data-phone="<?php echo htmlspecialchars($teacher['teacher_phone']); ?>"
                                                 data-email="<?php echo htmlspecialchars($teacher['teacher_email']); ?>" 
                                                 data-address="<?php echo htmlspecialchars($teacher['teacher_address']); ?>" 
                                                 data-desgination="<?php echo htmlspecialchars($teacher['desgination']); ?>" 
                                                 data-dob="<?php echo htmlspecialchars($teacher['dob']); ?>"
                                                 data-department="<?php echo htmlspecialchars($teacher['department_id']); ?>">
                                                  Edit
                                                </a>

                                                   
                                                   <!-- Dropdown Item HTML -->
                                                <a href="#" 
                                                class="dropdown-item edit-credentials"
                                                data-bs-toogle="modal"
                                                data-bs-target="#credentialsModal"
                                                data-teacher-id="<?php echo $teacher['teacher_id']; ?>">
                                                    LogIn Credentials
                                                </a>
                                                <a href="#" class="dropdown-item"
                                                 onclick="confirmDelete('<?php echo htmlspecialchars($teacher['teacher_id']); ?>')">
                                                Delete
                                            </a>
                                                  
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No Teachers found</td>
                                </tr>
                            <?php endif; ?>         
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTeacherForm">
                    <div class="mb-3">
                        <label for="teacherName" class="form-label">Teacher Name</label>
                        <input type="text" class="form-control" id="teacherName" name="teacherName" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherAddress" class="form-label">Teacher Address</label>
                        <input type="text" class="form-control" id="teacherAddress" name="teacherAddress" required>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <div class="mb-3 col-5">
                            <label for="teacherPhone" class="form-label">Teacher Phone_No.</label>
                            <input type="text" class="form-control" id="teacherPhone" name="teacherPhone" required pattern="\d{10}" title="Please enter 10 digits Phone Number">
                            </div>
                        <div class="mb-3 col-5">
                            <label for="teacherEmail" class="form-label">Teacher Email</label>
                            <input type="text" class="form-control" id="teacherEmail" name="teacherEmail" required>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <div class="mb-3 col-5">
                            <label for="Desgination" class="form-label">Desgination</label>
                            <select class="form-control" id="Desgination" name="Desgination" required>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>
                        <div class="mb-3 col-5">
                            <label for="DateOfBirth" class="form-label">Date-Of-Birth</label>
                            <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth" required>
                        </div>
                    </div>
                    <div class="mb-3">
                         <!-- Years Dropdown -->
                         <label for="department">Department:</label>
                        <select id="department" name="department" required class="form-control mb-3" required>
                        <option value="">Select Department</option>
                        <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Teacher Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTeacherForm">
                    <input type="hidden" id="editTeacherId" name="teacherId">
                    <div class="mb-3">
                        <label for="editTeacherName" class="form-label">Teacher Name</label>
                        <input type="text" class="form-control" id="editTeacherName" name="teacherName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTeacherAddress" class="form-label">Teacher Address</label>
                        <input type="text" class="form-control" id="editTeacherAddress" name="teacherAddress" required>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <div class="mb-3 col-5">
                            <label for="editTeacherPhone" class="form-label">Teacher Phone_No.</label>
                            <input type="text" class="form-control" id="editTeacherPhone" name="teacherPhone" required pattern="\d{10}" title="Please enter 10 digits Phone Number">
                        </div>
                        <div class="mb-3 col-5">
                            <label for="editTeacherEmail" class="form-label">Teacher Email</label>
                            <input type="email" class="form-control" id="editTeacherEmail" name="teacherEmail" required>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <div class="mb-3 col-5">
                            <label for="editDesgination" class="form-label">Desgination</label>
                            <select class="form-control" id="editDesgination" name="Desgination" required>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Professor">Professor</option>
                            </select>
                        </div>
                        <div class="mb-3 col-5">
                            <label for="editDateOfBirth" class="form-label">Date-Of-Birth</label>
                            <input type="date" class="form-control" id="editDateOfBirth" name="DateOfBirth" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">Department</label>
                        
                        <select class="form-control" id="editDepartment" name="department" required>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal HTML -->
<div class="modal fade" id="credentialsModal" tabindex="-1" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">LogIn Credentials</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="credentialsForm" action="../functions/teacher_de.php" method="post">

            <div class="modal-body">
                <input type="hidden" class="form-control" id="modalid" name="modalid" readonly>

                    <div class="form-group">
                        <label for="modalEmail">Email</label>
                        <input type="email" class="form-control" id="modalEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modalPhone">Phone</label>
                        <input type="text" class="form-control" id="modalPhone" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modalPassword">Password</label>
                        <input type="password" class="form-control" id="modalPassword" readonly>
                    </div>
                   
                        <input type="hidden" class="form-control" id="modalStatus" readonly>
            </div>
            <div class="modal-footer">
            <button id="deactivateButton" type="submit" class="btn btn-danger" name="action" value="Inactive" style="display: none;">De-Activate</button>
                    <button id="activateButton" type="submit" class="btn btn-success" name="action" value="active" style="display: none;">Activate</button>
               
            </div>
            </form>

        </div>
    </div>
</div>






<script>
function validatePhone(input) {
    if (input.value.length < 10) {
        input.setCustomValidity('Phone number must be exactly 10 digits.');
    } else {
        input.setCustomValidity('');
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    document.getElementById('addTeacherForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var name = document.getElementById('teacherName').value;
        var address = document.getElementById('teacherAddress').value;
        var phone = document.getElementById('teacherPhone').value;
        var email = document.getElementById('teacherEmail').value;
        var desgination = document.getElementById('Desgination').value;
        var dob = document.getElementById('DateOfBirth').value;
        var department = document.getElementById('department').value;

        fetch('../functions/add_teacher.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                teacher_name: name, 
                teacher_address: address, 
                teacher_phone: phone, 
                teacher_email: email, 
                desgination: desgination, 
                dob: dob,
                department: department
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);

                location.reload(); 
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
<script>
document.querySelectorAll('.edit-teacher').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var teacherId = this.getAttribute('data-id');
            var teacherName = this.getAttribute('data-teacher-name');
            var teacherPhone = this.getAttribute('data-phone');
            var teacherEmail = this.getAttribute('data-email');
            var teacherAddress = this.getAttribute('data-address');
            var desgination = this.getAttribute('data-desgination');
            var dob = this.getAttribute('data-dob');
            var department = this.getAttribute('data-department');

            console.log('Teacher ID:', teacherId);
            console.log('Teacher Name:', teacherName);
            console.log('Teacher Phone:', teacherPhone);
            console.log('Teacher Email:', teacherEmail);
            console.log('Teacher Address:', teacherAddress);
            console.log('Desgination:', desgination);
            console.log('Date of Birth:', dob);
            console.log('Department:', department);
           

              // Fetch departments from the server
        fetch(`../functions/get_department.php`)
        .then(response => response.json())
        .then(data => {
            const departmentSelect = document.getElementById('editDepartment');
            departmentSelect.innerHTML = '<option value="">Select Department</option>';

            // Populate the dropdown with department options
            data.forEach(departmentItem => {
                const option = document.createElement('option');
                option.value = departmentItem.department_id;
                option.textContent = departmentItem.department_name;

                // Set the option as selected if it matches the department value
                if (String(departmentItem.department_id) === String(department)) {
                    option.selected = true; // Set as selected
                }

                departmentSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching departments:', error));

            document.getElementById('editTeacherId').value = teacherId;
            document.getElementById('editTeacherName').value = teacherName;
            document.getElementById('editTeacherPhone').value = teacherPhone;
            document.getElementById('editTeacherEmail').value = teacherEmail;
            document.getElementById('editTeacherAddress').value = teacherAddress;
            document.getElementById('editDesgination').value = desgination;
            document.getElementById('editDateOfBirth').value = dob;
            // Set the selected department value in the department dropdown
        var departmentSelect = document.getElementById('editDepartment');
        departmentSelect.value = department;
        });
    });
    document.getElementById('editTeacherForm').addEventListener('submit', function (event) {
    event.preventDefault();

    var teacherId = document.getElementById('editTeacherId').value;
    var name = document.getElementById('editTeacherName').value;
    var address = document.getElementById('editTeacherAddress').value;
    var phone = document.getElementById('editTeacherPhone').value;
    var email = document.getElementById('editTeacherEmail').value;
    var desgination = document.getElementById('editDesgination').value;
    var dob = document.getElementById('editDateOfBirth').value;
    var department = document.getElementById('editDepartment').value;
    // Prepare the body data
    var bodyData = JSON.stringify({ 
        teacherId: teacherId, 
        teacherName: name, 
        teacherAddress: address, 
        teacherPhone: phone, 
        teacherEmail: email, 
        Desgination: desgination, 
        DateOfBirth: dob,
        department: department
    });

    // Log the body data to the console
    console.log('Request Body:', bodyData);

    // Send the fetch request
    fetch('../functions/update_teacher.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: bodyData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); 
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
document.querySelectorAll('.edit-credentials').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var teacherId = this.getAttribute('data-teacher-id');
        console.log('Teacher ID:', teacherId);

        fetch('../functions/fetch_teacher_credentials.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ teacher_id: teacherId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Server response:', data);

            if (data.success) {
                // Populate modal fields
                document.getElementById('modalid').value = data.credentials.Id;
                document.getElementById('modalEmail').value = data.credentials.email;
                document.getElementById('modalPhone').value = data.credentials.phone;
                document.getElementById('modalPassword').value = data.credentials.password;
                document.getElementById('modalStatus').value = data.credentials.status;
                var status = data.credentials.status;
                var activateButton = document.getElementById('activateButton');
                var deactivateButton = document.getElementById('deactivateButton');

                if (status === 'active') {
                    activateButton.style.display = 'none';
                    deactivateButton.style.display = 'block';
                } else {
                    activateButton.style.display = 'block';
                    deactivateButton.style.display = 'none';
                }



                // Show the modal using vanilla JS
                var modal = document.getElementById('credentialsModal');
                modal.classList.add('show');
                modal.style.display = 'block';
                modal.setAttribute('aria-modal', 'true');
                modal.removeAttribute('aria-hidden');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

// Close the modal when the 'Close' button is clicked
document.querySelector('.modal .close').addEventListener('click', function() {
    var modal = document.getElementById('credentialsModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    modal.removeAttribute('aria-modal');
});

// Define the confirmDelete function
window.confirmDelete = function(teacherId) {
    if (confirm('Are you sure you want to delete this Teacher?')) {
        fetch('../functions/delete_teacher.php', {
            method: 'POST',
            body: JSON.stringify({ teacher_id: teacherId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Response Status:', response.status); // Log response status
            return response.json(); // Parse JSON
        })
        .then(data => {
            console.log('Response Data:', data); // Log response data for debugging
            if (data.success) {
                alert(data.message); // Show success message
                window.location.reload(); // Reload the page
            } else {
                alert('Error: ' + data.message); // Show error message
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error); // Log any fetch error
            alert('Teacher deleted successfully.'); // User-friendly error message
            window.location.reload();
        });
    }
};













</script>

<?php include '../includes/footer.php'; ?>
