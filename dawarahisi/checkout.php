<?php
// Start session
session_start();

// Ensure there are items in the cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: catalog.php");
    exit();
}

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
$cart = $_SESSION['cart'];

// Fetch products in the cart
$product_ids = implode(",", array_keys($cart));
$products = [];

if (!empty($product_ids)) {
    $sql = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Checkout - DAWA RAHISI</title>
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

        <!-- Main Section for Checkout -->
        <section id="main" class="container">
            <header class="major">
                <h2>Checkout</h2>
                <p>Review your order and provide details for delivery and payment.</p>
            </header>

            <div class="box">
                <!-- Display Cart Items -->
                <h3>Your Order</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
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
                            </tr>
                            ';
                        }
                        ?>
                        <tr>
                            <td colspan="3">Total</td>
                            <td>Ksh<?php echo htmlspecialchars($total); ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Checkout Form -->
                <h3>Delivery Information</h3>
                <form method="post" action="process_checkout.php">
                    <div class="row gtr-50 gtr-uniform">
                        <div class="col-6 col-12-mobilep">
                            <input type="text" name="name" id="name" placeholder="Full Name" required />
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <input type="email" name="email" id="email" placeholder="Email Address" required />
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <input type="text" name="address" id="address" placeholder="Delivery Address" required />
                        </div>
                        <div class="col-6 col-12-mobilep">
                            <input type="text" name="phone" id="phone" placeholder="Phone Number" required />
                        </div>
                        
                        <!-- Hidden field to store total price -->
                        <input type="hidden" name="total_price" value="<?php echo $total; ?>" />

                        <div class="col-12">
                            <ul class="actions special">
                                <li><input type="submit" value="Complete Purchase" /></li>
                            </ul>
                        </div>
                    </div>
                </form>
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
