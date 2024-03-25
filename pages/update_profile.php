<?php
// Start or resume session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Check if form is submitted for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate form data
    $errors = [];

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }
    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // If there are no errors, proceed with updating profile
    if (empty($errors)) {
        // Update user profile in the database
        $stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE user_id = :user_id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT)); // Hash the password
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        // Set session variable to indicate profile update success
        $_SESSION['profile_updated'] = true;

        // Redirect back to profile page
        header('Location: profile.php');
        exit();
    } else {
        // Set session variable to hold errors
        $_SESSION['profile_update_errors'] = $errors;

        // Redirect back to profile page with errors
        header('Location: profile.php');
        exit();
    }
} else {
    // If form is not submitted properly, redirect to profile page
    header('Location: profile.php');
    exit();
}
