<?php
session_start();
include 'config/db.php'; // Database connection

// Fetch all products (You may want to change the query to match your product database)
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Available Products</h2>
    <div class="product-list">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="product">
                <h3><?php echo $product['product_name']; ?></h3>
                <p>Price: $<?php echo $product['price']; ?></p>
                <a href="cart.php?add=<?php echo $product['id']; ?>&quantity=1">Add to Cart</a>
            </div>
        <?php endwhile; ?>
    </div>

    <br><br>
    <a href="cart.php">View Cart</a>
</body>
</html>
