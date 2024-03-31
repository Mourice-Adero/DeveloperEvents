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
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $phone_number = $_POST['phone_number'];
    $visibility = $_POST['visibility'];
    $profession = $_POST['profession'];
    $linkedin = $_POST['linkedin'];
    $twitter = $_POST['twitter'];
    $github = $_POST['github'];

    // File upload
    $targetDir = "../assets/images/profile/";
    $fileName = basename($_FILES["profile_picture"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Validate form data
    $errors = [];

    // Check if file is selected
    if (!empty($_FILES["profile_picture"]["name"])) {
        // Allow certain file formats
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($fileType, $allowTypes)) {
            $errors[] = "Sorry, only JPG, JPEG, PNG, GIF files are allowed.";
        }
    }

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $username)) {
        $errors[] = "Username can only contain letters and spaces.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate current password
    $stmt = $db->prepare("SELECT password FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($current_password, $user['password'])) {
        $errors[] = "Current password is incorrect.";
    }

    // Validate new password
    if (!empty($new_password)) {
        if (strlen($new_password) < 8) {
            $errors[] = "New password must be at least 8 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New password and confirm password do not match.";
        }
    }

    // If there are no errors, proceed with updating profile
    if (empty($errors)) {
        // Upload file if selected
        if (!empty($_FILES["profile_picture"]["name"])) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
                // File uploaded successfully
                // Update user profile in the database with profile picture
                $update_stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password, phone_number = :phone_number, visibility = :visibility, profession = :profession, linkedin = :linkedin, twitter = :twitter, github = :github, profile_picture = :profile_picture WHERE user_id = :user_id");
                $update_stmt->bindParam(':profile_picture', $fileName);
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            // Update user profile in the database without profile picture
            $update_stmt = $db->prepare("UPDATE users SET username = :username, email = :email, password = :password, phone_number = :phone_number, visibility = :visibility, profession = :profession, linkedin = :linkedin, twitter = :twitter, github = :github WHERE user_id = :user_id");
        }

        // Bind parameters and execute update query
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':email', $email);
        // If new password is provided and not empty, hash it
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // Update password only if new password is provided
            $update_stmt->bindParam(':password', $hashed_password);
        } else {
            // Otherwise, keep the existing password
            $update_stmt->bindParam(':password', $user['password']);
        }
        $update_stmt->bindParam(':phone_number', $phone_number);
        $update_stmt->bindParam(':visibility', $visibility);
        $update_stmt->bindParam(':profession', $profession);
        $update_stmt->bindParam(':linkedin', $linkedin);
        $update_stmt->bindParam(':twitter', $twitter);
        $update_stmt->bindParam(':github', $github);
        $update_stmt->bindParam(':user_id', $_SESSION['user_id']);
        $update_stmt->execute();


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
