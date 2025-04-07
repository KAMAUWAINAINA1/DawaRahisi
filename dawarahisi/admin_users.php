<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";  // Your MySQL password
$dbname = "dawa_rahisi"; // Your MySQL database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from the database
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Manage Users - DAWA RAHISI</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body>
    <div id="page-wrapper">

        <!-- Header -->
        <header id="header">
            <h1><a href="index.html">DAWA</a> RAHISI</h1>
            <nav id="nav">
                <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="admin_orders.php" class="active">Manage Orders</a></li>
                    <li><a href="admin_users.php">Manage Users</a></li>
                    <li><a href="admin_generate_reports.php">Generate Reports</a></li>
                    <li><a href="admin_logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Section for Users -->
        <section id="main" class="container">
            <header class="major">
                <h2>Manage Users</h2>
                <p>View and manage registered users.</p>
            </header>

            <div class="box">
                <table border="1">
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    <?php
                    // Display users from the database
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No users found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <ul class="copyright">
                <li>&copy; DAWA RAHISI. All rights reserved.</li>
            </ul>
        </footer>

    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>
<?php
$conn->close();
?>
