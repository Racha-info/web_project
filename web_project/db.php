<?php
// db.php

$host = 'localhost';
$db   = 'web_project';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Create mysqli connection 
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$conn->set_charset($charset);
?>
