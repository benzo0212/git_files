<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Admin') {
    die('Access denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt->bind_param('sssi', $title, $description, $status, $task_id);
    $stmt->execute();
    echo "Task updated successfully!";
}
?>
