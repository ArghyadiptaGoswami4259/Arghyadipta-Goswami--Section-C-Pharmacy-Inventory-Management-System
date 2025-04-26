<?php
session_start();
include('../config/db.php');

// Ensure the cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$response = ['success' => false];

if (isset($data['action'], $data['product_id'])) {
    $product_id = intval($data['product_id']);

    if ($data['action'] === 'add') {
        // Add product to cart with quantity 1
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        $response = ['success' => true, 'quantity' => $_SESSION['cart'][$product_id]];
    } elseif ($data['action'] === 'update' && isset($data['change'])) {
        // Update product quantity
        $change = intval($data['change']);
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $change;
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]); // Remove product if quantity is 0
                $response = ['success' => true, 'quantity' => 0];
            } else {
                $response = ['success' => true, 'quantity' => $_SESSION['cart'][$product_id]];
            }
        }
    }

    // Calculate the total price of the cart
    $total_price = 0;
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $query = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $total_price += $product['price'] * $quantity;
        }
    }
    $response['total_price'] = $total_price;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);