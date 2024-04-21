<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Support - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php include './header.php'; ?>
    <section class="admin-content h-65">
        <div class="container">
            <h2>Admin Support</h2>

            <!-- Add Another Admin -->
            <div>
                <h3>Add Another Admin</h3>
                <ul>
                    <li>Go to <a href="./manage_users.php">Manage Users</a> page</li>
                    <li>Access the Admins table</li>
                    <li>Click on the "Add Admin" button</li>
                </ul>
            </div>

            <!-- Delete Users -->
            <div>
                <h3>Delete Users</h3>
                <ul>
                    <li>Go to <a href="./manage_users.php">Manage Users</a> page</li>
                    <li>Locate the Users table</li>
                    <li>Find the record you want to delete</li>
                    <li>Click the "Delete" button</li>
                </ul>
            </div>

            <!-- Print Reports -->
            <div>
                <h3>Print Reports</h3>
                <ul>
                    <li>Locate the reports you want to print</li>
                    <li>Use the print button provided for each report</li>
                </ul>
            </div>

            <!-- Add Events -->
            <div>
                <h3>Add Events</h3>
                <ul>
                    <li>Go to <a href="./manage_events.php">Manage Events</a> page</li>
                    <li>Fill out the Events form</li>
                    <li>Submit the form to add the event</li>
                </ul>
            </div>

            <!-- Filter Events -->
            <div>
                <h3>Filter Booked Events</h3>
                <ul>
                    <li>Go to <a href="./manage_bookings.php">Manage Bookings</a> page</li>
                    <li>Use the filter form to search by event name or status</li>
                </ul>
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