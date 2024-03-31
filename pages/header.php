<style>
    .header {
        background-image: url('../assets/images/pngegg\ \(1\).png');
        /* Path to your PNG background image */
        background-size: cover;
        background-position: center;
        padding: 20px 0;
        /* Adjust padding as needed */
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
        /* Change color on hover as needed */
    }
</style>
<header class="header">
    <div class="header-content">
        <h1>Developer Events</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="events.php">View Events</a></li>
                <li><a href="past_events.php">Past Events</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="connect.php">Connect</a></li>
                <li><a href="support.php">Support</a></li>

                <?php
                // Check if user is logged in
                if (isset($_SESSION['user_id'])) {
                    // If logged in, display profile and logout links with confirmation prompt
                    echo '<li><a href="profile.php">Profile</a></li>';
                    echo '<li><a href="#" onclick="logout()">Logout</a></li>';
                } else {
                    // If not logged in, display login and register links
                    echo '<li><a href="../auth/login.php">Login</a></li>';
                    echo '<li><a href="../auth/register.php">Register</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</header>

<script>
    function logout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../auth/logout.php";
        }
    }
</script>