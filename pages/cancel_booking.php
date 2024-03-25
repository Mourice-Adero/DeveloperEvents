<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Check if event ID is provided and is numeric
if (!isset($_POST['event_id']) || !is_numeric($_POST['event_id'])) {
    // Redirect to profile page with error message
    $_SESSION['booking_cancel_error'] = true;
    header('Location: ../pages/profile.php?error=Invalid event ID');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Prepare SQL statement to update booking status
$stmt = $db->prepare("UPDATE event_bookings SET cancellation_status = 1, confirmation_status = 0 WHERE user_id = :user_id AND event_id = :event_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->bindParam(':event_id', $_POST['event_id']);

// Execute the SQL statement
if ($stmt->execute()) {
    // Set session variable to indicate successful cancellation
    $_SESSION['booking_cancelled'] = true;
    // Redirect to profile page with success message
    header('Location: ../pages/profile.php?success=Booking cancelled successfully');
    exit();
} else {
    // Redirect to profile page with error message
    $_SESSION['booking_cancel_error'] = true;
    header('Location: ../pages/profile.php?error=Failed to cancel booking');
    exit();
}
