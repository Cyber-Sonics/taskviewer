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

// SQL query to fetch employee details
$sql = "SELECT username, Employee_Name, group_no FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row["username"];
        $employee_name = $row["Employee_Name"];
        $group_no = $row["group_no"];
    } else {
        echo "No employee found";
        exit();
    }
    $stmt->close();
} else {
    die("Statement preparation failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management Portal</title>
    <link rel="stylesheet" href="css/dash1.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to the Employee Management Portal, <?php echo htmlspecialchars($username); ?>!</h1>
        </div>
        
        <div class="employee-details">
            <p><strong>Employee Name:</strong> <?php echo htmlspecialchars($employee_name); ?></p>
            <p><strong>Group Number:</strong> <?php echo htmlspecialchars($group_no); ?></p>
        </div>

        <!-- Tasks management section -->
        <div class="section tasks">
            <h2>Tasks Management</h2>
            <a href="task_summary.php" class="btn btn-primary">Manage Tasks</a>
        </div>

        <!-- Attendance management section -->
        <div class="section attendance">
            <h2>Attendance</h2>
            <button id="clock-in-btn">Clock In</button>
            <button id="clock-out-btn">Clock Out</button>
            <a href="leave.php" class="btn btn-primary">Apply For Leave</a>
        
            <div id="clock-message"></div> <!-- Message area for displaying clock status -->
        </div>
        <div class="section attendance">
            <h2>Where are you working from?</h2>
            <button id="Working-from-home-btn">Home</button>
            <button id="Working-from-office-btn">Office</button>
        </div>

        <div class="footer">
            <a href="profile.php" class="btn btn-dark">View Profile</a>
            <a href="logout.php" class="btn btn-dark">Logout</a>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#clock-in-btn').click(function() {
                $.ajax({
                    url: 'clock_in.php',
                    type: 'POST',
                    success: function(response) {
                        $('#clock-message').text(response);
                    }
                });
            });

            $('#clock-out-btn').click(function() {
                $.ajax({
                    url: 'clock_out.php',
                    type: 'POST',
                    success: function(response) {
                        $('#clock-message').text(response);
                    }
                });
            });

            $('#Working-from-home-btn').click(function() {
                $.ajax({
                    url: 'update_work_location.php',
                    type: 'POST',
                    data: { work_location: 'home' },
                    success: function(response) {
                        $('#clock-message').text(response);
                    }
                });
            });

            $('#Working-from-office-btn').click(function() {
                $.ajax({
                    url: 'update_work_location.php',
                    type: 'POST',
                    data: { work_location: 'office' },
                    success: function(response) {
                        $('#clock-message').text(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
