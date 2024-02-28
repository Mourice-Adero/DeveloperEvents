<?php
// Start or resume session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';
// Include functions.php
require_once '../includes/functions.php';

// Define a variable to hold the thank you message
$thank_you_message = '';

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = $_POST['feedback'];
    $user_id = $_SESSION['user_id'];

    // Save feedback to the database
    save_feedback($db, $user_id, $feedback);

    // Set thank you message
    $thank_you_message = 'Thank you for your feedback!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Developer Events</title>
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
    <section class="feedback">
        <div class="container">
            <h2>Feedback</h2>
            <!-- Display thank you message -->
            <?php if (!empty($thank_you_message)): ?>
                <p><?php echo $thank_you_message; ?></p>
            <?php else: ?>
                
            <?php endif; ?>
            <!-- Display feedback form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <textarea name="feedback" placeholder="Write your feedback here..." required></textarea>
                    <button type="submit">Submit</button>
                </form>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
