<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $service = htmlspecialchars(trim($_POST['service']));
    $date = $_POST['date'];

    if (!empty($name) && !empty($service) && !empty($date)) {
        $stmt = $conn->prepare("INSERT INTO appointments (name, service, date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $service, $date);

        if ($stmt->execute()) {
            echo "<script>alert('Your appointment has been successfully booked.'); window.location.href = '../appointment.html';</script>";
        } else {
            echo "<script>alert('Error: Could not book your appointment. Please try again later.'); window.location.href = '../appointment.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.'); window.location.href = '../appointment.html';</script>";
    }
}

$conn->close();
?>
