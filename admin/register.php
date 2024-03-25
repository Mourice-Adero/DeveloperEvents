<?php
// Start or resume session
session_start();

// Check if admin is already logged in, redirect to admin dashboard if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Initialize variables
$username = $password = $confirm_password = '';
$error_messages = array();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate username
    if (empty($username)) {
        $error_messages['username'] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error_messages['username'] = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error_messages['username'] = "Username is already taken. Please choose a different username.";
        }
    }

    // Validate password
    if (empty($password)) {
        $error_messages['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $error_messages['password'] = "Password must be at least 6 characters long.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $error_messages['confirm_password'] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $error_messages['confirm_password'] = "Passwords do not match.";
    }

    // If no errors, insert admin into database
    if (empty($error_messages)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin into database
        $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Redirect to login page
        header('Location: manage_users.php');
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header {
            background-image: url('./../assets/images/pngegg\ \(1\).png');
            /* Path to your PNG background image */
            background-size: cover;
            background-position: center;
            padding: 20px 0;
            /* Adjust padding as needed */
            text-align: center;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header h1 {
            font-size: 36px;
            color: #fff;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-form form {
            display: flex;
            flex-direction: column;
        }

        .login-form .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .login-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .login-form input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-form button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .login-form .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-content">
            <h1>Admin Registration</h1>
        </div>
    </header>
    <section class="register-form">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <?php if (isset($error_messages['username'])) : ?>
                        <p class="error"><?php echo $error_messages['username']; ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (isset($error_messages['password'])) : ?>
                        <p class="error"><?php echo $error_messages['password']; ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <?php if (isset($error_messages['confirm_password'])) : ?>
                        <p class="error"><?php echo $error_messages['confirm_password']; ?></p>
                    <?php endif; ?>
                </div>
                <button type="submit">Add Admin</button>
                <a href="./manage_users.php"><button>Cancel</button></a>
            </form>
        </div>
    </section>
</body>

</html>