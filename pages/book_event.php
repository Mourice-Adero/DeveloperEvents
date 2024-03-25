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

// Check if event ID is provided and is numeric
if (!isset($_POST['event_id']) || !is_numeric($_POST['event_id'])) {
    // Redirect to events page if event ID is not provided or invalid
    header('Location: events.php');
    exit();
}

// Retrieve form data
$event_id = filter_var($_POST['event_id']);
$user_id = filter_var($_SESSION['user_id']);
$current_location = filter_var($_POST['current_location']);
$phone_number = filter_var($_POST['phone_number']);
// Add additional fields relevant to event registration here

// Validate phone number format
if (!preg_match('/^\d{10}$/', $phone_number)) {
    header('Location: single_event.php?event_id=' . $event_id . '&message=Invalid phone number format');
    exit();
}

// Check if the current location is provided
if (empty($current_location)) {
    header('Location: single_event.php?event_id=' . $event_id . '&message=Current location is required');
    exit();
}
// Check if already booked in event_bookings table
$stmt = $db->prepare("SELECT * FROM event_bookings WHERE event_id = :event_id AND user_id = :user_id");
$stmt->bindParam(':event_id', $event_id);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if ($booking) {
    if ($booking['cancellation_status'] == 1) {
        // Update booking status if previously cancelled
        $booking_id = $booking['booking_id'];
        $stmt = $db->prepare("UPDATE event_bookings SET cancellation_status = 0, confirmation_status = 0, current_location = :current_location, phone_number = :phone_number WHERE booking_id = :booking_id");
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->bindParam(':current_location', $current_location);
        $stmt->bindParam(':phone_number', $phone_number);
        if ($stmt->execute()) {
            header('Location: single_event.php?event_id=' . $event_id . '&message=Booking successfully restored!');
            exit();
        } else {
            header('Location: single_event.php?event_id=' . $event_id . '&message=Failed to restore booking!');
            exit();
        }
    } else {
        // User has already booked this event
        header('Location: single_event.php?event_id=' . $event_id . '&message=You have already registered to this event!');
        exit();
    }
} else {
    // Insert booking details into the event_bookings table
    $stmt = $db->prepare("INSERT INTO event_bookings (event_id, user_id, current_location, phone_number) VALUES (:event_id, :user_id, :current_location, :phone_number)");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':current_location', $current_location);
    $stmt->bindParam(':phone_number', $phone_number);

    if ($stmt->execute()) {
        // Redirect to profile page with success message
        header('Location: single_event.php?event_id=' . $event_id . '&message=Registration Successful. Thank you!');
        exit();
    } else {
        // Redirect to profile page with error message
        header('Location: single_event.php?event_id=' . $event_id . '&message=Failed to book event');
        exit();
    }
}
