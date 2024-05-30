<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "employee_mngt";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_SESSION['user_id'];

// Prepare and execute SQL query
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error: " . $conn->error); // Check for errors in preparing the statement
}

// Bind parameters and execute query
$stmt->bind_param("i", $employee_id);
$result = $stmt->execute();
if (!$result) {
    die("Error: " . $stmt->error); // Check for errors in executing the statement
}

// Get result set
$result = $stmt->get_result();

// Fetch employee details
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["username"];
    $employee_name = $row["Employee_Name"];
    $group_no = $row["group_no"];
} else {
    echo "No employee found";
    exit();
}

// Fetch clock in/out times for the past 30 days
$sql = "SELECT clock_in_time, clock_out_time, work_location FROM clock_records WHERE user_id=? AND DATE(clock_in_time) >= CURDATE() - INTERVAL 30 DAY";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$clock_times = [];
while ($row = $result->fetch_assoc()) {
    $clock_times[] = $row;
}

// Calculate various metrics
$total_hours = 0;
$late_count = 0;
$work_from_home_count = 0;
$work_from_office_count = 0;

foreach ($clock_times as $time) {
    $clock_in_time = new DateTime($time['clock_in_time']);
    $clock_out_time = new DateTime($time['clock_out_time']);
    $hours_worked = $clock_out_time->diff($clock_in_time)->h;

    $total_hours += $hours_worked;
    if ($clock_in_time->format('H:i') > '09:00') {
        $late_count++;
    }

    if ($time['work_location'] == 'home') {
        $work_from_home_count++;
    } else if ($time['work_location'] == 'office') {
        $work_from_office_count++;
    }
}

$average_hours = count($clock_times) > 0 ? $total_hours / count($clock_times) : 0;

// Fetch leave details
$sql = "SELECT SUM(days) AS total_leave_taken FROM leaves WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$leave_data = $result->fetch_assoc();
$total_leave_taken = $leave_data['total_leave_taken'] ?: 0;
$total_leave_remaining = 4 - $total_leave_taken;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/dash1.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee Profile</h1>
        </div>
        <div class="content">
            <div class="section employee-info">
                <h2>Employee Information</h2>
                <div class="employee-picture">
                    <img src="img/profile_pic.jpg" alt="Employee Picture">
                </div>
                <div class="employee-details">
                    <p><strong>Username:</strong> <?php echo $username; ?></p>
                    <p><strong>Employee Name:</strong> <?php echo $employee_name; ?></p>
                    <p><strong>Group Number:</strong> <?php echo $group_no; ?></p>
                    <p><strong>Total Leave Days Taken:</strong> <?php echo $total_leave_taken; ?></p>
                    <p><strong>Total Leave Days Remaining:</strong> <?php echo $total_leave_remaining; ?></p>
                    <p><strong>Number of Late Arrivals:</strong> <?php echo $late_count; ?></p>
                    <p><strong>Average Working Hours per Day:</strong> <?php echo round($average_hours, 2); ?></p>
                    <p><strong>Working from Home:</strong> <?php echo $work_from_home_count; ?></p>
                    <p><strong>Working from Office:</strong> <?php echo $work_from_office_count; ?></p>
                </div>
            </div>
        </div>
        <div class="footer">
            <a href="dashboard.php" class="btn btn-dark">Dashboard</a>
            <a href="logout.php" class="btn btn-dark">Logout</a>
        </div>
    </div>
</body>
</html>
