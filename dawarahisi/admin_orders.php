<?php
include 'db_connect.php'; // Connect to the database

// Fetch orders and their items
$query = "SELECT 
            o.order_number, 
            o.customer_name, 
            o.customer_email, 
            o.customer_address, 
            o.customer_phone, 
            o.order_status, 
            o.order_total, 
            o.created_at, 
            GROUP_CONCAT(CONCAT(p.name, ' (x', oi.quantity, ')') SEPARATOR ', ') AS items 
          FROM orders o
          LEFT JOIN order_items oi ON o.id = oi.order_id
          LEFT JOIN products p ON oi.product_id = p.id
          GROUP BY o.id";

$result = $conn->query($query);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Manage Orders - DAWA RAHISI</title>
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

        <!-- Main Section -->
        <section id="main" class="container">
            <header class="major">
                <h2>Manage Orders</h2>
                <p>View and manage all customer orders.</p>
            </header>

            <div class="box">
                <table>
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['order_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['customer_email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['customer_address']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['customer_phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['order_status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['order_total']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['items']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
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
$conn->close(); // Close the database connection
?>
