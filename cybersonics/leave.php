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

// SQL query to fetch employee leave details
$sql = "SELECT username, Employee_Name, group_no FROM users WHERE id=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

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

// Fetch leave details
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <link rel="stylesheet" href="css/dash1.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Leave Management for <?php echo htmlspecialchars($username); ?></h1>
        </div>
        
        <div class="employee-details">
            <p><strong>Employee Name:</strong> <?php echo htmlspecialchars($employee_name); ?></p>
            <p><strong>Group Number:</strong> <?php echo htmlspecialchars($group_no); ?></p>
            <p><strong>Total Annual Leave Days Taken:</strong> <?php echo htmlspecialchars($total_annual_leave_taken); ?></p>
            <p><strong>Total Annual Leave Days Remaining:</strong> <?php echo htmlspecialchars($total_annual_leave_remaining); ?></p>
        </div>

        <!-- Leave application section -->
        <div class="section leave-application">
            <h2>Apply for Leave</h2>
            <form action="process_leave.php" method="POST">
                <div class="form-group">
                    <label for="leave_days">Number of Leave Days:</label>
                    <input type="number" id="leave_days" name="leave_days" required>
                </div>
                <div class="form-group">
                    <label for="leave_type">Type of Leave:</label>
                    <select id="leave_type" name="leave_type" required>
                        <option value="annual">Annual Leave</option>
                        <option value="sick">Sick Leave</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply</button>
            </form>
        </div>

        <div class="footer">
            <a href="dashboard.php" class="btn btn-dark">Back to Dashboard</a>
            <a href="logout.php" class="btn btn-dark">Logout</a>
        </div>
    </div>
</body>
</html>
