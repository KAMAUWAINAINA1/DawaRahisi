<?php
// Start session
session_start();

// Check if cart session is set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the product ID from the query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id) {
    // Add product to cart (increase quantity if it exists)
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}

// Redirect to cart
header("Location: cart.php");
exit();
?>
