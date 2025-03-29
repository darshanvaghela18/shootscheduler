-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 28, 2025 at 04:09 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shootscheduler`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'admin', '$2y$10$LJAySsmThNUAbU4PPz7WTer4jnsj5OjzPayKz0M/5YwicPw42HssK');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(3, 'darshan vaghela', 'darshanedition17@gamil.com', 'jjjiijij', '2025-03-02 17:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `document_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `project_id`, `file_name`, `uploaded_at`, `document_name`) VALUES
(5, 10, 'test1.txt', '2025-03-01 06:30:57', 'screenplay'),
(7, 5, 'Project Front page (2).docx', '2025-03-01 10:48:12', ''),
(20, 11, '1740833934_1825.docx', '2025-03-01 12:58:54', 'scripts'),
(22, 14, '1742366584_6049.docx', '2025-03-19 06:43:04', 'scripts');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `expense_date` date NOT NULL,
  `expense_unit` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Thousand'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `project_id`, `amount`, `description`, `expense_date`, `expense_unit`) VALUES
(35, 11, 100.00, 'actro fees', '2025-03-01', 'Thousand'),
(36, 11, 500.00, 'vfx-cgi post production', '2025-03-01', 'Thousand'),
(37, 11, 300.00, 'fine', '2025-03-01', 'Thousand'),
(45, 5, 12.00, 'sfsfd', '2025-03-18', 'Thousand'),
(46, 14, 100.00, 'actors fees', '2025-03-19', 'Thousand'),
(47, 14, 24.00, 'vfx ', '2025-03-19', 'Thousand');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `admin_reply` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `message`, `admin_reply`, `created_at`) VALUES
(13, 17, 'i having some issue while createing proejcts', 'can give me detail that what kind of issue u facing', '2025-03-19 06:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int NOT NULL,
  `gmail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_requests`
--

INSERT INTO `otp_requests` (`id`, `gmail`, `otp`, `created_at`) VALUES
(1, 'vagheladarshan939@gmail.com', '777816', '2024-12-31 11:34:58'),
(2, 'vagheladarshan939@gmail.com', '972267', '2024-12-31 11:53:19'),
(3, 'vagheladarshan939@gmail.com', '255795', '2024-12-31 12:00:38'),
(4, 'vagheladarshan939@gmail.com', '819779', '2025-01-01 01:53:47'),
(5, 'vagheladarshan939@gmail.com', '519760', '2025-01-01 05:56:42'),
(6, 'vagheladarshan939@gmail.com', '513687', '2025-01-01 06:36:52'),
(7, 'vagheladarshan939@gmail.com', '681360', '2025-01-01 06:38:17'),
(8, 'vagheladarshan939@gmail.com', '670431', '2025-01-01 06:45:26'),
(9, 'vagheladarshan939@gmail.com', '176604', '2025-01-01 06:53:25'),
(10, 'vagheladarshan939@gmail.com', '309414', '2025-01-01 06:56:48'),
(11, 'vagheladarshan939@gmail.com', '708728', '2025-01-01 06:58:50'),
(12, 'vagheladarshan939@gmail.com', '436402', '2025-01-01 06:59:53'),
(13, 'vagheladarshan939@gmail.com', '504002', '2025-01-01 07:04:52'),
(14, 'vagheladarshan939@gmail.com', '656670', '2025-01-01 07:09:09'),
(15, 'vagheladarshan939@gmail.com', '481602', '2025-01-01 07:12:39'),
(16, 'vagheladarshan939@gmail.com', '249226', '2025-01-01 07:16:27'),
(17, 'vagheladarshan939@gmail.com', '928935', '2025-01-01 07:22:57'),
(18, 'vagheladarshan939@gmail.com', '526113', '2025-01-01 07:25:23'),
(19, 'vagheladarshan939@gmail.com', '209820', '2025-01-01 07:28:57'),
(20, 'vagheladarshan939@gmail.com', '249885', '2025-01-01 07:47:19'),
(21, 'vagheladarshan939@gmail.com', '219060', '2025-01-01 07:47:26'),
(22, 'vagheladarshan939@gmail.com', '518775', '2025-01-01 07:48:55'),
(23, 'vagheladarshan939@gmail.com', '122686', '2025-01-01 11:18:38'),
(24, 'vagheladarshan939@gmail.com', '160255', '2025-01-01 11:23:43'),
(25, 'vagheladarshan939@gmail.com', '509398', '2025-01-01 11:27:50'),
(26, 'vagheladarshan939@gmail.com', '143503', '2025-01-01 11:32:08'),
(27, 'vagheladarshan939@gmail.com', '833404', '2025-01-01 11:43:11'),
(28, 'vagheladarshan939@gmail.com', '756045', '2025-01-01 12:00:20'),
(29, 'vagheladarshan939@gmail.com', '365894', '2025-01-02 00:37:26'),
(30, 'vagheladarshan939@gmail.com', '329154', '2025-01-03 11:28:08'),
(31, 'vagheladarshan939@gmail.com', '651372', '2025-01-03 11:40:08'),
(32, 'vagheladarshan939@gmail.com', '486615', '2025-01-04 10:11:15'),
(33, 'vagheladarshan939@gmail.com', '270927', '2025-02-07 01:19:49'),
(34, 'vagheladarshan939@gmail.com', '429605', '2025-02-07 01:19:52'),
(35, 'jay@gmail.com', '464587', '2025-02-25 23:53:05'),
(36, 'jay@gmail.com', '770722', '2025-02-25 23:55:24'),
(37, 'darshanvaghela939@gmail.com', '717373', '2025-02-25 23:58:18'),
(38, 'darshanvaghela939@gmail.com', '675429', '2025-02-25 23:59:53'),
(39, 'darshanvaghela939@gmail.com', '336210', '2025-02-26 00:10:20'),
(40, 'chauhan39black@gmail.com', '759064', '2025-03-17 23:29:24'),
(41, 'chauhan39black@gmail.com', '950775', '2025-03-17 23:30:16'),
(42, 'chauhan39black@gmail.com', '191294', '2025-03-17 23:31:44'),
(43, 'chauhan39black@gmail.com', '571017', '2025-03-17 23:35:19'),
(44, 'darshanvaghela939@gmail.com', '131360', '2025-03-18 23:43:52'),
(45, 'darshanedition17@gamil.com', '577495', '2025-03-18 23:52:20'),
(46, 'darshanedition17@gamil.com', '431065', '2025-03-19 00:02:28'),
(47, 'vagheladarshan939@gmail.com', '635317', '2025-03-19 00:04:37'),
(48, 'vagheladarshan939@gmail.com', '335549', '2025-03-19 00:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `photo_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `project_id`, `file_name`, `uploaded_at`, `photo_name`) VALUES
(1, 10, 'DALL·E 2025-01-31 21.22.35 - A modern and eye-catching logo for \'QuickCuts\'. The design should be bold, sleek, and dynamic, featuring a sharp and stylish font. The theme should re.webp', '2025-03-01 06:20:55', 'poster1'),
(12, 11, 'WhatsApp Image 2024-11-03 at 8.46.33 AM.jpeg', '2025-03-01 12:59:13', 'photos1'),
(14, 11, 'Netflix bereikt wederom indrukwekkende mijlpaal.jpg', '2025-03-17 10:54:12', 'hsjdahsgj'),
(15, 14, 'FANTASTIC FOUR.jpg', '2025-03-19 06:46:49', 'poster1'),
(16, 14, 'Fantastic Four First Steps fanmade poster.jpg', '2025-03-19 06:47:06', 'poster2'),
(17, 14, 'download.jpg', '2025-03-19 06:47:20', 'poster3');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `director_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `budget` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `project_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expected_release_date` date DEFAULT NULL,
  `created_by` int NOT NULL,
  `budget_unit` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Lakh',
  `synopsis` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `director_name`, `budget`, `project_status`, `expected_release_date`, `created_by`, `budget_unit`, `synopsis`) VALUES
(3, 'pushpa2', 'sukumaran', '500cr', 'Completed', '2025-06-25', 0, 'Lakh', NULL),
(4, 'maharaja', 'drashan vaghela', '12cr', 'Post-Production', '2025-05-29', 0, 'Lakh', NULL),
(5, 'maharaja', 'drashan vaghela', '1200', 'Filming', '2025-02-27', 17, 'Cr', NULL),
(10, 'animal', 'sandip raddy vanag', '200', 'Pre-Production', '2025-03-10', 17, 'Cr', NULL),
(11, 'ramayan part1', 'nitish tiwari', '1000', 'Pre-Production', '2026-10-02', 18, 'Cr', 'ramayana part 1 a mordon day adaptaion of ramayan its about the life of lord rama'),
(14, 'The Fantastic Four: First Steps', 'Matt Shakman', '300', 'Post-Production', '2025-07-24', 17, 'Cr', 'Against the vibrant backdrop of a 1960s-inspired, retro-futuristic world, Marvel\'s First Family is forced to balance their roles as heroes with the strength of their family bond, while defending Earth from a ravenous space god called Galactus and his enigmatic Herald, Silver Surfer'),
(15, 'asdad', 'addada', '12', 'Filming', '2025-03-29', 17, 'Cr', 'sdfdgjdjffdfjjdsdgjdkssjs'),
(16, 'asdad', 'addada', '12', 'Filming', '2025-03-29', 17, 'Cr', 'sdfdgjdjffdfjjdsdgjdkssjs');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `member_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `project_id`, `member_name`, `role`, `email`) VALUES
(8, 3, 'alu arjun', 'Actor', 'alu@gmail.com'),
(9, 3, 'rashmika', 'Actor', 'rash@gmail.com'),
(11, 3, 'vivek', 'cinemetpgafer', 'viek@gmail.com'),
(12, 4, 'nitin', 'Director', 'nitin@gmail.com'),
(13, 4, 'goutam', 'Actor', 'gg@gmial.com'),
(17, 5, 'smit', 'Actor', 'smit@123'),
(22, 11, 'ranber kapoor', 'Actor', 'rab@gmail.com'),
(23, 11, 'yash', 'Actor', 'yah@gmail.com'),
(24, 11, 'hens zimmar', 'Crew', 'hens@gmail.com'),
(29, 14, 'Pedro Pascal', 'Actor', 'Pedro@gmail.com'),
(30, 14, 'Vanessa Kirby', 'Actor', 'Vanessa@gmail.com'),
(31, 14, 'Michael Giacchino', 'Crew', 'Michael@gmail.com'),
(32, 14, 'Joseph Quinn', 'Actor', 'Joseph@gmail.com'),
(33, 16, 'adasdada', 'Actor', 'dasdsadsa@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `shoot_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Scheduled','Completed','Postponed') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `shoot_purpose` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `project_id`, `shoot_date`, `start_time`, `end_time`, `location`, `status`, `created_by`, `user_id`, `shoot_purpose`) VALUES
(1, 5, '2025-04-16', '05:35:00', '18:35:00', 'palitana', 'Scheduled', 17, 17, ''),
(3, 10, '2025-02-27', '17:04:00', '21:04:00', 'benglor', NULL, 17, 17, 'Scene'),
(5, 11, '2025-04-04', '19:24:00', '23:24:00', 'ayodhya', NULL, 18, 18, 'Scene'),
(7, 14, '2025-05-22', '12:56:00', '19:56:00', 'New York', NULL, 17, 17, 'Photoshoot'),
(8, 14, '2025-02-25', '19:07:00', '23:07:00', 'SFAFAFAFA', NULL, 17, 17, 'Scene');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `gmail` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `gmail`, `password`, `created_at`, `last_login`) VALUES
(17, 'jay', 'jay@gmail.com', '$2y$10$X869K5X5s2YQkRNYh13ALeo/12FaUO8atEbC7WHb/Yn9ljoPqFqYW', '2025-02-26 04:11:05', '2025-03-19 16:00:11'),
(18, 'xyz', 'xyz@gmail.com', '$2y$10$WGWEl/So8.eG1eEyjebr1OEQv0.vfPgY46JLu65nN4C2pXvyf5l2S', '2025-02-27 09:43:44', '2025-03-18 11:25:12'),
(19, 'jaydeep', 'jaydeep@gmail.com', '$2y$10$IebS1e6RT0jvJLjQ6UHIyO5df7OAE50HKzyvoKowE5gtYFqeLXMjS', '2025-03-03 04:15:42', NULL),
(20, 'vivek', 'vivek1@gmail.com', '$2y$10$tmeVhrL9cnxP3V28xDkiSe/pUprFLosVEh22kyVeNAMuEvoWQSI/6', '2025-03-03 04:40:42', NULL),
(21, 'smit', 'chauhan39black@gmail.com', '$2y$10$E8bjmJEg4K4Tsom3CA1YMefCYwSzdkGQaC8At27BPQDfvZt.m98qu', '2025-03-18 04:55:11', '2025-03-18 10:25:49'),
(22, 'darshan vaghela', 'vagheladarshan939@gmail.com', '$2y$10$ueieatEPfN/YJiJlfaFKV.esrSrqZI6V9UtZSmyg0YJ90DZPJzQ0C', '2025-03-19 05:10:38', NULL),
(23, 'vaghela darshan', 'darshanvaghela939@gmail.com', '$2y$10$Gcqv.NsxmfZnOwGZKnHtiOFunl3tS.Z0dE2p7xF3apncDTHW4tKsG', '2025-03-19 05:12:08', NULL),
(24, 'asfssf', 'darshanedition17@gamil.com', '$2y$10$nJpIcfP6Td3Bk.iXzIIvouQHssRGowUjsAur5PAIRJEWe9Wrr13mK', '2025-03-19 05:20:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gmail` (`gmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `schedules_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
