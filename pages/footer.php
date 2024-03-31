<style>
    footer {
        background-color: #333;
        color: #fff;
        padding: 20px 0;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links ul li {
        display: inline;
        margin-right: 20px;
    }

    .footer-links ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    .footer-contact h3 {
        margin-bottom: 10px;
    }

    .footer-contact p {
        margin: 0;
    }

    .footer-info p {
        margin: 0;
    }
</style>
<footer>
    <div class="footer-container">
        <div class="footer-links">
            <ul>
                <li><a href="support.php">Support</a></li>
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>
        <div class="footer-contact">
            <h3>Contact Us</h3>
            <p>Email: support@developerevents.com</p>
            <p>Phone: +254 (123) 456-7890</p>
        </div>
        <div class="footer-info">
            <p>&copy; <?php echo date("Y"); ?> Developer Events. All rights reserved.</p>
        </div>
    </div>
</footer>