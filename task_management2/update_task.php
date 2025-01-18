<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Admin')) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $task_id = intval($data['task_id']);
    $title = $data['title'];
    $description = $data['description'];
    $status = $data['status'];

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt->bind_param('sssi', $title, $description, $status, $task_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update task']);
    }
}
?>
