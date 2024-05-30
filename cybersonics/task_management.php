<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Task Management</h2>
        <form method="post" action="process_task.php">
            <label for="task_name">Task Name:</label>
            <input type="text" id="task_name" name="task_name" required><br><br>
            
            <label for="task_description">Task Description:</label><br>
            <textarea id="task_description" name="task_description" rows="4" cols="50" required></textarea><br><br>

            <label for="task_rating">Task Rating:</label>
            <select name="task_rating" id="task_rating">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select><br><br>

            <label for="task_difficulty">Task Difficulty:</label>
            <select name="task_difficulty" id="task_difficulty">
                <option value="easy">Easy</option>
                <option value="normal">Normal</option>
                <option value="difficult">Difficult</option>
            </select><br><br>

            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>
