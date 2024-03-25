<?php
// Include database connection and common functions
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Start or resume session
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to events page
    header('Location: pages/index.php');
    exit();
}

// Handle form submissions (e.g., login and registration)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle login or registration form submissions here if necessary
    // Redirect users appropriately after form submission
}

// Display the appropriate page content based on user actions
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $allowed_pages = array('landing', 'about_us');
    // Check if the requested page is allowed
    if (in_array($page, $allowed_pages)) {
        include "pages/{$page}.php";
    } else {
        // If the requested page is not allowed, display a 404 error
        include "pages/404.php";
    }
} else {
    // Default page to display if no specific page is requested
    header('location: pages/index.php');
}
