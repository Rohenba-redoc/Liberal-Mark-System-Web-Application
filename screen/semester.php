<?php 
include '../includes/header.php';
include '../functions/display.php';

$semesters = fetchSemesters();
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
                            <h1 class="header-title">Semester</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="#" class="btn btn-primary lift" data-bs-toggle="modal" data-bs-target="#addModal">New Semester</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Card -->
            <div class="card" data-list='{"valueNames": ["semesters-semester","semesters-name"]}'>
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
                                    <a href="#" class="text-body-secondary list-sort" data-sort="semesters-name">Semester</a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($semesters)): ?>
                                <?php $slNo = 1; ?>
                                <?php foreach ($semesters as $semester): ?>
                                    <tr>
                                        <td class="semesters-semester"><?php echo $slNo++; ?></td>
                                        <td class="semesters-name"><?php echo htmlspecialchars($semester['semester_name']); ?></td>
                                        <td class="text-end">
                                            <!-- Dropdown -->
                                            <div class="dropdown" style="position:absolute">
                                                <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="#editModal" class="dropdown-item edit-btn" data-bs-toggle="modal" data-semester-id="<?php echo htmlspecialchars($semester['semester_id']); ?>" data-semester-name="<?php echo htmlspecialchars($semester['semester_name']); ?>">Edit</a>
                                                    <a href="#" class="dropdown-item" onclick="confirmDelete('<?php echo htmlspecialchars($semester['semester_id']); ?>')">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No Semesters found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- / .row -->
</div>

<!-- Add Semester Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Semester</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSemesterForm">
                    <div class="mb-3">
                        <label for="semesterName" class="form-label">Semester Name <span class="text-red">*</span></label>
                        <input type="text" class="form-control" id="semesterName" name="semester_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Semester</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Semester Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Semester</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSemesterForm">
                    <input type="hidden" id="editSemesterId" name="semester_id">
                    <div class="mb-3">
                        <label for="editSemesterName" class="form-label">Semester Name <span class="text-red">*</span></label>
                        <input type="text" class="form-control" id="editSemesterName" name="semester_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Add Semester
    document.getElementById('addSemesterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var name = document.getElementById('semesterName').value;

        fetch('../functions/add_semester.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ semester: { name: name } })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload the page to see the new semester
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Edit Semester
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-semester-id');
            var name = this.getAttribute('data-semester-name');

            document.getElementById('editSemesterId').value = id;
            document.getElementById('editSemesterName').value = name;
        });
    });

    document.getElementById('editSemesterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var id = document.getElementById('editSemesterId').value;
        var name = document.getElementById('editSemesterName').value;

        fetch('../functions/edit_semester.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ semester: { id: id, name: name } })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Reload the page to see the changes
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Confirm Delete
    window.confirmDelete = function(id) {
        if (confirm('Are you sure you want to delete this semester?')) {
            fetch('../functions/delete_semester.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload the page to remove the deleted semester
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };
});
</script>

<?php include '../includes/footer.php'; ?>
