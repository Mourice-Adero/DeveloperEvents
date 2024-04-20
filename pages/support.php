<?php
// Start or resume session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: #fff;
        }

        body::before {
            background-color: rgba(0, 0, 0, 0);
        }

        .help-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            z-index: 1000;
        }

        .help-section {
            margin-bottom: 40px;
        }

        .help-section h2 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .help-section p {
            margin-bottom: 15px;
        }

        .help-section ul {
            margin-bottom: 15px;
        }

        .help-section ul li {
            margin-bottom: 5px;
        }

        .feedback-link {
            text-decoration: none;
            color: #007bff;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "./header.php"; ?>
    <div class="help-container">
        <h1>Help & Support</h1>
        <div class="section">
            <p>You can send us direct messages by filling the feedback for in <a href="./feedback.php" class="feedback-link">Feedback Page</a></p>
        </div>
        <div class="help-section">
            <h2>Booking Events</h2>
            <p>To book an event, follow these steps:</p>
            <ul>
                <li>Login to your account.</li>
                <li>Navigate to the Events page.</li>
                <li>Find the event you want to book and click on it.</li>
                <li>Click the "Book Now" button and follow the prompts to complete the booking process.</li>
            </ul>
        </div>

        <div class="help-section">
            <h2>Giving Feedback for Individual Events</h2>
            <p>To give feedback for an individual event, follow these steps:</p>
            <ul>
                <li>Login to your account.</li>
                <li>Go to your Profile page.</li>
                <li>Find the event for which you want to provide feedback.</li>
                <li>Click on the "Submit Feedback" button.</li>
                <li>Enter your feedback in the provided text area and submit the form.</li>
            </ul>
        </div>

        <div class="help-section">
            <h2>Giving General Feedback</h2>
            <p>To give general feedback, follow these steps:</p>
            <ul>
                <li>Login to your account.</li>
                <li>Go to the Feedback page.</li>
                <li>Click on the "Provide Feedback" button.</li>
                <li>Enter your feedback in the provided text area and submit the form.</li>
            </ul>
        </div>

        <div class="help-section">
            <h2>Updating Profile</h2>
            <p>To update your profile information, follow these steps:</p>
            <ul>
                <li>Login to your account.</li>
                <li>Go to your Profile page.</li>
                <li>Update the necessary fields and click the "Save Changes" button.</li>
                <li>Click on the "Update Profile" button.</li>
            </ul>
        </div>

        <div class="help-section">
            <h2>Connecting with Other Users</h2>
            <p>To connect with other users, follow these steps:</p>
            <ul>
                <li>Login to your account.</li>
                <li>Go to the Connect page.</li>
                <li>Search for the user you want to connect with.</li>
            </ul>
        </div>

        <div class="help-section">
            <h2>Managing Profile Visibility</h2>
            <p>To manage your profile visibility, follow these steps:</p>
            <ul>
                <li>Login to your account.</li>
                <li>Go to your Profile page.</li>
                <li>Click on the "Edit Visibility" button.</li>
                <li>Choose the desired visibility settings (public or private).</li>
                <li>Click the "Save Changes" button to update your visibility settings.</li>
            </ul>
        </div>

    </div>
    <?php include "./footer.php"; ?>
</body>

</html>