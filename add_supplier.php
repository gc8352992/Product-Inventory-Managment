<?php
session_start();
include('db.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all fields are set before using them
    if (isset($_POST['supplier_name'], $_POST['supplier_location'], $_POST['email'], $_POST['phone_number'], $_POST['quantity_ordered'], $_POST['quantity_received'])) {

        $supplier_name = $_POST['supplier_name'];
        $supplier_location = $_POST['supplier_location'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $quantity_ordered = $_POST['quantity_ordered'];
        $quantity_received = $_POST['quantity_received'];

        // Insert data into the suppliers table
        $sql = "INSERT INTO suppliers (supplier_name, supplier_location, email, phone_number, created_by)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL Error: " . $conn->error); // Debugging output
        }

        $stmt->bind_param("sssss", $supplier_name, $supplier_location, $email, $phone_number, $_SESSION['user_id']);

        if ($stmt->execute()) {
            // Get the last inserted supplier id
            $supplier_id = $conn->insert_id;

            // Insert into product_supplier table (without quantity_remaining)
            $sql2 = "INSERT INTO product_supplier (supplier_id, quantity_ordered, quantity_received, created_by)
                     VALUES (?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);

            if (!$stmt2) {
                die("SQL Error: " . $conn->error); // Debugging output
            }

            $stmt2->bind_param("iiii", $supplier_id, $quantity_ordered, $quantity_received, $_SESSION['user_id']);

            if ($stmt2->execute()) {
                // Redirect back to the suppliers page after successful insertion
                header("Location: suppliers.php");
                exit();
            } else {
                echo "Error inserting into product_supplier table: " . $stmt2->error;
            }
        } else {
            echo "Error inserting into suppliers table: " . $stmt->error;
        }
    } else {
        echo "Some required fields are missing!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS link -->
</head>
<body>
    <div class="form-container">
        <h1>Add New Supplier</h1>
        <form action="add_supplier.php" method="POST">
            <div class="input-group">
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" id="supplier_name" name="supplier_name" required>
            </div>
            <div class="input-group">
                <label for="supplier_location">Location:</label>
                <input type="text" id="supplier_location" name="supplier_location" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>
            </div>
            <div class="input-group">
                <label for="quantity_ordered">Quantity Ordered:</label>
                <input type="number" id="quantity_ordered" name="quantity_ordered" required>
            </div>
            <div class="input-group">
                <label for="quantity_received">Quantity Received:</label>
                <input type="number" id="quantity_received" name="quantity_received" required>
            </div>
            <button type="submit" name="submit">Add Supplier</button>
        </form>
    </div>
</body>
</html>
