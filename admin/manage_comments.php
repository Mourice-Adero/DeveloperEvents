<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$stmt = $db->query("
    SELECT 
        c.comment_id, 
        c.comment, 
        e.event_name, 
        u.username 
    FROM 
        comments c 
    INNER JOIN 
        events e ON c.event_id = e.event_id 
    INNER JOIN 
        users u ON c.user_id = u.user_id");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    if (isset($_POST['comment_id']) && !empty($_POST['comment_id'])) {
        $comment_id = $_POST['comment_id'];

        $stmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE comment_id = :comment_id");
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $stmt = $db->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
            $stmt->bindParam(':comment_id', $comment_id);
            $stmt->execute();

            header('Location: manage_comments.php');
            exit();
        } else {
            $error_message = "Comment not found.";
        }
    } else {
        $error_message = "Invalid comment ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php
    include './header.php';
    ?>
    <section class="admin-content h-65">
        <div class="container">
            <h2>Manage Comments</h2>
            <?php if (isset($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <table>
                <tr>
                    <th>Comment</th>
                    <th>Event</th>
                    <th>User</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($comments as $comment) : ?>
                    <tr>
                        <td><?php echo $comment['comment']; ?></td>
                        <td><?php echo $comment['event_name']; ?></td>
                        <td><?php echo $comment['username']; ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                <button type="submit" name="delete_comment">Delete</button>
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