<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Employee Management System</title>
    <link rel="stylesheet" href="css/dstyles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to the Dashboard</h1>
        </div>
        <div class="content">
            <div class="section tasks">
                <a href="task_summary.php"><button>Manage Tasks</button></a>
            </div>
        </div>
        <div class="footer">
            <div class="clock-actions">
                <button id="clock-in-btn">Clock In</button>
                <div id="clock-in-message"></div> <!-- Message area for displaying clock-in status -->
                <a href="clock_out.php"><button>Clock Out</button></a>
            </div>
            <a href="includes/logout.php" class="btn btn-dark">Logout</a>
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
                        $('#clock-in-message').text(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
