<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    if (isset($_POST['category_name']) && !empty($_POST['category_name'])) {
        $category_name = $_POST['category_name'];

        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE category_name = :category_name");
        $stmt->bindParam(':category_name', $category_name);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $stmt = $db->prepare("INSERT INTO categories (category_name) VALUES (:category_name)");
            $stmt->bindParam(':category_name', $category_name);
            $stmt->execute();

            header('Location: manage_categories.php');
            exit();
        } else {
            $error_message = "Category name already exists.";
        }
    } else {
        $error_message = "Category name is required.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];

    $stmt = $db->prepare("DELETE FROM categories WHERE category_id = :category_id");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

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
    <?php
    include './header.php';
    ?>
    <section class="admin-content h-65">
        <div class="container">
            <h2>Manage Categories</h2>
            <?php if (isset($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" name="category_name" placeholder="Category Name" required>
                <button type="submit" name="add_category">Add Category</button>
            </form>
            <table>
                <tr>
                    <th>Category Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <?php foreach ($categories as $category) : ?>
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