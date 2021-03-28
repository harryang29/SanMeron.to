-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 28, 2021 at 12:32 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id12914471_sanmerondb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `date_time` datetime DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `category` int(10) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`post_id`, `user_id`, `date_time`, `title`, `category`, `description`, `picture`) VALUES
(1, 1, '2020-03-14 00:49:35', 'Where to buy surgical mask?', 1, '', 'surgical_mask.jpg'),
(2, 2, '2020-03-14 00:55:02', 'Where to buy Green Cross Alcohol', 5, '70%', 'alcohol.jpg'),
(3, 3, '2020-03-14 00:57:40', 'Where to buy Nintendo Switch', 4, 'Price: 13,000php', 'switch.jpg'),
(4, 6, '2020-03-14 01:01:09', 'Where to buy Xiaomi Pocophone F2', 3, '128+6G RAM\r\nBlack', 'Xiaomi-Pocophone-F2-C01.jpg'),
(8, 1, '2020-03-15 20:14:59', 'Where to buy Kryrie 6?', 2, 'primary color: red\r\nsecondary color: blue', ''),
(9, 1, '2020-03-15 20:40:27', 'Where to buy straw hat', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_replies`
--

CREATE TABLE `tbl_replies` (
  `reply_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `replycontent` varchar(500) DEFAULT NULL,
  `replypicture` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_replies`
--

INSERT INTO `tbl_replies` (`reply_id`, `user_id`, `post_id`, `date_time`, `replycontent`, `replypicture`) VALUES
(1, 2, 5, '2020-03-14 20:38:30', 'secret', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(10) NOT NULL,
  `userlevel` varchar(10) NOT NULL DEFAULT 'regular',
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `userpicture` varchar(100) NOT NULL DEFAULT 'default.png',
  `gov_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `userlevel`, `username`, `password`, `email`, `userpicture`, `gov_id`) VALUES
(1, 'premium', 'Ang', '5f4dcc3b5aa765d61d8327deb882cf99', 'harry@gmail.com', 'Harry.jpg', NULL),
(2, 'regular', 'Francisco', '5f4dcc3b5aa765d61d8327deb882cf99', 'paolo@gmail.com', 'Francisco.jpg', NULL),
(3, 'regular', 'DelaFuente', '5f4dcc3b5aa765d61d8327deb882cf99', 'riel@gmail.com', 'delafuente.jpg', NULL),
(4, 'regular', 'Mabunga', '5f4dcc3b5aa765d61d8327deb882cf99', 'ronn@gmail.com', 'default.png', NULL),
(5, 'regular', 'Atupan', '5f4dcc3b5aa765d61d8327deb882cf99', 'atupan@gmail.com', 'default.png', NULL),
(6, 'regular', 'Tiu', '5f4dcc3b5aa765d61d8327deb882cf99', 'jemarson@gmail.com', 'tiu.jpg', NULL),
(7, 'regular', 'Bustillos', '5f4dcc3b5aa765d61d8327deb882cf99', 'darryll@gmail.com', 'default.png', NULL),
(8, 'regular', 'Eleazar', '5f4dcc3b5aa765d61d8327deb882cf99', 'vince@gmail.com', 'default.png', NULL),
(9, 'regular', 'DeGuzman', '5f4dcc3b5aa765d61d8327deb882cf99', 'ruth@gmail.com', 'default.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `tbl_replies`
--
ALTER TABLE `tbl_replies`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `post_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_replies`
--
ALTER TABLE `tbl_replies`
  MODIFY `reply_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
