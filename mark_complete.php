<?php
session_start();
require_once 'db.php'; // Include file with database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['taskId'])) {
        $taskId = $_POST['taskId']; // Sanitize and validate task ID as needed

        // Update task as completed in the database
        $stmt = $conn->prepare("UPDATE tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        
        if ($stmt->execute()) {
            // Optionally, return success response if needed
            // echo "Task marked as completed!";
            // exit();
        } else {
            echo "Failed to mark task as completed.";
        }
    } else {
        echo "Task ID not provided.";
    }
}
?>
