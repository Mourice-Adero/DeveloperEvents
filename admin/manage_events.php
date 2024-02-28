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

// Pagination configuration
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of events
$total_stmt = $db->query("SELECT COUNT(*) FROM events");
$total_events = $total_stmt->fetchColumn();

// Fetch events for the current page
$stmt = $db->prepare("SELECT * FROM events ORDER BY event_id DESC LIMIT :offset, :records_per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories from the database
$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Handle form submission to add new event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
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
    if (isset($_POST["add_event"])) {
        $check = getimagesize($_FILES["event_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
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
            // Insert new event into the database with image URL and external link
            $stmt = $db->prepare("INSERT INTO events (event_name, event_description, event_date, event_location, category_id, event_image, event_external_link) VALUES (:event_name, :event_description, :event_date, :event_location, :category_id, :event_image, :event_external_link)");
            $stmt->bindParam(':event_name', $event_name);
            $stmt->bindParam(':event_description', $event_description);
            $stmt->bindParam(':event_date', $event_date);
            $stmt->bindParam(':event_location', $event_location);
            $stmt->bindParam(':category_id', $category_id); // Bind category ID
            $stmt->bindParam(':event_image', basename($_FILES["event_image"]["name"]));
            $stmt->bindParam(':event_external_link', $event_external_link); // Bind external link
            $stmt->execute();

            // Redirect to manage_events.php to refresh the page
            header('Location: manage_events.php');
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle form submission to delete event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    // Retrieve event ID from form data
    $event_id = $_POST['event_id'];

    // Delete event from the database
    $stmt = $db->prepare("DELETE FROM events WHERE event_id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();

    // Redirect to manage_events.php to refresh the page
    header('Location: manage_events.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <h1>Admin Panel - Manage Events</h1>
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
            <h2>Manage Events</h2>
            <div class="d-flex">
                <div class="vertical-line w-1/3">
                    <!-- Display form to add new event -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                        <input type="text" name="event_name" placeholder="Event Name" required><br><br>
                        <textarea name="event_description" placeholder="Event Description" required></textarea><br><br>
                        <input type="datetime-local" name="event_date" required><br><br>
                        <input type="text" name="event_location" placeholder="Event Location" required><br><br>
                        <select name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select><br><br>
                        <input type="file" name="event_image" accept="image/*" required><br><br> <!-- Image upload -->
                        <input type="text" name="event_external_link" placeholder="External Link (Optional)"><br><br> <!-- External link (optional) -->
                        <button type="submit" name="add_event">Add Event</button>
                    </form>
                </div>
                <div class="w-2/3">
                    <!-- Display existing events in a table -->
                    <table>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Date / Time</th>
                            <th>Image</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php foreach ($events as $event) : ?>
                            <tr>
                                <td><?php echo $event['event_name']; ?></td>
                                <td><?php echo $event['event_description']; ?></td>
                                <td><?php echo $event['event_date']; ?></td>
                                <td><img src="../assets/images/<?php echo $event['event_image']; ?>" alt="<?php echo $event['event_image']; ?>" width="100"></td> <!-- Image display -->
                                <td><a href="edit_event.php?event_id=<?php echo $event['event_id']; ?>">Edit</a></td> <!-- Edit link -->
                                <td>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                        <button type="submit" name="delete_event">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <!-- Pagination links -->
                    <?php
                    $total_pages = ceil($total_events / $records_per_page);
                    if ($total_pages > 1) {
                        echo '<div class="pagination">';
                        if ($page > 1) {
                            echo '<a href="manage_events.php?page=' . ($page - 1) . '">Previous</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i === $page) {
                                echo '<span>' . $i . '</span>';
                            } else {
                                echo '<a href="manage_events.php?page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="manage_events.php?page=' . ($page + 1) . '">Next</a>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>