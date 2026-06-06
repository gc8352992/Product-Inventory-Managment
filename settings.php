<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="style.css">  <!-- Using dashboard CSS -->
</head>
<body class="dashboard-page">  <!-- Keeping the same class for styling -->

<div class="sidebar">
    <h2>Settings</h2>
    <ul>
        <li><a href="update_password.php">🔑 Change Password</a></li>
        <li><a href="help.php">📞 Help & Support</a></li>
        <li><a href="terms.php">📜 Terms & Conditions</a></li>
        <li><a href="dashboard.php">⬅️ Back to Dashboard</a></li> 
        <li><a href="logout.php" class="logout-btn">🚪 Logout</a></li>
    </ul>
</div>

<div class="content">
    <div class="text-container">
        <h1>Manage Your Settings</h1>
        <h3>Select an option from the left sidebar to update your preferences.</h3>
    </div>
</div>
</body>
</html>
