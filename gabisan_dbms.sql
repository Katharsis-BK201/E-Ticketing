-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 02:36 AM
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
-- Database: `gabisan_dbms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodation`
--

CREATE TABLE `accommodation` (
  `accom_price_id` int(11) NOT NULL,
  `accom_type` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accommodation`
--

INSERT INTO `accommodation` (`accom_price_id`, `accom_type`) VALUES
(1, 'Tourist A (Aircon)'),
(2, 'Tourist B (Aircon)'),
(3, 'Economy A'),
(4, 'Economy B'),
(5, 'Tourist C (Aircon)'),
(6, 'Economy C'),
(7, 'Deck A'),
(8, 'Deck B'),
(9, 'Tourist');

-- --------------------------------------------------------

--
-- Table structure for table `accommodation_prices`
-- ASFASDFAWEFAWEFA WEFAWE

CREATE TABLE `accommodation_prices` (
  `ferry_id` int(11) NOT NULL,
  `accom_id` int(11) NOT NULL,
  `price` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accommodation_prices`
--

INSERT INTO `accommodation_prices` (`ferry_id`, `accom_id`, `price`) VALUES
(7001, 1, 550.00),
(7001, 3, 420.00),
(7001, 4, 400.00),
(7001, 5, 500.00),
(7002, 1, 550.00),
(7002, 5, 500.00),
(7002, 7, 420.00),
(7002, 8, 400.00),
(7003, 3, 650.00),
(7003, 4, 550.00),
(7003, 9, 700.00),
(7004, 1, 450.00),
(7004, 4, 350.00),
(7004, 6, 370.00);

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions_log`
--

CREATE TABLE `admin_actions_log` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` enum('add new ferry','delete ferry','add sail sched','update sail sched','delete sail sched','update user book','accept user book cancel','rejected user book cancel','confirmed user payment','add accommodation type','add accommodation price') NOT NULL,
  `target_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin_actions_log`
--

INSERT INTO `admin_actions_log` (`log_id`, `admin_id`, `action`, `target_id`, `timestamp`) VALUES
(1, 20240001, 'add new ferry', 7004, '2024-11-14 17:32:30'),
(2, 20240001, 'add new ferry', 7003, '2024-11-14 18:25:31'),
(3, 20240001, '', 7003, '2024-11-19 01:17:02'),
(4, 20240001, 'add new ferry', 7003, '2024-11-19 01:17:02'),
(5, 20240001, '', 7003, '2024-11-19 01:17:56'),
(6, 20240001, 'add new ferry', 7003, '2024-11-19 01:17:56'),
(7, 20240001, '', 7003, '2024-11-19 01:30:51'),
(8, 20240001, '', 7003, '2024-11-19 01:31:33'),
(9, 20240001, '', 7003, '2024-11-19 01:31:33'),
(10, 20240001, '', 7003, '2024-11-19 02:03:42'),
(11, 20240001, '', 7003, '2024-11-19 02:03:42'),
(12, 20240001, '', 7003, '2024-11-19 05:25:37'),
(13, 20240001, '', 7003, '2024-11-19 05:49:12'),
(14, 20240001, 'add new ferry', 7004, '2024-11-19 06:30:48'),
(15, 20240001, '', 7004, '2024-11-19 06:33:03'),
(16, 20240001, '', 7001, '2024-11-19 14:40:26'),
(17, 20240001, '', 7001, '2024-11-19 14:40:41'),
(18, 20240001, '', 7001, '2024-11-19 14:40:54'),
(19, 20240001, '', 7001, '2024-11-19 14:41:08'),
(20, 20240001, '', 7002, '2024-11-19 14:41:24'),
(21, 20240001, '', 7002, '2024-11-19 14:41:40'),
(22, 20240001, '', 7002, '2024-11-19 14:41:54'),
(23, 20240001, '', 7002, '2024-11-19 14:42:03'),
(24, 20240001, '', 7004, '2024-11-19 14:42:15'),
(25, 20240001, '', 7004, '2024-11-19 14:42:28'),
(26, 20240001, '', 7004, '2024-11-19 14:42:39'),
(27, 20240001, '', 7003, '2024-11-19 14:45:50'),
(28, 20240001, '', 7003, '2024-11-19 14:46:00'),
(29, 20240001, '', 7003, '2024-11-19 14:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_ferry_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `middle_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `birth_date` date NOT NULL,
  `civil_status` varchar(45) NOT NULL,
  `nationality` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  `passenger_type` enum('regular','student','senior','pwd','child','infant') NOT NULL,
  `sub_price` decimal(10,2) NOT NULL,
  `valid_id` varchar(255) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('confirmed','cancelled','pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancellation`
--

CREATE TABLE `cancellation` (
  `cancellation_id` int(11) NOT NULL,
  `fk_booking_id` int(11) NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `cancellation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ferries`
--

CREATE TABLE `ferries` (
  `ferry_id` int(11) NOT NULL,
  `ferry_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ferries`
--

INSERT INTO `ferries` (`ferry_id`, `ferry_name`) VALUES
(7001, 'MV GLORIA FIVE'),
(7002, 'MV GLORIA THREE'),
(7003, 'MV GLORIA G-1'),
(7004, 'MV GLORIA TWO');

-- --------------------------------------------------------

--
-- Table structure for table `ferry_schedule`
--

CREATE TABLE `ferry_schedule` (
  `ferry_id` int(11) NOT NULL,
  `departure_port` varchar(45) NOT NULL,
  `arrival_port` varchar(45) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ferry_schedule`
--

INSERT INTO `ferry_schedule` (`ferry_id`, `departure_port`, `arrival_port`, `departure_time`, `arrival_time`, `status`) VALUES
(7001, 'Hilongos', 'Cebu', '21:00:00', '03:00:00', 'active'),
(7002, 'Hilongos', 'Cebu', '21:00:00', '03:00:00', 'active'),
(7003, 'Hilongos', 'Cebu', '08:30:00', '14:30:00', 'active'),
(7004, 'Hilongos', 'Cebu', '21:30:00', '04:30:00', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `fk_booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_method` enum('credit_card','debit_card','paypal') NOT NULL,
  `status` enum('completed','refunded') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sail_history`
--

CREATE TABLE `sail_history` (
  `history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `ferry_id` int(11) NOT NULL,
  `action` enum('booked','cancelled','complete') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `acc_type` enum('admin','customer') NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone_num` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `acc_type`, `email`, `phone_num`, `created_at`, `updated_at`, `deleted_at`) VALUES
(20240001, 'admin', '$2y$10$1w0MHqEyeSbCzB/VJJMWHOb0mf4vC7kpWiEW7g', 'admin', 'admin@gmail.com', '09560051733', '2024-11-14 01:48:08', NULL, NULL),
(20240002, 'admin1', '$2y$10$U2xRwjV.SKJfUaHch3LhLuJxqKihsndxVhk0bd', 'admin', 'admin45@gmail.com', '09123456789', '2024-11-14 01:51:02', NULL, NULL),
(20240003, 'Percival', '$2y$10$MsI7GQE0n5HjT/ixDFW3TObnS63MOfpW/OR45Z', 'customer', 'percival@gmail.com', '09123456789', '2024-11-14 16:08:58', NULL, NULL),
(20240004, 'Ceejay', '$2y$10$wDTCLGXosyyzZtCoMMNVY.0A84jGYA8bbV6VJe', 'customer', 'ceejay@gmail.com', '09123456789', '2024-11-18 08:52:51', NULL, NULL),
(20240005, 'Julius', '$2y$10$r/nISyrX9r6gI59.neAp3OIbgquO0Hp3bjBrbww.DFn.loID3qa.G', 'customer', 'julius@gmail.com', '09987654321', '2024-11-18 09:23:52', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD PRIMARY KEY (`accom_price_id`);

--
-- Indexes for table `accommodation_prices`
--
ALTER TABLE `accommodation_prices`
  ADD PRIMARY KEY (`ferry_id`,`accom_id`),
  ADD KEY `fk_accom_id_idx` (`accom_id`);

--
-- Indexes for table `admin_actions_log`
--
ALTER TABLE `admin_actions_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id_idx` (`admin_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id_idx` (`fk_user_id`),
  ADD KEY `flight_id_idx` (`fk_ferry_id`);

--
-- Indexes for table `cancellation`
--
ALTER TABLE `cancellation`
  ADD PRIMARY KEY (`cancellation_id`),
  ADD KEY `booking_id_idx` (`fk_booking_id`);

--
-- Indexes for table `ferries`
--
ALTER TABLE `ferries`
  ADD PRIMARY KEY (`ferry_id`);

--
-- Indexes for table `ferry_schedule`
--
ALTER TABLE `ferry_schedule`
  ADD KEY `fk_ferry_schedule_id_idx` (`ferry_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id_idx` (`fk_booking_id`);

--
-- Indexes for table `sail_history`
--
ALTER TABLE `sail_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `ferry_id_idx` (`ferry_id`),
  ADD KEY `booking_id_idx` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodation`
--
ALTER TABLE `accommodation`
  MODIFY `accom_price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_actions_log`
--
ALTER TABLE `admin_actions_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cancellation`
--
ALTER TABLE `cancellation`
  MODIFY `cancellation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ferries`
--
ALTER TABLE `ferries`
  MODIFY `ferry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7005;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sail_history`
--
ALTER TABLE `sail_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20240006;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accommodation_prices`
--
ALTER TABLE `accommodation_prices`
  ADD CONSTRAINT `fk_accom_id` FOREIGN KEY (`accom_id`) REFERENCES `accommodation` (`accom_price_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ferry_price_id` FOREIGN KEY (`ferry_id`) REFERENCES `ferries` (`ferry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_actions_log`
--
ALTER TABLE `admin_actions_log`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_ferry_id` FOREIGN KEY (`fk_ferry_id`) REFERENCES `ferries` (`ferry_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cancellation`
--
ALTER TABLE `cancellation`
  ADD CONSTRAINT `fk_cancellation_booking_id` FOREIGN KEY (`fk_booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ferry_schedule`
--
ALTER TABLE `ferry_schedule`
  ADD CONSTRAINT `fk_ferry_schedule_id` FOREIGN KEY (`ferry_id`) REFERENCES `ferries` (`ferry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_booking_id` FOREIGN KEY (`fk_booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sail_history`
--
ALTER TABLE `sail_history`
  ADD CONSTRAINT `fk_sailhistory_booking_id` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sailhistory_ferry_id` FOREIGN KEY (`ferry_id`) REFERENCES `ferries` (`ferry_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sailhistory_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
