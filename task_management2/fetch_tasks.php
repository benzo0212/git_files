<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['status' => 'error', 'message' => 'Session invalid. Please log in again.']);
    http_response_code(403);
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

try {
    if ($role === 'Team Member') {
        // Fetch tasks assigned to the logged-in Team Member
        $sql = "SELECT id, title, description, status, deadline FROM tasks WHERE assignee = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Fetch all tasks for Admins and Managers
        $sql = "SELECT tasks.id, title, description, username AS assignee, status, deadline 
                FROM tasks 
                JOIN users ON tasks.assignee = users.id";
        $result = $conn->query($sql);
    }

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    echo json_encode(['status' => 'success', 'tasks' => $tasks]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch tasks.']);
    http_response_code(500);
}
?>
