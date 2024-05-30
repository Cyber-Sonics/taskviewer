<?php
// Include database connection
require_once 'includes/config.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch tasks allocated to the user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define reasons array for each difficulty level
$reasons = [
    'easy' => [
        'Skills and knowledge',
        'Clear instructions',
        'Adequate time',
        'Motivation',
        'Familiarity and knowledge'
    ],
    'normal' => [
        'Experience',
        'Extra Effort',
        'Lack of novelty',
        'Adaptability',
        'Expectations'
    ],
    'difficult' => [
        'Time Management',
        'Conflict within the group',
        'Complexity',
        'External Distractions',
        'Unrealistic deadlines or workplace challenges'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Task Summary</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <h2>Task Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Description</th>
                <th>Completion Status</th>
                <th>Difficulty Level</th>
                <th>Reasons</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo $task['task_name']; ?></td>
                    <td><?php echo $task['task_description']; ?></td>
                    <td>
                        <select name="completion_status" id="completion_status_<?php echo $task['id']; ?>">
                            <option value="completed" <?php echo ($task['completion_status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="pending" <?php echo ($task['completion_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </td>
                    <td>
                        <select name="difficulty_level" id="difficulty_level_<?php echo $task['id']; ?>" onchange="updateReasons(<?php echo $task['id']; ?>)">
                            <option value="easy" <?php echo ($task['difficulty_level'] == 'easy') ? 'selected' : ''; ?>>Easy</option>
                            <option value="normal" <?php echo ($task['difficulty_level'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
                            <option value="difficult" <?php echo ($task['difficulty_level'] == 'difficult') ? 'selected' : ''; ?>>Difficult</option>
                        </select>
                    </td>
                    <td>
                        <select name="reasons" id="reasons_<?php echo $task['id']; ?>">
                           
                        </select>
                    </td>
                    <td>
                        <button onclick="updateTask(<?php echo $task['id']; ?>)">Update</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function updateReasons(taskId) {
    var difficultyLevel = document.getElementById('difficulty_level_' + taskId).value;
    var reasonsDropdown = document.getElementById('reasons_' + taskId);
    var reasons = <?php echo json_encode($reasons); ?>;
    
    // Clear previous options
    reasonsDropdown.innerHTML = '';
    
    // Populate options based on selected difficulty level
    reasons[difficultyLevel].forEach(function(reason) {
        var option = document.createElement('option');
        option.text = reason;
        reasonsDropdown.add(option);
    });
}

function updateTask(taskId) {
    var completion_status = document.getElementById('completion_status_' + taskId).value;
    var difficulty_level = document.getElementById('difficulty_level_' + taskId).value;
    var reasons = document.getElementById('reasons_' + taskId).value;

    // AJAX request to update task
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_task.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Task updated successfully, show message
            alert(xhr.responseText);
        }
    };
    xhr.send('taskId=' + taskId + '&completion_status=' + completion_status + '&difficulty_level=' + difficulty_level + '&reasons=' + reasons);
}
</script>

</body>
</html>
