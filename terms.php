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
    <title>Terms & Conditions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">

<div class="sidebar">
    <h2>Settings</h2>
    <ul>
        <li><a href="update_password.php">🔑 Change Password</a></li>
        <li><a href="help.php">📞 Help & Support</a></li>
        <li><a href="terms.php" class="active">📜 Terms & Conditions</a></li>
        <li><a href="logout.php" class="logout-btn">🚪 Logout</a></li>
    </ul>
</div>

<div class="content">
    <div class="text-container">
        <h1>Terms & Conditions</h1>
        <p>
            Welcome to <strong>PIM</strong>. By using our inventory management system, you agree to the following terms and conditions. 
            This platform is designed to help small businesses efficiently track their inventory, manage suppliers, and oversee stock levels. 
            It is essential that all users provide accurate information when creating an account and ensure that their login credentials remain confidential. 
            Any unauthorized access or suspicious activity should be reported immediately.
        </p>

        <p>
            Users must use the platform solely for business-related inventory management purposes. Any attempt to manipulate, misuse, or disrupt 
            the system’s functionality is strictly prohibited. The accuracy of product details, stock levels, and supplier information entered into 
            the system is the sole responsibility of the user. We are not liable for any losses that may occur due to incorrect data input.
        </p>

        <p>
            We strive to maintain system availability at all times; however, occasional maintenance or unforeseen technical issues may result in 
            temporary downtime. In such cases, we will do our best to notify users in advance. Your privacy and data security are of utmost 
            importance to us. All business-related information stored on the platform is handled responsibly and is not shared with third parties 
            unless required by law.
        </p>

        <p>
            If a user violates these terms, engages in fraudulent activities, or misuses the platform in any way, we reserve the right to suspend 
            or terminate their account without prior notice. Users who wish to deactivate their accounts may do so by contacting our support team. 
            For any queries, issues, or assistance, please reach out to <strong>support@inventory.com</strong>.
        </p>

        <p>
            By continuing to use <strong>PIM</strong>, you acknowledge and accept these terms. If you do not agree with any of 
            these conditions, please refrain from using the platform.
        </p>
    </div>
</div>

</body>
</html>
