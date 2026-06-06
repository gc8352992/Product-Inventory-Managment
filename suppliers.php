<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch supplier data from the database
$sql = "SELECT s.supplier_name, s.supplier_location, s.email, s.phone_number, 
                ps.quantity_ordered, ps.quantity_received, 
                (ps.quantity_ordered - ps.quantity_received) AS quantity_remaining, 
                u.full_name AS created_by, ps.created_at AS stock_created_at
        FROM suppliers s
        LEFT JOIN product_supplier ps ON s.id = ps.supplier_id
        LEFT JOIN users u ON ps.created_by = u.id";

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
    <title>Suppliers - Inventory Management System</title>
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
                <li><a href="suppliers.php" class="active">Suppliers</a></li>
                <li><a href="stocks.php">Stocks</a></li>
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
            <h1>Suppliers List</h1>

            <!-- Table for displaying suppliers -->
            <table>
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Location</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Quantity Ordered</th>
                        <th>Quantity Received</th>
                        <th>Quantity Remaining</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['supplier_name']; ?></td>
                                <td><?php echo $row['supplier_location']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone_number']; ?></td>
                                <td><?php echo $row['quantity_ordered']; ?></td>
                                <td><?php echo $row['quantity_received']; ?></td>
                                <td><?php echo max($row['quantity_remaining'], 0); ?></td> <!-- Prevents negative values -->
                                <td><?php echo $row['created_by']; ?></td>
                                <td><?php echo $row['stock_created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No suppliers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- Add Supplier Button -->
            <div class="add-product-btn">
                <a href="add_supplier.php">Add Supplier</a>
            </div>
        </div>
    </div>
</body>
</html>
