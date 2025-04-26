<?php
// Include database connection files
include('../config/db.php');      // Object-oriented connection

// Check if 'id' is passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the product ID

    // Delete query using the object-oriented connection (db.php)
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id); // Bind the ID as an integer
        if ($stmt->execute()) {
            // On successful deletion, redirect to view_products.php
            echo "<script>
                    alert('Product deleted successfully.');
                    window.location.href = 'view_products.php';
                  </script>";
        } else {
            // If deletion fails
            echo "<script>
                    alert('Failed to delete product. Please try again.');
                    window.location.href = 'view_products.php';
                  </script>";
        }
        $stmt->close(); // Close the prepared statement
    } else {
        echo "<script>
                alert('Failed to prepare the delete statement.');
                window.location.href = 'view_products.php';
              </script>";
    }
    $conn->close(); // Close the database connection
} else {
    // If 'id' is not provided in the URL
    echo "<script>
            alert('Invalid request. Product ID is missing.');
            window.location.href = 'view_products.php';
          </script>";
}
?>

