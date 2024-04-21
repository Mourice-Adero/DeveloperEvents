<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$total_stmt = $db->query("SELECT COUNT(*) FROM events");
$total_events = $total_stmt->fetchColumn();

$stmt = $db->prepare("SELECT * FROM events ORDER BY event_id DESC LIMIT :offset, :records_per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error_messages = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_from = $_POST['event_from'];
    $event_to = $_POST['event_to'];
    $event_location = $_POST['event_location'];
    $category_id = $_POST['category_id'];
    $event_external_link = isset($_POST['event_external_link']) ? $_POST['event_external_link'] : null;

    if (empty($event_name)) {
        $error_messages[] = "Event name is required.";
    }
    if (empty($event_description)) {
        $error_messages[] = "Event description is required.";
    }
    if (empty($event_from)) {
        $error_messages[] = "Event start date is required.";
    }
    if (empty($event_to)) {
        $error_messages[] = "Event end date is required.";
    }
    if (empty($event_location)) {
        $error_messages[] = "Event location is required.";
    }
    if (empty($category_id)) {
        $error_messages[] = "Category is required.";
    }
    if (empty($event_from) || empty($event_to)) {
        $error_messages[] = "Event start and end dates are required.";
    } elseif (strtotime($event_from) >= strtotime($event_to)) {
        $error_messages[] = "Event end date must be after start date.";
    }

    if (empty($error_messages)) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["event_image"]["tmp_name"]);
        if ($check === false) {
            $error_messages[] = "File is not an image.";
        }

        if ($_FILES["event_image"]["size"] > 500000) {
            $error_messages[] = "Sorry, your file is too large.";
        }

        $allowed_formats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_formats)) {
            $error_messages[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (empty($error_messages)) {
            if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
                $stmt = $db->prepare("INSERT INTO events (event_name, event_description, event_from, event_to, event_location, category_id, event_image, event_external_link) VALUES (:event_name, :event_description, :event_from, :event_to, :event_location, :category_id, :event_image, :event_external_link)");
                $stmt->bindParam(':event_name', $event_name);
                $stmt->bindParam(':event_description', $event_description);
                $stmt->bindParam(':event_from', $event_from);
                $stmt->bindParam(':event_to', $event_to);
                $stmt->bindParam(':event_location', $event_location);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':event_image', basename($_FILES["event_image"]["name"]));
                $stmt->bindParam(':event_external_link', $event_external_link);
                $stmt->execute();

                header('Location: manage_events.php');
                exit();
            } else {
                $error_messages[] = "Sorry, there was an error uploading your file.";
            }
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
    <style>
        .d-flex {
            flex-wrap: wrap;
            flex-direction: column;
        }

        .error {
            color: red;
        }

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

        .print-container {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                /* position: static; */
                display: block;
            }

            .admin-content {
                display: none !important;
            }

            .print-container button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php
    include './header.php';
    ?>
    <section class="admin-content h-65">
        <div class="container">
            <h2>Add Event</h2>
            <div class="d-flex">
                <div class="">
                    <?php if (!empty($error_messages)) : ?>
                        <div class="error-messages">
                            <?php foreach ($error_messages as $error) : ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Display form to add new event -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                        <input type="text" name="event_name" placeholder="Event Name" required><br><br>
                        <textarea name="event_description" rows="7" placeholder="Event Description" required></textarea><br><br>
                        <label for="evet_from">From: </label>
                        <input type="datetime-local" name="event_from" min="<?php echo date('Y-m-d\TH:i'); ?>" required><br><br> <!-- Start date -->
                        <label for="evet_to">To: </label>
                        <input type="datetime-local" name="event_to" min="<?php echo date('Y-m-d\TH:i'); ?>" required><br><br> <!-- End date -->
                        <input type="text" name="event_location" placeholder="Event Location" required><br><br>
                        <select name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select><br><br>
                        <label for="event_image">Event Poster</label>
                        <input type="file" name="event_image" accept="image/*" required><br><br> <!-- Image upload -->
                        <input type="text" name="event_external_link" placeholder="External Link (Optional)"><br><br> <!-- External link (optional) -->
                        <button type="submit" name="add_event">Add Event</button>
                        <!-- Display error messages -->
                        <?php if (!empty($error_messages)) : ?>
                            <div class="error">
                                <?php foreach ($error_messages as $error_message) : ?>
                                    <p><?php echo $error_message; ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="">
                    <h2>Manage Events</h2>
                    <button onclick="printTable()">Print Table</button>
                    <table>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Date / From</th>
                            <th>Date / To</th>
                            <th>Image</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php foreach ($events as $event) : ?>
                            <tr>
                                <td><?php echo $event['event_name']; ?></td>
                                <td><?php echo substr($event['event_description'], 0, 100) . '...'; ?></td>
                                <td><?php echo date('M d, Y', strtotime($event['event_from'])) ?></td>
                                <td><?php echo date('M d, Y', strtotime($event['event_to'])) ?></td>
                                <td><img src="../assets/images/<?php echo $event['event_image']; ?>" alt="<?php echo $event['event_image']; ?>" height="50" class="object-cover w-full"></td> <!-- Image display -->
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
                    <div class="pagination">
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
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>
    <div class="print-container">
        <?php if (!empty($error_messages)) : ?>
            <div class="error-messages">
                <?php foreach ($error_messages as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h2>Events</h2>
        <!-- Display the events table -->
        <table>
            <!-- Table header -->
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Date / From</th>
                    <th>Date / To</th>
                </tr>
            </thead>
            <tbody>
                <!-- Display events -->
                <?php
                $counter = 1;
                foreach ($events as $event) : ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $event['event_name']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($event['event_from'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($event['event_to'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Display total events -->
        <p>Total Events: <?php echo $total_events; ?></p>

        <!-- Pagination links -->
        <div class="pagination">
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
    <script>
        function printTable() {
            window.print();
        }
    </script>
</body>

</html>