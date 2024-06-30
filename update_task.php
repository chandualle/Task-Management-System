<?php
require_once 'db.php';
require_once 'tasks.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['taskId']) && isset($_POST['task']) && isset($_POST['priority'])) {
        $taskId = $_POST['taskId'];
        $task = $_POST['task'];
        $priority = $_POST['priority'];

        $stmt = $conn->prepare("UPDATE tasks SET task=?, priority=? WHERE id=?");
        $stmt->bind_param("ssi", $task, $priority, $taskId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header('Location: notes.php');
            exit();
        } else {
            echo "Failed to update task";
        }
    }
}
