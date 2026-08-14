<?php 
session_start(); // Start the session


// Redirect to passkey validation if role is not set
if (!isset($_SESSION['teacher_role'])) {
    header('Location: ../index.php');
    exit();
}



if (isset($_SESSION['teacher'])) {
  header('Location: Views/dashboard.php');
  exit();
}
?>
<!doctype html>
<html lang="en" data-bs-theme="">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Libs CSS -->
    <link rel="stylesheet" href="../assets/css/libs.bundle.css">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="../assets/css/theme.bundle.css">

    <!-- Title -->
    <title>Liberal College - Teacher - LogIn</title>
    <style>
        body {
            background-image: url('../assets/img/covers/home.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .container {
            background: rgba(255,255,255,0.05);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.025);
        }
    </style>
</head>
<body class="d-flex align-items-center border-top border-top-2 border-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-5 col-xl-4 my-5">
                <!-- Heading -->
                <h1 class="display-4 text-center mb-3">Sign in</h1>
                <!-- Subheading -->
                <p class="text-body text-center mb-5">Liberal College Teacher Panel</p>
                <!-- Form -->
                <form id="loginForm">
                    <!-- Email address -->
                    <div class="form-group">
                        <!-- Label -->
                        <label class="form-label">Email Address</label>
                        <!-- Input -->
                        <input type="email" id="email" name="email" class="form-control" placeholder="name@address.com" required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <!-- Label -->
                                <label class="form-label">Password</label>
                            </div>
                        </div> <!-- / .row -->
                        <!-- Input group -->
                        <div class="input-group input-group-merge">
                            <!-- Input -->
                            <input id="password" name="password" class="form-control" type="password" placeholder="Enter your password" required>
                            <!-- Icon -->
                            <span class="input-group-text" onclick="togglePassword()">
                                <i id="toggleIcon" class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-lg w-100 btn-primary mb-3">Sign in</button>
                </form>
            </div>
        </div> <!-- / .row -->
    </div> <!-- / .container -->

    <!-- JAVASCRIPT -->
    <!-- Vendor JS -->
    <script src="../assets/js/vendor.bundle.js"></script>
    <!-- Theme JS -->
    <script src="../assets/js/theme.bundle.js"></script>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);

    // Log the form data to the console
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    fetch('controller/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = 'Views/dashboard.php'; 
        } else {
            alert(data.message); // Show error message
        }
    })
    .catch(error => console.error('Error:', error));
});

    </script>
</body>
</html>
