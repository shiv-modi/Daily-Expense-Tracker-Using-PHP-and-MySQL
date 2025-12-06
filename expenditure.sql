-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2023 at 11:46 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expenditure`
--

-- --------------------------------------------------------

--
-- Table structure for table `lending`
--

CREATE TABLE `lending` (
  `id` int(11) UNSIGNED NOT NULL,
  `UserId` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_of_lending` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `status` enum('pending','received') NOT NULL,
  `current_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lending`
--

INSERT INTO `lending` (`id`, `UserId`, `name`, `date_of_lending`, `amount`, `description`, `status`, `current_time`) VALUES
(10, 26, 'admin', '2023-03-16', 5000.00, 'hiii', 'pending', '2023-04-04 13:31:19'),
(11, 30, 'shivmodi', '2023-03-31', 6000.00, 'llll', 'received', '2023-04-04 14:28:52'),
(12, 26, 'king', '2023-03-22', 6000.00, 'hii bro give me my money 💵💵', 'pending', '2023-04-07 05:04:21'),
(13, 31, 'Shiv Modi', '2023-04-05', 5000.00, 'friend ', 'pending', '2023-04-05 14:13:15'),
(14, 31, 'Krsih Patel', '2023-04-04', 2000.00, 'Friends', 'received', '2023-04-05 14:13:48'),
(15, 66, 'shivmodi', '2023-04-03', 1000.00, 'I want to take money from harsh', 'received', '2023-04-11 13:26:38'),
(16, 67, 'Shiv Modi', '2023-04-05', 500.00, 'I want to take money from harsh', 'received', '2023-04-11 13:42:16'),
(17, 68, 'krish patel', '2023-04-04', 500.00, 'i want to take from krish patel on 14/04/23', 'received', '2023-04-12 05:23:44'),
(19, 68, 'shiv modi', '2023-04-12', 5000.00, 'i want to take money from shiv ', 'pending', '2023-04-12 05:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `CategoryId` int(11) NOT NULL,
  `CategoryName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Mode` enum('expense','income') NOT NULL DEFAULT 'expense',
  `UserId` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`CategoryId`, `CategoryName`, `UserId`, `Mode`, `CreatedAt`) VALUES
