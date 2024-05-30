<?php
session_start();
require_once 'includes/config.php'; // Include your database connection

// Check if the user has already clocked in today
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');
$sql = "SELECT * FROM clock_records WHERE user_id = ? AND DATE(clock_in_time) = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $current_date]);
$count = $stmt->rowCount();

if ($count > 0) {
    echo "You are already clocked in for today.";
} else {
    // Record the clock-in time in the database
    $clock_in_time = date('Y-m-d H:i:s');
    $sql = "INSERT INTO clock_records (user_id, clock_in_time) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $clock_in_time]);
    echo "Successfully clocked in at $clock_in_time.";
}
?>
