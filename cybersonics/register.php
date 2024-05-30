<?php
// Include database connection
require_once 'includes/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $group_no = $_POST['group_no'];
    $employee_name = $_POST['employee_name']; // New field added

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data
    $sql = "INSERT INTO users (username, password, group_no, Employee_Name) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters and execute query
    if ($stmt->execute([$username, $hashed_password, $group_no, $employee_name])) {
        // Registration successful, redirect to login page
        header("Location: login.php");
        exit; // Stop further execution
    } else {
        // Handle registration failure
        echo "Registration failed. Please try again.";
        // Optionally, display an error message or redirect to registration page
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Registration</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <h2>User Registration</h2>
    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="group_no">Group Number</label>
            <input type="text" id="group_no" name="group_no" required>
        </div>
        <div class="form-group">
            <label for="employee_name">Employee Name</label>
            <input type="text" id="employee_name" name="employee_name" required>
        </div>
        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
