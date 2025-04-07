<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";  // Replace with your actual MySQL password
$dbname = "dawa_rahisi"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define admin credentials
$admin_username = "admin";
$admin_password = password_hash("admin123", PASSWORD_DEFAULT); // Hash the password

// Insert the admin user into the database
$sql = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $admin_username, $admin_password);

if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
