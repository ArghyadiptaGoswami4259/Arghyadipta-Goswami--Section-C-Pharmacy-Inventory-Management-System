<?php
session_start();

// Redirect non-logged-in users or normal users to the shop page
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin')) {
    header("Location: php/shop.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Medicine Inventory</title>
    <style>
      /* Styling for navigation links */
      nav a {
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

      nav a:hover {
        background-color: #0056b3;
      }

      nav a:active {
        background-color: #003f7f;
      }

      nav button {
        display: inline-block;
        margin: 5px;
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      nav button:hover {
        background-color: #218838;
      }

      nav button:active {
        background-color: #1e7e34;
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Medicine Inventory Management</h1>
      <nav>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <button onclick="location.href='php/add_product.php'">Add Product</button>
          <button onclick="location.href='php/view_products.php'">View Products</button>
        <?php endif; ?>
      </nav>
      <nav>
        <a href="php/shop.php">Shop</a>
        <a href="php/cart.php">Cart</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="php/logout.php">Logout</a>
        <?php else: ?>
          <a href="php/login.php">Login</a>
        <?php endif; ?>
      </nav>
    </header>
    <main>
      <h2>Welcome to the Medicine Inventory Management System</h2>
    </main>
    <footer>
      <p>&copy; 2025 Medicine Inventory Management. All rights reserved.</p>
    </footer>
  </body>
</html>
