-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2024 at 01:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developerevents`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'Admin_A', '$2y$10$Y8geLIhfq/idcvFpc01omOJO8PMHI7yoTkhsL2SxMmlCxWR20qNNC');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(2, 'Tech'),
(3, 'Programming');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `event_id`, `user_id`, `comment`, `created_at`) VALUES
(9, 21, 5, 'I can\'t wait to attend!', '2024-03-25 10:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_from` datetime DEFAULT NULL,
  `event_to` datetime DEFAULT NULL,
  `event_description` text NOT NULL,
  `event_location` varchar(100) NOT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `event_external_link` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_from`, `event_to`, `event_description`, `event_location`, `event_image`, `event_external_link`, `category_id`) VALUES
(21, 'TEMs AFRICA ICT Expo 2024', '2024-04-25 08:00:00', '2024-04-26 16:00:00', '\"The Largest ICT Show in East Africa\"\r\nTEMS Africa ICT Expo is the world\'s gathering place for all those who thrive on the business of consumer technologies. It has served as the proving ground for innovators and breakthrough technologies on the global stage where next-generation innovations are introduced to the marketplace. Pioneered in Kenya by one of our very own it attracts the world\'s business leaders and pioneering thinkers.', 'The Sarit Expo Centre, Nairobi, Kenya 1', 'TEMS-LOGO-1536x519.png', 'https://10times.com/tems-ict-expo', 2),
(22, 'Retail Tech Show 2024', '2024-06-19 06:00:00', '2024-06-26 23:59:00', 'he best way to optimize the supply chain is to apply the digital transformation. Business platform presenting retail digital transformation and trends based on convergent technologies gathering retail tech leaders worldwide.', 'COEX Convention Center, Seoul, South Korea', 'retail-tech-show.png', 'https://www.retailtechnologyshow.com/', 2),
(23, 'Connected Africa Summit 2024', '2024-04-21 11:01:00', '2024-04-22 12:00:00', 'Join The Summit, where industry leaders, officials, and innovators unite to drive collaboration and growth. Shape Africa\'s digital future, contribute to transformative initiatives and unlock new possibilities for innovation and development. Forge a path for growth, connectivity, and innovation through strategic outcomes such as developing a Digital Inclusion Charter, securing economic commitments, creating a powerful investment nexus, establishing a blueprint for smart governance, facilitating digital trade, and promoting efficient transactions. Experience plenary sessions, breakout sessions, pavilions showcasing innovation, meetings exploring digital solutions, and an awards ceremony honoring digital excellence. Networking sessions provide valuable connections and engagement opportunities.', 'Nairobi, Kenya', 'connected africa summit.jpg', 'https://www.connected.go.ke/', 2);

-- --------------------------------------------------------

--
-- Table structure for table `event_bookings`
--

CREATE TABLE `event_bookings` (
  `booking_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `cancellation_status` tinyint(1) DEFAULT 0,
  `confirmation_status` tinyint(1) DEFAULT 0,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_bookings`
--

INSERT INTO `event_bookings` (`booking_id`, `event_id`, `user_id`, `current_location`, `phone_number`, `booking_time`, `cancellation_status`, `confirmation_status`, `booking_date`) VALUES
(29, 21, 5, 'Nakuru', '0712345678', '2024-03-25 10:19:07', 1, 0, '2024-03-25 10:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback_date` datetime DEFAULT NULL,
  `feedback_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`feedback_id`, `user_id`, `feedback_date`, `feedback_text`) VALUES
(10, 5, '2024-03-25 13:40:52', 'Thank you for the information and making events available to us. I appriciate.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`) VALUES
(4, 'Slade Wilson', 'sledwilson@gmail.com', '$2y$10$wD2NpctliKOZ6KF472XjZetSfJ80l3lzJ9xrUZPgAYTCtfNOGWv22', '2024-03-25 08:05:23'),
(5, 'JohnDoe1', 'johndoe@gmail.com', '$2y$10$5OD/XkUmYpcALDxmX4T0R.HyQwS7MiXWOim8rMfQ9T8o.kzAxHCoW', '2024-03-25 10:10:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `event_bookings`
--
ALTER TABLE `event_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `event_bookings`
--
ALTER TABLE `event_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `event_bookings`
--
ALTER TABLE `event_bookings`
  ADD CONSTRAINT `event_bookings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `event_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
