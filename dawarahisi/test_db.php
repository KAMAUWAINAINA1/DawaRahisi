<?php
$servername = "localhost";
$username = "root";
$password = "Windsor911!"; // Replace with correct password

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>

