<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check for notification message
$notification = '';
if (isset($_SESSION['notification'])) {
    $notification = $_SESSION['notification'];
    unset($_SESSION['notification']);
}

// Fetch product data from the database
$sql = "SELECT p.id, p.product_name, p.category, p.quantity, p.price, p.updated_at, 
               u.full_name AS created_by, s.supplier_name, p.company_id
        FROM products p
        LEFT JOIN users u ON p.created_by = u.id
        LEFT JOIN product_supplier ps ON ps.supplier_id = p.supplier
        LEFT JOIN suppliers s ON ps.supplier_id = s.id";

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
    <title>Products - Inventory Management System</title>
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
            <li><a href="products.php" class="active">Products</a></li>
            <li><a href="suppliers.php">Suppliers</a></li>
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
            <h1>Products List</h1>

            <!-- Notification Alert -->
            <?php if ($notification): ?>
                <div class="notification">
                    <?php echo $notification; ?>
                </div>
            <?php endif; ?>

            <!-- Search Bar -->
            <div class="searchInput">
                <input type="text" id="searchbar" placeholder="Search for products" onkeyup="searchProduct()">
            </div>

            <!-- Table for displaying products -->
            <table id="productTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Suppliers</th>
                        <th>Created By</th>
                        <th>Quantity</th>
                        <th>Price(per item)</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="productRow">
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['supplier_name']; ?></td>
                <td><?php echo $row['created_by']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['updated_at']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_product.php?id=<?php echo $row['id']; ?>">Delete</a> |
                    <form action="sell_product.php" method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="number" name="quantity_sold" min="1" max="<?php echo $row['quantity']; ?>" required>
                        <button type="submit">Sell</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No products found</td>
        </tr>
    <?php endif; ?>
</tbody>

            </table>

            <!-- Add Product Button -->
            <div class="add-product-btn">
                <a href="add_product.php">Add Product</a>
            </div>
        </div>
    </div>

    <!-- JavaScript for real-time search -->
    <script>
        function searchProduct() {
            // Get the search input value
            var input = document.getElementById('searchbar').value.toLowerCase();
            var rows = document.querySelectorAll('.productRow');

            rows.forEach(function(row) {
                var productName = row.cells[0].textContent.toLowerCase();
                var supplierName = row.cells[2].textContent.toLowerCase();
                
                if (productName.includes(input) || supplierName.includes(input)) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        }
    </script>

</body>
</html>
