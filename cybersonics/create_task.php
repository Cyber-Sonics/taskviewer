<?php
// Include database connection
require_once 'includes/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $deadline = $_POST['deadline'];
    $group_no = $_POST['group_no'];

    try {
        // SQL query to insert task into tasks table
        $sql = "INSERT INTO tasks (user_id, task_name, task_description, deadline, created_at) 
                SELECT u.id, ?, ?, ?, NOW()
                FROM users u
                WHERE u.group_no = ?";
        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute query
        if ($stmt->execute([$task_name, $task_description, $deadline, $group_no])) {
            // Task created successfully
            echo "Task created successfully.";
        } else {
            // Handle error
            echo "Error: Unable to create task.";
        }
    } catch (PDOException $e) {
        // Display error message
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Adjust the path to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Create Task</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input type="text" id="task_name" name="task_name" required>
            </div>
            <div class="form-group">
                <label for="task_description">Task Description</label>
                <textarea id="task_description" name="task_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" id="deadline" name="deadline" required>
            </div>
            <div class="form-group">
                <label for="group_no">Group Number</label>
                <input type="number" id="group_no" name="group_no" required>
            </div>
            <button type="submit">Create Task</button>
        </form>
    </div>
</body>
</html>