(73, 'Food ', 68, 'expense', '2023-04-12 05:06:22'),
(74, 'Games 🎮', 68, 'expense', '2023-04-12 05:06:30'),
(75, 'Entertainment 🍿', 68, 'expense', '2023-04-12 05:06:46'),
(76, 'Petrol ⛽⛽', 68, 'expense', '2023-04-12 05:11:53'),
(77, 'Electricity ⚡⚡', 68, 'expense', '2023-04-12 05:13:04'),
(78, 'Rent 🏠', 68, 'expense', '2023-04-12 05:14:10'),
(79, 'Entertainment', 68, 'expense', '2023-04-12 10:27:52'),
(80, 'Salary 💰', 68, 'income', '2023-04-01 09:00:00'),
(81, 'Freelance Work 💻', 68, 'income', '2023-04-01 09:30:00'),
(82, 'Investment Returns 📈', 68, 'income', '2023-04-01 10:00:00'),
(83, 'Side Business 🏪', 68, 'income', '2023-04-01 10:30:00'),
(84, 'Rent Income 🏠', 68, 'income', '2023-04-01 11:00:00'),
(85, 'Bonus 🎉', 68, 'income', '2023-04-01 11:30:00'),
(86, 'Gift Money 🎁', 68, 'income', '2023-04-01 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblincome`
--

CREATE TABLE `tblincome` (
  `ID` int(10) NOT NULL,
  `UserId` int(10) NOT NULL,
  `IncomeDate` date DEFAULT NULL,
  `CategoryId` int(11) NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IncomeAmount` varchar(200) DEFAULT NULL,
  `Description` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NoteDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblincome`
--

INSERT INTO `tblincome` (`ID`, `UserId`, `IncomeDate`, `CategoryId`, `category`, `IncomeAmount`, `Description`, `NoteDate`) VALUES
(1, 68, '2023-04-01', 80, 'Salary 💰', '45000', 'Monthly salary payment', '2023-04-01 09:00:00'),
(2, 68, '2023-04-05', 81, 'Freelance Work 💻', '12000', 'Web development project 💻', '2023-04-05 14:30:00'),
(3, 68, '2023-04-08', 82, 'Investment Returns 📈', '3500', 'Mutual fund returns 📊', '2023-04-08 11:15:00'),
(4, 68, '2023-04-10', 83, 'Side Business 🏪', '8000', 'Online store sales 🛒', '2023-04-10 16:20:00'),
(5, 68, '2023-04-12', 84, 'Rent Income 🏠', '15000', 'Property rent received 🏡', '2023-04-12 10:00:00'),
(6, 68, '2023-04-15', 85, 'Bonus 🎉', '10000', 'Performance bonus 🏆', '2023-04-15 12:30:00'),
(7, 68, '2023-04-18', 86, 'Gift Money 🎁', '5000', 'Birthday gift from family 🎂', '2023-04-18 18:45:00'),
(8, 68, '2023-03-01', 80, 'Salary 💰', '45000', 'Monthly salary payment', '2023-03-01 09:00:00'),
(9, 68, '2023-03-15', 81, 'Freelance Work 💻', '15000', 'Mobile app development 📱', '2023-03-15 14:00:00'),
(10, 68, '2023-03-25', 83, 'Side Business 🏪', '6500', 'Online consultancy 💼', '2023-03-25 11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblexpense`
--

CREATE TABLE `tblexpense` (
  `ID` int(10) NOT NULL,
  `UserId` int(10) NOT NULL,
  `ExpenseDate` date DEFAULT NULL,
  `CategoryId` int(11) NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ExpenseCost` varchar(200) DEFAULT NULL,
  `Description` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NoteDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblexpense`
--

INSERT INTO `tblexpense` (`ID`, `UserId`, `ExpenseDate`, `CategoryId`, `category`, `ExpenseCost`, `Description`, `NoteDate`) VALUES
(108, 30, '2023-04-01', 0, 'Food ', '5000', 'Food 😋😋😋 ', '2023-04-01 14:06:11'),
(109, 30, '2023-04-01', 0, 'Entertainment 🎞️', '1000', '🍿🍿🍿 ', '2023-04-01 14:07:09'),
(110, 30, '2023-04-01', 0, 'Entertainment 🎞️', '5000', 'xxxx ', '2023-04-01 14:30:27'),
(111, 30, '2023-04-01', 0, 'Entertainment 🎞️', '200', 'xxxxx ', '2023-04-01 14:31:30'),
(112, 30, '2023-04-01', 56, 'Entertainment 🎞️', '1000', 'dddd ', '2023-04-01 14:32:16'),
(113, 30, '2023-04-01', 57, 'Grass', '2000', 'hii 🔴🔴 ', '2023-04-01 15:35:50'),
(114, 30, '2023-04-01', 55, 'Food ', '5000', 'ccccccccc ', '2023-04-01 15:36:05'),
(115, 30, '2023-04-01', 58, 'Entertainment 🎞️', '6000', 'shiv modi', '2023-04-01 16:29:04'),
(116, 30, '2023-04-02', 57, 'Grass', '5000', 'dd ', '2023-04-02 08:17:59'),
(117, 30, '2023-04-02', 59, 'Games', '5000', 'hiiiii ', '2023-04-02 08:40:55'),
(122, 26, '2023-04-02', 60, 'Food ', '2000', '😋😋 ', '2023-04-02 18:12:56'),
(123, 26, '2023-04-02', 61, 'Food 😋😋', '5000', '🌽🌽🌽 ', '2023-04-02 18:13:15'),
(124, 31, '2023-04-05', 65, 'Games 🎮', '5000', 'PS5 🎮🎮🎮 ', '2023-04-05 14:07:09'),
(125, 31, '2023-04-05', 63, 'Food ', '4000', 'food  ', '2023-04-05 14:07:32'),
(126, 31, '2023-04-04', 64, 'Entertainment 🎞️', '10000', 'movie 🍿🍿 ', '2023-04-05 14:07:55'),
(127, 31, '2023-04-03', 66, 'Tea ☕', '15000', 'tea month cost ', '2023-04-05 14:08:32'),
(128, 31, '2023-04-02', 67, 'Bank 🏦🏦', '60000', 'bank money deposit ', '2023-04-05 14:09:23'),
(129, 65, '2023-04-11', 68, 'Food', '500', 'Food Expense 🌽🌽 ', '2023-04-11 13:19:27'),
(130, 66, '2023-04-11', 69, 'Food', '500', 'vegetables 🍅🍅 ', '2023-04-11 13:24:15'),
(131, 66, '2023-04-11', 70, 'Games 🎮', '1000', 'PS5 ', '2023-04-11 13:25:06'),
(132, 67, '2023-04-11', 71, 'Food', '600', 'Vegetables 🍅🍅 ', '2023-04-11 13:40:15'),
(133, 67, '2023-04-11', 72, 'Games 🎮', '5000', 'Ps5 ', '2023-04-11 13:41:16'),
(135, 68, '2023-04-11', 75, 'Entertainment 🍿', '6000', 'Movie time 🍿🍿 ', '2023-04-12 05:10:26'),
(136, 68, '2023-04-12', 74, 'Games 🎮', '980', 'PS5  🎞️🎞️ ', '2023-04-12 05:11:01'),
(137, 68, '2023-04-10', 76, 'Petrol ⛽⛽', '500', 'petrol  ', '2023-04-12 05:12:25'),
(138, 68, '2023-04-12', 77, 'Electricity ⚡⚡', '2523', 'electricity billl 🔴 ', '2023-04-12 05:13:38'),
(139, 68, '2023-04-10', 78, 'Rent 🏠', '15000', 'rent  ', '2023-04-12 05:14:25'),
(141, 68, '2023-04-11', 75, 'Entertainment 🍿', '8000', 'movie ', '2023-04-12 05:17:10'),
(142, 68, '2023-04-13', 74, 'Games 🎮', '5000', 'Ps6', '2023-04-12 05:18:07'),
(143, 68, '2023-04-12', 78, 'Rent 🏠', '5000', 'rent  ', '2023-04-12 10:28:09'),
(144, 68, '2023-04-18', 75, 'Entertainment 🍿', '500', 'Computer Organisation zero address instruction 1 address instruction ', '2023-04-18 08:45:06'),
(145, 68, '2023-04-18', 75, 'Entertainment 🍿', '7', 'hii ', '2023-04-18 08:52:11'),
(146, 68, '2023-04-18', 78, 'Rent 🏠', '20000', 'hiiii ', '2023-04-18 08:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(12) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `verification_code`, `created_at`) VALUES
(68, 'User', '91thiru@gmail.com', '9042967143', '$2y$10$JkvQ00olAxMBVQBUJ6kZp.rNtv0v5K7OChUeVvR04uAq8ZEFWDC2.', '4ebebb3c3d07', '2023-04-12 10:31:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lending`
--
ALTER TABLE `lending`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`CategoryId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `tblincome`
--
ALTER TABLE `tblincome`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblexpense`
--
ALTER TABLE `tblexpense`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lending`
--
ALTER TABLE `lending`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tblincome`
--
ALTER TABLE `tblincome`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblexpense`
--
ALTER TABLE `tblexpense`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD CONSTRAINT `tblcategory_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;