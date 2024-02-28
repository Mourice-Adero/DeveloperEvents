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

// Fetch feedbacks with user names from the database using a join operation
$stmt = $db->query("
    SELECT feedbacks.*, users.username 
    FROM feedbacks 
    INNER JOIN users ON feedbacks.user_id = users.user_id
");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedbacks - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Panel - Manage Feedbacks</h1>
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
            <h2>Manage Feedbacks</h2>
            <!-- Display existing feedbacks in a table -->
            <table>
                <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>Feedback</th>
                </tr>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo $feedback['username']; ?></td>
                        <td><?php echo $feedback['feedback_date']; ?></td>
                        <td><?php echo $feedback['feedback_text']; ?></td>
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
