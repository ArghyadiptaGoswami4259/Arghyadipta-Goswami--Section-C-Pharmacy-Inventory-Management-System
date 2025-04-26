<?php
include('../config/db.php');
session_start();

// Pagination setup
$products_per_page = 10; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $products_per_page; // Calculate the offset

// Search functionality
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch total number of products for pagination
$total_query = "SELECT COUNT(*) AS total FROM products WHERE product_name LIKE ?";
$stmt = $conn->prepare($total_query);
$searchTerm = '%' . $searchQuery . '%';
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$total_result = $stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $products_per_page); // Calculate total pages

// Fetch products for the current page
$query = "SELECT * FROM products WHERE product_name LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sii", $searchTerm, $products_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="../css/shop.css">
    <style>
        /* Styling for the shop page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        nav.breadcrumb {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: right;
        }
        nav.breadcrumb a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }
        nav.breadcrumb a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .product-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 250px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .product-card h2 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
        }
        .product-card p {
            font-size: 14px;
            color: #666;
        }
        .add-to-cart-button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-to-cart-button:hover {
            background-color: #218838;
        }
        .nav-options {
            text-align: right;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
        }
        .nav-options a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .nav-options a:hover {
            text-decoration: underline;
        }
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination a:hover {
            background-color: #0056b3;
            color: white;
        }
        .search-container {
            text-align: center;
            margin: 20px 0;
        }
        .search-container input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="nav-options">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="../index.php">Home</a>
        <?php endif; ?>
        <a href="cart.php">View Cart</a>
        <a href="profile.php">Profile</a> <!-- Add Profile Button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
    <h1>Shop</h1>
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="product-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card" id="product-<?php echo $row['id']; ?>">
                    <a href="product_details.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product Image">
                        <h2><?php echo htmlspecialchars($row['product_name']); ?></h2>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?></p>
                    </a>
                    <div class="cart-actions">
                        <?php if (isset($_SESSION['cart'][$row['id']])): ?>
                            <!-- If product is already in the cart, show quantity controls -->
                            <button onclick="updateCart(<?php echo $row['id']; ?>, -1)">-</button>
                            <span id="quantity-<?php echo $row['id']; ?>"><?php echo $_SESSION['cart'][$row['id']]; ?></span>
                            <button onclick="updateCart(<?php echo $row['id']; ?>, 1)">+</button>
                        <?php else: ?>
                            <!-- If product is not in the cart, show "Add to Cart" button -->
                            <button class="add-to-cart-button" onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchQuery); ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchQuery); ?>">Next</a>
        <?php endif; ?>
    </div>

    <script>
        function addToCart(productId) {
            const productCard = document.getElementById(`product-${productId}`);
            const cartActions = productCard.querySelector('.cart-actions');

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
                    cartActions.innerHTML = `
                        <button onclick="updateCart(${productId}, -1)">-</button>
                        <span id="quantity-${productId}">${data.quantity}</span>
                        <button onclick="updateCart(${productId}, 1)">+</button>
                    `;
                } else {
                    alert('Failed to add product to cart.');
                }
            });
        }

        function updateCart(productId, change) {
            const quantitySpan = document.getElementById(`quantity-${productId}`);
            const productCard = document.getElementById(`product-${productId}`);
            const cartActions = productCard.querySelector('.cart-actions');

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
                        cartActions.innerHTML = `
                            <button class="add-to-cart-button" onclick="addToCart(${productId})">Add to Cart</button>
                        `;
                    }
                } else {
                    alert('Failed to update cart.');
                }
            });
        }
    </script>
</body>
</html>