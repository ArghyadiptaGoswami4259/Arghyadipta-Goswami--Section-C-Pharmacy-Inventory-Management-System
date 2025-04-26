<?php
include('../config/db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

if (!isset($_GET['order_id'], $_GET['product_id'])) {
    header("Location: my_orders.php"); // Redirect to My Orders if no valid data is provided
    exit;
}

$order_id = intval($_GET['order_id']);
$product_id = intval($_GET['product_id']);
$user_id = $_SESSION['user_id'];

// Insert return request into the database
$query = "INSERT INTO returns (order_id, product_id, user_id, return_date, status) VALUES (?, ?, ?, NOW(), 'Pending')";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $order_id, $product_id, $user_id);

$return_success = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #666;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($return_success): ?>
            <h1>Return Request Submitted</h1>
            <p>Your return request has been submitted successfully. You will be notified once it is processed.</p>
        <?php else: ?>
            <h1>Return Request Failed</h1>
            <p>We encountered an issue while processing your return request. Please try again later.</p>
        <?php endif; ?>
        <a href="my_orders.php" class="back-button">Back to My Orders</a>
    </div>
</body>
</html>