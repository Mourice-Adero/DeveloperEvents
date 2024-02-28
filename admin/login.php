<?php
session_start();

// Check if admin is already logged in, redirect to admin dashboard if logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username and password
    if (!empty($username) && !empty($password)) {
        // Check if admin exists
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($admin && password_verify($password, $admin['password'])) {
            // Authentication successful, set session variables
            $_SESSION['admin_id'] = $admin['admin_id'];
            // Redirect to admin dashboard
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Username and password are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>
    <section class="login-form">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
