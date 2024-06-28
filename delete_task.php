<?php
session_start();
require_once 'db.php'; // Include file with database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['taskId'])) {
        $taskId = $_POST['taskId']; // Sanitize and validate task ID as needed

        // Delete task from the database
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        
        if ($stmt->execute()) {
            // Optionally, return success response if needed
            // echo "Task deleted!";
            // exit();
        } else {
            echo "Failed to delete task.";
        }
    } else {
        echo "Task ID not provided.";
    }
}
?>
