<?php
// Database configuration for Madang Car Rental Services
$host = "localhost";
$username = "root";  // Change to your database username
$password = "";      // Change to your database password
$database = "madang_car_rental";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Uncomment for debugging
// echo "Database connected successfully";
?>

