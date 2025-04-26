<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart is Empty</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .empty-cart-container {
                text-align: center;
                background-color: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .empty-cart-container h1 {
                font-size: 24px;
                color: #333;
            }
            .empty-cart-container p {
                font-size: 16px;
                color: #666;
                margin: 10px 0;
            }
            .empty-cart-container a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-size: 14px;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }
            .empty-cart-container a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="empty-cart-container">
            <h1>Your Cart is Empty</h1>
            <p>It looks like you haven't added anything to your cart yet.</p>
            <a href="../index.php">Go Back to Shopping</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Calculate the total price and fetch product details
$total_price = 0;
$cart_items = [];
include '../config/db.php'; // Ensure db.php is included

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $query = "SELECT product_name, price, image_url, description, quantity FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product['quantity'] < $quantity) {
        echo "<p>Insufficient stock for " . htmlspecialchars($product['product_name']) . ". Please adjust your cart.</p>";
        exit;
    }

    $cart_items[] = [
        'id' => $product_id,
        'name' => $product['product_name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'total' => $product['price'] * $quantity,
        'image_url' => $product['image_url'],
        'description' => $product['description']
    ];
    $total_price += $product['price'] * $quantity;
}

// Process the checkout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['prescription_code']) && $_POST['prescription_code'] !== "VALID_CODE") {
        echo "<p>Invalid prescription code. Please check and try again.</p>";
    } else {
        // Insert the order into the `orders` table
        $query = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("id", $_SESSION['user_id'], $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the ID of the newly created order

        // Insert the order items into the `order_items` table
        foreach ($cart_items as $item) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();

            // Reduce the stock of the product in the `products` table
            $query = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $item['quantity'], $item['id']);
            $stmt->execute();
        }

        // Clear the cart after checkout
        unset($_SESSION['cart']);

        // Generate Invoice
        $invoice = "<h2>Invoice</h2>";
        $invoice .= "<p><strong>Date:</strong> " . date("Y-m-d H:i:s") . "</p>";
        $invoice .= "<table border='1' cellpadding='10' cellspacing='0'>";
        $invoice .= "<tr>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                     </tr>";

        foreach ($cart_items as $item) {
            $invoice .= "<tr>
                            <td>" . htmlspecialchars($item['name']) . "</td>
                            <td>" . htmlspecialchars($item['description']) . "</td>
                            <td>" . htmlspecialchars($item['quantity']) . "</td>
                            <td>Rs." . number_format($item['price'], 2) . "</td>
                            <td>Rs." . number_format($item['total'], 2) . "</td>
                         </tr>";
        }

        $invoice .= "<tr>
                        <td colspan='4' style='text-align: right;'><strong>Total:</strong></td>
                        <td><strong>Rs." . number_format($total_price, 2) . "</strong></td>
                     </tr>";
        $invoice .= "</table>";

        // Save the invoice to a session variable
        $_SESSION['invoice'] = $invoice;

        // Redirect to the invoice page
        header("Location: invoice.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/cart.css">
    <script>
        function updateCart(productName, change) {
            const quantitySpan = document.getElementById(`quantity-${productName}`);
            const totalSpan = document.getElementById(`total-${productName}`);
            const totalPriceSpan = document.getElementById('total-price');

            // Send AJAX request to update the product quantity in the cart
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update', product_name: productName, change: change })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.quantity > 0) {
                        // Update the quantity and total for the product
                        quantitySpan.textContent = data.quantity;
                        totalSpan.textContent = `Rs.${data.total.toFixed(2)}`;
                    } else {
                        // Remove the product row if quantity is 0
                        document.getElementById(`product-${productName}`).remove();
                    }
                    // Update the total price
                    totalPriceSpan.textContent = data.total_price.toFixed(2);
                } else {
                    alert('Failed to update cart.');
                }
            });
        }

        function removeFromCart(productName) {
            // Send AJAX request to remove the product from the cart
            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'remove', product_name: productName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the product row
                    document.getElementById(`product-${productName}`).remove();
                    // Update the total price
                    const totalPriceSpan = document.getElementById('total-price');
                    totalPriceSpan.textContent = data.total_price.toFixed(2);
                } else {
                    alert('Failed to remove product from cart.');
                }
            });
        }
    </script>
</head>
<body>
    <nav class="breadcrumb">
        <a href="../index.html">Home</a> > <a href="cart.php">Shopping Cart</a> > <span>Checkout</span>
    </nav>
    <h2>Checkout</h2>
    <div id="bill">
        <h3>Receipt</h3>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
                <tr id="product-<?php echo $item['name']; ?>">
                    <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Product Image" style="width: 50px; height: auto;"></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>Rs.<?php echo number_format($item['price'], 2); ?></td>
                    <td id="total-<?php echo $item['name']; ?>">Rs.<?php echo number_format($item['total'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p><strong>Total: Rs.<span id="total-price"><?php echo number_format($total_price, 2); ?></span></strong></p>
    </div>
    <br>
    <form method="POST">
        <div>
            <label for="prescription_code">Enter Prescription Code (if required):</label>
            <input type="text" id="prescription_code" name="prescription_code">
        </div>
        <br>
        <button type="submit">Proceed with Payment</button>
    </form>
    <br><br>
    <a href="cart.php">Back to Cart</a>
</body>
</html>


