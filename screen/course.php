<?php 
include '../includes/header.php';
include '../functions/display.php';

$courses = fetchCourses();

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
                Discipline
              </h1>

            </div>
            <div class="col-auto">

              <!-- Button -->
              <a href="add_course.php" class="btn btn-primary lift">
                New Discipline
              </a>

            </div>
          </div> <!-- / .row -->
        
        </div>
      </div>

      <!-- Card -->
      <div class="card" data-list='{"valueNames": ["courses-code", "courses-name", "courses-duration", "courses-stream"]}'>
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
      <th>Course Name</th>
      <th>Duration</th>
      <th>Stream</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody class="list">
  <?php if (!empty($courses)): ?>
        <?php $slNo = 1; ?>
        <?php foreach ($courses as $course): ?>
    <tr>
      <td class="courses-code"><?php echo $slNo++; ?></td>
      <td class="courses-name"><?php echo htmlspecialchars($course['course_name']); ?></td>
      <td class="courses-duration"><?php echo htmlspecialchars($course['duration']); ?> Years</td>
      <td class="courses-stream"><?php echo htmlspecialchars($course['stream_title']); ?></td>
      <td class="text-end">
        <div class="dropdown" style="position:absolute">
          <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fe fe-more-vertical"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <a href="edit_course.php?course_code=<?php echo htmlspecialchars($course['course_code']); ?>" class="dropdown-item">
              Edit
            </a>
            <a href="#" class="dropdown-item" onclick="confirmDelete('<?php echo htmlspecialchars($course['course_code']); ?>')">
              Delete
            </a>
          </div>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
      <tr>
          <td colspan="5">No Courses found</td>
      </tr>
  <?php endif; ?>
  </tbody>
</table>

        </div>
      </div>

    </div>
  </div> <!-- / .row -->
</div>

<!-- JavaScript -->
<script>
  // Confirm and handle delete course
  window.confirmDelete = function(courseCode) {
    if (confirm('Are you sure you want to delete this Discipline?')) {
      fetch('../functions/delete_course.php', {
        method: 'POST',
        body: new URLSearchParams({ course_code: courseCode }),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
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
    }
  };
</script>

<?php 
include '../includes/footer.php';
?>
