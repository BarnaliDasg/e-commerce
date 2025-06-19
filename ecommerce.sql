-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 06:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('T-Shirts','Jeans','Dresses','Accessories','Footwear') NOT NULL,
  `target_audience` enum('Male','Female','Kid') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `stock` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `target_audience`, `price`, `image_url`, `stock`, `created_at`) VALUES
(1, 'Classic White T-Shirt', 'A comfortable cotton t-shirt for everyday wear.', 'T-Shirts', 'Male', 499.00, 'https://example.com/images/white-tshirt.jpg', 50, '2025-06-07 15:58:57'),
(2, 'Blue Skinny Jeans', 'Stylish skinny jeans made from stretchable denim.', 'Jeans', 'Female', 999.99, 'https://example.com/images/blue-jeans.jpg', 30, '2025-06-07 15:58:57'),
(3, 'Floral Summer Dress', 'Lightweight floral dress perfect for summer.', 'Dresses', 'Female', 1299.50, 'https://example.com/images/summer-dress.jpg', 20, '2025-06-07 15:58:57'),
(4, 'Leather Wallet', 'Genuine leather wallet with multiple compartments.', 'Accessories', 'Male', 799.00, 'https://example.com/images/leather-wallet.jpg', 40, '2025-06-07 15:58:57'),
(5, 'Kids Sneakers', 'Comfortable sneakers for children aged 4-10.', 'Footwear', 'Kid', 699.00, 'https://example.com/images/kids-sneakers.jpg', 25, '2025-06-07 15:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ac_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=not verified, 1=verified user, 2=blocked, 3=admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fname`, `lname`, `email`, `password`, `ac_status`, `created_at`) VALUES
(1, 'Barnali', 'Das', 'barnalidasg3@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 3, '2025-06-07 15:55:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
