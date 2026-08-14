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
              <h6 class="header-pretitle">New Subjects</h6>
              <h1 class="header-title">Add new Subjects</h1>
            </div>
          </div> 
        </div>
      </div>

      <!-- Form -->
      <form id="subjectsForm" class="mb-4">
        <div style="display:flex;justify-content:space-around;margin-bottom:20px;">
          <div class="col-5">
            <label class="mb-2">Department</label>
            <select class="form-control" id="departmentSelect" name="department">
              <option value="">No Department</option>
              <?php
                $sqlDepartments = "SELECT department_id, department_name FROM department";
                $resultDepartments = mysqli_query($conn, $sqlDepartments);
                while ($department = mysqli_fetch_assoc($resultDepartments)): ?>
                  <option value="<?php echo htmlspecialchars($department['department_id']); ?>">
                    <?php echo htmlspecialchars($department['department_name']); ?>
                  </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="col-5">
            <label class="mb-2">Semester</label>
            <select class="form-control" id="semesterSelect" name="semester">
              <?php
                $sqlSemesters = "SELECT semester_id, semester_name FROM semester";
                $resultSemesters = mysqli_query($conn, $sqlSemesters);
                while ($semester = mysqli_fetch_assoc($resultSemesters)): ?>
                  <option value="<?php echo htmlspecialchars($semester['semester_id']); ?>">
                    <?php echo htmlspecialchars($semester['semester_name']); ?>
                  </option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <!-- Subjects Table -->
        <table class="table table-bordered" id="subjectsTable">
          <thead>
            <tr>
              <th>Sl.No</th>
              <th>Subject Code<span class="text-red">*</span></th>
              <th>Subject Name<span class="text-red">*</span></th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="slno">1</td>
              <td><input type="text" name="subject_codes[]" class="form-control" required></td>
              <td><input type="text" name="subject_names[]" class="form-control" required></td>
              <td><input type="checkbox" name="type[]" value="core"> Core</td>
              <td><button type="button" class="btn btn-outline-danger deleteRowButton">Delete</button></td>
            </tr>
          </tbody>
        </table>

        <!-- Add Row Button -->
        <button type="button" class="btn btn-outline-primary mb-4" id="addRowButton">Add Another Row</button>

        <!-- Divider -->
        <hr class="mt-4 mb-5">

        <!-- Submit Button -->
        <button type="submit" class="btn w-100 btn-primary">Add Subjects</button>
        <a href="subject.php" class="btn w-100 btn-link text-body-secondary mt-2">Cancel</a>
      </form>

    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const addRowButton = document.getElementById("addRowButton");
    const subjectsTable = document.getElementById("subjectsTable").getElementsByTagName('tbody')[0];

    function updateRowNumbers() {
      const rows = subjectsTable.getElementsByTagName('tr');
      for (let i = 0; i < rows.length; i++) {
        rows[i].getElementsByClassName('slno')[0].innerText = i + 1;
      }
    }

    addRowButton.addEventListener("click", function() {
      const newRow = subjectsTable.insertRow();
      const newCell0 = newRow.insertCell(0);
      const newCell1 = newRow.insertCell(1);
      const newCell2 = newRow.insertCell(2);
      const newCell3 = newRow.insertCell(3);
      const newCell4 = newRow.insertCell(4);

      const rowCount = subjectsTable.rows.length;
      newCell0.className = "slno";
      newCell0.innerText = rowCount;
      newCell1.innerHTML = '<input type="text" name="subject_codes[]" class="form-control" required>';
      newCell2.innerHTML = '<input type="text" name="subject_names[]" class="form-control" required>';
      newCell3.innerHTML = '<input type="checkbox" name="type[]" value="core"> Core';
      newCell4.innerHTML = '<button type="button" class="btn btn-outline-danger deleteRowButton">Delete</button>';

      updateRowNumbers();
    });

    subjectsTable.addEventListener("click", function(event) {
      if (event.target.classList.contains("deleteRowButton")) {
        const row = event.target.closest("tr");
        row.parentNode.removeChild(row);
        updateRowNumbers();
      }
    });

    const form = document.getElementById("subjectsForm");

form.addEventListener("submit", function(event) {
  event.preventDefault();

  const department = document.getElementById("departmentSelect").value || null;  // Allow department to be null
  const semester = document.getElementById("semesterSelect").value;

  const formData = new FormData(form);
  const subjectCodes = formData.getAll("subject_codes[]");
  const subjectNames = formData.getAll("subject_names[]");
  const types = Array.from(subjectsTable.querySelectorAll('input[name="type[]"]')).map(checkbox => 
      checkbox.checked ? "core" : "optional"
    );
  const subjects = subjectCodes.map((code, index) => ({
    code,
    name: subjectNames[index],
    type: types[index]
  }));

  if (!semester) {
    alert("Please select a semester.");
    return;
  }
  const requestData = { subjects, department, semester };
  console.log("Data to be sent:", requestData);

  fetch('../functions/add_subject.php', {
    method: 'POST',
    body: JSON.stringify({ subjects, department, semester }),
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      window.location.href = 'subject.php';
    } else {
      alert(data.message);
    }
  })
  .catch((error) => {
    console.error('Error:', error);
  });
});

  });
</script>

<?php 
include '../includes/footer.php';
?>
