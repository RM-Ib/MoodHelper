<?php
// db_connect.php

$host = "localhost";    // Database host
$user = "root";         // Database username
$pass = "";             // Database password
$db   = "moodhelperdb"; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>