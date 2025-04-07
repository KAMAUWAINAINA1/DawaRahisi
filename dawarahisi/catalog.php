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

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Product Catalog - DAWA RAHISI</title>
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

        <!-- Main Section for Product Catalog -->
        <section id="main" class="container">
            <header class="major">
                <h2>Product Catalog</h2>
                <p>Browse through our selection of healthcare products.</p>
            </header>

            <div class="box">
                <div class="row">
                    <?php
                    // Display products from database
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <div class="col-4 col-12-narrower">
                                <section class="box special">
                                    <span class="image featured"><img src="' . $row['image'] . '" alt="' . $row['name'] . '" /></span>
                                    <h3>' . $row['name'] . '</h3>
                                    <p>Ksh' . $row['price'] . '</p>
                                    <ul class="actions special">
                                        <li><a href="add_to_cart.php?id=' . $row['id'] . '" class="button alt">Add to Cart</a></li>
                                    </ul>
                                </section>
                            </div>
                            ';
                        }
                    } else {
                        echo "<p>No products available.</p>";
                    }
                    ?>
                </div>
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
