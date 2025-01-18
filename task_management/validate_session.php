<?php
require 'session_config.php';
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Your session has expired. Please log in again.']);
    exit;
}

// Return user data for active sessions
echo json_encode([
    'status' => 'success',
    'user_id' => $_SESSION['user_id'],
    'role' => $_SESSION['role']
]);
?>
