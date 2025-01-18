<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO testimonials (name, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your testimonial!'); window.location.href = '../index.html';</script>";
        } else {
            echo "<script>alert('Error: Could not save your testimonial. Please try again later.'); window.location.href = '../index.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.'); window.location.href = '../index.html';</script>";
    }
}

$conn->close();
?>
