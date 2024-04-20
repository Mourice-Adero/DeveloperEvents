<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/db.php';

$error_message = '';

// Fetch general feedbacks (messages)
$stmt = $db->query("
    SELECT feedbacks.*, users.username 
    FROM feedbacks 
    INNER JOIN users ON feedbacks.user_id = users.user_id
");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$feedbacks) {
    $error_message = 'Failed to fetch feedbacks from the database.';
}

// Fetch event feedbacks with event names and user names
$stmt = $db->prepare("
    SELECT ef.*, e.event_name, u.username
    FROM event_feedback ef
    INNER JOIN events e ON ef.event_id = e.event_id
    INNER JOIN users u ON ef.user_id = u.user_id
");
$stmt->execute();
$event_feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$event_feedbacks) {
    $error_message = 'Failed to fetch event feedbacks from the database.';
}

// Fetch event names for filtering
$event_names = array();
$stmt = $db->query("SELECT DISTINCT event_name FROM events");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $event_names[] = $row['event_name'];
}

// Filter feedbacks based on selected event name
if (isset($_GET['event_name']) && in_array($_GET['event_name'], $event_names)) {
    $selected_event_name = $_GET['event_name'];
    $filtered_feedbacks = array_filter($event_feedbacks, function ($feedback) use ($selected_event_name) {
        return $feedback['event_name'] == $selected_event_name;
    });
} else {
    $selected_event_name = '';
    $filtered_feedbacks = $event_feedbacks;
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

        @media print {
            #print-content {
                display: block !important;
            }

            .container {
                max-width: none;
                width: auto;
            }

            .feedback-table {
                max-height: none;
            }

            .footer {
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
            <h2>Manage Feedbacks</h2>
            <?php if ($error_message) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php else : ?>
                <h3>General Feedbacks (Messages)</h3>
                <button onclick="printGeneralFeedbacks()">Print General Feedbacks</button>
                <div class="printable-content" id="general-feedbacks">
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
                </div>

                <h3>Event Feedbacks</h3>
                <form action="" method="GET">
                    <label for="event_name">Filter by Event Name:</label>
                    <select name="event_name" id="event_name">
                        <option value="">All</option>
                        <?php foreach ($event_names as $name) : ?>
                            <option value="<?php echo $name; ?>" <?php if ($name == $selected_event_name) echo 'selected'; ?>><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Apply Filter</button>
                </form>
                <button onclick="printEventFeedbacks()">Print Event Feedbacks</button>
                <div class="printable-content" id="event-feedbacks">
                    <table class="feedback-table">
                        <tr>
                            <th>Event Name</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Feedback</th>
                        </tr>
                        <?php foreach ($filtered_feedbacks as $event_feedback) : ?>
                            <tr>
                                <td><?php echo $event_feedback['event_name']; ?></td>
                                <td><?php echo $event_feedback['username']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($event_feedback['feedback_time'])); ?></td>
                                <td><?php echo $event_feedback['feedback']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function printGeneralFeedbacks() {
            var printableContent1 = document.getElementById('general-feedbacks').innerHTML;
            var originalContent1 = document.body.innerHTML;
            document.body.innerHTML = printableContent1;
            window.print();
            document.body.innerHTML = originalContent1;
        }

        function printEventFeedbacks() {
            var printableContent = document.getElementById('event-feedbacks').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printableContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
</body>

</html>