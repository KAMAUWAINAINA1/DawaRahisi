<?php
// Database configuration
$host = 'localhost';
$db_name = 'dawa_rahisi'; // Replace with your actual database name
$username = 'root'; // Default username for local servers, replace if different
$password = 'root'; // Default password is empty for local servers, replace if different

// Create a database connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
