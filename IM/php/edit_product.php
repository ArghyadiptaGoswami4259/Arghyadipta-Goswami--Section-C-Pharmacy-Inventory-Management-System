<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: shop.php"); // Redirect non-admin users to the shop page
    exit;
}

// Include the database connection file (assuming it's the correct one)
include('../config/db.php');  // For MySQLi connection

// Check if 'id' parameter is passed in URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If product exists, fetch data
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Redirect back to view products page with an error message
        header("Location: view_products.php?error=Product not found");
        exit();
    }
} else {
    // If no product ID is passed, redirect back to the product list
    header("Location: view_products.php?error=No product ID provided");
    exit();
}

// Handle form submission for editing the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $expiryDate = $_POST['expiry_date'];
    $description = $_POST['description'];
    $imageUrl = $_POST['image_url'];

    // Validate input (you can add more checks if necessary)
    if (empty($productName) || empty($quantity) || empty($price) || empty($expiryDate) || empty($description) || empty($imageUrl)) {
        echo "Please fill in all fields.";
    } else {
        // Update the product in the database
        $updateQuery = "UPDATE products SET product_name = ?, description = ?, image_url = ?, quantity = ?, price = ?, expiry_date = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssidsi", $productName, $description, $imageUrl, $quantity, $price, $expiryDate, $productId);
        
        if ($stmt->execute()) {
            echo "Product updated successfully.";
            // Redirect back to view products page after success
            header("Location: view_products.php");
            exit();
        } else {
            echo "Error updating product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" width="device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="edit_product.css"> <!-- Added CSS link for edit_product.css -->
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <nav class="breadcrumb">
            <a href="../index.php">Home</a> > <a href="view_products.php">Products</a> > <span>Edit Product</span>
        </nav>
        <form method="POST" action="">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="expiry_date">Expiry Date:</label>
            <input type="date" name="expiry_date" id="expiry_date" value="<?php echo htmlspecialchars($product['expiry_date']); ?>" required>

            <label for="description">Description:</label>
            <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($product['description']); ?>" required>

            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" id="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>

            <button type="submit">Update Product</button>
        </form>
        <a href="view_products.php">Back to Products</a>
    </div>
</body>
</html>



