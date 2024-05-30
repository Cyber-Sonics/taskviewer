<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Process task addition logic here (e.g., update database)
// Redirect back to dashboard after processing
header('Location: dashboard.php');
exit;
?>
