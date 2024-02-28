<?php
// Include database connection and common functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start or resume session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Check if event ID is provided in the URL
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    // Redirect to events page if event ID is not provided or invalid
    header('Location: events.php');
    exit();
}

// Fetch event details from the database
$event_id = $_GET['event_id'];
$event = get_event_by_id($event_id);

// Fetch comments for the event from the database
$comments = get_comments_for_event($event_id);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    // Add comment to the database
    add_comment($event_id, $user_id, $comment);

    // Refresh the page to display the new comment
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event['event_name']; ?> - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Developer Events</h1>
        <nav>
            <ul>
                <li><a href="events.php">View Events</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="single-event">
        <div class="container">
            <h2><?php echo $event['event_name']; ?></h2>
            <img src="../assets/images/<?php echo $event['event_image']; ?>" width="300" alt="<?php echo $event['event_name']; ?>">
            <p><?php echo $event['event_description']; ?></p>
            <p><strong>Date & Time:</strong> <?php echo date('M d, Y H:i', strtotime($event['event_date'])); ?></p>
            <p><strong>Location:</strong> <?php echo $event['event_location']; ?></p>
            <?php if (!empty($event['event_external_link'])): ?>
                <p><strong>External Link:</strong> <a href="<?php echo $event['event_external_link']; ?>" target="_blank">Visit Website</a></p>
            <?php endif; ?>
        </div>
    </section>
    <section class="comments">
        <div class="container">
            <h3>Comments</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?event_id=' . $event_id); ?>" method="POST">
                <textarea name="comment" placeholder="Write your comment here..." required></textarea>
                <button type="submit">Submit</button>
            </form>
            <div class="comment-list">
                <?php if($comments): ?>
                    <?php foreach($comments as $comment): ?>
                        <div class="comment">
                            <p><strong><?php echo get_username_by_id($comment['user_id']); ?>:</strong> <?php echo $comment['comment']; ?></p>
                            <small><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet.</p>
                <?php endif; ?>
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
