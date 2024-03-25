<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

function paginate($url, $page, $total_pages)
{
    $pagination = '';
    $pagination .= '<ul>';
    $prev = $page - 1;
    $next = $page + 1;
    $range = 3;

    if ($page > 1) {
        $pagination .= '<li><a href="' . $url . 'page=' . $prev . '">Previous</a></li>';
    }

    for ($i = max(1, $page - $range); $i <= min($page + $range, $total_pages); $i++) {
        if ($i == $page) {
            $pagination .= '<li class="active">' . $i . '</li>';
        } else {
            $pagination .= '<li><a href="' . $url . 'page=' . $i . '">' . $i . '</a></li>';
        }
    }

    if ($page < $total_pages) {
        $pagination .= '<li><a href="' . $url . 'page=' . $next . '">Next</a></li>';
    }

    $pagination .= '</ul>';

    return $pagination;
}

$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$filter_location = isset($_GET['current_location']) ? $_GET['current_location'] : '';

$stmt = $db->prepare("SELECT COUNT(*) FROM event_bookings WHERE (:current_location = '' OR current_location = :current_location)");
$stmt->bindParam(':current_location', $filter_location);
$stmt->execute();
$total_bookings = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM event_bookings WHERE cancellation_status = '0' AND confirmation_status = '0' AND (:current_location = '' OR current_location = :current_location)");
$stmt->bindParam(':current_location', $filter_location);
$stmt->execute();
$pending_bookings = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM event_bookings WHERE cancellation_status = '1' AND (:current_location = '' OR current_location = :current_location)");
$stmt->bindParam(':current_location', $filter_location);
$stmt->execute();
$cancelled_bookings = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM event_bookings WHERE confirmation_status = '1' AND (:current_location = '' OR current_location = :current_location)");
$stmt->bindParam(':current_location', $filter_location);
$stmt->execute();
$confirmed_bookings = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT * FROM event_bookings WHERE (:current_location = '' OR current_location = :current_location) ORDER BY booking_date DESC LIMIT :offset, :records_per_page");
$stmt->bindParam(':current_location', $filter_location);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error_message = '';
if (isset($_GET['current_location']) && empty($bookings) && !empty($_GET['current_location'])) {
    $error_message = 'No bookings found for the selected location.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .pending {
            color: white;
            background-color: greenyellow;
            padding: 3px;
        }

        .confirmed {
            color: white;
            background-color: green;
            padding: 3px;
        }

        .cancelled {
            color: white;
            background-color: gray;
            padding: 3px;
        }
    </style>
</head>

<body>
    <?php
    include './header.php';
    ?>
    <section class="admin-dashboard h-65">
        <div class="container">
            <h2>Booking Summary</h2>
            <div class="booking-summary">
                <p>Total Bookings: <?php echo $total_bookings; ?></p>
                <p>Pending: <?php echo $pending_bookings; ?></p>
                <p>Cancelled: <?php echo $cancelled_bookings; ?></p>
                <p>Confirmed: <?php echo $confirmed_bookings; ?></p>
            </div>
            <h2>Manage Bookings</h2>
            <?php if (!empty($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <div class="booking-filters">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
                    <label for="current_location">Filter by Location:</label>
                    <select name="current_location" id="current_location">
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>
            <div class="booking-list">
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User ID</th>
                            <th>Phone Number</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Booking Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : ?>
                            <tr>
                                <td><?php echo $booking['booking_id']; ?></td>
                                <td><?php echo $booking['user_id']; ?></td>
                                <td><?php echo $booking['phone_number']; ?></td>
                                <td><?php echo $booking['current_location']; ?></td>
                                <td>
                                    <?php
                                    if ($booking['cancellation_status'] == '1') {
                                        echo '<p class="cancelled">Cancelled</p>';
                                    } elseif ($booking['confirmation_status'] == '1') {
                                        echo '<p class="confirmed">Confirmed</p>';
                                    } else {
                                        echo '<p class="pending">Pending</p>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($booking['booking_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if ($total_bookings > $records_per_page) : ?>
                    <div class="pagination">
                        <?php
                        $total_pages = ceil($total_bookings / $records_per_page);
                        $url = $_SERVER['PHP_SELF'] . "?";
                        echo paginate($url, $page, $total_pages);
                        ?>
                    </div>
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