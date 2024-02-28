<?php
// Start or resume session
session_start();

// Check if admin is already logged in, redirect to admin dashboard if logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Initialize variables
$username = $password = $confirm_password = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate username
    if (empty($username)) {
        $error_message = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error_message = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error_message = "Username is already taken. Please choose a different username.";
        }
    }

    // Validate password
    if (empty($password)) {
        $error_message = "Password is required.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $error_message = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    }

    // If no error, insert admin into database
    if (empty($error_message)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin into database
        $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Redirect to login page
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Registration</h1>
    </header>
    <section class="register-form">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
