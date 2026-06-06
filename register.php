<?php
session_start();
include('db.php');  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $company_name = $_POST['company_name'];
    $phone_number = $_POST['phone']; 

    // Validate phone number
    if (!preg_match("/^[0-9]{10}$/", $phone_number)) {
        die("Invalid phone number. Please enter a 10-digit number.");
    }

    // Check if username exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) die("Username already taken.");
    $stmt->close();

    // Check if email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) die("Email already registered.");
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the company exists
    $sql = "SELECT id FROM companies WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $company_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $company_id = $row['id'];

        // Check the number of users in the company
        $sql = "SELECT COUNT(*) FROM users WHERE company_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        $stmt->bind_result($user_count);
        $stmt->fetch();
        $stmt->close();

        // First user is admin & approved, others are pending
        if ($user_count == 0) {
            $is_admin = 1;
            $status = "Approved";
        } else {
            $is_admin = 0;
            $status = "Pending";
        }
    } else {
        // Create new company
        $sql = "INSERT INTO companies (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $company_name);
        $stmt->execute();
        $company_id = $stmt->insert_id;

        $is_admin = 1;
        $status = "Approved";
    }

    // Register the user
    $sql = "INSERT INTO users (full_name, email, username, password, company_id, phone_number, status, is_admin, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssissi", $full_name, $email, $username, $hashed_password, $company_id, $phone_number, $status, $is_admin);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful! Your status: $status.');
            window.location.href = 'login.php'; 
        </script>";
    } else {
        die("Error: " . $stmt->error);
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="main-container">
        <div class="header">
            <h1>PIM</h1>
            <h2>PRODUCT INVENTORY MANAGEMENT</h2>
        </div>
        <div class="login-container">
            <form action="register.php" method="POST">
                <div class="input-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="company_name">Store Name:</label>
                    <input type="text" id="company_name" name="company_name" required>
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <button type="submit" name="register">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </div>
    </div>
</body>
</html>
