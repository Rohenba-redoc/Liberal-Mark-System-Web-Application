<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

include '../../includes/config.php'; // Include your database connection file

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['teacher']) || empty($_SESSION['teacher']['Id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get form data
$teacher_id = $_SESSION['teacher']['Id'];
$name = $_POST['uname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$dob = $_POST['dob'] ?? '';

// Validate input
if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($dob)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

// Prepare the SQL query to update profile information
$sql = "UPDATE teacher_credentials tc
        JOIN teacher t ON tc.teacher_id = t.teacher_id
        SET tc.email = ?, tc.phone = ?,t.teacher_phone=?,t.teacher_email=?, t.teacher_address = ?, t.dob = ?, t.teacher_name=?
        WHERE tc.Id = ?";

// Prepare the statement
if ($stmt = $conn->prepare($sql)) {
    // Bind parameters
    $stmt->bind_param('sssssssi', $email, $phone, $phone, $email, $address, $dob,$name, $teacher_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Update session data
        $_SESSION['teacher']['name'] = $name;
        $_SESSION['teacher']['email'] = $email;
        $_SESSION['teacher']['phone'] = $phone;
        $_SESSION['teacher']['address'] = $address;
        $_SESSION['teacher']['dob'] = $dob;

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement: ' . $conn->error]);
}

$conn->close();
?>
