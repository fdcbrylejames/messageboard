-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 25, 2024 at 08:59 AM
-- Server version: 8.0.37
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `messageboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_time` datetime DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `hobby` text COLLATE utf8mb4_general_ci,
  `image` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created`, `modified`, `last_login_time`, `gender`, `birthdate`, `hobby`, `image`) VALUES
(37, 'test bryle', 'fdc.bryle1@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-19 14:26:29', '2024-06-25 08:13:06', '2024-06-25 07:49:33', 'male', '2024-06-19', 'xsxsdcedccsdx', 'user_37_1719303186.jpeg'),
(38, 'test bryle 2', 'fdc.bryle2@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-19 14:27:55', '2024-06-25 08:53:17', '2024-06-25 08:48:25', 'male', '2024-06-25', 'sdvdscvdcsc', 'user_38_1719305257.jpeg'),
(39, 'ako123', 'fdc.bryle3@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-19 15:10:22', '2024-06-24 09:48:06', '2024-06-24 09:47:13', 'male', '2007-05-22', 'dedsfdsdssdxsdsswdxsxasxsxs', 'user_39_1719222486.jpeg'),
(40, 'akkoni21', 'fdc.bryle4@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-19 15:11:00', '2024-06-23 03:50:06', '2024-06-23 03:50:06', 'male', '2010-12-28', 'ako hobbyaDsd', 'user_40_1718837871.png'),
(41, 'tes5t', 'fdc.bryle5@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-19 22:15:22', '2024-06-19 22:15:22', NULL, NULL, NULL, NULL, 'avatar.png'),
(42, 'test bryle 6', 'fdc.bryle6@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-19 22:16:12', '2024-06-22 08:18:59', '2024-06-22 08:18:59', NULL, NULL, NULL, 'avatar.png'),
(43, 'test bryle', 'fdc.bryle7@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:43:19', '2024-06-22 08:46:00', '2024-06-22 08:46:00', NULL, NULL, NULL, 'avatar.png'),
(44, 'test bryle', 'fdc.bryle8@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:44:54', '2024-06-22 08:46:44', '2024-06-22 08:46:44', NULL, NULL, NULL, 'avatar.png'),
(45, 'bryle 9', 'fdc.bryle9@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:45:18', '2024-06-22 08:47:08', '2024-06-22 08:47:08', NULL, NULL, NULL, 'avatar.png'),
(46, 'bryle 10', 'fdc.bryle10@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:45:39', '2024-06-22 08:47:34', '2024-06-22 08:47:34', NULL, NULL, NULL, 'avatar.png'),
(47, 'bryle 11', 'fdc.bryle11@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:48:45', '2024-06-22 08:49:46', '2024-06-22 08:49:46', NULL, NULL, NULL, 'avatar.png'),
(48, 'bryle12', 'fdc.bryle12@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:49:01', '2024-06-22 08:50:05', '2024-06-22 08:50:05', NULL, NULL, NULL, 'avatar.png'),
(49, 'bryle13', 'fdc.bryle13@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-22 08:49:20', '2024-06-22 16:05:15', '2024-06-22 16:05:15', NULL, NULL, NULL, 'avatar.png'),
(50, 'test bryle', 'fdc.bryle122@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-23 02:35:50', '2024-06-23 02:35:50', NULL, NULL, NULL, NULL, 'avatar.png'),
(51, 'test bryle', 'fdc.bryle1123@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-23 02:39:45', '2024-06-23 02:39:45', NULL, NULL, NULL, NULL, 'avatar.png'),
(52, 'kanisw', 'fdc.bryle15@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-23 04:03:01', '2024-06-23 05:08:30', '2024-06-23 05:08:30', NULL, NULL, NULL, 'avatar.png'),
(53, 'test bryle', 'fdc.bryle16@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-23 22:45:37', '2024-06-23 22:45:37', NULL, NULL, NULL, NULL, 'avatar.png'),
(54, 'test bryle', 'fdc.bryle17@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-23 22:48:09', '2024-06-23 22:48:09', NULL, NULL, NULL, NULL, 'avatar.png'),
(55, 'test bryle122', 'fdc.bryle81@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-24 06:06:00', '2024-06-24 06:06:00', NULL, NULL, NULL, NULL, 'avatar.png'),
(56, 'sdsdcsdccs', 'fdc.bryle123@gmail.com', '0b6673e1a29ff16030e6bdf274119f0bb011d6c4', '2024-06-24 09:46:40', '2024-06-24 09:46:40', NULL, NULL, NULL, NULL, 'avatar.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
