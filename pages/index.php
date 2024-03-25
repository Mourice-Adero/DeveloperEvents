<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .slide {
            height: 400px;
        }

        .slide img {
            object-fit: cover;
            height: 400px;
        }

        /* Style for the container */
        .container2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        /* Style for the text content */
        .text-content {
            flex: 1;
            margin-right: 20px;
            /* Add some spacing between the text and image */
        }

        /* Style for the phone image */
        .phone-image {
            flex: 1;
            max-width: 300px;
            /* Adjust the size of the image as needed */
            height: auto;
            border-radius: 8px;
            /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
        }

        /* Style for the button */
        .btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php
    include "./header.php";
    ?>
    <section class="hero h-65">
        <div class="container">
            <!-- Bootstrap Carousel -->
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="../assets/images/daniel-josef-AMssSjUaTY4-unsplash.jpg" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="../assets/images/desola-lanre-ologun-kwzWjTnDPLk-unsplash.jpg" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="../assets/images/marvin-meyer-SYTO3xs06fU-unsplash.jpg" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <section class="hero h-65">
                <div class="container2">
                    <!-- Text content -->
                    <div class="text-content">
                        <h2>Welcome to Developer Events</h2>
                        <p>Find and explore developer events from around the world. Join us to enhance your skills, connect with peers, and stay updated with the latest trends in technology.</p>
                        <a href="events.php" class="btn"><button>View Events</button></a>
                    </div>
                    <!-- Phone image -->
                    <img src="../assets/images/pngegg (2).png" alt="Phone Image" class="phone-image">
                </div>
            </section>
        </div>
    </section>
    <?php
    include "./footer.php";
    ?>
</body>

</html>