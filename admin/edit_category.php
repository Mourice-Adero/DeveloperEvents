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

// Fetch category details based on category ID from URL parameter
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Retrieve category details from the database
    $stmt = $db->prepare("SELECT * FROM categories WHERE category_id = :category_id");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        // Category not found, redirect to manage_categories.php
        header('Location: manage_categories.php');
        exit();
    }
} else {
    // Category ID not provided, redirect to manage_categories.php
    header('Location: manage_categories.php');
    exit();
}

// Handle form submission to update category details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    // Retrieve form data
    $category_name = $_POST['category_name'];

    // Update category details in the database
    $stmt = $db->prepare("UPDATE categories SET category_name = :category_name WHERE category_id = :category_id");
    $stmt->bindParam(':category_name', $category_name);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    // Redirect to manage_categories.php after updating category
    header('Location: manage_categories.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Panel - Edit Category</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="manage_events.php">Manage Events</a></li>
                <li><a href="manage_categories.php">Manage Categories</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="admin-content">
        <div class="container">
            <h2>Edit Category</h2>
            <!-- Display form to edit category details -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?category_id=<?php echo $category['category_id']; ?>" method="POST">
                <input type="text" name="category_name" placeholder="Category Name" value="<?php echo $category['category_name']; ?>" required>
                <button type="submit" name="update_category">Update Category</button>
            </form>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
