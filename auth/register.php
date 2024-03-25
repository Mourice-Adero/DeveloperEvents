<?php
// Include database connection and common functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start or resume session
session_start();

// Check if user is already logged in, if yes, redirect to events page
if (isset($_SESSION['user_id'])) {
    header('Location: ../events.php');
    exit();
}

$username = "";
$email = "";
// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate username
    if (empty($username)) {
        $error_message = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error_message = "Username can only contain letters, numbers, and underscores.";
    }

    // Validate email
    if (empty($email)) {
        $error_message = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $error_message = "Password is required.";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }

    // If no error, attempt to register the user
    if (empty($error_message)) {
        $result = register_user($username, $email, $password);
        if ($result === true) {
            // Registration successful, redirect to login page
            header('Location: login.php');
            exit();
        } else {
            // Display error message if registration fails
            $error_message = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .register-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .register-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .register-form form {
            display: flex;
            flex-direction: column;
        }

        .register-form label {
            margin-bottom: 5px;
        }

        .register-form input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .register-form button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register-form button:hover {
            background-color: #0056b3;
        }

        .register-form p {
            margin-top: 10px;
            text-align: center;
        }

        .register-form p a {
            color: #007bff;
            text-decoration: none;
        }

        .register-form p a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-form">
            <h2>Register</h2>
            <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($username); ?>">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>

</html>