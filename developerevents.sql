-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2024 at 10:45 PM
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
(1, 5, 1, 'Cant wait to attend', '2024-02-28 18:17:17'),
(2, 18, 1, 'Test commnet', '2024-02-28 20:06:10'),
(3, 18, 2, 'I like it', '2024-02-28 20:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_description` text NOT NULL,
  `event_date` datetime NOT NULL,
  `event_location` varchar(100) NOT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `event_external_link` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_description`, `event_date`, `event_location`, `event_image`, `event_external_link`, `category_id`) VALUES
(1, 'Web Development', 'Learn the latest trends in web development.', '2024-03-15 10:00:00', 'New York, USA', NULL, NULL, NULL),
(2, 'Data Science Summit', 'Join experts to explore the world of data science.', '2024-04-20 09:30:00', 'San Francisco, CA', NULL, NULL, NULL),
(3, 'Mobile App Workshop', 'Hands-on workshop for building mobile apps.', '2024-03-25 11:00:00', 'London, UK', NULL, NULL, NULL),
(4, 'AI Conference', 'Discover the future of artificial intelligence.', '2024-05-10 09:00:00', 'Berlin, Germany', NULL, NULL, NULL),
(5, 'Hackathon', 'Compete with others to build innovative solutions.', '2024-04-05 08:00:00', 'Sydney, Australia', NULL, NULL, NULL),
(6, 'Tech Talk Series', 'Engage in insightful discussions on tech topics.', '2024-03-30 13:00:00', 'Tokyo, Japan', NULL, NULL, NULL),
(7, 'Cybersecurity Forum', 'Learn about the latest cybersecurity threats.', '2024-04-15 10:30:00', 'Washington, D.C.', NULL, NULL, NULL),
(8, 'Cloud Computing Expo', 'Explore advancements in cloud computing.', '2024-05-05 09:00:00', 'Paris, France', NULL, NULL, NULL),
(9, 'Blockchain Seminar', 'Dive into the world of blockchain technology.', '2024-03-20 14:00:00', 'Toronto, Canada', NULL, NULL, NULL),
(10, 'IoT Workshop', 'Learn to build IoT applications from scratch.', '2024-04-10 10:00:00', 'Mumbai, India', NULL, NULL, NULL),
(11, 'UX/UI Design Conference', 'Explore UX/UI design principles and best practices.', '2024-05-15 09:30:00', 'Barcelona, Spain', NULL, NULL, NULL),
(12, 'DevOps Summit', 'Discover the latest DevOps methodologies.', '2024-03-18 08:30:00', 'Melbourne, Australia', NULL, NULL, NULL),
(13, 'Big Data Symposium', 'Dive deep into big data analytics and technologies.', '2024-04-22 10:00:00', 'Seoul, South Korea', 'bds.jpg', 'https://bigdatasymposium.dsigroup.org/', 2),
(14, 'Software Engineering Forum', 'Discuss software engineering concepts and methodologies.', '2024-03-28 11:00:00', 'Sao Paulo, Brazil', NULL, NULL, NULL),
(15, 'Product Management Workshop', 'Learn product management strategies and techniques.', '2024-05-08 13:00:00', 'Amsterdam, Netherlands', NULL, NULL, NULL),
(18, 'Test event', 'Testing 131', '2024-02-12 12:11:00', 'Nakuru Kenya', 'snap6.jpg', NULL, 3),
(19, 'Test Event Two', 'TWo', '2024-08-08 17:06:00', 'Nairobi, Kenya', 'ERDImage.jpg', 'https://microcontrollerslab.com/embedded-systems-medical-applications/', 2);

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
(1, 1, '2024-02-28 22:01:34', 'I love the events'),
(2, 1, '2024-02-28 22:03:59', 'This website is amazing'),
(3, 1, '2024-02-28 22:05:07', 'this is a feedback message');

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
(1, 'johndoe@gmail.com', 'johndoe@gmail.com', '$2y$10$.Zh9BJEkpSIJiEpHHp5vvOjRYacLRnPhj4wC5YL9gdFyg5skK2kr2', '2024-02-28 18:13:12'),
(2, 'janedoe@gmail.com', 'janedoe@gmail.com', '$2y$10$lfCdQ2YObPPQz/6vrTicSuXTbj3jw.fhg85djgT.uxYcXbTbwbW46', '2024-02-28 20:06:53');

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
