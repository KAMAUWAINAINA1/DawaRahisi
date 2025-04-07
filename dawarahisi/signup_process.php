<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dawa_rahisi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Account created successfully! Please sign in.";
        header("Location: index.html"); // Redirect back to signup page
        exit();
    //} else {
    //   $_SESSION['message'] = "Error: " . $stmt->error;
    //    header("Location: index.html"); // Redirect back to signup page
    //    exit();
    }    

    $stmt->close();
    $conn->close();
}
?>
