<?php
// Start or resume session
session_start();

// Check if admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Fetch users from the users table
$stmt_users = $db->query("SELECT * FROM users");
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Fetch admins from the admins table
$stmt_admins = $db->query("SELECT * FROM admin");
$admins = $stmt_admins->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <h1>Admin Panel - Manage Users</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="manage_events.php">Manage Events</a></li>
                <li><a href="manage_categories.php">Manage Categories</a></li>
                <li><a href="manage_feedbacks.php">Manage Feedbacks</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="admin-content">
        <div class="container">
            <h2>Manage Users</h2>
            <h3>Users</h3>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <!-- Add more user-related fields as needed -->
                </tr>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <!-- Display more user-related fields -->
                    </tr>
                <?php endforeach; ?>
            </table>
            <h3>Admins</h3>
            <table>
                <tr>
                    <th>Admin ID</th>
                    <th>Username</th>
                    <!-- Add more admin-related fields as needed -->
                </tr>
                <?php foreach ($admins as $admin) : ?>
                    <tr>
                        <td><?php echo $admin['admin_id']; ?></td>
                        <td><?php echo $admin['username']; ?></td>
                        <!-- Display more admin-related fields -->
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>