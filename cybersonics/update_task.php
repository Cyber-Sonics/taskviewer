<?php
// Include database connection
require_once 'includes/config.php';

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get task ID and other parameters from POST data
    $taskId = $_POST['taskId'];
    $completion_status = $_POST['completion_status'];
    $difficulty_level = $_POST['difficulty_level'];
    $reasons = $_POST['reasons'];

    // Update task in the database
    $sql = "UPDATE tasks SET completion_status = ?, difficulty_level = ?, reasons = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$completion_status, $difficulty_level, $reasons, $taskId]);

    // Check if update was successful
    if ($stmt->rowCount() > 0) {
        // Task updated successfully
        echo "Task updated successfully";
    } else {
        // Task update failed
        echo "Failed to update task";
    }
} else {
    // If request method is not POST, return an error
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
