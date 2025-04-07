<?php
include 'db_connect.php'; // Connect to the database

// Fetch delivery data
$query = "SELECT 
            o.order_number, 
            o.customer_name, 
            o.customer_address 
          FROM orders o 
          WHERE o.order_status = 'Processing'"; // Adjust the status filter as needed

$result = $conn->query($query);
$delivery_data = [];
while ($row = $result->fetch_assoc()) {
    $delivery_data[] = $row;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Delivery Tracking - DAWA RAHISI</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        #delivery-image {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .order-details {
            margin: 20px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .order-details h3 {
            color: #4CAF50;
        }
    </style>
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
                    <li><a href="admin_delivery_tracking.php" class="active">Delivery Tracking</a></li>
                    <li><a href="admin_logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Section -->
        <section id="main" class="container">
            <header class="major">
                <h2>Delivery Tracking</h2>
                <p>Monitor active deliveries visually.</p>
            </header>

            <div class="box">
                <img src="assets/images/delivery.png" id="delivery-image" alt="Delivery Tracking">
            </div>

            <?php if (!empty($delivery_data)): ?>
                <div class="box">
                    <h3>Active Deliveries</h3>
                    <?php foreach ($delivery_data as $delivery): ?>
                        <div class="order-details">
                            <h4>Order Number: <?php echo htmlspecialchars($delivery['order_number']); ?></h4>
                            <p>Customer Name: <?php echo htmlspecialchars($delivery['customer_name']); ?></p>
                            <p>Address: <?php echo htmlspecialchars($delivery['customer_address']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No active deliveries at the moment.</p>
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

<?php
$conn->close(); // Close the database connection
?>
