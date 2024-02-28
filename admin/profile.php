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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Profile</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <!-- Add more admin panel links as needed -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="admin-profile">
        <div class="container">
            <h2>Welcome, <?php echo $admin['username']; ?>!</h2>
            <!-- Display admin profile details -->
            <p>Username: <?php echo $admin['username']; ?></p>
            <!-- Add more admin profile details as needed -->
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
