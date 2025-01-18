<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $assigned_to = intval($_POST['assigned_to']);
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, assigned_to, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $description, $assigned_to, $created_by);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
$users = $conn->query("SELECT id, username FROM users");
?>

<form method="post" action="">
    <input type="text" name="title" placeholder="Task Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <select name="assigned_to">
        <?php while ($user = $users->fetch_assoc()): ?>
        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Create Task</button>
</form>
