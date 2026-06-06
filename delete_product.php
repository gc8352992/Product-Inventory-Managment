<?php
session_start();
include('db.php');

// Check if user is logged in and is an admin or manager
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Check if the ID is set
if (!isset($_GET['id'])) {
    die("Product ID is missing.");
}

$product_id = $_GET['id'];

// Fetch product data from the database to confirm deletion
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

// Delete product if confirmed
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // STEP 1: Delete related records from 'stocks'
    $delete_stocks_sql = "DELETE FROM stocks WHERE product_id = ?";
    $stmt1 = $conn->prepare($delete_stocks_sql);
    $stmt1->bind_param("i", $product_id);
    $stmt1->execute();


    // STEP 3: Now delete the product from 'products'
    $delete_product_sql = "DELETE FROM products WHERE id = ?";
    $stmt3 = $conn->prepare($delete_product_sql);
    $stmt3->bind_param("i", $product_id);

    if ($stmt3->execute()) {
        header("Location: products.php");
        exit();
    } else {
        echo "Error deleting product: " . $stmt3->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <div class="form-container">
            <h1>Delete Product</h1>
            <p>Are you sure you want to delete the product <strong><?php echo $product['product_name']; ?> (<?php echo $product['id']; ?>)</strong>?</p>

            <form action="delete_product.php?id=<?php echo $product_id; ?>" method="POST">
                <button type="submit">Yes, Delete Product</button>
                <a href="products.php" class="cancel-button">Cancel</a>
            </form>
        </div>
    </div>

    <style>
        .cancel-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }

        .cancel-button:hover {
            background-color: #e53935;
        }
    </style>
</body>
</html>
