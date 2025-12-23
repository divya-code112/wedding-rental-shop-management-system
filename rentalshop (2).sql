-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 08:56 AM
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
-- Database: `rentalshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `rental_days` int(11) DEFAULT 1,
  `quantity` int(11) DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `item_rent_total` decimal(10,2) DEFAULT 0.00,
  `item_deposit` decimal(10,2) DEFAULT 0.00,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(3, 'Bengali Wedding'),
(2, 'Christian Wedding'),
(4, 'Gujarati Wedding'),
(1, 'Hindu Wedding'),
(6, 'Jain Wedding'),
(7, 'Muslim Wedding'),
(5, 'Punjabi Wedding'),
(8, 'South Indian Wedding');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(1, 'Divya Lawand', 'lawanddivya@gmail.com', 'Availability of Gown for Wedding', 'Hello Royal Drapes Team,  \r\n\r\nI am planning my wedding on 25th January 2026 and I am interested in renting a bridal lehenga and groomswear.  \r\nCould you please share your availability, pricing, and catalog?  \r\n\r\nThank you,  \r\nDivya Lawand', '2025-12-17 14:15:52');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `product_id`, `rating`, `comments`, `feedback_date`) VALUES
(2, 1, NULL, 5, 'The Dresses are very Nice', '2025-12-17 13:45:40'),
(3, 1, NULL, 5, 'The Dresses are very Nice', '2025-12-17 13:50:46'),
(4, 1, NULL, 5, 'Products are very Nice', '2025-12-17 13:51:08'),
(5, 1, NULL, 5, 'Products are very Nice', '2025-12-17 13:54:10');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `notification_type` enum('email','sms','system') DEFAULT NULL,
  `status` enum('sent','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('pending','confirmed','processing','delivered','returned','cancelled') DEFAULT 'pending',
  `delivery_date` date DEFAULT NULL,
  `return_due_date` date DEFAULT NULL,
  `cancellation_deadline` timestamp NULL DEFAULT NULL,
  `total_rent_amount` decimal(10,2) DEFAULT NULL,
  `total_deposit` decimal(10,2) DEFAULT NULL,
  `advance_amount` decimal(10,2) DEFAULT NULL,
  `final_amount` decimal(10,2) DEFAULT NULL,
  `late_fee` decimal(10,2) DEFAULT 0.00,
  `damage_fee` decimal(10,2) DEFAULT 0.00,
  `refund_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount_payable` decimal(10,2) DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL,
  `final_payment_status` enum('pending','paid') DEFAULT 'pending',
  `is_overdue` tinyint(1) DEFAULT 0,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `payment_method` varchar(20) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `order_status`, `delivery_date`, `return_due_date`, `cancellation_deadline`, `total_rent_amount`, `total_deposit`, `advance_amount`, `final_amount`, `late_fee`, `damage_fee`, `refund_amount`, `total_amount_payable`, `delivered_at`, `returned_at`, `final_payment_status`, `is_overdue`, `payment_status`, `payment_method`, `paid_amount`, `paid_at`) VALUES
(1, 1, '2025-11-27 13:34:51', 'cancelled', '0000-00-00', '0000-00-00', NULL, 36000.00, 11000.00, 11000.00, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(2, 1, '2025-11-27 13:50:43', 'cancelled', '2025-11-28', '2025-11-29', '2025-11-27 13:20:43', 15000.00, 5000.00, 5000.00, 0.00, 0.00, 0.00, 5000.00, 5000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(3, 1, '2025-11-27 14:14:33', 'returned', '2025-11-27', '2025-11-28', '2025-11-27 14:44:33', 35000.00, 11000.00, 11000.00, 0.00, 0.00, 0.00, 0.00, 11000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(4, 2, '2025-11-27 14:30:25', 'returned', '2025-11-10', '2025-11-28', NULL, 15000.00, 5000.00, 5000.00, 10000.00, 500.00, 0.00, 0.00, 5000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(5, 2, '2025-11-28 11:11:35', 'delivered', '2025-11-28', '2025-11-29', NULL, 40000.00, 12000.00, 12000.00, 28000.00, 0.00, 0.00, 0.00, 12000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(6, 1, '2025-11-28 15:25:25', 'delivered', '2025-11-28', '2025-11-29', '2025-11-28 15:55:25', 15000.00, 5000.00, 5000.00, 10250.00, 50.00, 200.00, 4750.00, 5000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(7, 1, '2025-11-30 12:35:14', 'pending', '2025-11-30', '2025-12-01', '2025-11-30 17:35:14', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 0.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(8, 1, '2025-11-30 12:45:54', 'pending', '2025-11-30', '2025-12-01', '2025-11-30 17:45:54', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 0.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(9, 1, '2025-11-30 13:35:37', 'pending', '2025-11-30', '2025-12-01', '2025-11-30 18:35:37', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 0.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(10, 1, '2025-11-30 13:39:37', 'pending', '2025-11-30', '2025-12-01', '2025-11-30 18:39:37', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 0.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(11, 1, '2025-11-30 13:41:14', 'pending', '2025-11-30', '2025-12-01', '2025-11-30 18:41:14', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 0.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(12, 1, '2025-11-30 13:42:58', 'pending', '2025-11-30', '2025-12-01', '2025-11-30 18:42:58', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 0.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(13, 1, '2025-11-30 14:26:37', 'cancelled', '2025-12-01', '2025-12-01', '2025-11-30 19:26:37', 20000.00, 6000.00, NULL, 26000.00, 0.00, 0.00, 26000.00, 26000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(14, 2, '2025-11-30 14:44:30', 'pending', '2025-12-01', '2025-12-01', '2025-11-30 19:44:30', 16000.00, 5000.00, NULL, 21000.00, 0.00, 0.00, 0.00, 21000.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(15, 3, '2025-12-03 05:39:27', 'returned', '2025-12-03', '2025-12-19', '2025-12-03 10:39:27', 5000.00, 2500.00, NULL, 5000.00, 0.00, 0.00, 2500.00, 7500.00, NULL, NULL, 'pending', 1, 'pending', NULL, NULL, NULL),
(16, 1, '2025-12-16 13:11:32', 'delivered', '2025-12-16', '2025-12-17', '2025-12-16 18:11:32', 7000.00, 4500.00, NULL, 7000.00, 0.00, 0.00, 4500.00, 11500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(17, 1, '2025-12-17 03:45:53', 'processing', '2025-12-28', '2025-12-31', '2025-12-17 08:45:53', 13500.00, 7000.00, NULL, 15000.00, 0.00, 1500.00, 5500.00, 20500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(18, 1, '2025-12-17 07:29:11', 'pending', '2025-12-17', '2025-12-18', '2025-12-17 12:29:11', 7000.00, 2500.00, NULL, 9500.00, 0.00, 0.00, 0.00, 9500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(19, 1, '2025-12-17 07:32:39', 'pending', '2025-12-17', '2025-12-18', '2025-12-17 12:32:39', 7000.00, 2500.00, NULL, 9500.00, 0.00, 0.00, 0.00, 9500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(20, 1, '2025-12-17 07:50:02', 'pending', '2025-12-23', '2026-01-08', '2025-12-17 12:50:02', 7000.00, 2500.00, NULL, 9500.00, 0.00, 0.00, 0.00, 9500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(21, 1, '2025-12-17 23:09:34', 'processing', '2025-12-25', '2025-12-31', '2025-12-18 04:09:34', 7000.00, 2500.00, NULL, 9500.00, 0.00, 0.00, 0.00, 9500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(22, 1, '2025-12-17 23:14:21', 'returned', '2025-12-18', '2025-12-19', '2025-12-18 04:14:21', 13500.00, 5000.00, NULL, 18500.00, 0.00, 200.00, 0.00, 18500.00, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(23, 2, '2025-12-18 08:47:23', 'cancelled', NULL, NULL, NULL, 6500.00, 2500.00, NULL, NULL, 0.00, 0.00, 2500.00, NULL, NULL, NULL, 'pending', 0, 'paid', 'upi', 2500.00, '2025-12-18 14:20:16'),
(24, 2, '2025-12-18 08:55:20', 'cancelled', NULL, NULL, NULL, 6500.00, 2500.00, NULL, NULL, 0.00, 0.00, 2500.00, NULL, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(25, 2, '2025-12-18 08:59:36', 'cancelled', NULL, NULL, NULL, 6500.00, 2500.00, NULL, NULL, 0.00, 0.00, 2500.00, NULL, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(26, 2, '2025-12-18 09:09:45', 'pending', NULL, NULL, NULL, 6500.00, 2500.00, NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'pending', 0, 'paid', 'net', 2500.00, '2025-12-18 10:13:45'),
(27, 2, '2025-12-18 10:05:11', 'returned', NULL, NULL, NULL, 6000.00, 2500.00, NULL, NULL, 0.00, 0.00, 2500.00, NULL, NULL, '2025-12-18 21:48:19', 'pending', 0, 'paid', 'net', 2500.00, '2025-12-18 11:05:26'),
(28, 2, '2025-12-18 10:06:48', 'returned', NULL, NULL, NULL, 30000.00, 2500.00, NULL, NULL, 0.00, 0.00, 2500.00, NULL, NULL, '2025-12-18 21:48:14', 'pending', 0, 'paid', 'net', 2500.00, '2025-12-18 11:07:02'),
(29, 4, '2025-12-18 10:15:55', 'returned', NULL, NULL, NULL, 34000.00, 2500.00, NULL, 34000.00, 0.00, 0.00, 0.00, NULL, '2025-12-18 16:30:38', '2025-12-18 21:48:07', 'pending', 0, 'pending', NULL, NULL, NULL),
(30, 4, '2025-12-18 10:23:18', 'delivered', NULL, NULL, NULL, 34000.00, 2500.00, NULL, NULL, 0.00, 0.00, 0.00, NULL, '2025-12-18 18:54:56', NULL, 'pending', 0, 'paid', 'net', 2500.00, '2025-12-18 11:36:12'),
(31, 4, '2025-12-18 15:18:21', 'delivered', '2025-12-27', '2025-12-30', NULL, 18000.00, 2500.00, NULL, 32550.00, 17050.00, 0.00, 0.00, NULL, '2025-12-19 01:02:03', '0000-00-00 00:00:00', 'pending', 0, 'paid', NULL, 2500.00, '2025-12-18 20:48:46'),
(32, 4, '2025-12-18 19:00:39', 'pending', '2025-12-18', '0000-00-00', NULL, 6000.00, 2500.00, 2500.00, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'pending', 0, 'pending', NULL, NULL, NULL),
(33, 4, '2025-12-18 19:00:54', 'returned', '2025-12-23', '0000-00-00', NULL, 12000.00, 2500.00, 2500.00, NULL, 500.00, 0.00, 0.00, NULL, NULL, '2025-12-19 01:01:50', 'pending', 0, 'pending', NULL, NULL, NULL),
(34, 4, '2025-12-18 19:01:11', 'confirmed', '2025-12-23', '0000-00-00', NULL, 12000.00, 2500.00, 2500.00, 99999999.99, 99999999.99, 0.00, 2500.00, NULL, '2025-12-19 01:01:36', '2025-12-18 00:00:00', 'pending', 0, 'paid', NULL, 2500.00, '2025-12-19 00:31:37'),
(35, 4, '2025-12-18 19:05:38', 'pending', NULL, NULL, NULL, 13600.00, 2500.00, NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'pending', 0, 'paid', NULL, 2500.00, '2025-12-19 00:36:03'),
(36, 1, '2025-12-18 19:45:13', 'delivered', '2025-12-24', '0000-00-00', NULL, 16000.00, 5500.00, 5500.00, NULL, 0.00, 0.00, 0.00, NULL, '2025-12-19 01:20:59', NULL, 'pending', 0, 'paid', NULL, 5500.00, '2025-12-19 01:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `rental_days` int(11) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `total_deposit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `size`, `rental_days`, `price_per_day`, `deposit_amount`, `total_price`, `total_deposit`) VALUES
(1, 1, 1, NULL, 1, 16000.00, 5000.00, 16000.00, NULL),
(2, 1, 4, NULL, 1, 20000.00, 6000.00, 20000.00, NULL),
(3, 2, 3, '', 1, 15000.00, 5000.00, 15000.00, NULL),
(4, 3, 3, '', 1, 15000.00, 5000.00, 15000.00, NULL),
(5, 3, 4, '', 1, 20000.00, 6000.00, 20000.00, NULL),
(6, 4, 3, '', 1, 15000.00, 5000.00, 15000.00, NULL),
(7, 5, 4, '', 1, 20000.00, 6000.00, 20000.00, NULL),
(8, 5, 4, '', 1, 20000.00, 6000.00, 20000.00, NULL),
(9, 6, 3, '', 1, 15000.00, 5000.00, 15000.00, NULL),
(10, 7, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(11, 8, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(12, 9, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(13, 10, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(14, 11, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(15, 12, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(16, 13, 4, '', 1, 20000.00, 6000.00, 20000.00, 6000.00),
(17, 14, 1, '', 1, 16000.00, 5000.00, 16000.00, 5000.00),
(18, 15, 4, '', 1, 5000.00, 2500.00, 5000.00, 2500.00),
(19, 16, 27, '', 1, 7000.00, 4500.00, 7000.00, 4500.00),
(20, 17, 27, '', 1, 7000.00, 4500.00, 7000.00, 4500.00),
(21, 17, 29, '', 1, 6500.00, 2500.00, 6500.00, 2500.00),
(22, 18, 55, '', 1, 7000.00, 2500.00, 7000.00, 2500.00),
(23, 19, 55, '', 1, 7000.00, 2500.00, 7000.00, 2500.00),
(24, 20, 55, '', 1, 7000.00, 2500.00, 7000.00, 2500.00),
(25, 21, 55, '', 1, 7000.00, 2500.00, 7000.00, 2500.00),
(26, 22, 55, '', 1, 7000.00, 2500.00, 7000.00, 2500.00),
(27, 22, 61, '', 1, 6500.00, 2500.00, 6500.00, 2500.00),
(28, 23, 59, '', 1, 6500.00, 2500.00, 6500.00, 2500.00),
(29, 24, 59, '', 1, 6500.00, 2500.00, 6500.00, 2500.00),
(30, 25, 59, '', 1, 6500.00, 2500.00, 6500.00, 2500.00),
(31, 26, 59, '', 1, 6500.00, 2500.00, 6500.00, 2500.00),
(32, 27, 60, '', 1, 6000.00, 2500.00, 6000.00, 2500.00),
(33, 28, 60, '', 5, 6000.00, 2500.00, 30000.00, 2500.00),
(34, 29, 50, '', 5, 6800.00, 2500.00, 34000.00, 2500.00),
(35, 30, 50, '', 5, 6800.00, 2500.00, 34000.00, 2500.00),
(36, 31, 60, '', 3, 6000.00, 2500.00, 18000.00, 2500.00),
(37, 32, 52, '', 1, 6000.00, 2500.00, 6000.00, 2500.00),
(38, 33, 52, '', 2, 6000.00, 2500.00, 12000.00, 2500.00),
(39, 34, 52, '', 2, 6000.00, 2500.00, 12000.00, 2500.00),
(40, 35, 50, '', 2, 6800.00, 2500.00, 13600.00, 2500.00),
(41, 36, 48, '', 2, 8000.00, 5500.00, 16000.00, 5500.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_type` enum('advance','final','refund') DEFAULT NULL,
  `payment_method` enum('upi','card','netbanking','cod') DEFAULT NULL,
  `payment_status` enum('success','failed','pending') DEFAULT 'success',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `razorpay_order_id` varchar(255) DEFAULT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `charges_for` enum('advance','final','late_fee','damage_fee') DEFAULT 'advance'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `user_id`, `amount`, `payment_type`, `payment_method`, `payment_status`, `transaction_date`, `razorpay_order_id`, `razorpay_payment_id`, `charges_for`) VALUES
(1, 2, 1, 5000.00, 'advance', 'card', 'success', '2025-11-27 13:50:43', NULL, NULL, 'advance'),
(2, 3, 1, 11000.00, 'advance', 'cod', 'success', '2025-11-27 14:14:33', NULL, NULL, 'advance'),
(4, 4, 2, 5000.00, 'advance', 'cod', 'success', '2025-11-27 14:30:25', NULL, NULL, 'advance'),
(5, 5, 2, 12000.00, 'advance', 'cod', 'success', '2025-11-28 11:11:35', NULL, NULL, 'advance'),
(6, 6, 1, 5000.00, 'advance', 'cod', 'failed', '2025-11-28 15:25:25', NULL, NULL, 'advance'),
(7, 13, 1, 14000.00, '', 'cod', 'success', '2025-11-30 18:59:41', NULL, NULL, 'advance'),
(8, 13, 1, 26000.00, 'refund', '', 'success', '2025-11-30 19:03:14', NULL, NULL, 'advance'),
(9, 14, 2, 5000.00, '', 'card', 'success', '2025-11-30 19:22:06', NULL, NULL, 'advance'),
(10, 23, 2, 2500.00, 'refund', '', 'success', '2025-12-18 09:23:48', NULL, NULL, 'advance'),
(11, 25, 2, 2500.00, 'refund', '', 'success', '2025-12-18 09:23:56', NULL, NULL, 'advance'),
(12, 24, 2, 2500.00, 'refund', '', 'success', '2025-12-18 09:24:16', NULL, NULL, 'advance'),
(13, 27, 2, 2500.00, 'refund', '', 'success', '2025-12-18 10:05:45', NULL, NULL, 'advance'),
(14, 28, 2, 2500.00, 'refund', '', 'success', '2025-12-18 10:13:45', NULL, NULL, 'advance'),
(15, 34, 4, 2500.00, 'refund', '', 'success', '2025-12-18 19:05:08', NULL, NULL, 'advance');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcat_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `product_name` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `max_rental_days` int(11) DEFAULT NULL,
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `stock_status` enum('available','rented','damaged','repair') DEFAULT 'available',
  `rating` decimal(3,2) DEFAULT 0.00,
  `image` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `subcat_id`, `type_id`, `product_name`, `description`, `size`, `price_per_day`, `max_rental_days`, `deposit_amount`, `stock_status`, `rating`, `image`, `created_at`, `views`) VALUES
(1, 1, 2, 2, 'Red lehanga', 'jfdjir', 'XL', 8000.00, 8, 3500.00, 'available', 0.00, 'lehanga.jpeg', '2025-11-26 10:55:26', 0),
(3, 1, 2, 2, 'lehanga', 'klfglkjsj', 'XL', 5000.00, 7, 2000.00, 'available', 0.00, '1764232261_pink.jpeg', '2025-11-27 08:31:01', 0),
(4, 1, 6, 2, 'Gown', NULL, 'XXL', 5000.00, 9, 2500.00, 'available', 0.00, '1764245204_lehanga 2.jpeg', '2025-11-27 12:06:44', 0),
(5, 4, 2, 2, 'Chaniya Choli', NULL, 'XL', 8000.00, 6, 4500.00, 'available', 0.00, '1764755083_9.jpg', '2025-12-03 09:44:43', 0),
(6, 4, 1, 2, 'Chaniya Choli', NULL, 'XL', 7500.00, 5, 2500.00, 'rented', 0.00, '1764755474_9.jpg', '2025-12-03 09:51:14', 0),
(7, 2, 5, 2, 'Gown', NULL, 'XL', 9000.00, 6, 4500.00, 'available', 0.00, '1764755522_11.jpg', '2025-12-03 09:52:02', 0),
(8, 2, 6, 2, 'White Wedding Gown', NULL, 'XL', 6500.00, 4, 2000.00, 'available', 0.00, '1764755589_66.jpg', '2025-12-03 09:53:09', 0),
(9, 2, 6, 1, 'Classic Tuxedo', NULL, 'XL', 5000.00, 5, 2500.00, 'available', 0.00, '1764755695_classic tuxedo.jpg', '2025-12-03 09:54:55', 0),
(10, 3, 2, 2, 'Bridal Lehanga', NULL, 'XXL', 8500.00, 6, 4000.00, 'available', 0.00, '1764755756_bengali.jpg', '2025-12-03 09:55:56', 0),
(11, 3, 6, 2, 'Bridal Lehanga', NULL, 'XL', 8500.00, 4, 4100.00, 'rented', 0.00, '1764755815_bengali.jpg', '2025-12-03 09:56:55', 0),
(12, 1, 1, 1, 'Kurta For Haldi', NULL, 'XL', 5000.00, 4, 3000.00, 'available', 0.00, '1764755875_haldi1.jpg', '2025-12-03 09:57:55', 0),
(13, 3, 3, 1, 'Mehandi Outfit', NULL, 'XXL', 5000.00, 4, 2500.00, 'available', 0.00, '1764755940_mehendi5.jpg', '2025-12-03 09:59:00', 0),
(14, 1, 3, 1, 'Mehandi Outfit', NULL, 'XXL', 6000.00, 4, 2000.00, 'available', 0.00, '1764755997_mehendi7.jpg', '2025-12-03 09:59:57', 0),
(15, 1, 1, 1, 'Haldi Outfit', NULL, 'XL', 5000.00, 6, 2000.00, 'available', 0.00, '1764756056_haldi12.jpg', '2025-12-03 10:00:56', 0),
(16, 5, 2, 2, 'Black Lehanga', NULL, 'XXL', 9000.00, 5, 5000.00, 'available', 0.00, '1764756123_88.jpg', '2025-12-03 10:02:03', 0),
(17, 2, 5, 2, 'White Wedding Gown', NULL, 'XL', 10000.00, 6, 5500.00, 'available', 0.00, '1764756178_777.jpg', '2025-12-03 10:02:58', 0),
(18, 2, 6, 2, 'Red Wedding Gown', NULL, 'XXL', 9000.00, 4, 5000.00, 'rented', 0.00, '1764756295_8888.jpg', '2025-12-03 10:04:55', 0),
(19, 4, 3, 2, 'Ghagra Choli', NULL, 'XL', 4000.00, 6, 2500.00, 'rented', 0.00, '1765904737_ghagra choli.jpg', '2025-12-16 17:05:37', 0),
(20, 1, 4, 1, 'Sherwani And Dhoti', NULL, 'XL', 6000.00, 5, 3500.00, 'available', 0.00, '1765905625_hindu9.jpg', '2025-12-16 17:20:25', 0),
(21, 1, 3, 1, 'Kurta For Mehandi', NULL, 'XL', 5000.00, 6, 2500.00, 'available', 0.00, '1765905710_mehendi3.jpg', '2025-12-16 17:21:50', 0),
(22, 3, 14, 1, 'Haldi Outfit', NULL, 'XL', 5000.00, 4, 2000.00, 'available', 0.00, '1765905776_haldi.jpg', '2025-12-16 17:22:56', 0),
(23, 2, 11, 2, 'White Wedding Gown', NULL, 'Free Size', 7000.00, 4, 4500.00, 'repair', 0.00, '1765905847_ch.jpg', '2025-12-16 17:24:07', 0),
(24, 1, 10, 1, 'Sherwani ', NULL, 'XL', 6000.00, 4, 2000.00, 'available', 0.00, '1765905914_hinduwed4.jpg', '2025-12-16 17:25:14', 0),
(25, 5, 13, 1, 'Sangeet Outfit', NULL, 'XL', 6500.00, 4, 2000.00, 'available', 0.00, '1765905970_hinduwed5.jpg', '2025-12-16 17:26:10', 0),
(26, 8, 11, 1, 'Sherwani ', NULL, 'XL', 5500.00, 5, 2000.00, 'available', 0.00, '1765906010_hinduwed2.jpg', '2025-12-16 17:26:50', 0),
(27, 4, 10, 2, 'Chaniya Choli', NULL, 'Free Size', 7000.00, 4, 4500.00, 'available', 0.00, '1765906072_guj.jpg', '2025-12-16 17:27:52', 0),
(28, 3, 3, 2, 'Mehandi Outfit', NULL, 'XL', 5000.00, 3, 2500.00, 'available', 0.00, '1765906353_mehandi2.jpg', '2025-12-16 17:32:33', 0),
(29, 1, 3, 1, 'Mehandi Outfit', NULL, 'Free Size', 6500.00, 6, 2500.00, 'available', 0.00, '1765906608_mehendi7.jpg', '2025-12-16 17:36:48', 0),
(30, 2, 12, 2, 'Red Gown', NULL, 'XL', 8000.00, 6, 2500.00, 'available', 0.00, '1765970538_777777.jpg', '2025-12-17 11:22:18', 0),
(31, 7, 13, 1, 'Men Outfit', NULL, 'Free Size', 8500.00, 7, 4500.00, 'available', 0.00, '1765970638_hinduwed6.jpg', '2025-12-17 11:23:58', 0),
(32, 7, 16, 1, 'Men Outfit', NULL, 'XL', 5000.00, 6, 2000.00, 'available', 0.00, '1765970697_muslim1.jpg', '2025-12-17 11:24:57', 0),
(33, 7, 16, 1, 'Men Outfit', NULL, 'Free Size', 8000.00, 8, 4000.00, 'available', 0.00, '1765970742_muslim2.jpg', '2025-12-17 11:25:42', 0),
(34, 1, 4, 2, 'Nauari Saree', NULL, 'XL', 7500.00, 2, 2000.00, 'available', 0.00, '1765970833_nawari3.jpg', '2025-12-17 11:27:13', 0),
(35, 5, 12, 1, 'Men Outfit', NULL, 'XL', 6500.00, 5, 2000.00, 'available', 0.00, '1765970893_punjabi2.jpg', '2025-12-17 11:28:13', 0),
(36, 7, 6, 1, 'Men Outfit', NULL, 'Free Size', 6000.00, 5, 2500.00, 'available', 0.00, '1765970953_nikah3.jpg', '2025-12-17 11:29:13', 0),
(37, 7, 3, 2, 'Mehandi Lehanga', NULL, 'XL', 8000.00, 5, 2000.00, 'available', 0.00, '1765971016_Mehandi.jpg', '2025-12-17 11:30:16', 0),
(38, 8, 16, 2, 'South Indian Lehanga', NULL, 'XL', 8000.00, 6, 3500.00, 'available', 0.00, '1765971062_white.jpg', '2025-12-17 11:31:02', 0),
(39, 2, 12, 1, 'Mens Suit', NULL, 'XL', 5000.00, 5, 2500.00, 'available', 0.00, '1765971120_reception.jpg', '2025-12-17 11:32:00', 0),
(40, 5, 12, 1, 'Men Outfit', NULL, 'XL', 6500.00, 5, 2500.00, 'available', 0.00, '1765971164_punjabi4.jpg', '2025-12-17 11:32:44', 0),
(41, 1, 12, 1, 'Royal Sherwani', NULL, 'XL', 5000.00, 5, 2500.00, 'available', 0.00, '1765971218_royal sherwani.jpg', '2025-12-17 11:33:38', 0),
(42, 1, 3, 2, 'Mehandi Lehanga', NULL, 'XXL', 5000.00, 4, 2500.00, 'available', 0.00, '1765971294_mehandi2.jpg', '2025-12-17 11:34:54', 0),
(43, 7, 12, 2, 'Red lehanga', NULL, 'XL', 5000.00, 6, 2500.00, 'available', 0.00, '1765971353_wedding8.jpg', '2025-12-17 11:35:53', 0),
(44, 6, 12, 1, 'Mens Suit', NULL, 'XL', 6500.00, 5, 2400.00, 'available', 0.00, '1765971405_reception2.jpg', '2025-12-17 11:36:45', 0),
(45, 5, 13, 1, 'Sangeet Outfit', NULL, 'XL', 6500.00, 5, 2500.00, 'available', 0.00, '1765971476_sangeet6.jpg', '2025-12-17 11:37:56', 0),
(46, 6, 13, 1, 'Sangeet Outfit', NULL, 'XL', 6500.00, 5, 2500.00, 'available', 0.00, '1765971523_sangeet2.jpg', '2025-12-17 11:38:43', 0),
(47, 8, 9, 1, 'South Indian Sherwani', NULL, 'Free Size', 6500.00, 6, 2500.00, 'available', 0.00, '1765971576_south3.jpg', '2025-12-17 11:39:36', 0),
(48, 8, 13, 2, 'South Indian Lehanga', NULL, 'Free Size', 8000.00, 3, 5500.00, 'available', 0.00, '1765971668_south.jpg', '2025-12-17 11:41:08', 0),
(49, 8, 15, 1, 'South Indian Sherwani', NULL, 'XL', 6000.00, 5, 2500.00, 'available', 0.00, '1765971744_southwed2.jpg', '2025-12-17 11:42:24', 0),
(50, 8, 15, 2, 'Haldi Outfit', NULL, 'XL', 6800.00, 5, 2500.00, 'available', 0.00, '1765971812_south2.jpg', '2025-12-17 11:43:32', 0),
(51, 1, 1, 1, 'Haldi Outfit', NULL, 'XL', 6800.00, 5, 2000.00, 'available', 0.00, '1765971863_haldi12.jpg', '2025-12-17 11:44:23', 0),
(52, 1, 5, 2, 'Lehanga', NULL, 'Free Size', 6000.00, 5, 2500.00, 'available', 0.00, '1765971943_s6.jpg', '2025-12-17 11:45:43', 0),
(53, 7, 16, 1, 'Men Outfit', NULL, 'XL', 5000.00, 5, 2500.00, 'available', 0.00, '1765971987_muslim1.jpg', '2025-12-17 11:46:27', 0),
(54, 4, 3, 2, 'Mehandi Lehanga', NULL, 'XL', 6800.00, 5, 2500.00, 'available', 0.00, '1765972045_gujrati mehandi.jpg', '2025-12-17 11:47:25', 0),
(55, 1, 4, 2, 'Nauari Kashta Saree', NULL, 'Free Size', 7000.00, 5, 2500.00, 'available', 0.00, '1765972114_navwari4.jpg', '2025-12-17 11:48:34', 0),
(56, 5, 12, 1, 'Sangeet Outfit', NULL, 'XL', 5000.00, 5, 2000.00, 'available', 0.00, '1765972210_sangeet6.jpg', '2025-12-17 11:50:10', 0),
(57, 7, 6, 2, 'Bridal Lehanga', NULL, 'Free Size', 9000.00, 5, 4500.00, 'rented', 0.00, '1765972267_bridal lehanga.jpg', '2025-12-17 11:51:07', 0),
(58, 8, 16, 2, 'South Indian Lehanga', NULL, 'Free Size', 8000.00, 5, 3000.00, 'available', 0.00, '1765972403_sou.jpg', '2025-12-17 11:53:23', 0),
(59, 8, 15, 1, 'Haldi Outfit', NULL, 'XL', 6500.00, 5, 2500.00, 'available', 0.00, '1765972461_southwed2.jpg', '2025-12-17 11:54:21', 0),
(60, 1, 12, 2, 'Lehanga', NULL, 'XXL', 6000.00, 5, 2500.00, 'available', 0.00, '1765972545_s4.jpg', '2025-12-17 11:55:45', 0),
(61, 6, 9, 1, 'Men Outfit', NULL, 'XL', 6500.00, 4, 2500.00, 'available', 0.00, '1765972586_varmala3.jpg', '2025-12-17 11:56:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_views`
--

CREATE TABLE `product_views` (
  `view_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_type` varchar(100) DEFAULT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `report_type`, `generated_by`, `generated_at`, `file_path`) VALUES
(1, 'Feedback Reminder', 1, '2025-11-28 15:52:58', 'Order #3 returned, please provide feedback');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `late_days` int(11) DEFAULT 0,
  `late_fee` decimal(10,2) DEFAULT 0.00,
  `damage_status` enum('none','minor','major','repair') DEFAULT 'none',
  `damage_fee` decimal(10,2) DEFAULT 0.00,
  `remaining_amount_due` decimal(10,2) DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `order_id`, `product_id`, `return_date`, `late_days`, `late_fee`, `damage_status`, `damage_fee`, `remaining_amount_due`, `returned_at`) VALUES
(1, 6, 3, '2025-12-04', 1, 50.00, 'minor', 200.00, 15000.00, '2025-11-30 01:04:51'),
(2, 3, 3, '2025-11-28', 0, 0.00, 'none', 1000.00, 35000.00, NULL),
(3, 3, 4, '2025-11-28', 0, 0.00, 'none', 0.00, 35000.00, NULL),
(4, 5, 4, '2025-12-04', 0, 0.00, 'none', 0.00, 12000.00, NULL),
(5, 5, 4, '2025-12-04', 0, 0.00, 'none', 0.00, 12000.00, NULL),
(6, 4, 3, '2025-11-24', 0, 500.00, 'none', 0.00, 5500.00, NULL),
(7, 17, 27, '2026-01-02', 0, 0.00, 'none', 1500.00, 0.00, NULL),
(8, 17, 29, '2026-01-02', 0, 0.00, 'minor', 1500.00, 0.00, '2025-12-17 14:46:25'),
(9, 16, 27, '2025-12-31', 0, 0.00, 'none', 0.00, 0.00, NULL),
(10, 17, 27, '2026-01-02', 0, 0.00, 'none', 1500.00, 0.00, NULL),
(11, 17, 29, '2026-01-02', 0, 0.00, 'none', 1500.00, 0.00, NULL),
(12, 17, 27, '2026-01-03', 0, 0.00, 'none', 1500.00, 0.00, NULL),
(13, 17, 29, '2026-01-03', 0, 0.00, 'none', 1500.00, 0.00, NULL),
(14, 15, 4, '2025-12-17', 0, 0.00, 'none', 0.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcat_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcat_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`subcat_id`, `category_id`, `subcat_name`) VALUES
(1, 1, 'Haldi'),
(2, 1, 'Sangeet'),
(3, 1, 'Mehandi'),
(4, 1, 'Saptapadi'),
(5, 1, 'Reception'),
(6, 1, 'Engagement'),
(7, 3, 'Ganga Nimantran'),
(8, 3, 'Haldi'),
(9, 3, 'Varmala'),
(10, 3, 'Engagement'),
(11, 2, 'Engagement'),
(12, 2, 'Reception'),
(13, 2, 'Sangeet'),
(14, 3, 'Haldi'),
(15, 4, 'Haldi'),
(16, 4, 'Engagement'),
(17, 4, 'Sangeet'),
(18, 4, 'Reception'),
(19, 4, 'Mehandi'),
(20, 4, 'Jaymala'),
(21, 6, 'Engagement'),
(22, 6, 'Mehandi'),
(23, 6, 'Reception'),
(24, 6, 'Sangeet'),
(25, 6, 'Haldi'),
(26, 7, 'Haldi'),
(27, 7, 'Engagement'),
(28, 7, 'Sangeet'),
(29, 7, 'Reception'),
(30, 7, 'Mehandi'),
(31, 5, 'Haldi'),
(32, 5, 'Engagement'),
(33, 5, 'Sangeet'),
(34, 5, 'Reception'),
(35, 5, 'Phera\'s'),
(36, 8, 'Haldi'),
(37, 8, 'Engagement'),
(39, 8, 'Sangeet'),
(40, 8, 'Reception'),
(41, 8, 'Mehandi'),
(42, 8, 'Saptapadi'),
(43, 3, 'Main Wedding Day'),
(44, 2, 'Main Wedding Day'),
(46, 4, 'Main Wedding Day'),
(47, 1, 'Main Wedding Day'),
(48, 6, 'Main Wedding Day'),
(49, 7, 'Main Wedding Day'),
(51, 5, 'Main Wedding Day'),
(52, 8, 'Main Wedding Day');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`type_id`, `type_name`) VALUES
(1, 'Men'),
(2, 'Women');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `mobile`, `password`, `address`, `created_at`) VALUES
(1, 'Divya Lawand', 'lawanddivya@gmail.com', '09067316913', '$2y$10$IQba.Juo8ueoa3vm9qBMhOLxrDkSSo4b5clarKmhYFiMpDnI/Ms8S', 'Khatav', '2025-11-26 19:12:26'),
(2, 'Kshitij Jagtap', 'kshitij1111@gmail.com', '9607806475', '$2y$10$J5O1siCI5/aoI0bAYKarSeJUktyTdhcNxkKHsqk/0/ZX0tt1psj6e', 'At post Khatav Tal:khatav Dist:satara', '2025-11-27 14:28:43'),
(3, 'Suhani Kadam', 'suhanikadam@gmail.com', '7972471310', '$2y$10$7CaIDOnew4j1U1RWINT67uEFVH7H3csGPixkKzYQtjagBAptWFC/u', 'At post Wathar Nimbalkar Tal:Phaltan Dist: Satara ', '2025-12-03 10:08:02'),
(4, 'Prerana Madane', 'prerana@gmail.com', '9874569856', '$2y$10$BitZt2fRcolWYVMjFMdlsO2Lu.gSNkZyg8LFBGqKcMdTKf5YbV7S.', 'Shevari Tal:Dahiwadi', '2025-12-18 10:15:02'),
(5, 'Divya Lawand', 'lawanddivya35@gmail.com', '09984523617', '$2y$10$JMP5HjRPB/9XYYTaFDYYv.YLzBK9Hgy7k9AyfVJvDRHKqqFuz./si', 'At post khatav dis satara', '2025-12-18 19:41:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `idx_feedback_user_rating` (`user_id`,`rating`),
  ADD KEY `idx_feedback_product` (`product_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_order_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_order_items_product` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `subcat_id` (`subcat_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `idx_products_category` (`category_id`);

--
-- Indexes for table `product_views`
--
ALTER TABLE `product_views`
  ADD PRIMARY KEY (`view_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `generated_by` (`generated_by`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`subcat_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `product_views`
--
ALTER TABLE `product_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcat_id`) REFERENCES `subcategory` (`subcat_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`generated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
