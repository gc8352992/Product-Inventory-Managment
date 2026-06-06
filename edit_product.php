<?php
session_start();
include('db.php');

// Check if the user is logged in and is either admin or manager
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    exit();
}

// Check if the ID is set
if (!isset($_GET['id'])) {
    die("Product ID is missing.");
}

$product_id = $_GET['id'];

// Fetch product data from the database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if product exists
if ($result->num_rows == 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Update product details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $supplier = $_POST['supplier'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Update product in the database
    $update_sql = "UPDATE products SET product_name = ?, category = ?, supplier = ?, quantity = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssiii", $product_name, $category, $supplier, $quantity, $price, $product_id);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <div class="form-container">
            <h1>Edit Product</h1>
            <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST">
                <div class="input-group">
                    <label for="product_name">Product Name:</label>
                    <input type="text" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" value="<?php echo $product['category']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="supplier">Supplier:</label>
                    <input type="text" id="supplier" name="supplier" value="<?php echo $product['supplier']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                </div>
                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>
</body>
</html>
