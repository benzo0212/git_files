<?php
session_start();
require 'db.php';

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['role'], $_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

if ($role === 'Team Member') {
    $sql = "SELECT id, title, description, status, deadline FROM tasks WHERE assignee = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT tasks.id, title, description, username AS assignee, status, deadline FROM tasks JOIN users ON tasks.assignee = users.id";
    $result = $conn->query($sql);
}

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
echo json_encode($tasks);
?>
