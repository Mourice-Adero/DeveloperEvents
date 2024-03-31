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

// Initialize errors array
$errors = [];

// Check if form is submitted for submitting feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    // Retrieve form data
    $event_id = $_POST['event_id'];
    $feedback = $_POST['feedback'];

    // Validate feedback
    if (empty($feedback)) {
        $errors[] = "Feedback is required.";
    }

    // If there are no errors, proceed with submitting feedback
    if (empty($errors)) {
        // Insert feedback into the database
        $stmt = $db->prepare("INSERT INTO event_feedback (event_id, user_id, feedback) VALUES (:event_id, :user_id, :feedback)");
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':feedback', $feedback);
        $stmt->execute();

        // Set session variable to indicate successful feedback submission
        $_SESSION['feedback_submitted'] = "Feedback submitted successfully.";

        // Redirect back to the profile page with a success message
        header("Location: profile.php");
        exit();
    } else {
        // Set session variable to hold errors
        $_SESSION['feedback_errors'] = $errors;

        // Redirect back to the profile page with error messages
        header("Location: profile.php");
        exit();
    }
} else {
    // If form is not submitted properly, redirect back to the profile page with an error message
    header("Location: profile.php?feedback_error=1");
    exit();
}
