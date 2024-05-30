<?php
// Include database connection
require_once 'includes/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to check if the username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verify password if user exists
    if ($user && password_verify($password, $user['password'])) {
        // Start session and store user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect to dashboard or another page
        header("Location: dashboard.php");
        exit;
    } else {
        // Login failed, redirect back to login page with error message
        header("Location: login.php?error=InvalidCredentials");
        exit;
    }
}
?>
