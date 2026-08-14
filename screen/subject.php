<?php 
include '../includes/header.php';
$sqlSubjects = "
   SELECT s.subject_code, s.subject_name, s.semester_id,s.type, s.department_id, d.department_name, sem.semester_name
FROM subject s
LEFT JOIN department d ON s.department_id = d.department_id
JOIN semester sem ON s.semester_id = sem.semester_id
ORDER BY sem.semester_name, d.department_name;
";
$subjects = mysqli_query($conn, $sqlSubjects);


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
                                Subjects
                            </h1>

                        </div>
                        <div class="col-auto">

                            <!-- Button -->
                            <a href="add_subject.php" class="btn btn-primary lift">
                                New Subject
                            </a>

                            <!-- Delete Selected Button -->
                            

                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Summary Details View -->
            <div class="card">
                
                <div class="card-body">
                    <!-- Group subjects by semester and department, including NULL department -->
                    <?php if (!empty($subjects)): ?>
                        <?php
                        $groupedSubjects = [];
                        
                        // Group subjects by semester and department, handle NULL department
                        foreach ($subjects as $subject) {
                            $semester = $subject['semester_name'];
                            $department = $subject['department_name'] ?? null; // NULL check
                            
                            // If department is NULL, group it directly under semester
                            if ($department === null) {
                                $groupedSubjects[$semester]['no_department'][] = $subject;
                            } else {
                                $groupedSubjects[$semester][$department][] = $subject;
                            }
                        }
                        ?>

                        <!-- Display grouped subjects -->
                        <?php foreach ($groupedSubjects as $semester => $departments): ?>
                            <details>
                                <summary style="background:#435583;margin:10px;padding:10px 20px;"><strong><?php echo htmlspecialchars($semester); ?></strong></summary>
                                <div class="ms-4">

                                    <!-- Display subjects without a department -->
                                    <?php if (!empty($departments['no_department'])): ?>
                                        <ul class="list-group ms-4">
                                            <?php foreach ($departments['no_department'] as $subject): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><?php echo htmlspecialchars($subject['subject_code']); ?> (<?php echo htmlspecialchars($subject['subject_name']); ?>)</span>
                                                    <div>
                                                        <!-- Edit and Delete Buttons -->
                                                        <a href="#editModal" class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" 
                                                        data-subject-code="<?php echo htmlspecialchars($subject['subject_code']); ?>"
                                                         data-subject-name="<?php echo htmlspecialchars($subject['subject_name']); ?>"
                                                         data-semester-id="<?php echo htmlspecialchars($subject['semester_id']); ?>"
                                                         data-type="<?php echo htmlspecialchars($subject['type']); ?>">
                                                         Edit</a>
                                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo htmlspecialchars($subject['subject_code']); ?>')">Delete</button>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Display subjects grouped by department -->
                                    <?php foreach ($departments as $department => $subjectsInDepartment): ?>
                                        <?php if ($department !== 'no_department'): ?>
                                            <details>
                                                <summary style="background:#772b46;margin:10px;padding:10px 20px;"><strong>Department: <?php echo htmlspecialchars($department); ?></strong></summary>
                                                <ul class="list-group ms-4">
                                                    <?php foreach ($subjectsInDepartment as $subject): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span><?php echo htmlspecialchars($subject['subject_code']); ?> (<?php echo htmlspecialchars($subject['subject_name']); ?>)</span>
                                                            <div>
                                                                <!-- Edit and Delete Buttons -->
                                                                <a href="#editModal" class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" 
                                                                  data-subject-code="<?php echo htmlspecialchars($subject['subject_code']); ?>" 
                                                                    data-subject-name="<?php echo htmlspecialchars($subject['subject_name']); ?>"
                                                                    data-semester-id="<?php echo htmlspecialchars($subject['semester_id']); ?>" 
                                                                    data-department-id="<?php echo htmlspecialchars($subject['department_id']); ?>"
                                                                    data-type="<?php echo htmlspecialchars($subject['type']); ?>"
                                                                    >
                                                                  Edit
                                                                </a>
                                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo htmlspecialchars($subject['subject_code']); ?>')">Delete</button>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </details>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No subjects found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div> <!-- / .row -->
</div>



