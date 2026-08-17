-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 11:10 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `printer_monitor`
--

-- --------------------------------------------------------

--
-- Table structure for table `print_logs`
--

CREATE TABLE `print_logs` (
  `id` int(11) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `print_logs`
--

INSERT INTO `print_logs` (`id`, `document_name`, `user_name`, `pages`, `created_at`, `status`) VALUES
(19, 'Unknown', 'LabUser', 1, '2026-03-20 09:02:34', 'success'),
(20, 'Unknown', 'LabUser', 1, '2026-03-20 09:07:34', 'success'),
(21, 'Unknown', 'LabUser', 1, '2026-03-20 09:09:34', 'success'),
(22, 'Unknown', 'LabUser', 1, '2026-03-20 09:12:23', 'success'),
(23, 'Unknown', 'LabUser', 1, '2026-03-20 09:15:08', 'success'),
(24, 'Unknown', 'LabUser', 1, '2026-03-20 09:15:33', 'success'),
(25, 'Unknown', 'LabUser', 1, '2026-03-20 09:16:53', 'success'),
(26, 'Unknown', 'LabUser', 1, '2026-03-20 09:17:21', 'success'),
(27, 'Unknown', 'LabUser', 1, '2026-03-20 09:17:46', 'success'),
(28, 'Unknown', 'LabUser', 1, '2026-03-20 09:21:44', 'success'),
(29, 'Unknown', 'LabUser', 1, '2026-03-20 09:22:15', 'success'),
(30, 'Unknown', 'LabUser', 1, '2026-03-20 09:26:25', 'success'),
(31, 'Unknown', 'LabUser', 1, '2026-03-20 10:05:04', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `print_logs`
--
ALTER TABLE `print_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `print_logs`
--
ALTER TABLE `print_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
