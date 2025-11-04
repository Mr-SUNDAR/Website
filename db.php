<?php
// includes/db.php

$host = "127.0.0.1";
$port = 3307; // your custom MySQL port
$user = "root";
$pass = ""; // change if different
$dbname = "eshop_db";

$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8mb4");
?>
