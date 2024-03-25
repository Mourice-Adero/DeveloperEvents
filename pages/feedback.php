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
$error_message = '';

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = trim($_POST['feedback']); // Trim whitespace from feedback
    $user_id = $_SESSION['user_id'];

    // Check if feedback is empty or contains only whitespace
    if (empty($feedback)) {
        // Set error message
        $error_message = 'Please enter your feedback.';
    } else {
        // Save feedback to the database
        save_feedback($db, $user_id, $feedback);

        // Set thank you message
        $thank_you_message = 'Thank you for your feedback!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Style for the thank you message */
        .thank-you-message {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: <?php echo !empty($thank_you_message) ? 'block' : 'none'; ?>;
            /* Display thank you message if it exists */
        }

        /* Style for the error message */
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: <?php echo !empty($error_message) ? 'block' : 'none'; ?>;
            /* Display error message if it exists */
        }

        /* Style for the image container */
        .image-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }

        /* Style for the image */
        .feedback-image {
            width: 300px;
            height: auto;
            margin-right: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Style for the feedback form */
        #feedback-form {
            flex: 1;
            /* Allow the form to take remaining space */
        }

        #feedback-form textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
    </style>
</head>

<body>
    <?php include "./header.php"; ?>
    <section class="feedback h-65">
        <div class="container">
            <h2>Feedback</h2>
            <!-- Display thank you message -->
            <?php if (!empty($thank_you_message)) : ?>
                <p class="thank-you-message" id="thank-you-message"><?php echo $thank_you_message; ?></p>
            <?php endif; ?>
            <!-- Display error message -->
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <!-- Feedback form and image container -->
            <div class="image-container">
                <img src="../assets/images/pngegg (6).png" alt="Feedback Image" class="feedback-image">
                <!-- Feedback form -->
                <form id="feedback-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <textarea name="feedback" placeholder="Write your feedback here..." rows="10" required></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>
    <?php include "./footer.php"; ?>
</body>

</html>