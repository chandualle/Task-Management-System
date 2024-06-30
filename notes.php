<?php
require_once 'tasks.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

function capitalizeFirstLetter($string) {
    $string = strtolower($string);
    return ucfirst($string);
}

$username = capitalizeFirstLetter($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?> Notes</title>
    <link rel="stylesheet" href="notes_styles.css">
</head>
<body>
<div class="main-container">
    <div class="notes-container">
        <h2><?php echo htmlspecialchars($username); ?> Notes</h2>

        <form action="add_task.php" method="post">
            <label for="task">Task:</label>
            <textarea id="task" name="task" rows="4" cols="50" required></textarea>

            <label for="priority">Priority:</label>
            <select name="priority" id="priority">
                <option value="normal">Normal</option>
                <option value="important">Important</option>
            </select>

            <input type="submit" value="Add Task">
        </form>
    </div>

    <div class="tasks-container">
        <h3>Your Tasks</h3>
            <?php
            $stmt = $conn->prepare("SELECT * FROM tasks WHERE username = ? ORDER BY created_at DESC");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $taskClass = ($row['priority'] == 'important') ? 'task important' : 'task';
                    ?>
                    <div class="<?php echo htmlspecialchars($taskClass); ?>" id="task-<?php echo htmlspecialchars($row['id']); ?>">
                        <p><?php echo htmlspecialchars($row['task']); ?></p>
                        <button type="button" onclick="editTask(<?php echo htmlspecialchars($row['id']); ?>)">Edit</button>
                        <button type="button" onclick="deleteTask(<?php echo htmlspecialchars($row['id']); ?>)">Delete</button>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No tasks found.</p>';
            }
            ?>
    </div>
</div>

    <script>
        function editTask(taskId) {
            var taskDiv = document.getElementById('task-' + taskId);
            var taskText = taskDiv.querySelector('p').innerText;
            var priority = taskDiv.classList.contains('important') ? 'important' : 'normal';

            taskDiv.innerHTML = `
                <form action="update_task.php" method="post">
                    <p>
                        <textarea name="task" rows="2" cols="50">${taskText}</textarea>
                    </p>
                    <select name="priority">
                        <option value="normal" ${priority == 'normal' ? 'selected' : ''}>Normal</option>
                        <option value="important" ${priority == 'important' ? 'selected' : ''}>Important</option>
                    </select>
                    <input type="hidden" name="taskId" value="${taskId}">
                    <input type="submit" value="Update Task">
                    <button type="button" onclick="cancelEdit(${taskId}, '${taskText}', '${priority}')">Cancel</button>
                </form>
            `;
        }

        function cancelEdit(taskId, taskText, priority) {
            var taskDiv = document.getElementById('task-' + taskId);

            taskDiv.innerHTML = `
                <p>${taskText}</p>
                <button type="button" onclick="editTask(${taskId})">Edit</button>
                <button type="button" onclick="deleteTask(${taskId})">Delete</button>
            `;
            taskDiv.className = priority == 'important' ? 'task important' : 'task';
        }

        function deleteTask(taskId) {
            if (confirm('Delete this task?')) {
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
