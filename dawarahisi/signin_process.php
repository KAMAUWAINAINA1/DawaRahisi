<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "root";      // Your MySQL password
$dbname = "dawa_rahisi";  // Your MySQL database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user information in session and redirect to catalog
            $_SESSION['user'] = $user['name'];
            header("Location: catalog.html");
            exit();
        } else {
            // Incorrect password
            $_SESSION['message'] = "Incorrect password.";
            header("Location: signup.html");
            exit();
        }
    } else {
        // No user found with that email
        $_SESSION['message'] = "No account found with that email.";
        header("Location: signup.html");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
