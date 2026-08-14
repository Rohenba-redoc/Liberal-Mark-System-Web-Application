<?php 
include '../includes/header.php';
include '../includes/config.php'; 

$course_code = $_GET['course_code'] ?? ''; 

// Fetch the specific course details
$course_sql = "SELECT * FROM course WHERE course_code = ?";
$stmt = $conn->prepare($course_sql);
$stmt->bind_param('s', $course_code);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

// Fetch all streams
$stream_sql = "SELECT * FROM streams"; 
$stream_result = $conn->query($stream_sql);

$streams = [];
if ($stream_result->num_rows > 0) {
    while($row = $stream_result->fetch_assoc()) {
        $streams[] = $row;
    }
}

$stmt->close();
$conn->close(); // Close the database connection
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
                Edit Discipline
              </h6>

              <!-- Title -->
              <h1 class="header-title">
                Edit Discipline
              </h1>

            </div>
          </div> <!-- / .row -->
        </div>
      </div>
    <form id="editCourseForm" class="mb-4">
        <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>">
        <div class="mb-3">
            <label for="courseName" class="form-label">Course Name<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="courseName" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="courseDuration" class="form-label">Duration (Years)<span class="text-red">*</span></label>
            <input type="text" class="form-control" id="courseDuration" name="duration" value="<?php echo htmlspecialchars($course['duration']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="streamId" class="form-label">Stream<span class="text-red">*</span></label>
            <select class="form-select" id="streamId" name="stream_id" required>
                <?php foreach ($streams as $stream): ?>
                    <option value="<?php echo htmlspecialchars($stream['stream_id']); ?>" <?php echo ($course['stream_id'] == $stream['stream_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($stream['stream_title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="course.php" class="btn btn-primary lift">
                Cancel
              </a>
    </form>
</div>
</div>
</div>

<!-- JavaScript to handle form submission -->
<script>
document.getElementById('editCourseForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    
    const formData = new FormData(this); // Get form data
    
    fetch('../functions/edit_course.php', {
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
