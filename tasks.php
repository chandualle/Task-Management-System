<?php
session_start(); // Move session_start() to the beginning of tasks.php

$server = "localhost";
$username = "root";
$password = "";
$database = "notes_db";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addTask($username, $task, $priority) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO tasks (username, task, priority) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $task, $priority);
    return $stmt->execute();
}

function deleteTask($taskId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    return $stmt->execute();
}
?>
