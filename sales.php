<?php
session_start();
include('db.php');

// Fetch sales records
$sql = "SELECT s.id, p.product_name, s.quantity_sold, s.total_price, u.full_name AS sold_by, s.sale_date
        FROM sales s
        LEFT JOIN products p ON s.product_id = p.id
        LEFT JOIN users u ON s.sold_by = u.id
        ORDER BY s.sale_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <div>
    <div class="sidebar">
    <h2>PIM</h2>
    <div class="profile">
        <p><?php echo $_SESSION['username']; ?></p>
    </div>
    <ul>
    <li><a href="products.php">Products</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="stocks.php">Stocks</a></li>
                <li><a href="sales.php" class="active">Sales</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="ai.php">AI</a></li>
                <?php if ($_SESSION['is_admin']): ?>
                    <li><a href="users.php">Users</a></li>
                <?php endif; ?>
                <li><a href="settings.php">Settings</a></li>
    </ul>
    </div>

        <div class="content products-page">
            <h1>Sales Records</h1>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity Sold</th>
                        <th>Total Price</th>
                        <th>Sold By</th>
                        <th>Sale Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['quantity_sold']; ?></td>
                                <td><?php echo $row['total_price']; ?></td>
                                <td><?php echo $row['sold_by']; ?></td>
                                <td><?php echo $row['sale_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No sales recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
