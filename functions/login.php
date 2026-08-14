<?php
session_start(); // Start the session

include '../includes/config.php'; 

header('Content-Type: application/json');

// Get POST data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password required']);
    exit();
}

// Query to check credentials
$query = "SELECT id, username, email, password, status, level FROM admin_credentials WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user exists and password matches
if ($user && $password == $user['password']) {
    // Check if the account status is active
    if ($user['status'] === 'active') {
        // Generate a token
        $token = bin2hex(random_bytes(16)); // Simple token generation

        // Save the token in the database
        $insertTokenQuery = "INSERT INTO tokens (user_id, token) VALUES (?, ?)";
        $insertTokenStmt = $conn->prepare($insertTokenQuery);
        $insertTokenStmt->bind_param('is', $user['id'], $token);
        $insertTokenStmt->execute();

        // Set session variables
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'password' => $user['password'],
            'status' => $user['status'],
            'level' => $user['level']
        ];

        echo json_encode([
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'status' => $user['status'],
                'level' => $user['level']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Account is not active! Contact Admin to Activate the Account']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
}
?>
