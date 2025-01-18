<?php
require 'session_config.php';
header('Content-Type: application/json');

// Ensure the session is started and valid
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(403);  // Set proper HTTP status code for forbidden access
    echo json_encode(['status' => 'error', 'message' => 'Session invalid or expired. Please log in again.']);
    exit;
}

// Return success if the session is valid
echo json_encode(['status' => 'success', 'role' => $_SESSION['role']]);
?>
