<?php
$server = "server_name";
$username = "root";
$password = "";
$database = "notes_db";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
