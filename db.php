<?php
$host = "localhost";
$user = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "blog_db";

// Connect to the MySQL database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
