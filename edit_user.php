<?php
session_start();
include('db.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Check if the ID is set
if (!isset($_GET['id'])) {
    die("User ID is missing.");
}

$user_id = $_GET['id'];

// Fetch user data from the database
$sql = "SELECT id, username, full_name, is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0; // Checkbox value

    // Update user in the database
    $update_sql = "UPDATE users SET username = ?, full_name = ?, is_admin = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssii", $username, $full_name, $is_admin, $user_id);
    
    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <div class="form-container">
            <h1>Edit User</h1>

            <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                </div>
                <div class="input-group">
                    <label for="is_admin">Admin:</label>
                    <input type="checkbox" id="is_admin" name="is_admin" <?php echo ($user['is_admin'] == 1) ? 'checked' : ''; ?>>
                </div>
                <button type="submit">Update User</button>
            </form>
        </div>
    </div>
</body>
</html>
