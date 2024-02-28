<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Welcome to Developer Events</h1>
        <nav>
            <ul>
                <li><a href="events.php">View Events</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="../auth/login.php">Login</a></li>
                <li><a href="../auth/register.php">Register</a></li>
            </ul>
        </nav>
    </header>
    <section class="hero">
        <div class="container">
            <h2>Welcome to Developer Events</h2>
            <p>Find and explore developer events from around the world. Join us to enhance your skills, connect with peers, and stay updated with the latest trends in technology.</p>
            <a href="events.php" class="btn">View Events</a>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
