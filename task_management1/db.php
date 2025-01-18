<?php
$host = 'localhost';
$user = 'root';
$password = 'mysql';
$dbname = 'task_management1';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
