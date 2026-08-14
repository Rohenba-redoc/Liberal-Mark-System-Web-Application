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
                New Department
              </h6>

              <!-- Title -->
              <h1 class="header-title">
                Add new Department
              </h1>

            </div>
          </div> <!-- / .row -->
        </div>
      </div>

      <!-- Form -->
      <form id="streamsForm" class="mb-4">

        <!-- Streams Table -->
        <table class="table table-bordered" id="streamsTable">
          <thead>
            <tr>
              <th>Sl.No</th>
              <th>Department Name<span class="text-red">*</span></th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="slno">1</td>
              <td><input type="text" name="department_name[]" class="form-control"></td>
              <td><button type="button" class="btn btn-outline-danger deleteRowButton">Delete</button></td>
            </tr>
          </tbody>
        </table>

        <!-- Add Row Button -->
        <button type="button" class="btn btn-outline-primary mb-4" id="addRowButton">
          Add Another Row
        </button>
        
        <!-- Divider -->
        <hr class="mt-4 mb-5">
        
        <!-- Submit Button -->
        <button type="submit" class="btn w-100 btn-primary">
          Add Department
        </button>
        <a href="department.php" class="btn w-100 btn-link text-body-secondary mt-2">
          Cancel
        </a>

      </form>

    </div>
  </div> 
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const addRowButton = document.getElementById("addRowButton");
    const streamsTable = document.getElementById("streamsTable").getElementsByTagName('tbody')[0];

    function updateRowNumbers() {
      const rows = streamsTable.getElementsByTagName('tr');
      for (let i = 0; i < rows.length; i++) {
        rows[i].getElementsByClassName('slno')[0].innerText = i + 1;
      }
    }

    addRowButton.addEventListener("click", function() {
      const newRow = streamsTable.insertRow();
      const newCell0 = newRow.insertCell(0);
      const newCell1 = newRow.insertCell(1);
      const newCell2 = newRow.insertCell(2);

      const rowCount = streamsTable.rows.length;
      newCell0.className = "slno";
      newCell0.innerText = rowCount;
      newCell1.innerHTML = '<input type="text" name="department_name[]" class="form-control">';
      newCell2.innerHTML = '<button type="button" class="btn btn-outline-danger deleteRowButton">Delete</button>';

      newCell2.getElementsByTagName('button')[0].addEventListener("click", function() {
        streamsTable.deleteRow(newRow.rowIndex - 1);
        updateRowNumbers();
      });
    });

    streamsTable.addEventListener("click", function(event) {
      if (event.target.classList.contains("deleteRowButton")) {
        const row = event.target.closest("tr");
        row.parentNode.removeChild(row);
        updateRowNumbers();
      }
    });

    const form = document.getElementById("streamsForm");
    form.addEventListener("submit", function(event) {
      event.preventDefault();

      const formData = new FormData(form);
      const departmentNames = formData.getAll("department_name[]");

      const departments = departmentNames.map(name => ({
        name
      }));

      fetch('../functions/add_department.php', {
        method: 'POST',
        body: JSON.stringify({ departments }),
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            window.location.href = 'department.php';
          } else {
            alert(data.message);
          }
        }).catch((error) => {
          console.error('Error:', error);
        });
    });
  });
</script>

<?php 
include '../includes/footer.php';
?>
