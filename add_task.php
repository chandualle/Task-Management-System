<?php
session_start();
require_once 'db.php'; // Include file with database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task']) && isset($_POST['priority'])) {
        $username = $_SESSION['username']; // Get username from session
        $task = $_POST['task']; // Sanitize and validate task input as needed
        $priority = $_POST['priority']; // Validate priority input

        // Insert task into database
        $stmt = $conn->prepare("INSERT INTO tasks (username, task, priority) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $task, $priority);
        
        if ($stmt->execute()) {
            echo "Task added successfully!";
            // Optionally, redirect or reload the page after adding task
            // header('Location: notes.php');
            // exit();
        } else {
            echo "Failed to add task.";
        }
    } else {
        echo "Incomplete form submission.";
    }
}
?>
