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

// Fetch event details based on event ID from URL parameter
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Retrieve event details from the database
    $stmt = $db->prepare("SELECT * FROM events WHERE event_id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        // Event not found, redirect to manage_events.php
        header('Location: manage_events.php');
        exit();
    }
} else {
    // Event ID not provided, redirect to manage_events.php
    header('Location: manage_events.php');
    exit();
}

// Fetch all categories from the database
$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission to update event details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    // Retrieve form data
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $category_id = $_POST['category_id']; // Added for category selection
    $event_external_link = isset($_POST['event_external_link']) ? $_POST['event_external_link'] : null; // Check if external link is provided

    // Handle image upload
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;
    // Check if image file is a actual image or fake image
    if (isset($_POST["update_event"])) {
        $check = getimagesize($_FILES["event_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check file size
    if ($_FILES["event_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
            // Update event details in the database with image URL and external link
            $stmt = $db->prepare("UPDATE events SET event_name = :event_name, event_description = :event_description, event_date = :event_date, event_location = :event_location, category_id = :category_id, event_image = :event_image, event_external_link = :event_external_link WHERE event_id = :event_id");
            $stmt->bindParam(':event_name', $event_name);
            $stmt->bindParam(':event_description', $event_description);
            $stmt->bindParam(':event_date', $event_date);
            $stmt->bindParam(':event_location', $event_location);
            $stmt->bindParam(':category_id', $category_id); // Bind category ID
            $stmt->bindParam(':event_image', basename($_FILES["event_image"]["name"]));
            $stmt->bindParam(':event_external_link', $event_external_link); // Bind external link
            $stmt->bindParam(':event_id', $event_id);
            $stmt->execute();

            // Redirect to manage_events.php after updating event
            header('Location: manage_events.php');
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
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
</head>
<body>
    <header>
        <h1>Admin Panel - Edit Event</h1>
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
            <h2>Edit Event</h2>
            <!-- Display form to edit event details -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?event_id=<?php echo $event['event_id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="text" name="event_name" placeholder="Event Name" value="<?php echo $event['event_name']; ?>" required><br><br>
                <textarea name="event_description" placeholder="Event Description" required><?php echo $event['event_description']; ?></textarea><br><br>
                <input type="datetime-local" name="event_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>" required><br><br>
                <input type="text" name="event_location" placeholder="Event Location" value="<?php echo $event['event_location']; ?>" required><br><br>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $event['category_id']) echo 'selected'; ?>><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <label>Current Image:</label>
                <img src="../assets/images/<?php echo $event['event_image']; ?>" alt="<?php echo $event['event_image']; ?>" width="200"><br><br> <!-- Current Image -->
                <input type="file" name="event_image" accept="image/*"><br><br> <!-- Image upload -->
                <input type="text" name="event_external_link" placeholder="External Link (Optional)" value="<?php echo $event['event_external_link']; ?>"><br><br> <!-- External link (optional) -->
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
