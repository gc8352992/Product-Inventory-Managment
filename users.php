<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure only admins can access this page
$is_admin_check_sql = "SELECT is_admin FROM users WHERE id = ?";
$stmt = $conn->prepare($is_admin_check_sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch(); // FIX: Fetch the result before closing
$stmt->close();

// If the logged-in user is NOT an admin, redirect them
if (!$is_admin) {
    echo "<script>alert('Unauthorized action. Only admins can approve, reject, or change admin status.'); window.location.href='dashboard.php';</script>";
    exit();
}

// Approve User
if (isset($_POST['approve_user'])) {
    $user_id = $_POST['user_id'];

    $sql = "UPDATE users SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User approved successfully.'); window.location.href='users.php';</script>";
    } else {
        echo "Error approving user: " . $conn->error;
    }
}

// Reject User
if (isset($_POST['reject_user'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User rejected and deleted.'); window.location.href='users.php';</script>";
    } else {
        echo "Error rejecting user: " . $conn->error;
    }
}

// Change Admin Status
if (isset($_POST['change_admin_status'])) {
    $user_id = $_POST['user_id'];
    $new_admin_status = $_POST['new_admin_status'];

    $sql = "UPDATE users SET is_admin = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_admin_status, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Admin status updated successfully.'); window.location.href='users.php';</script>";
    } else {
        echo "Error updating admin status: " . $conn->error;
    }
}

// Fetch users
$sql = "SELECT id, username, full_name, created_at, status, is_admin FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <div>
        <!-- Sidebar -->
        <div class="sidebar">
    <h2>PIM</h2>
    <div class= "profile">
        <p><?php echo $_SESSION['username']; ?></p>
    </div>
    <ul>
    <li><a href="products.php">Products</a></li>
            <li><a href="suppliers.php">Suppliers</a></li>
            <li><a href="stocks.php">Stocks</a></li>
            <li><a href="sales.php">Sales</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="ai.php">AI</a></li>
            <?php if ($_SESSION['is_admin']): ?>
                <li><a href="users.php" class="active">Users</a></li>
            <?php endif; ?>
            <li><a href="settings.php">Settings</a></li>
    </ul>
    </div>
      <!-- Main Content -->
        <div class="content products-page">
            <h1>Users List</h1>

            <!-- Users Table -->
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                        <select name="new_admin_status" onchange="this.form.submit();">
                                            <option value="1" <?php echo ($row['is_admin'] == 1) ? 'selected' : ''; ?>>Yes</option>
                                            <option value="0" <?php echo ($row['is_admin'] == 0) ? 'selected' : ''; ?>>No</option>
                                        </select>
                                        <input type="hidden" name="change_admin_status">
                                    </form>
                                </td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Pending'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="approve_user">Approve</button>
                                        </form>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="reject_user" onclick="return confirm('Reject this user?');">Reject</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                        <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
