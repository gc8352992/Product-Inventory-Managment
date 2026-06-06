<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch sales data for the last 3 months
$sales_query = "
    SELECT p.product_name, 
           SUM(s.quantity_sold) AS total_sold, 
           AVG(p.cost_price) AS avg_cost, 
           AVG(p.price) AS avg_price
    FROM sales s
    LEFT JOIN products p ON s.product_id = p.id
    WHERE s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
    GROUP BY s.product_id
    ORDER BY total_sold DESC
";
$sales_result = $conn->query($sales_query);

// Fetch low stock alerts (Now triggers at 7 units)
$low_stock_query = "
    SELECT p.product_name, s.total_quantity
    FROM stocks s
    LEFT JOIN products p ON s.product_id = p.id
    WHERE s.total_quantity < 7
";
$low_stock_result = $conn->query($low_stock_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Analysis - Inventory Management System</title>
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
                <li><a href="stocks.php">Stocks</a></li>
                <li><a href="sales.php">Sales</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="ai.php" class="active">AI</a></li>
                <?php if ($_SESSION['is_admin']): ?>
                <li><a href="users.php">Users</a></li>
                <?php endif; ?>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content products-page">
            <h1>AI Sales Analysis & Restocking Suggestions</h1>

            <!-- Sales Trend & Restocking Suggestions -->
            <h2>Sales Trend (Last 3 Months)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Total Sold</th>
                        <th>AI Order Suggestion</th>
                        <th>Estimated Profit</th>
                        <th>Stock Status</th>
                        <th>Demand Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $sales_result->fetch_assoc()): 
                        $suggested_order = ceil($row['total_sold'] * 1.2); // Order 20% more
                        $profit = ($row['avg_price'] - $row['avg_cost']) * $row['total_sold'];

                        // Stock Status Logic for Small Stores
                        $stock_status = "🟢 Overstock";
                        if ($row['total_sold'] > 50) {
                            $stock_status = "🔴 Low Stock";
                        } elseif ($row['total_sold'] > 20) {
                            $stock_status = "🟡 Moderate";
                        }

                        // Demand Level for Small Stores
                        $demand_level = "❄️ Low";
                        if ($row['total_sold'] > 100) {
                            $demand_level = "🔥 High";
                        } elseif ($row['total_sold'] > 50) {
                            $demand_level = "📉 Medium";
                        }
                    ?>
                    <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['total_sold']; ?></td>
                        <td><?php echo $suggested_order; ?></td>
                        <td>₹<?php echo number_format($profit, 2); ?></td>
                        <td><?php echo $stock_status; ?></td>
                        <td><?php echo $demand_level; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Low Stock Alerts -->
            <h2>Low Stock Alerts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Stock Remaining</th>
                        <th>Reorder Urgency</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $low_stock_result->fetch_assoc()): 
                        // Adjust Reorder Urgency for Small Stores
                        $urgency = "🟢 Low";
                        if ($row['total_quantity'] < 3) {
                            $urgency = "🔴 Critical";
                        } elseif ($row['total_quantity'] < 7) {
                            $urgency = "🟡 Moderate";
                        }
                    ?>
                    <tr style="color: <?php echo ($urgency === '🔴 Critical') ? 'red' : (($urgency === '🟡 Moderate') ? 'orange' : 'green'); ?>;">
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['total_quantity']; ?></td>
                        <td><?php echo $urgency; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
