<?php
session_start();
require 'db.php';

// Redirect unauthorized users
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Admin')) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit;
}

// Handle task deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = intval($_POST['task_id']);

    if (!$task_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid task ID.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param('i', $task_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete task.']);
    }
}
?>
