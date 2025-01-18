<?php
session_start();
require 'db.php';

// Only Managers and Admins can create tasks
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Admin')) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $assignee = intval($_POST['assignee']);
    $deadline = $_POST['deadline'];

    if (empty($title) || empty($description) || empty($assignee) || empty($deadline)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, assignee, deadline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssis', $title, $description, $assignee, $deadline);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create task']);
    }
}
?>
