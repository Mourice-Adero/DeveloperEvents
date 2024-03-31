<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Function to find password reset information based on email and code
function find_password_reset_info($email, $code)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM password_reset WHERE email = :email AND code = :code");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset_password'])) {
        // Reset password form submitted
        $email = $_POST['email'];
        $code = $_POST['code'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate email
        if (empty($email)) {
            $_SESSION['reset_error'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['reset_error'] = "Invalid email format.";
        }

        // Validate reset code
        if (empty($code)) {
            $_SESSION['reset_error'] = "Reset code is required.";
        }

        // Validate password
        if (empty($password)) {
            $_SESSION['reset_error'] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $_SESSION['reset_error'] = "Password must be at least 6 characters long.";
        }

        // Validate confirm password
        if (empty($confirm_password)) {
            $_SESSION['reset_error'] = "Confirm password is required.";
        } elseif ($password !== $confirm_password) {
            $_SESSION['reset_error'] = "Passwords do not match.";
        }

        // If no validation errors, proceed with password reset
        if (!isset($_SESSION['reset_error'])) {
            // Check if the code and email match an entry in the password_reset table
            $reset_info = find_password_reset_info($email, $code);
            if ($reset_info) {
                // Check if the reset code is expired
                if (strtotime($reset_info['expiry']) > time()) {
                    // Code is valid and not expired, update the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':email', $email);
                    if ($stmt->execute()) {
                        // Delete the record from the password reset table
                        $stmt = $db->prepare("DELETE FROM password_reset WHERE email = :email AND code = :code");
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':code', $code);
                        $stmt->execute();

                        // Password reset successful, redirect to login page
                        $_SESSION['reset_success'] = "Password reset successful. You can now login with your new password.";
                        header('Location: login.php');
                        exit();
                    } else {
                        $_SESSION['reset_error'] = "Failed to reset password. Please try again.";
                    }
                } else {
                    // Code is expired
                    $_SESSION['reset_error'] = "The password reset link has expired. Please request a new one.";
                }
            } else {
                // Code or email is invalid
                $_SESSION['reset_error'] = "Invalid password reset code or email address.";
            }
        }

        // Retain form data on validation error
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $code;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>

<body>
    <div class="container">
        <div class="reset-form">
            <h2>Reset Password</h2>
            <?php if (isset($_SESSION['reset_error'])) : ?>
                <p class="error"><?php echo $_SESSION['reset_error']; ?></p>
                <?php unset($_SESSION['reset_error']); ?>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : ''; ?>">
                <label for="code">Reset Code:</label>
                <input type="text" id="code" name="code" required value="<?php echo isset($_SESSION['reset_code']) ? $_SESSION['reset_code'] : ''; ?>">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
            <p>Remember your password? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>

</html>