<!-- Edit Modal -->
<!-- Modal Code -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Subject</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editSubjectForm">
          <!-- Subject Code and Name -->
          <div class="mb-3">
            <label for="editSubjectCode" class="form-label">Subject Code<span class="text-red">*</span></label>
            <input type="text" id="editSubjectCode" class="form-control" readonly>
          </div>
          <input type="hidden" id="editSubjectCodeHidden">
          <input type="hidden" id="editType">
          <div class="mb-3">
            <label for="editSubjectName" class="form-label">Subject Name<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="editSubjectName">
          </div>
          <div class="mb-3">
            <label for="editSemester" class="form-label">Semester<span class="text-red">*</span></label>
            <select id="editSemester" class="form-select">
              <!-- Options populated by PHP -->
              <?php
              $sqlSemesters = "SELECT semester_id, semester_name FROM semester";
              $resultSemesters = mysqli_query($conn, $sqlSemesters);
              while ($semester = mysqli_fetch_assoc($resultSemesters)): ?>
                  <option value="<?php echo htmlspecialchars($semester['semester_id']); ?>"
                      <?php echo ($semester['semester_id'] == $subject['semester_id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($semester['semester_name']); ?>
                  </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="editDepartment" class="form-label">Department<span class="text-red">*</span></label>
            <select id="editDepartment" class="form-select">
              <option value="">No Department</option>
              <?php
              $sqlDepartments = "SELECT department_id, department_name FROM department";
              $resultDepartments = mysqli_query($conn, $sqlDepartments);
              while ($department = mysqli_fetch_assoc($resultDepartments)): ?>
                  <option value="<?php echo htmlspecialchars($department['department_id']); ?>"
                      <?php echo ($department['department_id'] == $subject['department_id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($department['department_name']); ?>
                  </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <input type="checkbox" name="type" id="type" value="core">
            <span>Core Subject</span>
              </div>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
include '../includes/footer.php';
?>

<!-- JavaScript -->
<script>
// JavaScript
document.addEventListener("DOMContentLoaded", function() {
  // Populate the modal with subject data
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const subjectCode = this.getAttribute('data-subject-code');
        const subjectName = this.getAttribute('data-subject-name');
        const semesterId = this.getAttribute('data-semester-id');
        const departmentId = this.getAttribute('data-department-id');
        const type = this.getAttribute('data-type');

        console.log('Subject Code:', subjectCode);
        console.log('Subject Name:', subjectName);
        console.log('Semester ID:', semesterId);
        console.log('Department ID:', departmentId);
        console.log('Type:', type);

        document.getElementById('editSubjectCode').value = subjectCode;
        document.getElementById('editSubjectName').value = subjectName;
        document.getElementById('editSubjectCodeHidden').value = subjectCode;

        document.getElementById('editType').value = type; 
        document.getElementById('editSemester').value = semesterId; 
        document.getElementById('editDepartment').value = (departmentId === 'null') ? '' : departmentId; 
        document.getElementById('type').checked = (type === 'core');
  });
});


  // Handle form submission
  document.getElementById('editSubjectForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const subjectCode = document.getElementById('editSubjectCodeHidden').value;
    const subjectName = document.getElementById('editSubjectName').value;
    const semester = document.getElementById('editSemester').value;
    const department = document.getElementById('editDepartment').value; // corrected this line
    const type = document.getElementById('type').checked ? 'core' : 'optional'; // use checked property

    fetch('../functions/edit_subject.php', {
        method: 'POST',
        body: JSON.stringify({ 
            subject_code: subjectCode, 
            subject_name: subjectName, 
            semester: semester, 
            department: department, 
            type: type // send type as core or optional
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


  // Define the confirmDelete function
  window.confirmDelete = function(subjectCode) {
    if (confirm('Are you sure you want to delete this subject?')) {
      fetch('../functions/delete_subject.php', {
        method: 'POST',
        body: JSON.stringify({ subject_code: subjectCode }),
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
    }
  };

  // Handle delete selected button click
  document.getElementById('deleteSelectedBtn').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('.list-checkbox:checked');
    const selectedCodes = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedCodes.length === 0) {
      alert('No subjects selected for deletion.');
      return;
    }
   
    if (confirm('Are you sure you want to delete the selected subjects?')) {
      fetch('../functions/delete_multiple_subjects.php', {
        method: 'POST',
        body: JSON.stringify({ subject_codes: selectedCodes }),
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
  });
});
</script>
