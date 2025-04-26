<?php
session_start();
include('../config/db.php');

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_GET['add'])) {
    $productId = $_GET['add'];
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }
    header("Location: cart.php");
    exit;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    unset($_SESSION['cart'][$productId]);
    header("Location: cart.php");
    exit;
}

// Fetch product details for items in the cart
$cart_items = [];
$total_price = 0;

foreach ($_SESSION['cart'] as $productId => $quantity) {
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $cart_items[] = [
        'id' => $product['id'],
        'name' => $product['product_name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'total' => $product['price'] * $quantity,
        'image_url' => $product['image_url']
    ];
    $total_price += $product['price'] * $quantity;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/cart.css">
    <script>
        function updateCart(productId, change) {
            const quantitySpan = document.getElementById(`quantity-${productId}`);
            const totalSpan = document.getElementById(`total-${productId}`);
            const totalPriceSpan = document.getElementById('total-price');

            // Retrieve the price from the data-price attribute
            const price = parseFloat(totalSpan.getAttribute('data-price'));

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
                        // Update the quantity and total for the product
                        quantitySpan.textContent = data.quantity;
                        totalSpan.textContent = `₹${(data.quantity * price).toFixed(2)}`;
                    } else {
                        // Remove the product row if quantity is 0
                        document.getElementById(`product-${productId}`).remove();
                    }
                    // Update the total price
                    totalPriceSpan.textContent = `₹${data.total_price.toFixed(2)}`;
                } else {
                    alert('Failed to update cart.');
                }
            });
        }

        function removeFromCart(productId) {
            // Send AJAX request to remove the product from the cart
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'remove', product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the product row
                    document.getElementById(`product-${productId}`).remove();
                    // Update the total price
                    const totalPriceSpan = document.getElementById('total-price');
                    totalPriceSpan.textContent = `₹${data.total_price.toFixed(2)}`;
                } else {
                    alert('Failed to remove product from cart.');
                }
            });
        }
    </script>
</head>
<body>
    <nav class="breadcrumb">
        <a href="shop.php">Shop</a> > <span>Cart</span>
    </nav>
    <h1>Your Cart</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
            <?php
            $query = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            ?>
            <tr id="product-<?php echo $product_id; ?>">
                <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" style="width: 50px; height: auto;"></td>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td>
                    <button onclick="updateCart(<?php echo $product_id; ?>, -1)">-</button>
                    <span id="quantity-<?php echo $product_id; ?>"><?php echo $quantity; ?></span>
                    <button onclick="updateCart(<?php echo $product_id; ?>, 1)">+</button>
                </td>
                <td>₹<?php echo number_format($product['price'], 2); ?></td>
                <td id="total-<?php echo $product_id; ?>" data-price="<?php echo $product['price']; ?>">
                    ₹<?php echo number_format($product['price'] * $quantity, 2); ?>
                </td>
                <td>
                    <button onclick="removeFromCart(<?php echo $product_id; ?>)">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><strong>Total: ₹<span id="total-price"><?php echo number_format($total_price, 2); ?></span></strong></p>
    <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
</body>
</html>
