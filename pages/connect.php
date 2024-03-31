<?php
// Include database connection and common functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch users with visibility enabled
$stmt = $db->prepare("SELECT * FROM users WHERE visibility = 1");
$stmt->execute();
$visible_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variable to hold search query
$search_query = '';

// Check if search query is provided
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    // Fetch users with visibility enabled and matching username
    $stmt = $db->prepare("SELECT * FROM users WHERE visibility = 1 AND username LIKE ?");
    $stmt->execute(["%$search_query%"]);
    $visible_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If no search query provided, fetch all users with visibility enabled
    $stmt = $db->prepare("SELECT * FROM users WHERE visibility = 1");
    $stmt->execute();
    $visible_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Additional CSS styles for the Connect page */

        .user-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .user-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            /* Lift up the card on hover */
        }

        .user-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 20px;
        }

        .user-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .user-card p {
            margin-bottom: 5px;
        }

        .social-icons a {
            color: #555;
            text-decoration: none;
            margin-right: 10px;
            transition: color 0.3s ease;
            padding: 5px;
            font-size: 1.5rem;
        }

        .social-icons a:hover {
            color: #007bff;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container h-65">
        <h2>Connect with Other Users</h2>
        <div class="search-bar">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                <input type="text" name="search" placeholder="Search by username" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="user-list">
            <?php if (empty($visible_users)) : ?>
                <p>No users found.</p>
            <?php else : ?>
                <?php foreach ($visible_users as $user) : ?>
                    <div class="user-card">
                        <img src="<?php echo (!empty($user['profile_picture'])) ? '../assets/images/profile/' . $user['profile_picture'] : '../assets/images/profile/avatar.png'; ?>" alt="Profile Picture">
                        <h3><?php echo $user['username']; ?></h3>
                        <p>Email: <?php echo $user['email']; ?></p>
                        <p>Phone: <?php echo $user['phone_number']; ?></p>
                        <div class="social-icons">
                            <a href="<?php echo $user['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                            <a href="<?php echo $user['twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="<?php echo $user['github']; ?>" target="_blank"><i class="fab fa-github"></i></a>
                            <!-- Add more social media icons as needed -->
                        </div>
                        <p>Profession: <?php echo $user['profession']; ?></p>
                        <!-- Add more user details as needed -->
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>