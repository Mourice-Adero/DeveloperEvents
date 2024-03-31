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

$message = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event['event_name']; ?> - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Add your custom CSS styles for the success message box */
        .message {
            position: absolute;
            margin: 25px;
            right: 0;
            top: 0;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .comment-list {
            max-height: 200px;
            /* Set a maximum height for the comments section */
            overflow-y: auto;
            padding: 5px;
            /* Enable vertical scrolling if content overflows */
        }

        .comment {
            padding: 5px;
        }

        /* Alternating row colors */
        .comment:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .event-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .event-description {
            padding: 10px 0 5px 0;

        }
    </style>
</head>

<body>
    <?php
    include "./header.php";
    ?>
    <section class="single-event">
        <div class="container event-container">
            <div>
                <h2><?php echo $event['event_name']; ?></h2>
                <img src="../assets/images/<?php echo $event['event_image']; ?>" width="500" class="object-cover" alt="<?php echo $event['event_name']; ?>">
                <p class="event-description"><?php echo $event['event_description']; ?></p>
                <p><strong>From:</strong> <?php echo date('M d, Y H:i', strtotime($event['event_from'])); ?></p>
                <p><strong>To:</strong> <?php echo date('M d, Y H:i', strtotime($event['event_to'])); ?></p>
                <p><strong>Location:</strong> <?php echo $event['event_location']; ?></p>
                <?php if (!empty($event['event_external_link'])) : ?>
                    <p><strong>External Link:</strong> <a href="<?php echo $event['event_external_link']; ?>" target="_blank">Visit Website</a></p>
                <?php else : ?>
                    <p><strong>External Link:</strong>Link Not Availabe</p>
                <?php endif; ?>
            </div>

            <!-- Booking form -->
            <div id="book-event">
                <?php
                if (isset($_GET['message'])) {
                    $message = $_GET['message'];
                    echo "<p class='message'>" . $message . "</p>";
                }
                ?>
                <h2 style="margin-top: 10px;">Register For Event</h2>
                <form action="book_event.php" method="POST" onsubmit="return confirm('Are you sure you want to register for this event?')">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" readonly required><br><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" readonly required><br><br>
                    <label for="current_location">Current Location/Institution:</label>
                    <input type="text" id="current_location" name="current_location" required><br><br>
                    <label for="phone_number">Phone Number:</label>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $stmt = $db->prepare("SELECT phone_number FROM users WHERE user_id = :user_id");
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $phone_number = $user['phone_number'];
                    ?>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required><br><br>
                    <button type="submit">Book Event</button>
                </form>
            </div>

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
                <?php if ($comments) : ?>
                    <?php foreach ($comments as $key => $comment) : ?>
                        <div class="comment">
                            <p><strong><?php echo get_username_by_id($comment['user_id']); ?>:</strong> <?php echo $comment['comment']; ?></p>
                            <small><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></small>
                        </div>
                        <!-- Add a line break after the fifth comment -->
                        <?php if ($key === 4) break; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No comments yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    include "./footer.php";
    ?>
    <script>
        // Automatically hide the success message after 5 seconds
        setTimeout(function() {
            document.querySelector('.message').style.display = 'none';
        }, 5000);
    </script>
</body>

</html>