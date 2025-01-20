-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2025 at 01:53 PM
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
-- Database: `smm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_full_name` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_full_name`, `username`, `password`) VALUES
(1, 'Earshadul Bari Siddique', 'farabi', '123');

-- --------------------------------------------------------

--
-- Table structure for table `admin_tokens`
--

CREATE TABLE `admin_tokens` (
  `admin_token_id` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tokens`
--

INSERT INTO `admin_tokens` (`admin_token_id`, `token`, `admin_id`, `created_at`, `expires_at`) VALUES
(6, 0, 1, '2025-01-19 07:17:37', '2025-02-18 02:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_user_id` int(11) NOT NULL,
  `order_api_id` int(11) NOT NULL,
  `order_link` text NOT NULL,
  `order_service_id` int(11) NOT NULL,
  `order_qty` int(11) NOT NULL,
  `order_charge` float NOT NULL,
  `order_status` text NOT NULL,
  `order_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_user_id`, `order_api_id`, `order_link`, `order_service_id`, `order_qty`, `order_charge`, `order_status`, `order_created_at`) VALUES
(1, 1, 101, 'http://example.com/1', 201, 5, 25.5, 'Pending', '2025-01-01 04:00:00'),
(2, 2, 102, 'http://example.com/2', 202, 3, 15.75, 'Completed', '2025-01-02 05:15:00'),
(3, 3, 103, 'http://example.com/3', 203, 10, 50, 'Processing', '2025-01-03 08:30:00'),
(4, 4, 104, 'http://example.com/4', 204, 8, 40, 'Cancelled', '2025-01-04 10:45:00'),
(5, 5, 105, 'http://example.com/5', 205, 2, 12.99, 'Pending', '2025-01-05 03:00:00'),
(6, 1, 106, 'http://example.com/6', 206, 1, 5.5, 'Completed', '2025-01-06 06:00:00'),
(7, 2, 107, 'http://example.com/7', 207, 7, 35, 'Processing', '2025-01-07 08:10:00'),
(8, 3, 108, 'http://example.com/8', 208, 4, 20, 'Pending', '2025-01-08 10:20:00'),
(9, 4, 109, 'http://example.com/9', 209, 6, 30, 'Cancelled', '2025-01-09 02:30:00'),
(10, 5, 110, 'http://example.com/10', 210, 9, 45, 'Completed', '2025-01-10 04:00:00'),
(11, 6, 111, 'http://example.com/11', 211, 15, 75, 'Processing', '2025-01-11 05:30:00'),
(12, 7, 112, 'http://example.com/12', 212, 20, 100, 'Pending', '2025-01-12 07:45:00'),
(13, 8, 113, 'http://example.com/13', 213, 12, 60, 'Completed', '2025-01-13 09:50:00'),
(14, 9, 114, 'http://example.com/14', 214, 18, 90, 'Cancelled', '2025-01-14 11:55:00'),
(15, 10, 115, 'http://example.com/15', 215, 5, 25, 'Pending', '2025-01-15 13:10:00'),
(16, 11, 116, 'http://example.com/16', 216, 8, 40, 'Processing', '2025-01-16 14:20:00'),
(17, 12, 117, 'http://example.com/17', 217, 14, 70, 'Cancelled', '2025-01-17 16:25:00'),
(18, 13, 118, 'http://example.com/18', 218, 6, 30, 'Completed', '2025-01-18 17:30:00'),
(19, 14, 119, 'http://example.com/19', 219, 2, 10, 'Pending', '2025-01-18 19:00:00'),
(20, 15, 120, 'http://example.com/20', 220, 10, 50, 'Completed', '2025-01-19 21:15:00'),
(21, 16, 121, 'http://example.com/21', 221, 25, 125, 'Processing', '2025-01-20 23:30:00'),
(22, 17, 122, 'http://example.com/22', 222, 3, 15, 'Pending', '2025-01-22 01:45:00'),
(23, 18, 123, 'http://example.com/23', 223, 20, 100, 'Cancelled', '2025-01-23 04:00:00'),
(24, 19, 124, 'http://example.com/24', 224, 7, 35, 'Processing', '2025-01-24 06:15:00'),
(25, 20, 125, 'http://example.com/25', 225, 9, 45, 'Completed', '2025-01-25 08:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `pm_id` int(11) NOT NULL,
  `pm_name` text NOT NULL,
  `pm_no` int(11) NOT NULL,
  `pm_logo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`pm_id`, `pm_name`, `pm_no`, `pm_logo`) VALUES
(1, 'bKash', 1868338693, './img/bkash_logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` text NOT NULL,
  `service_cat_id` int(11) NOT NULL,
  `service_subcat_id` int(11) NOT NULL,
  `service_api_id` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `service_rate_percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_category`
--

CREATE TABLE `service_category` (
  `service_category_id` int(11) NOT NULL,
  `service_category_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_subcategory`
--

CREATE TABLE `service_subcategory` (
  `service_subcategory_id` int(11) NOT NULL,
  `service_subcategory_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_id` int(11) NOT NULL,
  `dollar_rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_id`, `dollar_rate`) VALUES
(1, 122);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` text NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` text NOT NULL,
  `user_balance` float NOT NULL,
  `user_active` int(11) NOT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_email`, `user_pass`, `user_balance`, `user_active`, `activated_at`, `created_at`) VALUES
(1, 'Farabi', 'farabi@gmail.com', '123', 0, 1, '2024-01-26 01:22:05', '2024-01-26 01:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `user_token_id` int(11) NOT NULL,
  `token` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`user_token_id`, `token`, `user_id`, `created_at`, `expires_at`) VALUES
(4, '06748490ddc7225edee2c8c80022d563f566d89f8ff541d143f1798f3e0e76d976c33016aa65b465099c7ac19bf0e5d67a185f02824798ea78b459be5c636971', 1, '2025-01-17 10:34:49', '2025-02-16 05:34:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_tokens`
--
ALTER TABLE `admin_tokens`
  ADD PRIMARY KEY (`admin_token_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`pm_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_category`
--
ALTER TABLE `service_category`
  ADD PRIMARY KEY (`service_category_id`);

--
-- Indexes for table `service_subcategory`
--
ALTER TABLE `service_subcategory`
  ADD PRIMARY KEY (`service_subcategory_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`user_token_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_tokens`
--
ALTER TABLE `admin_tokens`
  MODIFY `admin_token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `pm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_category`
--
ALTER TABLE `service_category`
  MODIFY `service_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_subcategory`
--
ALTER TABLE `service_subcategory`
  MODIFY `service_subcategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `user_token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
