<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $company_name = $_POST['company_name'];

    // Fetch company_id from the company name
    $sql = "SELECT id FROM companies WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $company_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Store not found.";
        exit;
    }

    $company = $result->fetch_assoc();
    $company_id = $company['id'];

    // Fetch user details based on username and company_id
    $sql = "SELECT * FROM users WHERE username = ? AND company_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $username, $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            if ($row['status'] == 'Pending') {
                echo "Your account is pending approval.";
                exit;
            }

            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['company_id'] = $row['company_id'];
            $_SESSION['is_admin'] = $row['is_admin']; // Store admin status in session

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username or company.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="main-container">
        <div class="header">
            <h1>PIM</h1>
            <h2>PRODUCT INVENTORY MANAGEMENT</h2>
        </div>
        <div class="login-container">
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="company_name">Store Name:</label>
                    <input type="text" id="company_name" name="company_name" required>
                </div>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="submit">Login</button>
                <p>Don't have an account? <a href="register.php">Register now</a></p>
            </form>
        </div>
    </div>
</body>
</html>
