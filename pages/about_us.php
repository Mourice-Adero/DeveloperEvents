<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Custom CSS for responsive layout */
        .about-us {
            margin: 0;

        }

        .about-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            /* Allow items to wrap to next line */
        }

        .container {
            width: 100%;
            max-width: 500px;
            /* Limit the width of the container */
            margin: 0 auto;
            /* Center the container */
            text-align: center;
            /* Center text inside container */
        }

        .about-section .container:nth-child(odd) {
            order: 2;
            /* Reverse order for odd containers */
        }

        .about-section img {
            max-width: 100%;
            /* Make images responsive */
            height: auto;
        }

        @media (max-width: 768px) {
            .about-section {
                flex-direction: column;
                /* Stack sections vertically on smaller screens */
            }
        }

        .p-3 {
            padding: 3px;
        }
    </style>
</head>

<body>
    <?php include "./header.php"; ?>
    <section class="about-us h-65">
        <div class="about-section">
            <div class="container">
                <img src="./../assets/images/desola-lanre-ologun-kwzWjTnDPLk-unsplash.jpg" alt="About Us Image">
            </div>
            <div class="container">
                <h2>About Us</h2>
                <p>Welcome to Developer Events! We are dedicated to providing a platform for developers to discover, share, and connect with events happening around the world. Whether you're a seasoned professional or just starting your journey in the world of development, our goal is to help you stay informed, inspired, and engaged.</p>
                <p>Our team is passionate about technology and the developer community. We believe that by bringing developers together, we can foster collaboration, innovation, and continuous learning.</p>
                <p>Thank you for being a part of Developer Events. We look forward to seeing you at our upcoming events!</p>
            </div>
        </div>
        <div class="about-section">
            <div class="container">
                <h2>Contact Us</h2>
                <p class="p-3"><strong>Phone:</strong> +254 020 123 458</p>
                <p class="p-3"><strong>Email:</strong> info@developerevents.co.ke</p>
            </div>
            <div class="container">
                <img src="./../assets/images/pngegg (3).png" alt="About Us Image">
            </div>
        </div>
    </section>
    <?php include "./footer.php"; ?>
</body>

</html>