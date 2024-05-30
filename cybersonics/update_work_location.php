<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to your database
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "employee_mngt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$work_location = $_POST['work_location'];

// Update work location in the clock_records table
$sql = "UPDATE clock_records SET work_location=? WHERE user_id=? AND DATE(clock_in_time) = CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $work_location, $user_id);

if ($stmt->execute()) {
    echo "You are working from " . htmlspecialchars($work_location) . ".";
} else {
    echo "Error updating work location: " . $stmt->error;
}

$conn->close();
?>
