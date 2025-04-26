<?php
include('../config/db.php');
session_start();

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: shop.php"); // Redirect non-admin users to the shop page
    exit;
}

// Pagination setup
$products_per_page = 10; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $products_per_page; // Calculate the offset

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch total number of products for pagination
$total_query = "SELECT COUNT(*) AS total FROM products WHERE product_name LIKE '%$searchQuery%'";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $products_per_page); // Calculate total pages

// Fetch products for the current page
$query = "SELECT * FROM products WHERE product_name LIKE '%$searchQuery%' ORDER BY id DESC LIMIT $products_per_page OFFSET $offset";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="../css/view_products.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb">
            <a href="../index.php">Home</a> > <span>View Products</span>
        </nav>

        <h1>View Products</h1>
        <div class="search-container">
            <input
                type="text"
                id="searchBar"
                placeholder="Search products..."
                onkeyup="liveSearch(this.value)"
                value="<?php echo htmlspecialchars($searchQuery); ?>"
            >
            <button onclick="manualSearch()">Search</button> <!-- Search Button -->
            <ul id="suggestionsList"></ul> <!-- Suggestions List -->
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Expiry Date</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productTable">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['expiry_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product Image" style="width: 100px; height: auto;"></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

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
    </div>
</body>
</html>

<script>
// Live search with suggestions functionality
function liveSearch(query) {
    const suggestionsList = document.getElementById("suggestionsList");
    const productTable = document.getElementById("productTable");

    // Clear previous suggestions
    suggestionsList.innerHTML = '';

    if (query.length === 0) {
        productTable.style.display = ""; // Show all products if search is empty
        return;
    }

    // Make the search case-insensitive
    query = query.toLowerCase();

    // Loop through the table rows to filter them
    const rows = document.querySelectorAll("table tbody tr");
    let suggestions = [];

    rows.forEach(row => {
        const productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

        if (productName.startsWith(query)) {
            row.style.display = ""; // Show rows that match the query
            suggestions.push(row.querySelector("td:nth-child(2)").textContent); // Add to suggestions
        } else {
            row.style.display = "none"; // Hide rows that don't match
        }
    });

    // Display search suggestions below the input field
    suggestions.forEach(suggestion => {
        const listItem = document.createElement("li");
        listItem.textContent = suggestion;
        suggestionsList.appendChild(listItem);
    });
}

// Manual search triggered by the search button
function manualSearch() {
    const query = document.getElementById("searchBar").value.toLowerCase();
    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        const productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
        if (productName.includes(query)) {
            row.style.display = ""; // Show rows that match the query
        } else {
            row.style.display = "none"; // Hide rows that don't match
        }
    });
}
</script>








