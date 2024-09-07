-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2024 at 09:53 AM
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
-- Database: `car_sale_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `seats_no` int(11) NOT NULL,
  `doors` enum('2','3','4','5') NOT NULL,
  `fuel` enum('Petrol','Diesel','Gas','Electric','Hybrid/Petrol') NOT NULL,
  `transmission` enum('Automatic','Manual','CVT') NOT NULL,
  `wheel` enum('2WD','4WD') NOT NULL,
  `color` enum('Pearl white','Metallic maroon','Gray','Matte black','Blue','Silver','Black') NOT NULL,
  `mileage` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `seats_no`, `doors`, `fuel`, `transmission`, `wheel`, `color`, `mileage`, `year`, `image`, `created_at`, `price`) VALUES
(10, 'Toyota', 'Toyota Crown Athlete', 5, '5', 'Petrol', 'Automatic', '4WD', 'Silver', 78000, 2005, 'uploads/toyota-crown-athlete-xii-s180-facelift-2005.jpg', '2024-07-15 08:54:37', 13500000.00),
(11, 'Toyota', 'ToToyota Land Cruiser (VDJ200R) VX wagon', 5, '5', 'Petrol', 'Automatic', '4WD', 'Blue', 50000, 2008, 'uploads/1024px-2008_Toyota_Land_Cruiser_(VDJ200R)_VX_wagon_(2008-10-10)_02.jpg', '2024-07-15 09:06:03', 62500000.00),
(12, 'Toyota', 'Toyota Harrier Hybrid', 5, '5', 'Petrol', 'Automatic', '2WD', 'Pearl white', 58000, 2006, 'uploads/1024px-Toyota_Harrier_Hybrid_01.jpeg', '2024-07-15 09:13:06', 38000000.00),
(13, 'Mercedes Benz', 'Mercedes-Benz C-Class', 4, '5', 'Diesel', 'Automatic', '4WD', 'Black', 60000, 2019, 'uploads/1024px-Mercedes-Benz_C-Class_All-Terrain_IAA_2021_1X7A0279.jpg', '2024-07-15 09:24:02', 146000000.00),
(14, 'Land Rover', '2015 Land Rover Discovery (L319 MY15) TDV6 wagon ', 5, '5', 'Diesel', 'Automatic', '2WD', 'Pearl white', 62000, 2015, 'uploads/1024px-2015_Land_Rover_Discovery_(L319_MY15)_TDV6_wagon_(2015-07-24)_01.jpg', '2024-07-15 09:36:48', 122000000.00),
(15, 'BMW', 'BMW 3 SERIES GRAN TURISMO', 5, '5', 'Petrol', 'Automatic', '4WD', 'Black', 84000, 2013, 'uploads/1024px-BMW_5-Series_F07_GT_01_China_2012-06-16.jpg', '2024-07-15 09:56:41', 38000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 1, 'add tesla', '2024-07-14 12:40:50'),
(2, 1, 'The price of the cars are too expensive', '2024-07-14 13:12:33'),
(3, 1, 'The Wishlist is ok  ', '2024-07-14 13:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `status` enum('not picked','picked') DEFAULT 'not picked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `car_id`, `order_date`, `status`) VALUES
(26, 1, 10, '2024-07-15 16:28:59', 'picked'),
(27, 8, 10, '2024-07-17 12:31:04', 'not picked'),
(28, 8, 11, '2024-07-17 12:32:59', 'not picked');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `address`, `contact`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Joshua Speaker Urio', 'Ilala', '0753291621', 'joshuaurio99@gmail.com', '$2y$10$RLavR5ADm8nM054fcDPzDOwWILSpqRS96rsFvNUcTcYAt1vMOmnlK', '2024-06-18 16:59:03', 'user'),
(4, 'administrato', 'Ilala', '0753291621', 'administrator@gmail.com', '$2y$10$GR3jfGjcVdag/CZANU0EzO6.saltrR.k7BEd3.vIskfdWcHo42Mx2', '2024-06-18 17:50:35', 'admin'),
(5, 'Alex Njau', 'Ilala', '0753291621', 'alex@gmail.com', '$2y$10$FsPnpDYqVnhnQ4yxSro/bueh5FMvFSLXJWVy3AzKeO1hbl7vyL8HK', '2024-06-19 10:43:37', 'user'),
(6, 'mishael', 'Ilala', '0753291621', 'donard@gmail.com', '$2y$10$yeLxbPOzIIoyJSvsWQCf3uBm6SUm7TY2a0LrmmDB6Zg3BGhcgieBW', '2024-06-20 07:20:56', 'user'),
(8, 'Ali', 'Ilala', '0753291621', 'ali@gmail.com', '$2y$10$haLFze9wdjHS9yy5eAS9.eTh5Mw7aLzznQNIc6NJE1Wnms/4go/.q', '2024-07-17 09:28:48', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `car_id`) VALUES
(40, 1, 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
