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

// Fetch total number of events
$stmt = $db->query("SELECT COUNT(*) FROM events");
$total_events = $stmt->fetchColumn();

// Fetch events from the database for the current page
$stmt = $db->prepare("SELECT * FROM events ORDER BY event_id DESC LIMIT :offset, :records_per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter events based on search criteria if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
    $category_id = $_POST['category_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];

    $events = filter_events($search_query, $category_id, $start_date, $end_date, $location);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Developer Events</h1>
        <nav>
            <ul>
                <li><a href="events.php">View Events</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="events">
        <div class="container">
            <h2>Upcoming Events</h2>
            <!-- Event Filter Form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" name="search_query" placeholder="Search...">
                <select name="category_id">
                    <option value="">All Categories</option>
                    <?php foreach(get_categories() as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="date" name="start_date">
                <input type="date" name="end_date">
                <input type="text" name="location" placeholder="Location...">
                <button type="submit" name="search">Search</button>
            </form>
            <!-- Display Events -->
            <div class="event-list">
                <?php if($events): ?>
                    <?php foreach($events as $event): ?>
                        <div class="event">
                            <img src="../assets/images/<?php echo $event['event_image']; ?>" width="300" alt="<?php echo $event['event_name']; ?>">
                            <div class="event-details">
                                <h3><?php echo $event['event_name']; ?></h3>
                                <p><?php echo substr($event['event_description'], 0, 100) . '...'; ?></p>
                                <p><strong>Date & Time:</strong> <?php echo date('M d, Y H:i', strtotime($event['event_date'])); ?></p>
                                <p><strong>Location:</strong> <?php echo $event['event_location']; ?></p>
                                <a href="single_event.php?event_id=<?php echo $event['event_id']; ?>" class="btn">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Pagination links -->
                    <?php
                    $total_pages = ceil($total_events / $records_per_page);
                    ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="events.php?page=<?php echo $page - 1; ?>">Previous</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i === $page): ?>
                                <span><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="events.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="events.php?page=<?php echo $page + 1; ?>">Next</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p>No events found.</p>
                <?php endif; ?>
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
