<style>
    .header {
        background-image: url('./../assets/images/pngegg\ \(1\).png');
        background-size: cover;
        background-position: center;
        padding: 20px 0;
        text-align: center;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .header h1 {
        font-size: 36px;
        color: #fff;
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    nav ul li {
        display: inline;
        margin: 0 10px;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
    }

    nav ul li a:hover {
        color: #ffd700;
    }
</style>
<header class="header">
    <div class="header-content">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="manage_events.php">Events</a></li>
                <li><a href="manage_bookings.php">Booked-Events</a></li>
                <li><a href="manage_categories.php">Categories</a></li>
                <li><a href="manage_feedbacks.php">Feedbacks</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>