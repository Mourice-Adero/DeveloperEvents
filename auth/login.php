<?php
// Include database connection and common functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start or resume session
session_start();

// Check if user is already logged in, if yes, redirect to events page
if (isset($_SESSION['user_id'])) {
    header('Location: ../pages/events.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate login credentials
    $user = authenticate_user($username, $password);
    if ($user) {
        // Set user session and redirect to events page
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ../pages/events.php');
        exit();
    } else {
        // Display error message if authentication fails
        $error_message = "Invalid username or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if(isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
