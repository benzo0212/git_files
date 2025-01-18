<?php
session_start();
require 'db.php';

// Destroy any existing session
session_unset();
session_destroy();

// Restart a fresh session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username.']);
        exit;
    }

    $user = $result->fetch_assoc();
    if (md5($password) !== $user['password']) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
        exit;
    }

    // Regenerate session ID
    session_regenerate_id(true);

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_activity'] = time();

    echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'role' => $user['role']]);
    exit;
}
?>
