<?php
require_once 'tasks.php';

// No need to start session here, as it's already started in tasks.php

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?> Notes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .task {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .important {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2><?php echo htmlspecialchars($username); ?> Notes</h2>

    <h3>Add Task</h3>
    <form action="add_task.php" method="post">
        <label for="task">Task:</label><br>
        <textarea id="task" name="task" rows="4" cols="50" required></textarea><br><br>

        <label for="priority">Priority:</label>
        <select name="priority" id="priority">
            <option value="normal">Normal</option>
            <option value="important">Important</option>
        </select><br><br>

        <input type="submit" value="Add Task">
    </form>

    <h3>Your Tasks</h3>
    <?php
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $taskClass = ($row['priority'] == 'important') ? 'task important' : 'task';
            $completedText = ($row['completed']) ? 'Completed' : 'Mark Complete';
            ?>
            <div class="<?php echo htmlspecialchars($taskClass); ?>">
                <p><?php echo htmlspecialchars($row['task']); ?></p>
                <button onclick="markComplete(<?php echo htmlspecialchars($row['id']); ?>)"><?php echo htmlspecialchars($completedText); ?></button>
                <button onclick="deleteTask(<?php echo htmlspecialchars($row['id']); ?>)">Delete</button>
            </div>
            <?php
        }
    } else {
        echo '<p>No tasks found.</p>';
    }
    ?>

    <script>
        function markComplete(taskId) {
            if (confirm('Mark this task as complete?')) {
                // Call PHP script to mark task as complete
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'mark_complete.php');
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        location.reload(); // Reload page to update task list
                    }
                };
                xhr.send('taskId=' + taskId);
            }
        }

        function deleteTask(taskId) {
            if (confirm('Delete this task?')) {
                // Call PHP script to delete task
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_task.php');
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        location.reload(); // Reload page to update task list
                    }
                };
                xhr.send('taskId=' + taskId);
            }
        }
    </script>
</body>
</html>
