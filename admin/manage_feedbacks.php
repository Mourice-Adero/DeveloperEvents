<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$error_message = '';

$stmt = $db->query("
    SELECT feedbacks.*, users.username 
    FROM feedbacks 
    INNER JOIN users ON feedbacks.user_id = users.user_id
");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$feedbacks) {
    $error_message = 'Failed to fetch feedbacks from the database.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedbacks - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .feedback-table {
            max-height: 60vh;
            overflow-y: auto;
        }

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
            <h2>Manage Feedbacks</h2>
            <?php if ($error_message) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php else : ?>
                <table class="feedback-table">
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Feedback</th>
                    </tr>
                    <?php foreach ($feedbacks as $feedback) : ?>
                        <tr>
                            <td><?php echo $feedback['username']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($feedback['feedback_date'])); ?></td>
                            <td><?php echo $feedback['feedback_text']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>