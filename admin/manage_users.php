<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Initialize variables for error handling
$error_message_users = '';
$error_message_admins = '';

// Fetch users from the users table
$stmt_users = $db->query("SELECT * FROM users");
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Check if users are fetched successfully
if (!$users) {
    $error_message_users = 'Failed to fetch users from the database.';
}

// Fetch admins from the admins table
$stmt_admins = $db->query("SELECT * FROM admin");
$admins = $stmt_admins->fetchAll(PDO::FETCH_ASSOC);

// Check if admins are fetched successfully
if (!$admins) {
    $error_message_admins = 'Failed to fetch admins from the database.';
}

// Check if admin ID is provided and is numeric
if (isset($_POST['admin_id']) && is_numeric($_POST['admin_id'])) {
    // Prepare SQL statement to delete admin
    $stmt = $db->prepare("DELETE FROM admin WHERE admin_id = :admin_id");
    $stmt->bindParam(':admin_id', $_POST['admin_id']);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Redirect with success message
        $_SESSION['delete_admin_success'] = 'Admin deleted successfully';
        header('Location: manage_users.php');
        exit();
    } else {
        // Redirect with error message
        $_SESSION['delete_admin_error'] = 'Failed to delete admin';
        header('Location: manage_users.php');
        exit();
    }
}

// Check if user ID is provided and is numeric
if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    // Prepare SQL statement to delete user
    $stmt = $db->prepare("DELETE FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_POST['user_id']);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Redirect with success message
        $_SESSION['delete_user_success'] = 'User deleted successfully';
        header('Location: manage_users.php');
        exit();
    } else {
        // Redirect with error message
        $_SESSION['delete_user_error'] = 'Failed to delete user';
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
    <title>Manage Users - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php
    include './header.php';
    ?>
    <section class="admin-content">
        <div class="container">
            <h2>Manage Users</h2>
            <!-- Display error message if there is an issue fetching users -->
            <?php if ($error_message_users) : ?>
                <p class="error"><?php echo $error_message_users; ?></p>
            <?php else : ?>
                <h3>Users</h3>
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Form for delete action -->
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <?php if ($error_message_admins) : ?>
                <p class="error"><?php echo $error_message_admins; ?></p>
            <?php else : ?>
                <h3>Admins</h3>
                <table>
                    <tr>
                        <th>Admin ID</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($admins as $admin) : ?>
                        <tr>
                            <td><?php echo $admin['admin_id']; ?></td>
                            <td><?php echo $admin['username']; ?></td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Form for delete action -->
                                    <input type="hidden" name="admin_id" value="<?php echo $admin['admin_id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <a href="./register.php"><button>Add Admin</button></a>
            <?php endif; ?>
        </div>
    </section>
    <footer>
        <div class="container">
        </div>
        </section>
        <footer>
            <div class="container">
                <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
            </div>
        </footer>
</body>

</html>