<?php
// Include database connection
require_once 'db.php';

// Function to authenticate user
function authenticate_user($username, $password)
{
    global $db;

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    } else {
        return false;
    }
}

// Function to register a new user
function register_user($username, $email, $password)
{
    global $db;

    // Check if username or email already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        return "Username or email already exists.";
    } else {
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        return true;
    }
}

// Function to fetch all events from the database
function get_events()
{
    global $db;

    $stmt = $db->query("SELECT * FROM events");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch event details by event ID
function get_event_by_id($event_id)
{
    global $db;

    $stmt = $db->prepare("SELECT * FROM events WHERE event_id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to fetch all categories from the database
function get_categories()
{
    global $db;

    $stmt = $db->query("SELECT * FROM categories");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to filter events based on search criteria
function filter_events($search_query, $category_id, $start_date, $end_date, $location)
{
    global $db;

    // Construct the query based on the search criteria
    $query = "SELECT * FROM events WHERE 1=1";

    if (!empty($search_query)) {
        $query .= " AND (event_name LIKE :search_query OR event_description LIKE :search_query)";
    }
    if (!empty($category_id)) {
        $query .= " AND category_id = :category_id";
    }
    if (!empty($start_date) && !empty($end_date)) {
        $query .= " AND (event_from BETWEEN :start_date AND :end_date OR event_to BETWEEN :start_date AND :end_date)";
    } elseif (!empty($start_date)) {
        $query .= " AND event_from >= :start_date";
    } elseif (!empty($end_date)) {
        $query .= " AND event_to <= :end_date";
    }
    if (!empty($location)) {
        $query .= " AND event_location LIKE :location";
    }

    // Prepare and execute the query
    $stmt = $db->prepare($query);

    if (!empty($search_query)) {
        $search_query = '%' . $search_query . '%';
        $stmt->bindParam(':search_query', $search_query);
    }
    if (!empty($category_id)) {
        $stmt->bindParam(':category_id', $category_id);
    }
    if (!empty($start_date)) {
        $stmt->bindParam(':start_date', $start_date);
    }
    if (!empty($end_date)) {
        $stmt->bindParam(':end_date', $end_date);
    }
    if (!empty($location)) {
        $location = '%' . $location . '%';
        $stmt->bindParam(':location', $location);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Function to add a comment for an event
function add_comment($event_id, $user_id, $comment)
{
    global $db;

    $stmt = $db->prepare("INSERT INTO comments (event_id, user_id, comment) VALUES (:event_id, :user_id, :comment)");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
}

// Function to fetch comments for an event
function get_comments_for_event($event_id)
{
    global $db;

    $stmt = $db->prepare("SELECT * FROM comments WHERE event_id = :event_id ORDER BY created_at DESC");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get username by user ID
function get_username_by_id($user_id)
{
    global $db;

    $stmt = $db->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user['username'];
}

// Function to save feedback to the database
function save_feedback($db, $user_id, $feedback)
{
    // Prepare and execute SQL query to insert feedback into the database
    $stmt = $db->prepare("INSERT INTO feedbacks (user_id, feedback_date, feedback_text) VALUES (:user_id, NOW(), :feedback)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':feedback', $feedback);
    $stmt->execute();
}
