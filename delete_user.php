<?php
session_start();
include('db.php');

// Ensure database connection is working
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Check if the user ID is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("User ID is missing.");
}

$user_id = intval($_GET['id']); // Ensure it's an integer

// Fetch user data from the database to confirm deletion
$sql = "SELECT id, username, full_name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

// Delete user if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect back to the users page after successful deletion
        header("Location: users.php");
        exit();
    } else {
        echo "Error deleting user: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <div class="form-container">
            <h1>Delete User</h1>
            <p>Are you sure you want to delete the user <strong><?php echo htmlspecialchars($user['full_name']); ?> (<?php echo htmlspecialchars($user['username']); ?>)</strong>?</p>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $user_id; ?>" method="POST">
                <button type="submit">Yes, Delete User</button>
                <a href="users.php" class="cancel-button">Cancel</a>
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
