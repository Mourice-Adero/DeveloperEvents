<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $stmt = $db->prepare("SELECT * FROM events WHERE event_id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        header('Location: manage_events.php');
        exit();
    }
} else {
    header('Location: manage_events.php');
    exit();
}

$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_from = $_POST['event_from'];
    $event_to = $_POST['event_to'];
    $event_location = $_POST['event_location'];
    $category_id = $_POST['category_id'];
    $event_external_link = isset($_POST['event_external_link']) ? $_POST['event_external_link'] : null;
    if (empty($event_name)) {
        $errors[] = "Event name is required.";
    }

    if (empty($event_description)) {
        $errors[] = "Event description is required.";
    }

    if (empty($event_from) || empty($event_to)) {
        $errors[] = "Event start and end dates are required.";
    } elseif (strtotime($event_from) >= strtotime($event_to)) {
        $errors[] = "Event end date must be after start date.";
    }

    if (empty($event_location)) {
        $errors[] = "Event location is required.";
    }

    if (empty($category_id)) {
        $errors[] = "Please select a category.";
    }
    if (empty($event_from) || empty($event_to)) {
        $error_messages[] = "Event start and end dates are required.";
    } elseif (strtotime($event_from) >= strtotime($event_to)) {
        $error_messages[] = "Event end date must be after start date.";
    }

    if (!empty($_FILES["event_image"]["name"])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["event_image"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "File is not an image.";
        }

        if ($_FILES["event_image"]["size"] > 500000) {
            $errors[] = "Sorry, your file is too large.";
        }

        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    if (empty($errors)) {
        if (!empty($_FILES["event_image"]["name"])) {
            if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
                $event_image = basename($_FILES["event_image"]["name"]);
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $event_image = $event['event_image'];
        }

        $stmt = $db->prepare("UPDATE events SET event_name = :event_name, event_description = :event_description, event_from = :event_from, event_to = :event_to, event_location = :event_location, category_id = :category_id, event_image = :event_image, event_external_link = :event_external_link WHERE event_id = :event_id");
        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':event_description', $event_description);
        $stmt->bindParam(':event_from', $event_from);
        $stmt->bindParam(':event_to', $event_to);
        $stmt->bindParam(':event_location', $event_location);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':event_image', $event_image);
        $stmt->bindParam(':event_external_link', $event_external_link);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();

        header('Location: manage_events.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .error-messages p {
            margin: 0;
        }
    </style>
</head>

<body>
    <?php include './header.php'; ?>
    <section class="admin-content">
        <div class="container">
            <h2>Edit Event</h2>
            <?php if (!empty($error_messages)) : ?>
                <div class="error-messages">
                    <?php foreach ($error_messages as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?event_id=<?php echo $event['event_id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="text" name="event_name" placeholder="Event Name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required><br><br>
                <textarea name="event_description" rows="7" placeholder="Event Description" required><?php echo htmlspecialchars($event['event_description']); ?></textarea><br><br>
                <label>From:</label>
                <input type="datetime-local" name="event_from" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_from'])); ?>" required><br><br>
                <label>To:</label>
                <input type="datetime-local" name="event_to" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_to'])); ?>" required><br><br>
                <label>Location:</label>
                <input type="text" name="event_location" placeholder="Event Location" value="<?php echo htmlspecialchars($event['event_location']); ?>" required><br><br>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $event['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <label>Current Image:</label>
                <img src="../assets/images/<?php echo htmlspecialchars($event['event_image']); ?>" alt="<?php echo htmlspecialchars($event['event_image']); ?>" width="200"><br><br>
                <input type="file" name="event_image" accept="image/*"><br><br>
                <label>External Link:</label>
                <input type="text" name="event_external_link" placeholder="External Link (Optional)" value="<?php echo htmlspecialchars($event['event_external_link']); ?>"><br><br>
                <?php if (!empty($errors)) : ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error) : ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <button type="submit" name="update_event">Update Event</button>
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