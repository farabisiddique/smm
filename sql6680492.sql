-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: sql6.freemysqlhosting.net
-- Generation Time: Feb 26, 2024 at 01:05 PM
-- Server version: 5.5.62-0ubuntu0.14.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sql6680492`
--

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
  `order_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_user_id`, `order_api_id`, `order_link`, `order_service_id`, `order_qty`, `order_charge`, `order_status`, `order_created_at`) VALUES
(36, 2, 1532758, 'https://www.facebook.com/mayafilmscreation/', 12, 100, 0.1225, 'Pending', '2024-02-19 06:51:36'),
(37, 2, 1532860, 'https://www.youtube.com/watch?v=e_zuM2qaPb4', 38, 100, 0.171, 'Pending', '2024-02-19 07:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `pm_id` int(11) NOT NULL,
  `pm_name` text NOT NULL,
  `pm_no` int(11) NOT NULL,
  `pm_logo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`pm_id`, `pm_name`, `pm_no`, `pm_logo`) VALUES
(1, 'Bkash Send Money', 1868338693, './img/bkash_logo.png'),
(2, 'Nagad Send Money', 1869447682, './img/nagad_logo.png');

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
  `service_rate_percentage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_cat_id`, `service_subcat_id`, `service_api_id`, `service_rate_percentage`) VALUES
(11, 'Page Likes with Followers (Orginal)', 1, 11, 1, 400),
(12, 'Page & Profile Followers (Orginal)', 1, 11, 1038, 400),
(13, 'Post Reaction Like &#128077; (Orginal)', 1, 11, 1325, 400),
(14, 'Post Reaction Love &#128150; (Orginal)', 1, 11, 8410, 400),
(15, 'Post Reaction Care &#129392; (Orginal)', 1, 11, 8414, 400),
(16, 'Post Reaction WoW &#128562; (Orginal)', 1, 11, 4696, 400),
(17, 'Post Reaction HaHa &#128512; (Orginal)', 1, 11, 8412, 400),
(18, 'Post Reaction Angry &#128545; (Orginal)', 1, 11, 8413, 400),
(19, 'Post Reaction Sad &#128546; (Orginal)', 1, 11, 8411, 400),
(20, 'BD Page Likes & Followers (Orginal)', 1, 12, 746, 200),
(21, 'BD Profile Followers (Orginal)', 1, 12, 8925, 200),
(22, 'BD Post Shares (Orginal)', 1, 12, 1829, 400),
(23, 'BD Post Reaction Likes &#128077; (Orginal)', 1, 12, 1952, 400),
(24, 'BD Post Reaction Love &#128150; (Orginal)', 1, 12, 1674, 400),
(25, 'BD Post Reaction Care &#129392; (Orginal)', 1, 12, 1527, 400),
(26, 'BD Post Reaction WoW &#128550; (Orginal)', 1, 12, 1929, 400),
(27, 'BD Post Reaction HaHa &#128514; (Orginal)', 1, 12, 1599, 400),
(28, 'BD Post Reaction Sad &#128549; (Orginal)', 1, 12, 1540, 400),
(29, 'BD Post Reaction Angry &#128545; (Orginal)', 1, 12, 1629, 400),
(30, 'Facebook Video Views (Orginal)', 1, 13, 4504, 400),
(31, 'Facebook Video Views with 30 Second watch time (Orginal)', 1, 13, 166, 400),
(32, 'TikTok Video Views', 2, 14, 174, 400),
(33, 'TikTok Video Likes', 2, 14, 58, 400),
(34, 'TikTok Followers', 2, 14, 361, 400),
(35, 'TikTok Followers (Orginal)', 2, 14, 831, 400),
(36, 'YouTube Channel Subscribers', 3, 15, 1358, 200),
(37, 'YouTube Video Views', 3, 15, 8999, 200),
(38, 'YouTube Video Likes', 3, 15, 100, 200),
(39, 'Instagram Likes (Orginal)', 4, 16, 1304, 200),
(40, 'Instagram Likes INSTANT (Orginal)', 4, 16, 2431, 400),
(41, 'Instagram Followers', 4, 16, 74, 400);

-- --------------------------------------------------------

