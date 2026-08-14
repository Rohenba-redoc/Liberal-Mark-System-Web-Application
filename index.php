<?php
session_start();
include('includes/config.php'); // Ensure you have a file that handles DB connection

if (isset($_SESSION['role'])) {
    header('Location: screen/sign-in.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_passkey = implode("", $_POST['passkey']); // Combine the input digits

    // Prepare and execute a query to fetch the role for the entered passkey
    $stmt = $conn->prepare("SELECT role FROM passkeys WHERE passkey = ?");
    $stmt->bind_param("s", $entered_passkey);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    // If a matching role is found, set session and redirect
    if ($role) {
        
        if($role == 'admin'){
            $_SESSION = [];

            $_SESSION['admin_role'] = $role;
            header('Location: screen/sign-in.php');
            exit();
        }
        else{
            $_SESSION = [];

            $_SESSION['teacher_role'] = $role;
            header('Location: teacher/');
            exit();
        }
        
    } else {
        $error = "Invalid passkey. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberal College</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f5;
            margin: 0;
            background-image: url('assets/img/covers/home.jpg');
            background-repeat:no-repeat;
            background-size:cover;
            background-position:center;
        }

        .passkey-container {
            text-align: center;
            background: rgba(255,255,255,0.05);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(3px);
            border: 1px solid rgba(255,255,255,0.025);
            padding:40px;
        }

        .passkey-container h2 {
            margin-bottom: 20px;
            color:white;
        }

        .passkey-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .passkey-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
        }

        .passkey-input:focus {
            border-color: #007aff;
            box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.2);
            outline: none;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #17D527;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0E8E19;
        }

        .error-message {
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="passkey-container">
        <h2>Enter Passkey</h2>
        <form method="POST" action="">
            <div class="passkey-inputs">
                <input type="text" name="passkey[]" maxlength="1" class="passkey-input" required>
                <input type="text" name="passkey[]" maxlength="1" class="passkey-input" required>
                <input type="text" name="passkey[]" maxlength="1" class="passkey-input" required>
                <input type="text" name="passkey[]" maxlength="1" class="passkey-input" required>
            </div>
            <button type="submit" class="submit-btn">Verify</button>
            <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        </form>
    </div>

    <script>
        // Auto-focus on the next input field when a digit is entered
        const inputs = document.querySelectorAll('.passkey-input');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length > 0 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>
</html>
