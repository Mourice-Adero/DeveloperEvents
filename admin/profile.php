<?php
session_start();

// Check if admin is logged in, redirect to login page if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Fetch admin details
$stmt = $db->prepare("SELECT * FROM admin WHERE admin_id = :admin_id");
$stmt->bindParam(':admin_id', $_SESSION['admin_id']);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize variables for error handling
$errors = array();

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Retrieve form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate username
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    // Validate current password
    if (empty($current_password)) {
        $errors['current_password'] = 'Current password is required.';
    } elseif (!password_verify($current_password, $admin['password'])) {
        $errors['current_password'] = 'Incorrect current password.';
    }

    // Validate new password
    if (!empty($new_password) || !empty($confirm_password)) {
        if (empty($new_password)) {
            $errors['new_password'] = 'New password is required.';
        } elseif (strlen($new_password) < 6) {
            $errors['new_password'] = 'Password must be at least 6 characters long.';
        } elseif ($new_password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }
    }

    // If no errors, update profile
    if (empty($errors)) {
        // Update username and email
        $update_stmt = $db->prepare("UPDATE admin SET username = :username, email = :email WHERE admin_id = :admin_id");
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':admin_id', $_SESSION['admin_id']);
        $update_stmt->execute();

        // Update password if a new password is provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_stmt = $db->prepare("UPDATE admin SET password = :password WHERE admin_id = :admin_id");
            $update_password_stmt->bindParam(':password', $hashed_password);
            $update_password_stmt->bindParam(':admin_id', $_SESSION['admin_id']);
            $update_password_stmt->execute();
        }

        // Redirect to profile page
        header('Location: admin_profile.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php
    include './header.php';
    ?>
    <section class="admin-profile">
        <div class="container">
            <h2>Welcome, <?php echo $admin['username']; ?>!</h2>
            <!-- Display error messages -->
            <?php if (!empty($errors)) : ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li class="error"><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- Update profile form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : $admin['username']; ?>"><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : $admin['email']; ?>"><br>
                <label for="current_password">Current Password:</label><br>
                <input type="password" id="current_password" name="current_password"><br>
                <label for="new_password">New Password:</label><br>
                <input type="password" id="new_password" name="new_password"><br>
                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password"><br><br>
                <button type="submit" name="update_profile">Update Profile</button>
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