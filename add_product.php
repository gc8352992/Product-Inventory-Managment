<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['company_id'])) {
    die("User not logged in. Please log in again.");
}

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

// Fetch suppliers
$sql = "SELECT id, supplier_name FROM suppliers";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $supplier_id = $_POST['supplier'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $cost_price = $_POST['cost_price'];

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Insert into products table
    $sql = "INSERT INTO products (product_name, category, supplier, quantity, price, cost_price, created_by, company_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("ssiidiii", $product_name, $category, $supplier_id, $quantity, $price, $cost_price, $user_id, $company_id);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; // Get the inserted product's ID

        // Calculate total price for stocks
        $total_price = $quantity * $price;

        // Insert into stocks table
        $stock_sql = "INSERT INTO stocks (product_id, total_quantity, total_price, created_by) VALUES (?, ?, ?, ?)";
        $stock_stmt = $conn->prepare($stock_sql);
        
        if (!$stock_stmt) {
            die("Error in stock SQL query: " . $conn->error);
        }

        $stock_stmt->bind_param("iidi", $product_id, $quantity, $total_price, $user_id);
        $stock_stmt->execute();

        $_SESSION['notification'] = 'Product added successfully!';
        header("Location: products.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Product</h1>
        <form action="add_product.php" method="POST" class="form-container">
            <div class="input-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>
            <div class="input-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="input-group">
                <label for="supplier">Supplier:</label>
                <select id="supplier" name="supplier" required>
                    <option value="">Select Supplier</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['supplier_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="input-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>
            <div class="input-group">
                <label for="cost_price">Cost Price(per item):</label>
                <input type="number" name="cost_price" id="cost_price" required>
            </div> 
            <div class="input-group">
                <label for="price">Selling Price (per item):</label>
                <input type="number" id="price" name="price" required>
            </div>
            <button type="submit" name="submit" class="add-product-btn">Add Product</button>
        </form>
    </div>
</body>
</html>
