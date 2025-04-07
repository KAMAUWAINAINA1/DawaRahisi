<?php
session_start();

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

// Collect form data from the checkout page
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];

// Ensure the cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. Please add items to your cart before proceeding to checkout.</p>";
    exit;
}

// Retrieve cart contents from the session
$cart = $_SESSION['cart'];

// Calculate total order amount
$total_amount = 0;
foreach ($cart as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $total_amount += $price * $quantity;
    $stmt->close();
}

// Generate a unique order number
$order_number = rand(100000, 999999);

// Insert order into the `orders` table
$stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, customer_email, customer_address, customer_phone, order_total) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('sssssd', $order_number, $name, $email, $address, $phone, $total_amount);
$stmt->execute();
$order_id = $stmt->insert_id;  // Get the ID of the inserted order
$stmt->close();

// Insert each product in the cart into the `order_items` table
foreach ($cart as $product_id => $quantity) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    // Fetch price again for each product
    $stmt_price = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt_price->bind_param('i', $product_id);
    $stmt_price->execute();
    $stmt_price->bind_result($price);
    $stmt_price->fetch();
    $stmt_price->close();
    
    $stmt->bind_param('iiid', $order_id, $product_id, $quantity, $price);
    $stmt->execute();
    $stmt->close();
}

// Clear the cart
unset($_SESSION['cart']);

// Redirect to the order tracking page with the generated order number
$_SESSION['order_number'] = $order_number;
header("Location: order_tracking.php?order_number=$order_number");
exit;
?>
