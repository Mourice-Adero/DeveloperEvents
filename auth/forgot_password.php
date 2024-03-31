<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Function to generate a unique code for password reset
function generateUniqueCode($length = 20)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

// Function to store the reset code in the database
function store_reset_code($email, $code, $expiry)
{
    // Assume you have a database connection named $db
    global $db;

    // Prepare and execute SQL statement to store reset code
    $stmt = $db->prepare("INSERT INTO password_reset (email, code, expiry) VALUES (:email, :code, :expiry)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':expiry', $expiry);
    $stmt->execute();
}

// Function to send the password reset email
function send_reset_email($email, $code)
{
    // Here you would implement the code to send the email with the reset link.
    // This could involve using a library like PHPMailer or the built-in mail() function.
    // Below is a basic example using the mail() function.

    $subject = 'Password Reset Request';
    $message = "Dear User,\n\nYou have requested to reset your password. Please click the following link to reset your password:\n\nReset Link: http://yourdomain.com/reset_password.php?code=$code\n\nIf you didn't request this, please ignore this email.\n\nBest regards,\nDeveloper Events Team";
    $headers = 'From: your@example.com' . "\r\n" .
        'Reply-To: your@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Send the email
    mail($email, $subject, $message, $headers);
}

// Function to find a user by their email address
function find_user_by_email($email)
{
    // Assume you have a database connection named $db
    global $db;

    // Prepare and execute SQL statement to find the user by email
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the user from the database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user; // Returns the user if found, otherwise returns false
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $user = find_user_by_email($email);
    if ($user) {
        // Generate a unique code and store it in the database
        $code = generateUniqueCode();
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Code expires in 1 hour
        store_reset_code($email, $code, $expiry);

        // Send email with password reset link
        send_reset_email($email, $code);

        $_SESSION['reset_email_sent'] = true;
        header('Location: reset_password.php');
        exit();
    } else {
        $error_message = "Email not found. Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Developer Events</title>
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

        .forgot-password-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .forgot-password-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .forgot-password-form form {
            display: flex;
            flex-direction: column;
        }

        .forgot-password-form label {
            margin-bottom: 5px;
        }

        .forgot-password-form input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .forgot-password-form button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .forgot-password-form button:hover {
            background-color: #0056b3;
        }

        .forgot-password-form p {
            margin-top: 10px;
            text-align: center;
        }

        .forgot-password-form p a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password-form p a:hover {
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
        <div class="forgot-password-form">
            <h2>Forgot Password</h2>
            <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <label for="email">Enter your email address:</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Send Reset Link</button>
            </form>
            <p>Remember your password? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>

</html>