<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch stock data from the database
$sql = "SELECT p.product_name, 
               SUM(s.total_quantity) AS total_quantity, 
               SUM(s.total_price) AS total_price, 
               MAX(s.updated_at) AS updated_at
        FROM stocks s
        LEFT JOIN products p ON s.product_id = p.id
        GROUP BY p.id";

$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Error executing the query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks - Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-page">
    <div>
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>PIM</h2>
            <div class="profile">
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <ul>
            <li><a href="products.php">Products</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="stocks.php" class="active">Stocks</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="ai.php">AI</a></li>
                <?php if ($_SESSION['is_admin']): ?>
                    <li><a href="users.php">Users</a></li>
                <?php endif; ?>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content products-page">
            <h1>Stocks List</h1>

            <!-- Table for displaying stocks -->
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Total Quantity</th>
                        <th>Total Price</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td> 
                <td><?php echo $row['total_quantity']; ?></td>
                <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                <td><?php echo $row['updated_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No stocks found</td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </div>
</body>
</html>
