<?php
// Start or resume session
session_start();

// Check if admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Fetch all categories from the database
$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission to add new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    // Retrieve form data
    $category_name = $_POST['category_name'];

    // Insert new category into the database
    $stmt = $db->prepare("INSERT INTO categories (category_name) VALUES (:category_name)");
    $stmt->bindParam(':category_name', $category_name);
    $stmt->execute();

    // Redirect to manage_categories.php to refresh the page
    header('Location: manage_categories.php');
    exit();
}

// Handle form submission to delete category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    // Retrieve category ID from form data
    $category_id = $_POST['category_id'];

    // Delete category from the database
    $stmt = $db->prepare("DELETE FROM categories WHERE category_id = :category_id");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    // Redirect to manage_categories.php to refresh the page
    header('Location: manage_categories.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Panel - Manage Categories</h1>
        <nav>
            <ul>
            <li><a href="index.php">Dashboard</a></li>
                <li><a href="manage_events.php">Manage Events</a></li>
                <li><a href="manage_categories.php">Manage Categories</a></li>
                <li><a href="manage_feedbacks.php">Manage Feedbacks</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="admin-content">
        <div class="container">
            <h2>Manage Categories</h2>
            <!-- Display form to add new category -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" name="category_name" placeholder="Category Name" required>
                <button type="submit" name="add_category">Add Category</button>
            </form>
            <!-- Display existing categories in a table -->
            <table>
                <tr>
                    <th>Category Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['category_name']; ?></td>
                        <td><a href="edit_category.php?category_id=<?php echo $category['category_id']; ?>">Edit</a></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                <button type="submit" name="delete_category">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
