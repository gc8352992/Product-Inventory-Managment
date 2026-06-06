<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <h1>Product Inventory Management</h1>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h2>Welcome to PIM</h2>
            <p>Your ultimate Inventory Management Solution</p>
            <a href="login.php" class="cta-btn">Get Started</a>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features">
    <h2>Our Features</h2>
    <div class="feature-cards">
        <div class="card">
            <img src="https://dapulse-res.cloudinary.com/image/upload/f_auto,q_auto/remote_mondaycom_static/uploads/DanielleHassan/2aa9c785-af9b-46b9-9ee6-b9a1dc0095e2_Inventory_tracking_board.png" alt="Track Inventory">
            <h3>Track Inventory</h3>
            <p>Monitor stock levels in real-time and avoid shortages.</p>
        </div>
        <div class="card">
            <img src="https://img.favpng.com/14/15/19/computer-icons-dashboard-inventory-management-software-sales-png-favpng-wF3PJiuXLx7gkjpPmHYhqDGsW.jpg" alt="Analytics">
            <h3>Detailed Analytics</h3>
            <p>Get insightful reports to optimize your inventory.</p>
        </div>
        <div class="card">
            <img src="https://e7.pngegg.com/pngimages/857/907/png-clipart-inventory-management-software-supply-chain-management-inventory-control-warehouse-miscellaneous-company-thumbnail.png" alt="Automation">
            <h3>Automated Process</h3>
            <p>Reduce manual work with automated stock management.</p>
        </div>
    </div>
</section>


    <!-- Testimonials Section -->
    <div class="testimonials">
    <h2>What Our Users Say</h2>
    <div class="testimonial-cards">
        <div class="testimonial-card">
            <img src="https://lumiere-a.akamaihd.net/v1/images/ct_moana_upcportalreskin_20694_9c72dc27.jpeg?region=0,0,330,330" alt="User 1">
            <h3>John Doe</h3>
            <p>"This system made managing our inventory so much easier! Highly recommended."</p>
        </div>
        <div class="testimonial-card">
            <img src="https://images.squarespace-cdn.com/content/v1/57b5ef68c534a5cc06edc769/8444f600-0f07-41e4-9db9-00df9ca2cc2f/Understanding+Others+with+Belle+sq+headshot.jpg" alt="User 2">
            <h3>Jane Smith</h3>
            <p>"An excellent tool for keeping track of all our products. Great interface!"</p>
        </div>
        <div class="testimonial-card">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvzP0_5erR1nQsk9YbGR5sP_dd2yhNxZXsDw&s" alt="User 3">
            <h3>Mark Johnson</h3>
            <p>"A real game changer in inventory management. Simple, fast, and reliable!"</p>
        </div>
    </div>
</div>


    <!-- CTA Section -->
    <div class="cta-section">
    <h2>Ready to manage your inventory?</h2>
    <p>Start using our Inventory Management System and streamline your operations today.</p>
    <a href="login.php" class="cta-btn">Get Started</a>
</div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 PIM. All rights reserved.</p>
        <p><a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a></p>
    </footer>
</body>
</html>
