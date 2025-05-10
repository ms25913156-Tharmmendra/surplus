-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 12:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `surplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Rating` int(11) NOT NULL CHECK (`Rating` between 1 and 5),
  `Comments` text DEFAULT NULL,
  `Date` date NOT NULL DEFAULT curdate(),
  `DonarContactNo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `userID`, `Rating`, `Comments`, `Date`, `DonarContactNo`) VALUES
(1, 4, 4, 'Fine', '2025-04-22', '0776789787'),
(2, 5, 4, 'Better', '2025-04-22', '0776789787'),
(3, 5, 3, 'Good packaging', '2025-04-22', '0776789787');

-- --------------------------------------------------------

--
-- Table structure for table `postfood`
--

CREATE TABLE `postfood` (
  `food_id` int(11) NOT NULL,
  `food_name` varchar(30) NOT NULL,
  `Qty` int(11) NOT NULL,
  `Description` text NOT NULL,
  `location` varchar(30) NOT NULL,
  `pickuptime` datetime NOT NULL,
  `Contactinformation` varchar(30) NOT NULL,
  `status` varchar(50) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postfood`
--

INSERT INTO `postfood` (`food_id`, `food_name`, `Qty`, `Description`, `location`, `pickuptime`, `Contactinformation`, `status`, `latitude`, `longitude`) VALUES
(1, 'Rice & Curry', 10, 'Freshly cooked rice and curry ', 'Colombo', '0000-00-00 00:00:00', '0771234567', 'Claimed', 12.97160000, 77.59460000),
(2, 'pittu', 5, 'red rice pittu', 'Jaffna', '2025-03-17 17:06:00', '0776969156', 'Claimed', NULL, NULL),
(3, 'bread', 10, 'ghghj', 'mannar', '2025-03-20 17:08:00', '0776969156', 'Claimed', NULL, NULL),
(4, 'bread', 10, 'ghghj', 'mannar', '2025-03-20 17:08:00', '0776969156', 'Claimed', NULL, NULL),
(5, 'Rice', 13, 'ghfj', 'vavuniya', '2025-03-20 17:10:00', '0771568789', 'Claimed', NULL, NULL),
(6, 'Rice', 13, 'sxxxx', 'Tellippalai, Jaffna District, ', '2025-03-19 23:22:00', '0771593690', 'Claimed', NULL, NULL),
(7, 'Kottu', 25, 'abc', 'Tellippalai, Jaffna District, ', '2025-03-22 23:27:00', '0771593690', 'Claimed', NULL, NULL),
(8, 'idiyaapam', 10, 'abc', 'Mathura, 251, Arasady Road, Th', '2025-03-20 16:25:00', '0771593690', 'Claimed', NULL, NULL),
(9, 'xx', 10, 'xxxy', 'Mathura, 251, Arasady Road, Th', '2025-03-25 16:26:00', 'xxxx', 'Claimed', NULL, NULL),
(10, 'bread', 78, 'apple ', 'Tellippalai, Jaffna District, ', '2025-03-28 03:46:00', '+94767819803', 'Claimed', NULL, NULL),
(11, 'Rice', 23, 'xze', 'Tellippalai, Jaffna District, ', '2025-03-28 04:06:00', '+94771593690', 'Claimed', NULL, NULL),
(12, 'bread', 20, 'xyz', 'Tellippalai, Jaffna District, ', '2025-03-29 04:08:00', '+94764653943', 'Claimed', NULL, NULL),
(13, 'pizza', 45, 'zzxx', 'Mathura, 251, Arasady Road, Th', '2025-03-28 11:23:00', '+94778790207', 'Claimed', NULL, NULL),
(14, 'Sandwiches', 10, 'bread,potato,oinion', 'Tellippalai, Jaffna District, ', '2025-03-30 02:37:00', '0771593690', 'Claimed', NULL, NULL),
(15, 'pittu', 10, 'xyz', 'Tellippalai, Jaffna District, ', '2025-03-31 02:52:00', '0771568789', 'Claimed', NULL, NULL),
(16, 'Rice', 23, 'sxcfd', 'Mathura, 251, Arasady Road, Th', '2025-04-16 13:33:00', '+94779785796', 'Claimed', NULL, NULL),
(17, 'Thosai', 35, 'sscdsc', 'K B Christie Perera Road, Kota', '2025-04-11 22:16:00', '0776789789', 'Claimed', NULL, NULL),
(18, 'Idly', 40, 'sscdsc', 'K B Christie Perera Road, Kota', '2025-04-11 22:16:00', '0776789789', 'Claimed', NULL, NULL),
(19, 'Noodles', 37, 'rggdgfddfdfds', 'K B Christie Perera Road, Kota', '2025-04-11 22:32:00', '0776789789', 'Claimed', NULL, NULL),
(20, 'Noodles 1', 30, 'jkjhkjhkj', 'K B Christie Perera Road, Kota', '2025-04-11 22:40:00', '0776789789', 'Claimed', NULL, NULL),
(21, 'aaaaa', 12, 'sadasd', 'K B Christie Perera Road, Kota', '2025-04-12 21:00:00', '0776789789', 'Claimed', 9.67972700, 80.01323069),
(22, 'Noodles 2', 45, 'ererer', 'K B Christie Perera Road, Kota', '2025-04-12 21:57:00', '0776789789', 'Claimed', NULL, NULL),
(23, 'Noodles 3', 45, 'sddsadsad', 'Thiviya Mahal, K.K.S Road, Jaf', '2025-04-15 22:41:00', '0776789789', 'Claimed', 9.68483928, 80.01323069),
(24, '', 0, '', 'Marine Drive, Dehiwala, Colomb', '0000-00-00 00:00:00', '', 'Available', 6.85050000, 79.86030000),
(25, 'Pizza', 10, 'ghjgj', 'Thiviya Mahal, K.K.S Road, Jaf', '2025-04-16 01:06:00', '0776789787', 'Claimed', 9.67972700, 80.03015100),
(26, 'Noodles 5', 45, 'ssfs', 'Northern UNI, Kandarmadam, Jaf', '2025-04-17 10:44:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(27, 'Noodles 6', 45, 'sddsfds', 'Northern UNI, Kandarmadam, Jaf', '2025-04-17 11:00:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(28, 'Noodles 7', 45, 'dsfdsfdf', 'Northern UNI, Kandarmadam, Jaf', '2025-04-17 13:14:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(29, 'Noodles 8', 40, 'hkhkh', 'Northern UNI, Kandarmadam, Jaf', '2025-04-19 17:11:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(30, 'Noodles 9', 40, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-20 13:05:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(31, 'Noodles 10', 30, 'Non Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-20 13:12:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(32, 'Noodles 11', 20, 'Non Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-20 13:21:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(33, 'Noodles 13', 25, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-20 13:34:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(34, 'Noodles 14', 25, 'veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-20 13:46:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(35, 'Noodles 15', 25, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-20 13:59:00', '0776789787', 'Available', 9.65896400, 80.03005100),
(36, 'Rice and curry', 25, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-23 10:26:00', '0776789787', 'Claimed', 9.65896400, 80.03005100),
(37, 'Rice and curry1', 25, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-04-23 10:29:00', '0776789787', 'Available', 9.65896400, 80.03005100),
(38, 'Rice and curry1', 10, 'Veg', 'Selva Road Araly North Vaddukk', '2025-05-05 12:30:00', '0776789787', 'Claimed', 9.66353000, 79.88509100),
(39, 'Rice and curry3', 15, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-05-09 14:30:00', '0776789787', 'Available', 9.65896400, 80.03005100),
(40, 'Rice and curry3', 15, 'Veg', 'Northern UNI, Kandarmadam, Jaf', '2025-05-09 14:30:00', '0776789787', 'Available', 9.65896400, 80.03005100);

-- --------------------------------------------------------

--
-- Table structure for table `reservedfood`
--

CREATE TABLE `reservedfood` (
  `Id` int(11) NOT NULL,
  `ReserverName` varchar(50) NOT NULL,
  `FoodName` varchar(50) NOT NULL,
  `Qty` int(11) NOT NULL,
  `DonarContactNo` varchar(15) NOT NULL,
  `ReserverContact` varchar(15) NOT NULL,
  `ReservedDate` datetime NOT NULL,
  `Status` varchar(25) NOT NULL,
  `PostFoodID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservedfood`
--

INSERT INTO `reservedfood` (`Id`, `ReserverName`, `FoodName`, `Qty`, `DonarContactNo`, `ReserverContact`, `ReservedDate`, `Status`, `PostFoodID`) VALUES
(1, 'Raj', 'Rice & Curry', 10, '<br />\r\n<b>Warn', 'Unknown', '2025-04-11 11:28:59', 'Pending', NULL),
(2, 'Raj', 'Rice & Curry', 10, '0771234567', '0771234567', '2025-04-11 15:59:40', 'Pending', NULL),
(3, 'Tharmakulasingam Tharmmendra', 'pittu', 5, '2', 'Unknown', '2025-04-11 16:39:39', 'Pending', NULL),
(4, 'Tharmakulasingam Tharmmendra', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:46:26', 'Pending', NULL),
(5, 'Tharmakulasingam Tharmmendra', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:47:46', 'Pending', NULL),
(6, 'Raj', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:49:11', 'Pending', NULL),
(7, 'Raj', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:52:17', 'Pending', NULL),
(8, 'Tharmakulasingam Tharmmendra', 'Rice', 13, '0771593690', 'Unknown', '2025-04-11 16:52:20', 'Pending', NULL),
(9, 'Tharmakulasingam Tharmmendra', 'idiyaapam', 10, '0771593690', 'Unknown', '2025-04-11 16:52:25', 'Pending', NULL),
(10, 'Tharmakulasingam Tharmmendra', 'Rice & Curry', 10, '0771234567', 'Unknown', '2025-04-11 16:53:27', 'Pending', NULL),
(11, 'Tharmakulasingam Tharmmendra', 'Rice & Curry', 10, '0771234567', 'Unknown', '2025-04-11 16:53:29', 'Pending', NULL),
(12, 'Tharmakulasingam Tharmmendra', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:53:31', 'Pending', NULL),
(13, 'Tharmakulasingam Tharmmendra', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:53:32', 'Pending', NULL),
(14, 'Tharmakulasingam Tharmmendra', 'Rice', 13, '0771593690', 'Unknown', '2025-04-11 16:53:34', 'Pending', NULL),
(15, 'Tharmakulasingam Tharmmendra', 'Rice & Curry', 10, '0771234567', 'Unknown', '2025-04-11 16:59:22', 'Pending', NULL),
(16, 'Tharmakulasingam Tharmmendra', 'pittu', 5, '0776969156', 'Unknown', '2025-04-11 16:59:24', 'Pending', NULL),
(17, 'Tharmakulasingam Tharmmendra', 'idiyaapam', 10, '0771593690', 'Unknown', '2025-04-11 16:59:29', 'Pending', NULL),
(18, 'Tharmmendra', 'bread', 10, '0776969156', 'Unknown', '2025-04-11 17:03:38', 'Pending', NULL),
(19, 'Tharmmendra', 'bread', 10, '0776969156', 'Unknown', '2025-04-11 17:03:44', 'Pending', NULL),
(20, 'Tharmmendra', 'Rice', 13, '0771593690', 'Unknown', '2025-04-11 17:04:09', 'Pending', NULL),
(21, 'Tharmmendra', 'Rice', 13, '0771593690', 'Unknown', '2025-04-11 17:04:10', 'Pending', NULL),
(22, 'Tharmmendra', 'Rice', 13, '0771593690', 'Unknown', '2025-04-11 17:04:12', 'Pending', NULL),
(23, 'Tharmmendra', 'Rice', 13, '0771568789', 'Unknown', '2025-04-11 17:09:17', 'Collected', NULL),
(24, 'Tharmmendra', 'Kottu', 25, '0771593690', 'Unknown', '2025-04-11 17:10:07', 'Collected', NULL),
(25, 'Tharmmendra', 'xx', 10, 'xxxx', 'Unknown', '2025-04-11 17:10:31', 'Collected', NULL),
(26, 'Tharmakulasingam Tharmmendra', 'Rice', 23, '+94779785796', 'Unknown', '2025-04-11 17:13:06', 'Pending', NULL),
(27, 'Tharmakulasingam Tharmmendra', 'pizza', 45, '+94778790207', 'Unknown', '2025-04-11 17:18:50', 'Pending', NULL),
(28, 'Tharmakulasingam Tharmmendra', 'pittu', 10, '0771568789', 'Unknown', '2025-04-11 17:19:08', 'Pending', NULL),
(29, 'Tharmakulasingam Tharmmendra', 'Sandwiches', 10, '0771593690', 'Unknown', '2025-04-11 17:21:18', 'Pending', NULL),
(30, 'Tharmakulasingam Tharmmendra', 'bread', 20, '+94764653943', 'Unknown', '2025-04-11 17:25:33', 'Pending', NULL),
(31, 'Tharmakulasingam Tharmmendra', 'Rice', 23, '+94771593690', 'Unknown', '2025-04-11 17:28:26', 'Pending', NULL),
(32, 'Tharmakulasingam Tharmmendra', 'bread', 78, '+94767819803', 'Unknown', '2025-04-11 18:18:27', 'Collected', NULL),
(33, 'Tharmakulasingam Tharmmendra', 'Noodles 1', 30, '0776789789', 'Unknown', '2025-04-11 19:11:09', 'Collected', NULL),
(34, 'Tharmakulasingam Tharmmendra', 'Noodles', 37, '0776789789', 'Unknown', '2025-04-12 05:04:29', 'Pending', NULL),
(35, 'Tharmakulasingam Tharmmendra', 'Idly', 40, '0776789789', 'Unknown', '2025-04-12 05:16:32', 'Collected', NULL),
(36, 'Tharmmendra', 'Thosai', 35, '0776789789', '+94779785796', '2025-04-12 05:39:44', 'Collected', NULL),
(46, 'Tharmmendra', 'Noodles 3', 45, '0776789789', '+94779785796', '2025-04-15 19:24:49', 'Pending', 23),
(47, 'Tharmmendra', 'Pizza', 10, '0776789787', '+94779785796', '2025-04-15 21:38:13', 'Collected', 25),
(48, 'Tharmmendra', 'Noodles 2', 45, '0776789789', '+94779785796', '2025-04-15 22:35:20', 'Collected', 22),
(49, 'Tharmmendra', 'aaaaa', 12, '0776789789', '+94779785796', '2025-04-15 22:48:33', 'Collected', 21),
(50, 'Tharmmendra', 'Noodles 5', 45, '0776789787', '+94779785796', '2025-04-17 07:15:42', 'Collected', 26),
(51, 'Tharmmendra', 'Noodles 6', 45, '0776789787', '+94779785796', '2025-04-17 07:31:18', 'Collected', 27),
(52, 'Tharmmendra', 'Noodles 7', 45, '0776789787', '+94779785796', '2025-04-17 09:44:23', 'Collected', 28),
(53, 'Tharmakulasingam Tharmmendra', 'Noodles 8', 40, '0776789787', '+94779785796', '2025-04-20 10:57:47', 'Pending', 29),
(54, 'Tharmakulasingam Tharmmendra', 'Noodles 9', 40, '0776789787', '+94779785796', '2025-04-20 10:58:45', 'Pending', 30),
(55, 'Tharmakulasingam Tharmmendra', '', 0, '0776789787', '+94779785796', '2025-04-22 16:56:05', 'Pending', 34),
(56, 'Tharmakulasingam Tharmmendra', '', 0, '0776789787', '+94779785796', '2025-04-22 16:56:30', 'Pending', 33),
(57, 'Tharmakulasingam Tharmmendra', 'Noodles 10', 30, '0776789787', '+94779785796', '2025-04-22 18:18:32', 'Pending', 31),
(58, 'Tharmakulasingam Tharmmendra', 'Noodles 11', 20, '0776789787', '+94779785796', '2025-04-23 06:55:54', 'Pending', 32),
(59, 'Tharmakulasingam Tharmmendra', 'Rice and curry', 25, '0776789787', '+94779785796', '2025-04-23 06:58:23', 'Pending', 36),
(60, 'Tharmakulasingam Tharmmendra', 'Rice and curry1', 10, '0776789787', '+94779785796', '2025-05-04 18:05:10', 'Pending', 38);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `location` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_temp_password` tinyint(1) DEFAULT 0,
  `role` enum('admin','poster','seeker') DEFAULT 'seeker'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `phone`, `location`, `email`, `password`, `is_temp_password`, `role`) VALUES
(2, 'Tharmmendra', '+94779785796', 'Jaffna', 'ms25913156@my.sliit.lk', '$2y$10$5C57gN7v9W5.tVxMPjBhfuPFzMkKj70faKmru06hyCG.3rIc/Ftw2', 0, 'admin'),
(3, 'Raj', '+94779785796', 'Jaffna', 'raj123@gmail.com', '$2y$10$5jv5AwhYpS68duKizxozv.qigBpcrVWj662fccGo0UJF7657RwCP.', 0, 'poster'),
(4, 'Tharmakulasingam Tharmmendra', '+94779785796', 'Jaffna', 'tharmmendra@gmail.com', '$2y$10$5jv5AwhYpS68duKizxozv.qigBpcrVWj662fccGo0UJF7657RwCP.', 0, 'seeker'),
(5, 'Vithiyasahar', '+94764653943', 'Jaffna', 'vithiyasaharv@northernuni.com', '$2y$10$LSsigDBcbRzGWfRp.ojIJ.wH3JxtT4Mvz89.FZl.GYfrXxMCJzGcG', 0, 'seeker');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `postfood`
--
ALTER TABLE `postfood`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `reservedfood`
--
ALTER TABLE `reservedfood`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_postfood` (`PostFoodID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `postfood`
--
ALTER TABLE `postfood`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `reservedfood`
--
ALTER TABLE `reservedfood`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `reservedfood`
--
ALTER TABLE `reservedfood`
  ADD CONSTRAINT `fk_postfood` FOREIGN KEY (`PostFoodID`) REFERENCES `postfood` (`food_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
