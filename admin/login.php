<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

require_once '../includes/db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
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
            background-size: cover;
            background-position: center;
            padding: 20px 0;
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
            <h1>Admin Login</h1>
        </div>
    </header>
    <div class="container">
        <div class="login-form">
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
            <?php if (isset($error_message) && !empty($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>