--
-- Table structure for table `service_category`
--

CREATE TABLE `service_category` (
  `service_category_id` int(11) NOT NULL,
  `service_category_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_category`
--

INSERT INTO `service_category` (`service_category_id`, `service_category_name`) VALUES
(1, 'Facebook'),
(2, 'Tiktok'),
(3, 'Youtube'),
(4, 'Instagram'),
(5, 'Offer');

-- --------------------------------------------------------

--
-- Table structure for table `service_subcategory`
--

CREATE TABLE `service_subcategory` (
  `service_subcategory_id` int(11) NOT NULL,
  `service_subcategory_name` text NOT NULL,
  `service_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_subcategory`
--

INSERT INTO `service_subcategory` (`service_subcategory_id`, `service_subcategory_name`, `service_category_id`) VALUES
(11, 'Facebook All Country', 1),
(12, 'Facebook Bangladesh', 1),
(13, 'Facebook Video Views', 1),
(14, 'TikTok Best Services', 2),
(15, 'YouTube Service', 3),
(16, 'Instagram Services BD', 4);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_id` int(11) NOT NULL,
  `dollar_rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_id`, `dollar_rate`) VALUES
(1, 109.733);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `txn_id` int(11) NOT NULL,
  `txn_sender_userid` int(11) NOT NULL,
  `txn_amount_usd` decimal(10,2) DEFAULT NULL,
  `txn_fee_bdt` decimal(10,2) DEFAULT NULL,
  `txn_amount_total_sent_bdt` decimal(10,2) DEFAULT NULL,
  `txn_payment_method_id` int(11) DEFAULT NULL,
  `txn_sender_no` int(11) DEFAULT NULL,
  `txn_amount_total_received_bdt` decimal(10,2) DEFAULT NULL,
  `txn_approved` int(11) DEFAULT NULL,
  `txn_balance_amount_added` decimal(10,2) DEFAULT NULL,
  `txn_created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `txn_approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_email` text NOT NULL,
  `user_pass` text NOT NULL,
  `user_name` text NOT NULL,
  `user_balance` float NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `user_pass`, `user_name`, `user_balance`, `created_at`) VALUES
(1, 'farabi@gmail.com', 'ffffff1', 'Earshadul Bari Siddique', 28.85, '2024-01-29 09:36:01'),
(2, 'fahim@gmail.com', '123', 'Fahim Siddique', 91.04, '2024-02-04 09:27:47'),
(21, 'farabi2@gmail.com', 'ffffff1', 'Dr A B M Haroon', 0, '2024-02-05 05:39:59'),
(22, 'cuentapokerkevin@gmail.com', 'Kevin1234.', 'kevinroses', 0, '2024-02-23 20:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text CHARACTER SET utf8mb4 NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`token_id`, `user_id`, `token`, `expires_at`) VALUES
(76, 2, '900768580458c4a3e9cf5d58bf1c6e90df76e5d1fa75d7ef4191e539b25865ef493658dda6f76dc9ce3e0f1e253d5f07bccaab07f22627ceca6d7997043d8fa2', '2024-03-20 09:01:52'),
(77, 2, 'e0638f490a497aff7cc3720cd6cb930f9cd0e7a43fb6d15b8ac61d2591612797b75f12454af0efb87ca3fd6f4c06c4578a70c40eb4413e2d4fc8909c91c4d5dc', '2024-03-21 16:44:51'),
(78, 22, 'b8e7c524793dca53bb908d568d04ec7ad4f0f5c2fabb43e52ed72cdd2b8e2ab251b074f9003463d3d993d6378a8c197eecd9744dba7e06a2b9642a0111fe88bb', '2024-03-24 21:44:30'),
(79, 22, '9a26b2ae0896bc7fdef376196977092117dca59eddcfd9a3d056a695e10f66ed532119c8140fd7778acaa3a5dd7bee41c75bb13b4320107d3d901670b0a27858', '2024-03-24 21:44:34');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`txn_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `pm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `service_category`
--
ALTER TABLE `service_category`
  MODIFY `service_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `service_subcategory`
--
ALTER TABLE `service_subcategory`
  MODIFY `service_subcategory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `txn_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
