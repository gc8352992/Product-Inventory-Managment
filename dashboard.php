<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];  
$is_admin = $_SESSION['is_admin'];  

$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_suppliers = $conn->query("SELECT COUNT(*) AS count FROM suppliers")->fetch_assoc()['count'];
$total_stocks = $conn->query("SELECT SUM(total_quantity) AS count FROM stocks")->fetch_assoc()['count'];
$total_categories = $conn->query("SELECT COUNT(DISTINCT category) AS count FROM products")->fetch_assoc()['count'];

$total_users = 0;
if ($is_admin == 1) {
    $total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <div class="sidebar">
        <h2>PIM</h2>
        <div class="profile">
            <p><?php echo $_SESSION['username']; ?></p>
        </div>
        <ul>
                <li><a href="products.php">Products</a></li>
                <li><a href="suppliers.php">Suppliers</a></li>
                <li><a href="stocks.php">Stocks</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="ai.php">AI</a></li>
                <?php if ($_SESSION['is_admin']): ?>
                    <li><a href="users.php">Users</a></li>
                <?php endif; ?>
                <li><a href="settings.php">Settings</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="summary-cards">
            <h1>Welcome, <?php echo $username; ?>!</h1>
            <div class="card">Total Products: <strong><?php echo $total_products; ?></strong></div>
            <div class="card">Total Suppliers: <strong><?php echo $total_suppliers; ?></strong></div>
            <div class="card">Total Stocks: <strong><?php echo $total_stocks; ?></strong></div>
            <div class="card">Total Categories: <strong><?php echo $total_categories; ?></strong></div>
            <?php if ($is_admin == 1) { ?>
                <div class="card">Total Workers: <strong><?php echo $total_users; ?></strong></div>
            <?php } ?>
            <div class="card">
            <a href="analytics.php"><strong>View Analytics</strong></a>
            </div>
        </div>
    </div>
</body>
</html>
