<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch category-wise quantity (Fetching category from products)
$category_query = $conn->query("
    SELECT p.category, SUM(s.total_quantity) AS quantity 
    FROM products p 
    JOIN stocks s ON p.id = s.product_id 
    GROUP BY p.category
");

$categories = [];
$category_quantities = [];
while ($row = $category_query->fetch_assoc()) {
    $categories[] = $row['category'];
    $category_quantities[] = $row['quantity'];
}

// Fetch product-wise quantity categorized (Fetching category from products)
$product_query = $conn->query("
    SELECT p.category, p.product_name, SUM(s.total_quantity) AS quantity 
    FROM products p 
    JOIN stocks s ON p.id = s.product_id 
    GROUP BY p.category, p.product_name
");

$products_by_category = [];
while ($row = $product_query->fetch_assoc()) {
    $products_by_category[$row['category']][] = [
        'name' => $row['product_name'], 
        'quantity' => $row['quantity']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ====== Page Styling (Inline) ====== */
        .analytics-content {
            width: 95%;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #1B1F3B;
            margin-bottom: 15px;
        }

        h3 {
            color: #B76E79;
            font-weight: 600;
            margin-top: 20px;
        }

        /* Chart Container */
        .chart-container {
            width: 90%;
            max-width: 500px;  /* ⬅️ Smaller Chart Size */
            margin: 10px auto;
            padding: 10px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Fixing chart size properly */
        canvas {
            display: block;
            margin: 20px auto; /* Centering the chart */
            width: 90% !important;  /* Takes up most of the page */
            max-width: 800px;  /* Wider than before */
            height: 400px !important;  /* Balanced height */
        }

        /* Category Selector */
        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #B76E79;
            font-size: 14px;
            background-color: white;
            color: #333;
            margin-top: 10px;
            cursor: pointer;
        }

        /* Buttons */
        .analytics-button {
            display: inline-block;
            text-decoration: none;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            background-color: #B76E79;
            border-radius: 5px;
            transition: 0.3s;
        }

        .analytics-button:hover {
            background-color: #E1B07E;
            transform: translateY(-1px);
        }

        .back-button {
            display: block;
            width: fit-content;
            margin: 15px auto;
        }
    </style>
</head>
<body>
    <div class="analytics-content">
        <h1>Inventory Analytics</h1>

        <div class="chart-container">
            <h3>Category vs. Quantity</h3>
            <canvas id="categoryChart"></canvas>
        </div>

        <div class="chart-container">
            <h3>Product vs. Quantity (By Category)</h3>
            <select id="categorySelector">
                <option value="">Select a Category</option>
                <?php foreach (array_keys($products_by_category) as $category) { ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                <?php } ?>
            </select>
            <canvas id="productChart"></canvas>
        </div>

        <a href="dashboard.php" class="analytics-button back-button">Back to Dashboard</a>
    </div>

    <script>
        var categoryChart = new Chart(document.getElementById("categoryChart"), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categories); ?>,
                datasets: [{
                    label: "Total Quantity",
                    data: <?php echo json_encode($category_quantities); ?>,
                    backgroundColor: '#B76E79',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        var productData = <?php echo json_encode($products_by_category); ?>;
        var productChart = new Chart(document.getElementById("productChart"), {
            type: 'bar',
            data: { labels: [], datasets: [{ label: "Quantity", data: [], backgroundColor: '#E1B07E' }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        document.getElementById("categorySelector").addEventListener("change", function () {
            var selectedCategory = this.value;
            var labels = [];
            var data = [];

            if (productData[selectedCategory]) {
                productData[selectedCategory].forEach(function (item) {
                    labels.push(item.name);
                    data.push(item.quantity);
                });
            }

            productChart.data.labels = labels;
            productChart.data.datasets[0].data = data;
            productChart.update();
        });
    </script>
</body>
</html>
