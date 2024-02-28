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

// Retrieve counts for various entities
$stmt = $db->query("SELECT COUNT(*) FROM events");
$total_events = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM categories");
$total_categories = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM feedbacks");
$total_feedbacks = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
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
    <section class="admin-dashboard">
        <div class="container">
            <h2>Welcome, Admin!</h2>
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Events</h3>
                    <p><?php echo $total_events; ?></p>
                    <a href="manage_events.php">View Events</a>
                </div>
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                    <a href="manage_users.php">View Users</a>
                </div>
                <div class="card">
                    <h3>Total Categories</h3>
                    <p><?php echo $total_categories; ?></p>
                    <a href="manage_categories.php">View Categories</a>
                </div>
                <div class="card">
                    <h3>Total Feedbacks</h3>
                    <p><?php echo $total_feedbacks; ?></p>
                    <a href="manage_feedbacks.php">View Feedbacks</a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
