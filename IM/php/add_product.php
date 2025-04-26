<?php
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $expiry_date = $_POST['expiry_date'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $sql = "INSERT INTO products (product_name, description, image_url, quantity, price, expiry_date) 
            VALUES ('$product_name', '$description', '$image_url', '$quantity', '$price', '$expiry_date')";
    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/add_product.css">
    <title>Add Product</title>
</head>
<body>
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb">
            <a href="../index.php">Home</a> > <span>Add Product</span>
        </nav>

        <header>
            <h1>Add New Product</h1>
        </header>
        <main>
            <form method="POST">
                <input type="text" name="product_name" placeholder="Product Name" required><br>
                <input type="number" name="quantity" placeholder="Quantity" required><br>
                <input type="number" step="0.01" name="price" placeholder="Price" required><br>
                <input type="date" name="expiry_date" placeholder="Expiry Date" required><br>
                <input type="text" name="description" placeholder="Description" required><br>
                <input type="text" name="image_url" placeholder="Image URL" required><br>
                <button type="submit">Add Product</button>
            </form>
        </main>
    </div>
</body>
</html>


