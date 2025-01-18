<?php
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT name, message FROM testimonials ORDER BY submitted_at DESC";
$result = $conn->query($sql);

$testimonials = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

echo json_encode($testimonials);

$conn->close();
?>
