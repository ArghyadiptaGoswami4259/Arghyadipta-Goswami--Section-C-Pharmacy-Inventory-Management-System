<?php
include('../config/db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the user's orders from the database
$query = "SELECT o.id AS order_id, o.order_date, o.total_price, p.product_name, p.image_url, oi.product_id, oi.quantity, oi.price,
          (SELECT COUNT(*) FROM returns r WHERE r.order_id = o.id AND r.product_id = oi.product_id) AS return_exists
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.id
          WHERE o.user_id = ?
          ORDER BY o.order_date DESC, o.id ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .order {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
        }
        .order h2 {
            font-size: 18px;
            color: #007bff;
        }
        .order p {
            font-size: 14px;
            color: #666;
        }
        .order img {
            max-width: 50px;
            height: auto;
            margin-right: 10px;
        }
        .return-button {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .return-button:hover {
            background-color: #c82333;
        }
        .return-disabled {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            cursor: not-allowed;
        }
        .order-items {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Orders</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php
            $current_order_id = null; // Track the current order ID
            while ($row = $result->fetch_assoc()):
                if ($current_order_id !== $row['order_id']): // New order
                    if ($current_order_id !== null): ?>
                        </div> <!-- Close the previous order -->
                    <?php endif; ?>
                    <div class="order">
                        <h2>Order #<?php echo $row['order_id']; ?></h2>
                        <p><strong>Order Date:</strong> <?php echo $row['order_date']; ?></p>
                        <p><strong>Total Price:</strong> ₹<?php echo number_format($row['total_price'], 2); ?></p>
                        <div class="order-items">
                <?php
                $current_order_id = $row['order_id'];
                endif;
                ?>
                <div>
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product Image">
                    <strong><?php echo htmlspecialchars($row['product_name']); ?></strong>
                    <p>Quantity: <?php echo $row['quantity']; ?></p>
                    <p>Price: ₹<?php echo number_format($row['price'], 2); ?></p>
                    <?php if ($row['return_exists'] > 0): ?>
                        <span class="return-disabled">Applied for Return</span>
                    <?php else: ?>
                        <a href="return_product.php?order_id=<?php echo $row['order_id']; ?>&product_id=<?php echo $row['product_id']; ?>" class="return-button">Apply for Return</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
            </div> <!-- Close the last order -->
            <div class="navigation-buttons" style="text-align: center; margin-top: 20px;">
                <a href="profile.php" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">Back to Profile</a>
                <a href="shop.php" style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">Back to Shop</a>
            </div>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>