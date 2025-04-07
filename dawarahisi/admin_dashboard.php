<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Admin Dashboard - DAWA RAHISI</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        /* Add spacing between the quick links */
        .quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Adjust the spacing as needed */
            justify-content: center;
            margin-top: 20px;
        }
        .quick-links a {
            text-align: center;
            display: block;
            padding: 10px 20px;
        }
    </style>
</head>
<body class="landing is-preload">
    <div id="page-wrapper">

        <!-- Header -->
        <header id="header">
            <h1><a href="index.html">DAWA</a> RAHISI - Admin Dashboard</h1>
            <nav id="nav">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="admin_login.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Banner -->
        <section id="banner">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Manage the platform efficiently and effectively.</p>
        </section>

        <!-- Main -->
        <section id="main" class="container">
            <section class="box special">
                <header class="major">
                    <h2>Quick Links</h2>
                </header>
                <div class="quick-links">
                    <a href="admin_generate_reports.php" class="button primary">Generate Reports</a>
                    <a href="admin_orders.php" class="button">Manage Orders</a>
                    <a href="admin_users.php" class="button">Manage Users</a>
                    <a href="admin_delivery_tracking.php" class="button">Delivery Tracking</a>
                </div>
            </section>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <p>&copy; DAWA RAHISI. All rights reserved.</p>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.dropotron.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
