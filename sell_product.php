<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity_sold = $_POST['quantity_sold'];
    $sold_by = $_SESSION['user_id'];

    // Fetch product and stock details
    $stmt = $conn->prepare("
        SELECT p.price, p.quantity AS product_quantity, s.total_quantity AS stock_quantity, s.total_price AS stock_price
        FROM products p 
        LEFT JOIN stocks s ON p.id = s.product_id 
        WHERE p.id = ?
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        die("Error in query: " . $conn->error);
    }

    $data = $result->fetch_assoc();
    if (!$data) {
        die("Error: Product not found.");
    }

    $price_per_item = $data['price'];
    $product_quantity = $data['product_quantity'];
    $stock_quantity = $data['stock_quantity'];
    $stock_price = $data['stock_price'];

    if ($stock_quantity === null) {
        die("Error: No stock entry for this product.");
    }

    if ($stock_quantity >= $quantity_sold && $product_quantity >= $quantity_sold) {
        $total_price_sold = $quantity_sold * $price_per_item;

        // **Calculate new stock price based on remaining quantity**
        $remaining_quantity = $stock_quantity - $quantity_sold;
        if ($remaining_quantity > 0) {
            $price_per_unit = $stock_price / $stock_quantity; // Get price per unit in stock
            $new_total_stock_price = round($price_per_unit * $remaining_quantity, 2); // Adjust price accordingly
        } else {
            $new_total_stock_price = 0; // If no stock left, price should be zero
        }

        // Start transaction to prevent partial updates
        $conn->begin_transaction();

        try {
            // Insert sale record
            $sale_stmt = $conn->prepare("
                INSERT INTO sales (product_id, quantity_sold, total_price, sold_by) 
                VALUES (?, ?, ?, ?)
            ");
            $sale_stmt->bind_param("iidi", $product_id, $quantity_sold, $total_price_sold, $sold_by);
            if (!$sale_stmt->execute()) {
                throw new Exception("Error inserting sale: " . $sale_stmt->error);
            }

            // Update product quantity
            $product_update_stmt = $conn->prepare("
                UPDATE products 
                SET quantity = quantity - ? 
                WHERE id = ?
            ");
            $product_update_stmt->bind_param("ii", $quantity_sold, $product_id);
            if (!$product_update_stmt->execute()) {
                throw new Exception("Error updating product quantity: " . $product_update_stmt->error);
            }

            // Update stock quantity and total price
            $stock_update_stmt = $conn->prepare("
                UPDATE stocks 
                SET total_quantity = ?, total_price = ? 
                WHERE product_id = ?
            ");
            $stock_update_stmt->bind_param("idi", $remaining_quantity, $new_total_stock_price, $product_id);
            if (!$stock_update_stmt->execute()) {
                throw new Exception("Error updating stock: " . $stock_update_stmt->error);
            }

            // Commit transaction
            $conn->commit();
            $_SESSION['notification'] = "Sale recorded successfully!";
            header("Location: sales.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback(); // Undo changes if any query fails
            die($e->getMessage());
        }
    } else {
        die("Not enough stock available!");
    }
}
?>
