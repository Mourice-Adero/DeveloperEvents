<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$stmt = $db->query("SELECT COUNT(*) FROM events");
$total_events = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM categories");
$total_categories = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM feedbacks");
$total_feedbacks = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM event_bookings");
$total_booked_events = $stmt->fetchColumn();

$stmt = $db->query("SELECT COUNT(*) FROM comments");
$total_event_comments = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<style>
    .card a {
        padding: 10px 20px;
        background: linear-gradient(to right, #caaf12, #4975b8);
        color: #000000;
        border: none;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
    }

    .card a:hover,
    .card a:hover {
        background: linear-gradient(to right, #a18b0dd7, #39629ede);
        color: #000000;
    }

    .item-number {
        padding: 5px;
    }
</style>

<body>
    <?php
    include './header.php';
    ?>
    <section class="admin-dashboard h-65" style="margin-bottom: 0;">
        <div class="container">
            <h2>Welcome, Admin!</h2>
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Events</h3>
                    <p class="item-number"><?php echo $total_events; ?></p>
                    <a href="manage_events.php">View Events</a>
                </div>
                <div class="card">
                    <h3>Total Booked Events</h3>
                    <p class="item-number"><?php echo $total_booked_events; ?></p>
                    <a href="manage_bookings.php">View Booked Events</a>
                </div>
                <div class="card">
                    <h3>Total Users</h3>
                    <p class="item-number"><?php echo $total_users; ?></p>
                    <a href="manage_users.php">View Users</a>
                </div>
                <div class="card">
                    <h3>Total Categories</h3>
                    <p class="item-number"><?php echo $total_categories; ?></p>
                    <a href="manage_categories.php">View Categories</a>
                </div>
                <div class="card">
                    <h3>Feedbacks/Messages</h3>
                    <p class="item-number"><?php echo $total_feedbacks; ?></p>
                    <a href="manage_feedbacks.php">View Feedbacks</a>
                </div>
                <div class="card">
                    <h3>Event Comments</h3>
                    <p class="item-number"><?php echo $total_event_comments; ?></p>
                    <a href="manage_comments.php">View Comments</a>
                </div>
            </div>
        </div>
    </section>
    <?php include './footer.php' ?>
</body>

</html>