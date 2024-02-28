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

// Fetch all comments from the database
$stmt = $db->query("SELECT * FROM comments");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission to delete comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    // Retrieve comment ID from form data
    $comment_id = $_POST['comment_id'];

    // Delete comment from the database
    $stmt = $db->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();

    // Redirect to manage_comments.php to refresh the page
    header('Location: manage_comments.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Panel - Manage Comments</h1>
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
            <h2>Manage Comments</h2>
            <!-- Display existing comments -->
            <ul>
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <?php echo $comment['comment']; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                            <button type="submit" name="delete_comment">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
