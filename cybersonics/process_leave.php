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
$leave_days = $_POST['leave_days'];
$leave_type = $_POST['leave_type'];

// Check if the leave type is annual and ensure there are enough remaining annual leave days
if ($leave_type === 'annual') {
    $sql_leave = "SELECT SUM(days) AS total_leave_taken FROM leaves WHERE user_id=? AND leave_type='annual'";
    $stmt_leave = $conn->prepare($sql_leave);

    if (!$stmt_leave) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt_leave->bind_param("i", $user_id);
    $stmt_leave->execute();
    $result_leave = $stmt_leave->get_result();
    $leave_data = $result_leave->fetch_assoc();
    $total_annual_leave_taken = $leave_data['total_leave_taken'] ?: 0;
    $total_annual_leave_remaining = 4 - $total_annual_leave_taken;

    if ($leave_days > $total_annual_leave_remaining) {
        echo "Error: You do not have enough remaining annual leave days.";
        exit();
    }
}

// Insert leave application into database
$sql = "INSERT INTO leaves (user_id, days, leave_type) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("iis", $user_id, $leave_days, $leave_type);

if ($stmt->execute()) {
    echo "Leave application submitted successfully.";
} else {
    echo "Error submitting leave application: " . $stmt->error;
}

$conn->close();
header("Location: leave.php");
exit();
?>
