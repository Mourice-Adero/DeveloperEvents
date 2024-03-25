<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $stmt = $db->prepare("SELECT * FROM categories WHERE category_id = :category_id");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        header('Location: manage_categories.php');
        exit();
    }
} else {
    header('Location: manage_categories.php');
    exit();
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $errors[] = "Category name is required.";
    }

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE categories SET category_name = :category_name WHERE category_id = :category_id");
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        header('Location: manage_categories.php');
        exit();
    }
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
    <?php
    include './header.php';
    ?>
    <section class="admin-content">
        <div class="container">
            <h2>Edit Category</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?category_id=<?php echo $category['category_id']; ?>" method="POST">
                <input type="text" name="category_name" placeholder="Category Name" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
                <?php if (!empty($errors)) : ?>
                    <div class="errors">
                        <ul>
                            <?php foreach ($errors as $error) : ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
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