-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 08:05 PM
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
-- Database: `friendzone2`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`, `parent_id`) VALUES
(19, 14, 1, 'Nice Design....', '2024-07-22 08:56:32', NULL),
(20, 14, 1, 'Great Job', '2024-07-24 06:33:15', NULL),
(21, 14, 1, 'Thanks', '2024-07-24 07:29:49', 19),
(22, 14, 1, 'Okay', '2024-07-24 07:30:02', NULL),
(23, 14, 2, 'Very nice bro', '2024-07-24 07:34:47', NULL),
(24, 14, 2, 'Beautiful', '2024-07-24 07:34:57', NULL),
(25, 18, 2, 'Latest Post MY...', '2024-07-24 07:40:46', NULL),
(26, 18, 3, 'Wow', '2024-07-24 17:41:26', 25),
(27, 18, 3, 'This is comment\n', '2024-07-24 17:48:50', NULL),
(28, 18, 3, 'This is  Reply', '2024-07-24 17:49:03', 27),
(29, 14, 4, 'Thanks', '2024-07-24 17:51:34', 24),
(30, 14, 5, 'Lol...', '2024-07-24 17:55:06', 19),
(31, 14, 5, 'Fist comment', '2024-07-24 17:56:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','accepted','declined') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `sender_id`, `receiver_id`, `status`, `created_at`) VALUES
(1, 1, 3, 'accepted', '2024-07-20 07:12:23'),
(2, 2, 3, 'accepted', '2024-07-20 07:52:33'),
(4, 3, 1, 'accepted', '2024-07-20 08:35:49'),
(5, 1, 2, 'accepted', '2024-07-22 08:58:33'),
(6, 4, 12, 'pending', '2024-07-22 09:06:02'),
(7, 4, 14, 'pending', '2024-07-22 09:06:12'),
(8, 4, 16, 'pending', '2024-07-23 15:22:11'),
(9, 4, 15, 'pending', '2024-07-23 15:22:16'),
(10, 20, 5, 'accepted', '2024-07-23 15:52:27'),
(11, 20, 19, 'pending', '2024-07-23 15:52:30'),
(13, 4, 1, 'pending', '2024-07-24 05:51:02'),
(14, 4, 5, 'accepted', '2024-07-24 06:00:10'),
(15, 4, 18, 'pending', '2024-07-24 07:20:54'),
(16, 4, 7, 'pending', '2024-07-24 07:20:57'),
(17, 4, 17, 'pending', '2024-07-24 07:20:58'),
(18, 4, 13, 'pending', '2024-07-24 07:21:01'),
(19, 4, 19, 'pending', '2024-07-24 07:21:15'),
(20, 3, 4, 'accepted', '2024-07-24 07:21:53'),
(21, 3, 19, 'pending', '2024-07-24 07:21:55'),
(22, 3, 5, 'accepted', '2024-07-24 17:49:58'),
(23, 3, 6, 'pending', '2024-07-24 17:49:59'),
(24, 3, 15, 'pending', '2024-07-24 17:50:01'),
(25, 3, 11, 'pending', '2024-07-24 17:50:03'),
(26, 3, 18, 'pending', '2024-07-24 17:50:04'),
(27, 3, 12, 'pending', '2024-07-24 17:50:06'),
(28, 3, 10, 'pending', '2024-07-24 17:50:08'),
(29, 3, 14, 'pending', '2024-07-24 17:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image`, `created_at`) VALUES
(14, 3, 'Checkout My new design', 'uploads/1141_1.jpg', '2024-07-22 08:47:45'),
(15, 4, 'Nice To Meet Everyone......', NULL, '2024-07-23 15:24:06'),
(17, 2, 'Time to cook...', 'uploads/9959_tabitha-turner-yLbmZLbNILg-unsplash.jpg', '2024-07-24 07:36:30'),
(18, 2, 'Love to work...', 'uploads/9774_alabaster-co-wNVhAqxuL6o-unsplash.jpg', '2024-07-24 07:36:51'),
(19, 3, 'It\'s time for something special', 'uploads/3906_andy-chilton-0JFveX0c778-unsplash.jpg', '2024-07-24 17:50:33'),
(20, 3, 'New baby dress', 'uploads/2786_WhatsApp Image 2024-07-24 at 2.19.55 AM.jpeg', '2024-07-24 17:50:50'),
(21, 3, 'How are you All???', NULL, '2024-07-24 17:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'uploads/default_profile.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `profile_image`) VALUES
(1, 'Sami Mushtaq', 'Abadali', 'abad71804@gmail.com', '$2y$10$oDNidqCeNXKMO0CH9uWVsedXvtIFJWIWtzPIcr97.M4XUfmhfq4wy', 'uploads/profile_images/6bf9d89c334f78951582f03fdf25b503.png'),
(2, 'Sami Mushtaq', 'abdul786', 'abad180@gmail.com', '$2y$10$dec/.FGtRSvGKqDFf2gmR.4sQY585N0esmFIj6rk1iloUvF.GGAdO', 'uploads/profile_images/2b4002e3360fabc89f8d2b1a949388d9.jpg'),
(3, 'Sami Mushtaq', 'admin', 'samimushtaq222@gmail.com', '$2y$10$f3DhUCAGO63pn8SUlxyKduA4t1RL7ZkHlhigdadJfyaZprq0h3zXG', 'uploads/default_profile.jpg'),
(4, 'Samina khalid', 'user1', 'user1@gmail.com', '$2y$10$43EnI9mYOP1pWhDbvKDKduzGLIQaafNjuOb5k61FN7cazjDHurpJy', 'uploads/profile_images/7d19b0ff7f1ff21952ac49a480dbab82.jpeg'),
(5, 'Samina shahid', 'user2', 'user2@gmail.com', '$2y$10$WXE7tDvAxjFOGvto7ut5nuSo5DVYU8vzDU0./DjKb0A0Z8Y8EKfCS', 'uploads/profile_images/8813c2e3afa1a3b95b8aa13eb0ebc4c3.jpeg'),
(6, 'Sulman Parwaiz', 'user3', 'user3@gmail.com', '$2y$10$3Xit9y8YJRNtbvAAxorx6OoUoYRX28D/5mGsEIDYQBRMQOs2zbiHW', 'uploads/default_profile.jpg'),
(7, 'Sohail Shezad', 'user4', 'user4@gmail.com', '$2y$10$Gv3Gzn83DjmuR6ec4cehc.hUFGUsh7iOdtZwkU02frmW.hMmybMHW', 'uploads/default_profile.jpg'),
(8, 'Mirza Hanief', 'user6', 'user6@gmail.com', '$2y$10$KmPMQWp1YFb534zJnR.an.N/ftPjW9wVUpCvO2h92PrJdYsikb4Ry', 'uploads/default_profile.jpg'),
(9, 'Samjad Hussain', 'user7', 'user7@gmail.com', '$2y$10$/f9VhkZ771vLvytzKGGJf.1FHjnmrKXuuei5Iy0puSy/5bOdc2g6q', 'uploads/default_profile.jpg'),
(10, 'Majid khan', 'user8', 'user8@gmail.com', '$2y$10$nDdhfRWFFa30D2aK2p1W6.Y/zblt.KCju8AdbEf8PiGP89LsVB3/a', 'uploads/default_profile.jpg'),
(11, 'Sultan Shahid', 'user9', 'user9@gmail.com', '$2y$10$K/FxDsHW296jsnY/Un1UdOvxU2/tsyK9j1ka4XnM2HOZSLUfBjMqu', 'uploads/default_profile.jpg'),
(12, 'Tania Noor', 'user10', 'user10@gmail.com', '$2y$10$UpW4ddDXGI1BVEfari/gr.pRposJzS0h2zjgLh/P/F1KUHR33MWv2', 'uploads/default_profile.jpg'),
(13, 'Minahil Shaw', 'user11', 'user11@gmail.com', '$2y$10$easwKv7HnMxpDCDo8.SiT.czV3GhOkoLllSfcelMLvmfmIrR5.jSm', 'uploads/default_profile.jpg'),
(14, 'Sana Shah', 'user12', 'user12@gmail.com', '$2y$10$KRXbixhs7P1mogrh0h4uq.LA.osLMRJwRHzhNk9RNoV3qvD16apX6', 'uploads/default_profile.jpg'),
(15, 'Fatima Naz', 'user13', 'user13@gmail.com', '$2y$10$ltE0KvdE41X3SdF.Q28D.uWxGiCrQqK3JVxjfb8k3iBi/iG4/KBAG', 'uploads/default_profile.jpg'),
(16, 'Gul sher Khan', 'user14', 'user14@gmail.com', '$2y$10$DCYpbD1UsJcAHkztbzHwB.33xWIsrFA6dcQ/MXzyWAkYawhl7Yaw2', 'uploads/default_profile.jpg'),
(17, 'Husnain Majid', 'user15', 'user15@gmail.com', '$2y$10$rxuDoC0HHqYcGKDX6pRSlOyJ/4/zXpYhQVsrsh91kHid0L8gqSdHO', 'uploads/default_profile.jpg'),
(18, 'Aryan Khan', 'user16', 'user16@gmail.com', '$2y$10$sSU1ZtSu6M3Fx3iwxRUWYuLHWAC9X0C4taQSrt2/A0PSUtllFV2G2', 'uploads/default_profile.jpg'),
(19, 'Humaiyoun Khan', 'user17', 'user17@gmail.com', '$2y$10$wpKy6gPN24l8nPKXQAXAceUH/sLVwQTUKA05Y10EPizwCTDZ6cAxW', 'uploads/default_profile.jpg'),
(20, 'Dr Abdul Khan', 'user20', 'user20@gmail.com', '$2y$10$OuuryTCl4WYZJY.qCkzO4eSaJNNOtfjA.0MclPxyjmxczHWAJ6MTK', 'uploads/default_profile.jpg'),
(21, 'Smex EX', 'smex123', 'smex@gmail.com', '$2y$10$eiMOgW03r6RhWG4BhfSYHeKS02xw5FVGoaM/ZyS7DcT/cDGNGU/su', 'uploads/default_profile.jpg'),
(22, 'pojja khan', 'pooja', 'pooja@gmail.com', '$2y$10$FEtkiXfDavoOp2I1J4aHMeQOvv44/LvXCnO3yb3Kqn28MrJXPcQXS', 'uploads/default_profile.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_parent_id` (`parent_id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`);

--
-- Constraints for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `friend_requests_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friend_requests_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
