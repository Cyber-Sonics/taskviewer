<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to clock out.";
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

// Check if the user has clocked in today
$sql = "SELECT id, clock_in_time FROM clock_records WHERE user_id = ? AND DATE(clock_in_time) = CURDATE() AND clock_out_time IS NULL";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User has clocked in today and has not clocked out yet
        $row = $result->fetch_assoc();
        $clock_record_id = $row['id'];
        $clock_in_time = $row['clock_in_time'];
        $clock_out_time = date('Y-m-d H:i:s'); // Current time as clock out time
        
        // Calculate total hours worked
        $total_hours_worked = round((strtotime($clock_out_time) - strtotime($clock_in_time)) / 3600, 2);
        
        // Update the clock out time and total hours worked
        $sql_update = "UPDATE clock_records SET clock_out_time = ?, total_hours_worked = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        if ($stmt_update) {
            $stmt_update->bind_param("sdi", $clock_out_time, $total_hours_worked, $clock_record_id);
            $stmt_update->execute();
            echo "Successfully clocked out. Total hours worked: $total_hours_worked hours";
        } else {
            echo "Failed to prepare the statement: " . $conn->error;
        }
    } else {
        // No clock in record found for today or user already clocked out
        echo "You have not clocked in today or you have already clocked out.";
    }
    $stmt->close();
} else {
    echo "Failed to prepare the statement: " . $conn->error;
}

$conn->close();
?>
