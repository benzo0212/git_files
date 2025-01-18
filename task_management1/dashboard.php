<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$tasks = $conn->query("SELECT * FROM tasks WHERE assigned_to = $user_id OR created_by = $user_id");
?>

<h1>Task Dashboard</h1>
<a href="create_task.php">Create Task</a>
<table border="1">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($task = $tasks->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($task['title']) ?></td>
        <td><?= htmlspecialchars($task['description']) ?></td>
        <td><?= htmlspecialchars($task['status']) ?></td>
        <td><a href="update_task.php?id=<?= $task['id'] ?>">Edit</a></td>
    </tr>
    <?php endwhile; ?>
</table>
