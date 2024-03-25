<?php
// Include database connection and common functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start or resume session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Pagination configuration
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of past events
$current_date = date('Y-m-d H:i:s');
$stmt = $db->prepare("SELECT COUNT(*) FROM events WHERE event_to < :current_date");
$stmt->bindParam(':current_date', $current_date);
$stmt->execute();
$total_events = $stmt->fetchColumn();

// Fetch past events from the database for the current page
$stmt = $db->prepare("SELECT * FROM events WHERE event_to < :current_date ORDER BY event_to DESC LIMIT :offset, :records_per_page");
$stmt->bindParam(':current_date', $current_date);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Events - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .events {
            padding: 1rem;
            margin-bottom: 1.5rem;
            margin: 0;
        }

        .event-list {
            background: #fff;
            padding: 10px;
        }

        .search-form {
            background: #fff;
            padding: 5px;
        }

        .event-details {
            text-align: left;
            margin-top: 20px;
        }

        .event-flag {
            background-color: #ff6b6b;
            /* Red color for event flag */
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #333;
            border-radius: 5px;
            margin: 0 2px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        .pagination span {
            color: #ddd;
            padding: 8px 16px;
            border-radius: 5px;
            margin: 0 2px;
        }
    </style>
</head>

<body>
    <?php include "./header.php"; ?>
    <section class="events h-65">
        <div class="container">
            <h2>Past Events</h2>
            <!-- Display Past Events -->
            <div class="event-list">
                <?php if ($events) : ?>
                    <?php foreach ($events as $event) : ?>
                        <div class="event">
                            <div class="mb-4 w-50">
                                <img src="../assets/images/<?php echo $event['event_image']; ?>" width="200" height="300" class="w-full object-cover" alt="<?php echo $event['event_name']; ?>">
                            </div>
                            <div class="event-details">
                                <h3><?php echo $event['event_name']; ?></h3>
                                <p><?php echo substr($event['event_description'], 0, 25) . '...'; ?></p>
                                <p><strong>From:</strong> <?php echo date('M d, Y H:i', strtotime($event['event_from'])); ?></p>
                                <p><strong>To:</strong> <?php echo date('M d, Y H:i', strtotime($event['event_to'])); ?></p>
                                <p><strong>Location:</strong> <?php echo $event['event_location']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Pagination links -->
                    <?php
                    $total_pages = ceil($total_events / $records_per_page);
                    ?>
                    <div class="pagination">
                        <?php if ($page > 1) : ?>
                            <a href="past_events.php?page=<?php echo $page - 1; ?>">Previous</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <?php if ($i === $page) : ?>
                                <span><?php echo $i; ?></span>
                            <?php else : ?>
                                <a href="past_events.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages) : ?>
                            <a href="past_events.php?page=<?php echo $page + 1; ?>">Next</a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <p>No past events found.</p>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php
    include "./footer.php";
    ?>
</body>

</html>