<?php
include('../config/db.php');
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: shop.php"); // Redirect to shop if no valid ID is provided
    exit;
}

$product_id = intval($_GET['id']);

// Fetch product details from the database
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Product not found.</p>";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-image {
            text-align: center;
        }
        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-details {
            margin-top: 20px;
        }
        .product-details h1 {
            font-size: 24px;
            color: #333;
        }
        .product-details p {
            font-size: 16px;
            color: #666;
            margin: 10px 0;
        }
        .product-details .price {
            font-size: 20px;
            color: #007bff;
            font-weight: bold;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .actions a {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
        .actions button {
            margin: 5px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .actions button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function addToCart(productId) {
            const actionsDiv = document.querySelector('.actions');

            // Send AJAX request to add the product to the cart
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'add', product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Replace "Add to Cart" button with quantity controls
                    actionsDiv.innerHTML = `
                        <button onclick="updateCart(${productId}, -1)">-</button>
                        <span id="quantity-${productId}">${data.quantity}</span>
                        <button onclick="updateCart(${productId}, 1)">+</button>
                        <a href="shop.php">Back to Shop</a>
                    `;
                } else {
                    alert('Failed to add product to cart.');
                }
            });
        }

        function updateCart(productId, change) {
            const quantitySpan = document.getElementById(`quantity-${productId}`);
            const actionsDiv = document.querySelector('.actions');

            // Send AJAX request to update the product quantity in the cart
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update', product_id: productId, change: change })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.quantity > 0) {
                        // Update the quantity displayed
                        quantitySpan.textContent = data.quantity;
                    } else {
                        // If quantity is 0, replace quantity controls with "Add to Cart" button
                        actionsDiv.innerHTML = `
                            <button class="add-to-cart-button" onclick="addToCart(${productId})">Add to Cart</button>
                            <a href="shop.php">Back to Shop</a>
                        `;
                    }
                } else {
                    alert('Failed to update cart.');
                }
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image">
        </div>
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price">Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
            <p>Quantity Available: <?php echo htmlspecialchars($product['quantity']); ?></p>
            <p>Expiry Date: <?php echo htmlspecialchars($product['expiry_date']); ?></p>
        </div>
        <div class="actions">
            <?php if (isset($_SESSION['cart'][$product['id']])): ?>
                <!-- If product is already in the cart, show quantity controls -->
                <button onclick="updateCart(<?php echo $product['id']; ?>, -1)">-</button>
                <span id="quantity-<?php echo $product['id']; ?>"><?php echo $_SESSION['cart'][$product['id']]; ?></span>
                <button onclick="updateCart(<?php echo $product['id']; ?>, 1)">+</button>
            <?php else: ?>
                <!-- If product is not in the cart, show "Add to Cart" button -->
                <button class="add-to-cart-button" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
            <?php endif; ?>
            <a href="shop.php">Back to Shop</a>
        </div>
    </div>
</body>
</html>