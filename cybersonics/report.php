<?php
// Include database connection
require_once 'includes/config.php';

// Fetch data from the database
$sql = "SELECT t.user_id, u.employee_name, u.group_no, t.task_name, t.task_description, t.completion_status, t.difficulty_level, t.reasons 
        FROM tasks t 
        INNER JOIN users u ON t.user_id = u.id";
$stmt = $pdo->query($sql);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Report</title>
<link rel="stylesheet" href="css/rstyles.css">
</head>
<body>

<div class="container">
    <h2>Admin Report</h2>
    <div class="container">
    <div class="filters">
        <button onclick="filterByCompletion('completed')">Completed</button>
        <button onclick="filterByCompletion('pending')">Pending</button>
        <button onclick="filterByDifficulty('easy')">Easy</button>
        <button onclick="filterByDifficulty('normal')">Normal</button>
        <button onclick="filterByDifficulty('difficult')">Difficult</button>
        <button onclick="resetFilters()">Reset Filters</button>
    </div>
    <table id="taskTable">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Employee Name</th>
                <th>Group No.</th>
                <th>Task Name</th>
                <th>Description</th>
                <th>Completion Status</th>
                <th>Difficulty Level</th>
                <th>Reasons</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr class="<?php echo ($task['completion_status'] === 'completed') ? 'completed' : 'pending'; ?>"
            data-completion-status="<?php echo $task['completion_status']; ?>"
            data-difficulty-level="<?php echo $task['difficulty_level']; ?>">
            <td><?php echo $task['user_id']; ?></td>
            <td><?php echo $task['employee_name']; ?></td>
            <td><?php echo $task['group_no']; ?></td>
            <td><?php echo $task['task_name']; ?></td>
            <td><?php echo $task['task_description']; ?></td>
            <td><?php echo $task['completion_status']; ?></td>
            <td><?php echo $task['difficulty_level']; ?></td>
            <td><?php echo $task['reasons']; ?></td>
        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function filterByCompletion(status) {
    var rows = document.querySelectorAll('#taskTable tbody tr');
    rows.forEach(function(row) {
        if (row.dataset.completionStatus === status || status === 'all') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterByDifficulty(level) {
    var rows = document.querySelectorAll('#taskTable tbody tr');
    rows.forEach(function(row) {
        if (row.dataset.difficultyLevel === level || level === 'all') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

function resetFilters() {
    var rows = document.querySelectorAll('#taskTable tbody tr');
    rows.forEach(function(row) {
        row.style.display = 'table-row';
    });
}

// Initially display all rows
resetFilters();
</script>

</body>
</html>