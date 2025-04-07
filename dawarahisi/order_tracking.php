<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";  
$dbname = "dawa_rahisi"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the order number from the URL or session
$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : null;

if ($order_number) {
    // Fetch order details from the database
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->bind_param('s', $order_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if ($order) {
        // Fetch ordered products from the database
        $stmt = $conn->prepare("SELECT p.name, oi.quantity, oi.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt->bind_param('i', $order['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Simulated order status steps
        $status_steps = ['Order Placed', 'Processing', 'Dispatched', 'Out for Delivery', 'Delivered']; 
        $current_step = 2;  // Simulate the current step of the order status

        // Display the order details
        $customer = [
            'name' => $order['customer_name'],
            'address' => $order['customer_address'],
            'phone' => $order['customer_phone'],
        ];
    } else {
        echo "<p>Order not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid Order Number or no order details available.</p>";
    exit;
}
$conn->close();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Order Tracking - DAWA RAHISI</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
    <style>
        .progress-bar {
            display: flex;
            justify-content: space-between;
            list-style: none;
            counter-reset: step;
            padding: 0;
            margin: 0;
        }
        .progress-bar li {
            position: relative;
            flex: 1;
            text-align: center;
            color: grey;
        }
        .progress-bar li:before {
            content: counter(step);
            counter-increment: step;
            display: block;
            margin: 0 auto 10px;
            background: grey;
            color: white;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
        }
        .progress-bar li:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background: grey;
            top: 15px;
            left: -50%;
            z-index: -1;
        }
        .progress-bar li:first-child:after {
            content: none;
        }
        .progress-bar li.active {
            color: green;
        }
        .progress-bar li.active:before {
            background: green;
        }
        .progress-bar li.active + li:after {
            background: green;
        }
        #map {
            height: 400px;
            width: 100%;
            margin-top: 20px;
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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="catalog.php">Catalog</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Section for Order Tracking -->
        <section id="main" class="container">
            <header class="major">
                <h2>Order Tracking</h2>
                <p>Track your order status and delivery details.</p>
            </header>

            <div class="box">
                <h3>Order Details</h3>
                <p>Order Number: <?php echo htmlspecialchars($order_number); ?></p>
                <p>Name: <?php echo htmlspecialchars($customer['name']); ?></p>
                <p>Address: <?php echo htmlspecialchars($customer['address']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($customer['phone']); ?></p>
                <p>Status: <?php echo htmlspecialchars($status_steps[$current_step - 1]); ?></p>

                <!-- Progress Bar -->
                <h4>Order Status:</h4>
                <ul class="progress-bar">
                    <?php
                    foreach ($status_steps as $index => $step) {
                        $active = ($index + 1 <= $current_step) ? 'active' : '';
                        echo "<li class=\"$active\">$step</li>";
                    }
                    ?>
                </ul>

                <!-- Products Ordered -->
                <h4>Products Ordered:</h4>
                <ul>
                    <?php
                    if (!empty($items)) {
                        foreach ($items as $item) {
                            echo "<li>Product: " . htmlspecialchars($item['name']) . " | Quantity: " . htmlspecialchars($item['quantity']) . " | Price: Ksh" . htmlspecialchars($item['price']) . "</li>";
                        }
                    } else {
                        echo "<p>No products found in the order.</p>";
                    }
                    ?>
                </ul>

                <!-- Map Section -->
                <h4>Track Your Delivery:</h4>
                <div id="map"></div>

                <script>
                    // Initialize Google Maps with a default location (Example: Nairobi)
                    function initMap() {
                        var location = {lat: -1.286389, lng: 36.817223};  // Coordinates for Nairobi, Kenya
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            center: location
                        });
                        var marker = new google.maps.Marker({
                            position: location,
                            map: map
                        });
                    }

                    // Call the function to initialize the map
                    initMap();
                </script>
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
