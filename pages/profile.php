<?php
// Start or resume session
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Include database connection
require_once '../includes/db.php';

// Display feedback submission success message if set
$feedback_message = '';
if (isset($_SESSION['feedback_submitted'])) {
    $feedback_message = "<div class='success-message'>{$_SESSION['feedback_submitted']}</div>";
    unset($_SESSION['feedback_submitted']); // Clear the session variable
}

// Check if there are any feedback submission errors
$feedback_error_message = '';
if (isset($_SESSION['feedback_errors']) && !empty($_SESSION['feedback_errors'])) {
    // Display error messages
    $feedback_error_message = "<div class='error-message'>";
    foreach ($_SESSION['feedback_errors'] as $error) {
        $feedback_error_message .= "<p>$error</p>";
    }
    $feedback_error_message .= "</div>";
    // Clear the session variable
    unset($_SESSION['feedback_errors']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Add your custom CSS styles for the profile picture */
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        /* Add your custom CSS styles for the error message box */
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        /* Add styles for action buttons */
        .action-button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .profile-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2rem;
            width: 100%;
        }

        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .profile-form input {
            padding: 5px;
        }

        .success-message {
            position: absolute;
            margin: 25px;
            right: 0;
            top: 0;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .profile-container {
            display: flex;
            flex-wrap: wrap;
        }

        .titles {
            border-bottom: 2px solid #ffd700;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php include "./header.php"; ?>
    <div class="profile-container h-65">
        <section class="user-profile">
            <div class="container profile-container">
                <div>
                    <h2 class="titles">User Details</h2>
                    <?php
                    // Initialize message variable
                    $message = '';

                    // Check if user profile was updated successfully and set the message accordingly
                    if (isset($_SESSION['profile_updated'])) {
                        $message = "User profile updated successfully.";
                        unset($_SESSION['profile_updated']); // Clear the session variable
                    }

                    // Check if there are any profile update errors
                    if (isset($_SESSION['profile_update_errors']) && !empty($_SESSION['profile_update_errors'])) {
                        // Display error messages
                        foreach ($_SESSION['profile_update_errors'] as $error) {
                            echo "<div class='error-message'>$error</div>";
                        }
                        // Clear the session variable
                        unset($_SESSION['profile_update_errors']);
                    }

                    // Fetch user details
                    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Check if user exists
                    if (!$user) {
                        echo "<p>User not found.</p>";
                    } else {
                        // Display user details
                        echo "<img class='profile-picture' src='../assets/images/profile/" . (!empty($user['profile_picture']) ? $user['profile_picture'] : 'avatar.png') . "' alt='Profile Picture'>";
                        echo "<p>Username: " . $user['username'] . "</p>";
                        echo "<p>Email: " . $user['email'] . "</p>";
                        // Add more user details fields as needed
                        echo "<p>Phone Number: " . $user['phone_number'] . "</p>";
                        echo "<p>Visibility: " . ($user['visibility'] ? 'Enabled' : 'Disabled') . "</p>";
                        echo "<p>Profession: " . $user['profession'] . "</p>";
                        echo "<p>LinkedIn: " . $user['linkedin'] . "</p>";
                        echo "<p>Twitter: " . $user['twitter'] . "</p>";
                        echo "<p>GitHub: " . $user['github'] . "</p>";
                    }
                    ?>
                </div>
                <div>
                    <!-- Display success message if profile was updated -->
                    <?php if (!empty($message)) : ?>
                        <div class="success-message">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <div class="profile-form-container">
                        <h3 class="titles">Update Profile</h3>
                        <!-- Form to update user details -->
                        <form action="update_profile.php" method="POST" class="profile-form" enctype="multipart/form-data" id="profile-form">
                            <input type="file" name="profile_picture">
                            <input type="text" name="username" placeholder="Username" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" required>
                            <input type="email" name="email" placeholder="Email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
                            <input type="password" name="current_password" placeholder="Current Password" required>
                            <input type="password" name="new_password" placeholder="New Password">
                            <input type="password" name="confirm_password" placeholder="Confirm Password">
                            <input type="text" name="phone_number" placeholder="Phone Number" value="<?php echo isset($user['phone_number']) ? $user['phone_number'] : ''; ?>">
                            <label for="visibility">Visibility</label>
                            <select name="visibility">
                                <option value="0" <?php echo ($user['visibility'] == 0) ? 'selected' : ''; ?>>Disabled</option>
                                <option value="1" <?php echo ($user['visibility'] == 1) ? 'selected' : ''; ?>>Enabled</option>
                            </select>
                            <input type="text" name="profession" placeholder="Profession" value="<?php echo isset($user['profession']) ? $user['profession'] : ''; ?>">
                            <input type="text" name="linkedin" placeholder="LinkedIn" value="<?php echo isset($user['linkedin']) ? $user['linkedin'] : ''; ?>" onblur="validateLink('linkedin')">
                            <input type="text" name="twitter" placeholder="Twitter" value="<?php echo isset($user['twitter']) ? $user['twitter'] : ''; ?>" onblur="validateLink('twitter')">
                            <input type="text" name="github" placeholder="GitHub" value="<?php echo isset($user['github']) ? $user['github'] : ''; ?>" onblur="validateLink('github')">
                            <button type="submit" name="update_profile">Update Profile</button>
                        </form>


                    </div>
                </div>
            </div>
        </section>

        <!-- Section for displaying booked events -->
        <section class="booked-events">
            <div class="container">
                <h2 class="titles">Booked Events</h2>
                <?php echo $feedback_message; ?>
                <?php echo $feedback_error_message; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Event Date</th>
                            <th>Action</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch booked events for the current user
                        $stmt = $db->prepare("SELECT e.*, b.cancellation_status, b.confirmation_status FROM events e INNER JOIN event_bookings b ON e.event_id = b.event_id WHERE b.user_id = :user_id");
                        $stmt->bindParam(':user_id', $_SESSION['user_id']);
                        $stmt->execute();
                        $booked_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($booked_events) {
                            // Display booked events
                            foreach ($booked_events as $event) {
                                echo "<tr>";
                                echo "<td>{$event['event_name']}</td>";
                                // Format the event date
                                $event_date_from = date('M d, Y H:i', strtotime($event['event_from']));
                                $event_date_to = date('M d, Y H:i', strtotime($event['event_to']));
                                echo "<td>From: {$event_date_from} To: {$event_date_to}</td>";
                                // Display cancellation or confirmation buttons based on booking status
                                echo "<td>";
                                if ($event['cancellation_status'] == 0 && strtotime($event['event_from']) > time()) {
                                    echo "<form action='cancel_booking.php' method='POST' onsubmit='return confirm(\"Are you sure you want to cancel this booking?\")'>";
                                    echo "<input type='hidden' name='event_id' value='{$event['event_id']}'>";
                                    echo "<button type='submit' class='action-button'>Cancel Booking</button>";
                                    echo "</form>";
                                } else {
                                    echo "<button class='action-button' disabled>Booking Canceled</button>";
                                }
                                if ($event['confirmation_status'] == 0 && strtotime($event['event_from']) - 43200 > time() && $event['cancellation_status'] == 0) {
                                    echo "<form action='confirm_attendance.php' method='POST' onsubmit='return confirm(\"Are you sure you want to confirm attendance?\")'>";
                                    echo "<input type='hidden' name='event_id' value='{$event['event_id']}'>";
                                    echo "<button type='submit' class='action-button'>Confirm Attendance</button>";
                                    echo "</form>";
                                } else {
                                    echo "<button class='action-button' disabled>Attendance Confirmed</button>";
                                }
                                echo "</td>";
                                // Add feedback form if the event has started
                                if (strtotime($event['event_from']) <= time()) {
                                    echo "<td>";
                                    echo "<form action='submit_feedback.php' method='POST'>";
                                    echo "<input type='hidden' name='event_id' value='{$event['event_id']}'>";
                                    echo "<textarea name='feedback' placeholder='Provide your feedback'></textarea>";
                                    echo "<button type='submit'>Submit Feedback</button>";
                                    echo "</form>";
                                    echo "</td>";
                                } else {
                                    echo "<td>Event hasn't started yet</td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            // No booked events
                            echo "<tr><td colspan='4'>No events booked yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
    <?php include "./footer.php"; ?>

    <script>
        function validateLink(type) {
            var input = document.getElementsByName(type)[0];
            var value = input.value.trim();
            var pattern;
            var hint;

            switch (type) {
                case 'linkedin':
                    pattern = /^(https?:\/\/)?(www\.)?linkedin\.com\/.*$/;
                    hint = "Enter a valid LinkedIn profile URL.";
                    break;
                case 'twitter':
                    pattern = /^(https?:\/\/)?(www\.)?twitter\.com\/.*$/;
                    hint = "Enter a valid Twitter profile URL.";
                    break;
                case 'github':
                    pattern = /^(https?:\/\/)?(www\.)?github\.com\/.*$/;
                    hint = "Enter a valid GitHub profile URL.";
                    break;
                default:
                    return;
            }

            if (value !== '' && !pattern.test(value)) {
                // Display hint if the link is invalid
                input.setCustomValidity(hint);
            } else {
                // Clear hint if the link is valid or empty
                input.setCustomValidity('');
            }
        }
        // Automatically hide the success message after 5 seconds
        setTimeout(function() {
            document.querySelector('.success-message').style.display = 'none';
        }, 5000);
    </script>

</body>

</html>