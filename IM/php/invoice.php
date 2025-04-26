<?php
session_start();

if (!isset($_SESSION['invoice'])) {
    echo "No invoice found. <a href='index.php'>Go back to shopping.</a>";
    exit;
}

$invoice = $_SESSION['invoice'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="../css/cart.css">
    <style>
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .print-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <?php echo $invoice; ?>
        <a href="#" class="print-button" onclick="window.print()">Print Invoice</a>
    </div>
</body>
</html>