<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body class="dashboard-page">

<div class="sidebar">
    <h2>Settings</h2>
    <ul>
        <li><a href="update_password.php">🔑 Change Password</a></li>
        <li><a href="help.php" class="active">📞 Help & Support</a></li>
        <li><a href="terms.php">📜 Terms & Conditions</a></li>
        <li><a href="logout.php" class="logout-btn">🚪 Logout</a></li>
    </ul>
</div>

<div class="content">
<div class="text-container">
    <h1>Help & Support</h1>
    <p>If you need any assistance, feel free to reach out to us:</p>
        <h2>📞 Contact Us</h2>
        <p><strong>Phone:</strong> +91 12345 67890</p>
        <p><strong>Email:</strong> support@inventory.com</p>
        <p><strong>Working Hours:</strong> Mon - Fri, 9 AM - 6 PM</p>
    
        <h2>💡 Frequently Asked Questions</h2>
        <p><strong>1. How do I reset my password?</strong><br>Go to Settings > Change Password and enter your new password.</p>
        <p><strong>2. How can I contact customer support?</strong><br>You can email us at support@inventory.com or call us.</p>
    </div>
</div>

</body>
</html>
