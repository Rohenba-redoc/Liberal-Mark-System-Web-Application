<?php 
include '../includes/header.php';
include '../functions/display.php';

$departments = fetchDepartments();
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
                            <h1 class="header-title">Departments</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="add_department.php" class="btn btn-primary lift">New Department</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["departments-department", "departments-name"]}'>
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
                               
                                <th>Sl No.</th>
                                <th colspan="2">
                                    <a href="#" class="text-body-secondary list-sort" data-sort="departments-name">Name</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                        <?php if (!empty($departments)): ?>
                            <?php $slNo = 1; ?>
                            <?php foreach ($departments as $department): ?>
                            <tr>
                                
                                <td class="departments-department"><?php echo $slNo++; ?></td>
                                <td class="departments-name"><?php echo htmlspecialchars($department['department_name']); ?></td>
                                <td class="text-end">
                                    <!-- Dropdown -->
                                    <div class="dropdown" style="position:absolute">
                                        <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal" data-department-id="<?php echo htmlspecialchars($department['department_id']); ?>" data-department-name="<?php echo htmlspecialchars($department['department_name']); ?>">
                                                Edit
                                            </a>
                                            <a href="#" class="dropdown-item" onclick="confirmDelete('<?php echo htmlspecialchars($department['department_id']); ?>')">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No Departments found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- / .row -->
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDepartmentForm">
                    <input type="hidden" id="editDepartmentId">
                    <div class="mb-3">
                        <label for="editDepartmentName" class="form-label">Department Name<span class="text-red">*</span></label>
                        <input type="text" class="form-control" id="editDepartmentName">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// JavaScript
document.addEventListener("DOMContentLoaded", function() {
    // Populate the modal with stream data
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const departmentId = this.getAttribute('data-department-id');
            const departmentName = this.getAttribute('data-department-name');

            document.getElementById('editDepartmentId').value = departmentId;
            document.getElementById('editDepartmentName').value = departmentName;
        });
    });

    // Handle form submission
    document.getElementById('editDepartmentForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const departmentId = document.getElementById('editDepartmentId').value;
        const departmentName = document.getElementById('editDepartmentName').value;

        fetch('../functions/edit_department.php', {
            method: 'POST',
            body: JSON.stringify({ department_id: departmentId, department_name: departmentName }),
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            }).catch(error => {
                console.error('Error:', error);
            });
    });

    // Define the confirmDelete function
    window.confirmDelete = function(departmentId) {
        if (confirm('Are you sure you want to delete this department?')) {
            fetch('../functions/delete_department.php', {
                method: 'POST',
                body: JSON.stringify({ department_id: departmentId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
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

<?php 
include '../includes/footer.php';
?>
