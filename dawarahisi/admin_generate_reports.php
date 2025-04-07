<?php
// Include necessary files
include 'db_connect.php';

// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// If form is submitted, fetch the data
$reports = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_date = $_POST['from_date'] ?? '';
    $to_date = $_POST['to_date'] ?? '';
    $filter = "";
    
    if ($from_date && $to_date) {
        $filter = "WHERE created_at >= '$from_date' AND created_at <= '$to_date'";
    }
    
    // Fetch user metrics
    $user_query = "SELECT COUNT(*) AS total_users FROM users";
    $user_result = $conn->query($user_query);
    $reports['user_metrics'] = $user_result->fetch_assoc();

    // Fetch order metrics
    $order_query = "SELECT COUNT(*) AS total_orders,
                           SUM(CASE WHEN order_status = 'Processing' THEN 1 ELSE 0 END) AS processing_orders,
                           SUM(CASE WHEN order_status = 'Completed' THEN 1 ELSE 0 END) AS completed_orders,
                           SUM(order_total) AS total_revenue
                    FROM orders $filter";
    $order_result = $conn->query($order_query);
    $reports['order_metrics'] = $order_result->fetch_assoc();

    // Fetch product metrics (optional)
    $product_query = "SELECT name, SUM(quantity) AS total_quantity_sold
                      FROM order_items
                      JOIN products ON order_items.product_id = products.id
                      GROUP BY product_id";
    $product_result = $conn->query($product_query);
    $product_metrics = [];
    while ($row = $product_result->fetch_assoc()) {
        $product_metrics[] = $row;
    }
    $reports['product_metrics'] = $product_metrics;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Admin Generate Reports - DAWA RAHISI</title>
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
                    <li><a href="admin_orders.php">Manage Orders</a></li>
                    <li><a href="admin_users.php">Manage Users</a></li>
                    <li><a href="admin_generate_reports.php" class="active">Generate Reports</a></li>
                    <li><a href="admin_logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Section -->
        <section id="main" class="container">
            <header class="major">
                <h2>Generate Reports</h2>
                <p>Select a date range to generate reports.</p>
            </header>

            <div class="box">
                <form method="POST">
                    <div class="row gtr-uniform">
                        <div class="col-6 col-12-mobilep">
                            <label for="from_date">From Date:</label>
                            <input type="date" name="from_date" required>
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <label for="to_date">To Date:</label>
                            <input type="date" name="to_date" required>
                        </div>
                        <div class="col-12">
                            <ul class="actions special">
                                <li><button type="submit" name="view_report" class="button primary">View Report</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($reports)): ?>
                <div class="box">
                    <h3>Report Results</h3>
                    <div>
                        <h4>User Metrics</h4>
                        <ul>
                            <li>Total Users: <?php echo $reports['user_metrics']['total_users']; ?></li>
                        </ul>
                    </div>

                    <div>
                        <h4>Order Metrics</h4>
                        <ul>
                            <li>Total Orders: <?php echo $reports['order_metrics']['total_orders']; ?></li>
                            <li>Processing Orders: <?php echo $reports['order_metrics']['processing_orders']; ?></li>
                            <li>Completed Orders: <?php echo $reports['order_metrics']['completed_orders']; ?></li>
                            <li>Total Revenue: <?php echo $reports['order_metrics']['total_revenue']; ?></li>
                        </ul>
                    </div>

                    <div>
                        <h4>Product Metrics</h4>
                        <ul>
                            <?php foreach ($reports['product_metrics'] as $product): ?>
                                <li><?php echo $product['name']; ?>: <?php echo $product['total_quantity_sold']; ?> sold</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Footer -->
        <footer id="footer">
            <ul class="copyright">
                <li>&copy; DAWA RAHISI. All rights reserved.</li>
            </ul>
        </footer>
    </div>
</body>
</html>
