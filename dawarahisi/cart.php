<?php
// Start session
session_start();

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

// Get cart from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Initialize products array
$products = [];

if (!empty($cart)) {
    // Use prepared statement to fetch products in the cart
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    
    // Bind parameters
    $types = str_repeat('i', count($cart));
    $stmt->bind_param($types, ...array_keys($cart));
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Shopping Cart - DAWA RAHISI</title>
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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="catalog.php">Catalog</a></li>
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Section for Cart -->
        <section id="main" class="container">
            <header class="major">
                <h2>Your Cart</h2>
                <p>Items you have added to your cart.</p>
            </header>

            <div class="box">
                <?php if (!empty($products)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($products as $product) {
                            $quantity = $cart[$product['id']];
                            $subtotal = $quantity * $product['price'];
                            $total += $subtotal;
                            echo '
                            <tr>
                                <td>' . htmlspecialchars($product['name']) . '</td>
                                <td>' . htmlspecialchars($quantity) . '</td>
                                <td>Ksh' . htmlspecialchars($product['price']) . '</td>
                                <td>Ksh' . htmlspecialchars($subtotal) . '</td>
                                <td><a href="remove_from_cart.php?id=' . htmlspecialchars($product['id']) . '" class="button">Remove</a></td>
                            </tr>
                            ';
                        }
                        ?>
                        <tr>
                            <td colspan="3">Total</td>
                            <td colspan="2">$<?php echo htmlspecialchars($total); ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="checkout.php" class="button special">Proceed to Checkout</a>
                <?php } else { ?>
                    <p>Your cart is empty.</p>
                <?php } ?>
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
