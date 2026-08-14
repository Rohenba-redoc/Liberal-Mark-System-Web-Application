

<?php
include '../includes/header.php';

include '../includes/config.php'; 

$sql = "SELECT * FROM streams"; 
$result = $conn->query($sql);

$streams = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $streams[] = $row;
    }
}

$conn->close(); 

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
                New Discipline
              </h6>

              <!-- Title -->
              <h1 class="header-title">
                Add new Discipline
              </h1>

            </div>
          </div> <!-- / .row -->
        </div>
      </div>
      <form id="addCourseForm"  class="mb-4">
        <div class="mb-3">
            <label for="courseName" class="form-label">Course Name<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="courseName" name="course_name" required>
        </div>
        <div class="mb-3">
            <label for="courseDuration" class="form-label">Duration (Years)<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="courseDuration" name="duration" required>
        </div>
        <div class="mb-3">
            <label for="streamId" class="form-label">Stream<span class="text-red">*</span></label>
            <select class="form-select" id="streamId" name="stream_id" required>
                <?php foreach ($streams as $stream): ?>
                    <option value="<?php echo htmlspecialchars($stream['stream_id']); ?>"><?php echo htmlspecialchars($stream['stream_title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Discipline</button>
        <a href="course.php" class="btn btn-primary lift">
                Cancel
              </a>
    </form>
</div>
</div>
</div>
<script>
document.getElementById('addCourseForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    
    const formData = new FormData(this); // Get form data
    
    fetch('../functions/add_course.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // Show success alert
            window.location.href = 'course.php'; // Redirect to course.php
        } else {
            alert(data.message); // Show error message
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>

<?php include '../includes/footer.php'; ?>
