<?php 
include '../includes/header.php';
?>
<div class="main-content">

  <!-- HEADER -->
  <div class="header">
    <!-- Image -->
    <img src="../assets/img/covers/home.jpg" class="header-img-top" alt="..." style="height:auto;width:100%">

    <div class="container-fluid">
      <!-- Body -->
      <div class="header-body mt-n5 mt-md-n6">
        <div class="row align-items-end">
          <div class="col-auto">
            <!-- Avatar -->
            <div class="avatar avatar-xxl header-avatar-top">
              <img src="../assets/img/avatars/profiles/R.png" alt="..." class="avatar-img rounded-circle border border-4 border-body">
            </div>
          </div>
          <div class="col mb-3 ms-n3 ms-md-n2">
            <!-- Pretitle -->
            <h6 class="header-pretitle">Admin</h6>
            <!-- Title -->
            <h1 class="header-title"><?php echo$username?></h1>
          </div>
          <div class="col-12 col-md-auto mt-2 mt-md-0 mb-md-3">
            <!-- Button -->
            <a href="../functions/logout.php" class="btn btn-danger d-block d-md-inline-block lift">LogOut</a>
          </div>
        </div> <!-- / .row -->
        <div class="row align-items-center">
          <div class="col">
            <!-- Nav -->
            <ul class="nav nav-tabs nav-overflow header-tabs" id="profileTabs">
              <li class="nav-item">
                <a href="#profile" class="nav-link active" onclick="showTab('profile')">Profile</a>
              </li>
              <li class="nav-item">
                <a href="#reset-password" class="nav-link" onclick="showTab('reset-password')">Reset Password</a>
              </li>
            </ul>
          </div>
        </div>
      </div> <!-- / .header-body -->
    </div>
  </div>

  <!-- CONTENT -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-xl-12">
        <!-- Card -->
        <div class="card">
          <div class="card-body">
            <!-- Tab Content -->
            <div id="profileContent" class="tab-content">
              <!-- Profile Tab -->
              <div id="profile" class="tab-pane active">
                <!-- List group -->
                <div class="list-group list-group-flush my-n3">
                  <div class="list-group-item">
                    <div class="row align-items-center">
                      <div class="col">
                        <!-- Title -->
                        <h5 class="mb-0">Email</h5>
                      </div>
                      <div class="col-auto">
                        <!-- Time -->
                        <time class="small text-body-secondary" ><?php echo$email?></time>
                      </div>
                    </div> <!-- / .row -->
                  </div>
                  <div class="list-group-item">
                    <div class="row align-items-center">
                      <div class="col">
                        <!-- Title -->
                        <h5 class="mb-0">Account Role</h5>
                      </div>
                      <div class="col-auto">
                        <!-- Time -->
                        <time class="small text-body-secondary" ><?php echo$level?></time>
                      </div>
                    </div> <!-- / .row -->
                  </div>
                </div>
              </div>
              <!-- Reset Password Tab -->
              <div id="reset-password" class="tab-pane">
                <h5>Reset Password</h5>
                <form action="../functions/reset_password.php" method="POST" id="passwordChangeForm">
                  <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current_password" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="text" id="new-password" name="new_password" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="text" id="confirm-password" name="confirm_password" class="form-control" required>
                  </div>
                  <button type="submit" class="btn btn-primary">Reset Password</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- / .row -->
  </div>
</div>
<script>
  document.getElementById('passwordChangeForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);

    fetch('../functions/reset_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
          alert(data.message); 

            window.location.href = data.redirect; // Redirect on success
        } else {
            alert(data.error); // Show error message
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
<?php include '../includes/footer.php';?>

<script>
  function showTab(tabId) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(function(pane) {
      pane.classList.remove('active');
    });

    // Remove active class from all nav links
    document.querySelectorAll('.nav-link').forEach(function(link) {
      link.classList.remove('active');
    });

    // Show the selected tab pane
    document.getElementById(tabId).classList.add('active');

    // Activate the clicked nav link
    document.querySelector(`a[href="#${tabId}"]`).classList.add('active');
  }
</script>
