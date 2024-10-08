-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 01:13 PM
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
-- Database: `mgpos-database`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `middle` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `firstname`, `lastname`, `middle`, `suffix`, `age`, `address`, `contact_number`, `gender`, `role`, `username`, `password`, `status`, `created_at`, `updated_at`, `avatar`) VALUES
(1, 'John', 'Doe', 'Smith', NULL, 30, '123 Main St, City, Country', '123-456-7890', 'Male', 'Manager', 'johndoe', '$2y$12$/dAfgqQRarC60SlBr7eiC./CG0UNEhP26zxjkN8H.0knnJQFcmlXu', 'active', '2024-09-30 07:03:47', '2024-09-30 07:03:47', NULL),
(2, 'KhentRJ', 'Calaque', '', NULL, 20, 'Bicutan Taguig', '09123456789', 'Male', 'Cashier', 'cashier', '$2y$12$JVxg2sWH8.f7JtfH7BByuemZLrcWQi1wZkysqIA/q/XGfpQ6wOZ86', 'Active', '2024-09-30 07:09:10', '2024-09-30 07:09:10', NULL),
(3, 'Armen', 'XC', 'Xavier', NULL, 22, 'Koronadal City', '09123456789', 'Male', 'Cashier', 'armen123', '$2y$12$HkohsOR25tEDeDGzLhzJ9.Sgmdh96IF5cQm8idc.9vTebUvhiSD6S', 'Active', '2024-09-30 08:44:11', '2024-09-30 08:44:11', NULL),
(4, 'awr', 'ar', 'awr', 'u', 22, 'awfawfgawg', '0987654321232', 'Male', 'Manager', 'khent12', '$2y$12$n1PJZG6eLqO68JqEO6vK1eK7NQdQTIPQjQHDD3eii0YuTzfNQ4V2y', 'Active', '2024-10-04 16:45:31', '2024-10-04 16:45:31', NULL),
(5, 'qwrq', 'qwr', 'rqwr', NULL, 22, 'qwrqwrqrwq', '09764467567', 'Male', 'Cashier', 'qwerty', '$2y$12$W0QxLHJ2iBAW/EBtV3MW/eVcMz5o0mEwedfeXbNT5LN9n95erN5/i', 'Active', '2024-10-04 16:49:57', '2024-10-07 00:02:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventoryId` bigint(20) UNSIGNED NOT NULL,
  `itemID` bigint(20) UNSIGNED NOT NULL,
  `qtyonhand` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `date_received` date NOT NULL,
  `original_quantity` int(11) NOT NULL,
  `batch` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `SupplierId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventoryId`, `itemID`, `qtyonhand`, `expiry_date`, `date_received`, `original_quantity`, `batch`, `status`, `SupplierId`) VALUES
(1, 1, -8, '2025-03-30', '2024-09-30', 10, 'BATCH-20240930070744', 'Not Yet', 1),
(2, 2, -1, '2025-03-30', '2024-09-30', 100, 'BATCH-20240930083059', 'Not Yet', 1),
(3, 1, 810, '2025-03-30', '2024-09-30', 100, 'BATCH-20240930083101', '', 1),
(4, 1, 52, '2025-04-02', '2024-10-02', 12, 'BATCH-20241002100325', '', 1),
(5, 2, 423, '2025-04-02', '2024-10-02', 123, 'BATCH-20241002100325', '', 1),
(6, 2, 12, '2025-04-02', '2024-10-02', 12, 'BATCH-20241002100407', '', 2),
(7, 1, 18, '2025-04-03', '2024-10-03', 12, 'BATCH-20241003165043', '', 2),
(8, 2, 12, '2025-04-03', '2024-10-03', 12, 'BATCH-20241003165043', '', 2),
(9, 1, 63, '2025-04-03', '2024-10-03', 12, 'BATCH-20241003172700', '', 1),
(10, 2, 25, '2025-04-03', '2024-10-03', 2, 'BATCH-20241003173301', '', 1),
(11, 3, 186, '2025-04-04', '2024-10-04', 150, 'BATCH-20241004110545', '', 2),
(12, 1, 112, '2025-04-04', '2024-10-04', 112, 'BATCH-20241004111524', '', 1),
(13, 2, 123, '2025-04-04', '2024-10-04', 21, 'BATCH-20241004111524', '', 1),
(14, 2, 23, '2025-04-04', '2024-10-04', 23, 'BATCH-20241004143201', '', 1),
(15, 3, 127, '2025-04-04', '2024-10-04', 12, 'BATCH-20241004143229', '', 2),
(16, 2, 21, '2025-04-04', '2024-10-04', 21, 'BATCH-20241004143402', '', 2),
(17, 2, 21, '2025-04-04', '2024-10-04', 21, 'BATCH-20241004184709', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` bigint(20) UNSIGNED NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `itemCategory` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unitPrice` decimal(8,2) NOT NULL,
  `sellingPrice` decimal(8,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `vatApplicable` tinyint(1) NOT NULL DEFAULT 0,
  `barcode` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isVatable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itemName`, `itemCategory`, `description`, `unitPrice`, `sellingPrice`, `status`, `vatApplicable`, `barcode`, `created_at`, `updated_at`, `isVatable`) VALUES
(1, 'Nissin Wafer', 'Snacks', '20 pieces per pack', 12.00, 14.40, 'Active', 0, '123456789012', '2024-09-30 07:06:11', '2024-09-30 07:06:11', 0),
(2, 'Pepsi', 'Beverages', '6x 1 case', 45.00, 54.00, 'Active', 0, '123456789011', '2024-09-30 08:24:00', '2024-09-30 08:24:00', 0),
(3, 'Sting Energy Drink 330ml', 'Beverages', '6x 1 pack.', 23.00, 27.60, 'Active', 0, '089453786321', '2024-10-03 23:47:29', '2024-10-04 00:12:53', 0),
(7, 'Oishi', 'Snacks', 'seafood snack', 20.00, 24.00, 'Active', 0, '928374657483', '2024-10-04 10:26:06', '2024-10-04 10:26:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_09_01_145951_create_employees_table', 1),
(2, '2024_09_02_174826_create_items_table', 1),
(3, '2024_09_02_175426_create_order_items_table', 1),
(4, '2024_09_08_162335_add-supplier', 1),
(5, '2024_09_09_173514_purchase-order-item', 1),
(6, '2024_09_17_230303_add-inventory', 1),
(7, '2024_09_21_034636_drop_employees_table', 1),
(8, '2024_09_21_034758_drop_employees_table', 1),
(9, '2024_09_21_043838_drop_employees_table', 1),
(10, '2024_09_21_044345_create_sessions_table', 1),
(11, '2024_09_22_144252_update-item', 1),
(12, '2024_09_22_150254_add-supplier-item', 1),
(13, '2024_09_28_060342_create_cache_table', 1),
(14, '2024_09_29_014550_add-barcode', 1),
(15, '2024_09_29_014958_add-purchase_number', 1),
(16, '2024_09_29_020805_add-_inventory', 1),
(17, '2024_09_29_020848_add-_inventory', 1),
(18, '2024_09_29_021012_add-batch', 1),
(19, '2024_09_29_021035_add-batch', 1),
(20, '2024_09_29_021507_ad-stockcard', 1),
(21, '2024_09_29_022229_purchase_item', 1),
(22, '2024_09_29_022338_inventory', 1),
(23, '2024_09_29_022408_inventory', 1),
(25, '2024_10_04_171051_add_is_vatable_to_items_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderItemID` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `orderAmount` decimal(8,2) NOT NULL,
  `itemID` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_item`
--

CREATE TABLE `purchase_item` (
  `purchase_item_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `itemID` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inventoryId` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_item`
--

INSERT INTO `purchase_item` (`purchase_item_id`, `purchase_order_id`, `itemID`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `inventoryId`) VALUES
(1, 1, 1, 10, 12.00, 120.00, '2024-09-30 07:07:33', '2024-09-30 07:07:44', 1),
(2, 2, 1, 100, 12.00, 1200.00, '2024-09-30 08:30:41', '2024-09-30 08:31:01', 3),
(3, 3, 2, 100, 45.00, 4500.00, '2024-09-30 08:30:54', '2024-09-30 08:30:59', 2),
(4, 4, 1, 12, 12.00, 144.00, '2024-10-02 10:03:08', '2024-10-02 10:03:25', 4),
(5, 4, 2, 123, 45.00, 5535.00, '2024-10-02 10:03:08', '2024-10-02 10:03:25', 5),
(6, 5, 2, 12, 45.00, 540.00, '2024-10-02 10:04:05', '2024-10-02 10:04:07', 6),
(7, 6, 2, 23, 45.00, 1035.00, '2024-10-03 15:39:20', '2024-10-04 14:32:01', 14),
(8, 7, 2, 21, 45.00, 945.00, '2024-10-03 16:47:17', '2024-10-04 10:47:09', 17),
(9, 8, 1, 112, 12.00, 1344.00, '2024-10-03 16:48:08', '2024-10-04 11:15:24', 12),
(10, 8, 2, 21, 45.00, 945.00, '2024-10-03 16:48:08', '2024-10-04 11:15:24', 13),
(11, 9, 2, 2, 45.00, 90.00, '2024-10-03 16:48:33', '2024-10-03 17:33:01', 10),
(12, 10, 1, 12, 12.00, 144.00, '2024-10-03 16:49:22', '2024-10-03 16:49:22', NULL),
(13, 10, 2, 12, 45.00, 540.00, '2024-10-03 16:49:22', '2024-10-03 16:49:22', NULL),
(14, 11, 1, 12, 12.00, 144.00, '2024-10-03 16:50:14', '2024-10-03 16:50:43', 7),
(15, 11, 2, 12, 45.00, 540.00, '2024-10-03 16:50:14', '2024-10-03 16:50:43', 8),
(16, 12, 1, 12, 12.00, 144.00, '2024-10-03 17:04:11', '2024-10-03 17:27:00', 9),
(17, 13, 1, 12, 12.00, 144.00, '2024-10-03 18:32:29', '2024-10-03 18:32:29', NULL),
(18, 14, 3, 150, 23.00, 3450.00, '2024-10-04 11:05:39', '2024-10-04 11:05:45', 11),
(19, 15, 3, 12, 23.00, 276.00, '2024-10-04 14:32:21', '2024-10-04 14:32:29', 15),
(20, 16, 2, 21, 45.00, 945.00, '2024-10-04 14:34:00', '2024-10-04 14:34:02', 16);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `SupplierId` bigint(20) UNSIGNED NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `order_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`purchase_order_id`, `SupplierId`, `purchase_number`, `quantity`, `total_price`, `order_date`, `delivery_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'PO-66FA4E3519AF9', 10, 120.00, '2024-09-30', '2024-09-30', 'Completed', '2024-09-30 07:07:33', '2024-09-30 07:07:44'),
(2, 1, 'PO-66FA61B106597', 100, 1200.00, '2024-09-30', '2024-09-30', 'Completed', '2024-09-30 08:30:41', '2024-09-30 08:31:01'),
(3, 1, 'PO-66FA61BE91692', 100, 4500.00, '2024-09-30', '2024-09-30', 'Completed', '2024-09-30 08:30:54', '2024-09-30 08:30:59'),
(4, 1, 'PO-66FD1A5C07541', 135, 5679.00, '2024-10-02', '2024-10-02', 'Completed', '2024-10-02 10:03:08', '2024-10-02 10:03:25'),
(5, 2, 'PO-66FD1A95747CB', 12, 540.00, '2024-10-02', '2024-10-02', 'Completed', '2024-10-02 10:04:05', '2024-10-02 10:04:07'),
(6, 1, 'PO-66FEBAA82A30F', 23, 1035.00, '2024-10-03', '2024-10-04', 'Completed', '2024-10-03 15:39:20', '2024-10-04 14:32:01'),
(7, 1, 'PO-66FECA95B2E29', 21, 945.00, '2024-10-03', '2024-10-04', 'Completed', '2024-10-03 16:47:17', '2024-10-04 10:47:09'),
(8, 1, 'PO-66FECAC853C3A', 133, 2289.00, '2024-10-03', '2024-10-04', 'Completed', '2024-10-03 16:48:08', '2024-10-04 11:15:24'),
(9, 1, 'PO-66FECAE1CE347', 2, 90.00, '2024-10-03', '2024-10-03', 'Completed', '2024-10-03 16:48:33', '2024-10-03 17:33:01'),
(10, 1, 'PO-66FECB12D26CB', 24, 684.00, '2024-10-03', NULL, 'Cancelled', '2024-10-03 16:49:22', '2024-10-03 18:52:51'),
(11, 2, 'PO-66FECB4694CD6', 24, 684.00, '2024-10-03', '2024-10-03', 'Completed', '2024-10-03 16:50:14', '2024-10-03 16:50:43'),
(12, 1, 'PO-66FECE8B51D85', 12, 144.00, '2024-10-03', '2024-10-03', 'Completed', '2024-10-03 17:04:11', '2024-10-03 17:27:00'),
(13, 1, 'PO-66FEE33D6E2D6', 12, 144.00, '2024-10-03', NULL, 'Cancelled', '2024-10-03 18:32:29', '2024-10-03 18:53:13'),
(14, 2, 'PO-66FFCC033BCE2', 150, 3450.00, '2024-10-04', '2024-10-04', 'Completed', '2024-10-04 11:05:39', '2024-10-04 11:05:45'),
(15, 2, 'PO-66FFFC75E2F49', 12, 276.00, '2024-10-04', '2024-10-04', 'Completed', '2024-10-04 14:32:21', '2024-10-04 14:32:29'),
(16, 2, 'PO-66FFFCD851C2D', 21, 945.00, '2024-10-04', '2024-10-04', 'Completed', '2024-10-04 14:34:00', '2024-10-04 14:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('px5fiFvrlrCxDbgDhUOsXZBbACZY61f00T7qjSNU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YToxMzp7czo2OiJfdG9rZW4iO3M6NDA6IkFwNUZMRG5hdXhLOHZHNDE0eHJqdVBZWXFKZGl6T2JOeXcwdEx2bVUiO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcHJpbnQtdHJhbnNhY3Rpb24tcmVwb3J0Ijt9czo1NToibG9naW5fZW1wbG95ZWVfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTM6ImVtcGxveWVlX25hbWUiO3M6ODoiSm9obiBEb2UiO3M6MTM6ImVtcGxveWVlX3JvbGUiO3M6NzoiTWFuYWdlciI7czoxNDoiZW1wbG95ZWVfZW1haWwiO3M6Nzoiam9obmRvZSI7czoxNjoiZW1wbG95ZWVfY29udGFjdCI7czoxMjoiMTIzLTQ1Ni03ODkwIjtzOjEyOiJlbXBsb3llZV9hZ2UiO2k6MzA7czoxNjoiZW1wbG95ZWVfYWRkcmVzcyI7czoyNjoiMTIzIE1haW4gU3QsIENpdHksIENvdW50cnkiO3M6MTU6ImVtcGxveWVlX2dlbmRlciI7czo0OiJNYWxlIjtzOjE1OiJlbXBsb3llZV9zdGF0dXMiO3M6NjoiYWN0aXZlIjtzOjE1OiJlbXBsb3llZV9hdmF0YXIiO047fQ==', 1728289522),
('qnuL2kOQ0ENqjLZ0BLBqsvQHgV4L9Rbkxz9W7AEw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YToxMzp7czo2OiJfdG9rZW4iO3M6NDA6IkVjVmJ6R1puOFpoR0RsaHNjYm1VSFRTdXd6NDJLNzEwcEg0UUpGVTEiO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvaXRlbV9tYW5hZ2VtZW50Ijt9czo1NToibG9naW5fZW1wbG95ZWVfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTM6ImVtcGxveWVlX25hbWUiO3M6ODoiSm9obiBEb2UiO3M6MTM6ImVtcGxveWVlX3JvbGUiO3M6NzoiTWFuYWdlciI7czoxNDoiZW1wbG95ZWVfZW1haWwiO3M6Nzoiam9obmRvZSI7czoxNjoiZW1wbG95ZWVfY29udGFjdCI7czoxMjoiMTIzLTQ1Ni03ODkwIjtzOjEyOiJlbXBsb3llZV9hZ2UiO2k6MzA7czoxNjoiZW1wbG95ZWVfYWRkcmVzcyI7czoyNjoiMTIzIE1haW4gU3QsIENpdHksIENvdW50cnkiO3M6MTU6ImVtcGxveWVlX2dlbmRlciI7czo0OiJNYWxlIjtzOjE1OiJlbXBsb3llZV9zdGF0dXMiO3M6NjoiYWN0aXZlIjtzOjE1OiJlbXBsb3llZV9hdmF0YXIiO047fQ==', 1728291501);

-- --------------------------------------------------------

--
-- Table structure for table `stockcard`
--

CREATE TABLE `stockcard` (
  `StockCardID` bigint(20) UNSIGNED NOT NULL,
  `DateReceived` date NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Value` decimal(8,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `supplierItemID` bigint(20) UNSIGNED NOT NULL,
  `inventoryId` bigint(20) UNSIGNED NOT NULL,
  `Remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stockcard`
--

INSERT INTO `stockcard` (`StockCardID`, `DateReceived`, `Type`, `Value`, `Quantity`, `supplierItemID`, `inventoryId`, `Remarks`) VALUES
(1, '2024-09-30', 'Order', 120.00, 10, 1, 1, 'Received'),
(2, '2024-09-30', 'Order', 4500.00, 100, 1, 2, 'Received'),
(3, '2024-09-30', 'Order', 1200.00, 100, 1, 3, 'Received'),
(4, '2024-10-02', 'Order', 144.00, 12, 1, 4, 'Received'),
(5, '2024-10-02', 'Order', 5535.00, 123, 1, 5, 'Received'),
(6, '2024-10-02', 'Order', 540.00, 12, 2, 6, 'Received'),
(7, '2024-10-03', 'Sales', 120.00, 10, 1, 1, 'Send'),
(8, '2024-10-03', 'Sales', 144.00, 12, 1, 3, 'Send'),
(9, '2024-10-03', 'Sales', 144.00, 12, 1, 3, 'Send'),
(10, '2024-10-03', 'Sales', 144.00, 12, 1, 3, 'Send'),
(11, '2024-10-03', 'Order', 144.00, 12, 1, 3, 'Received'),
(12, '2024-10-03', 'Order', 144.00, 12, 1, 3, 'Received'),
(13, '2024-10-03', 'Order', 144.00, 12, 2, 7, 'Received'),
(14, '2024-10-03', 'Order', 540.00, 12, 2, 8, 'Received'),
(15, '2024-10-03', 'Order', 144.00, 12, 1, 9, 'Received'),
(16, '2024-10-03', 'Order', 90.00, 2, 1, 10, 'Received'),
(17, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(18, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(19, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(20, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(21, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(22, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(23, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(24, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(25, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(26, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(27, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(28, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(29, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(30, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(31, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(32, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(33, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(34, '2024-10-04', 'Sales', 90.00, 2, 3, 2, 'Send'),
(35, '2024-10-04', 'Sales', 2520.00, 56, 3, 2, 'Send'),
(36, '2024-10-04', 'Sales', 45.00, 1, 3, 2, 'Send'),
(37, '2024-10-04', 'Order', 3450.00, 150, 2, 11, 'Received'),
(38, '2024-10-04', 'Order', 1200.00, 100, 1, 3, 'Received'),
(39, '2024-10-04', 'Order', 1200.00, 100, 1, 3, 'Received'),
(40, '2024-10-04', 'Order', 1200.00, 100, 1, 3, 'Received'),
(41, '2024-10-04', 'Order', 1200.00, 100, 1, 3, 'Received'),
(42, '2024-10-04', 'Order', 1200.00, 100, 1, 3, 'Received'),
(43, '2024-10-04', 'Order', 1344.00, 112, 1, 12, 'Received'),
(44, '2024-10-04', 'Order', 945.00, 21, 1, 13, 'Received'),
(45, '2024-10-04', 'Order', 1035.00, 23, 1, 14, 'Received'),
(46, '2024-10-04', 'Order', 276.00, 12, 2, 15, 'Received'),
(47, '2024-10-04', 'Order', 945.00, 21, 2, 16, 'Received'),
(48, '2024-10-04', 'Order', 4500.00, 100, 3, 5, 'Received'),
(49, '2024-10-04', 'Order', 4500.00, 100, 3, 5, 'Received'),
(50, '2024-10-04', 'Order', 4500.00, 100, 3, 5, 'Received'),
(51, '2024-10-04', 'Order', 945.00, 21, 1, 17, 'Received'),
(52, '2024-10-05', 'Order', 1200.00, 100, 1, 3, 'Received'),
(53, '2024-10-05', 'Order', 1200.00, 100, 1, 3, 'Received'),
(54, '2024-10-07', 'Order', 24.00, 2, 1, 4, 'Received'),
(55, '2024-10-07', 'Order', 24.00, 2, 1, 7, 'Received'),
(56, '2024-10-07', 'Order', 24.00, 2, 1, 9, 'Received'),
(57, '2024-10-07', 'Order', 24.00, 2, 1, 4, 'Received'),
(58, '2024-10-07', 'Order', 24.00, 2, 1, 7, 'Received'),
(59, '2024-10-07', 'Order', 24.00, 2, 1, 9, 'Received'),
(60, '2024-10-07', 'Order', 24.00, 2, 1, 4, 'Received'),
(61, '2024-10-07', 'Order', 24.00, 2, 1, 7, 'Received'),
(62, '2024-10-07', 'StockIn', 0.00, 34, 1, 13, 'StockIn'),
(72, '2024-10-07', 'StockIn', 0.00, 2, 1, 3, 'StockIn'),
(73, '2024-10-07', 'StockIn', 0.00, 2, 1, 3, 'StockIn'),
(74, '2024-10-07', 'StockOut', 0.00, 2, 1, 3, 'StockOut'),
(75, '2024-10-07', 'StockOut', 0.00, 2, 1, 3, 'StockOut'),
(80, '2024-10-07', 'SalesReturn', 82.80, 3, 2, 11, 'Sales Return'),
(82, '2024-10-07', 'SalesReturn', 172.80, 12, 1, 9, 'Sales Return'),
(83, '2024-10-07', 'SalesReturn', 634.80, 23, 2, 11, 'Sales Return'),
(84, '2024-10-07', 'SalesReturn', 331.20, 23, 1, 9, 'Sales Return'),
(85, '2024-10-07', 'SalesReturn', 331.20, 23, 1, 3, 'Sales Return'),
(86, '2024-10-07', 'Sales', 12.00, 1, 1, 1, 'Sent'),
(87, '2024-10-07', 'Sales', 23.00, 1, 3, 11, 'Sent');

-- --------------------------------------------------------

--
-- Table structure for table `supplieritem`
--

CREATE TABLE `supplieritem` (
  `supplierItemID` bigint(20) UNSIGNED NOT NULL,
  `supplierID` bigint(20) UNSIGNED NOT NULL,
  `itemID` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplieritem`
--

INSERT INTO `supplieritem` (`supplierItemID`, `supplierID`, `itemID`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 2, 2),
(4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierId` bigint(20) UNSIGNED NOT NULL,
  `CompanyName` varchar(255) NOT NULL,
  `ContactPerson` varchar(255) NOT NULL,
  `ContactNumber` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierId`, `CompanyName`, `ContactPerson`, `ContactNumber`, `Address`, `Status`) VALUES
(1, 'KCC Mall of Marbel', 'Mr John', '09123456789', 'Koronadal City', 1),
(2, 'ACE', 'Mr John', '09123456789', 'Koronadal City', 1),
(3, 'Gina Jane Bread House', 'Ms Gina Cataculan', '09759372341', 'Koronadal City, South Cotabato', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventoryId`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderItemID`);

--
-- Indexes for table `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD PRIMARY KEY (`purchase_item_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`purchase_order_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stockcard`
--
ALTER TABLE `stockcard`
  ADD PRIMARY KEY (`StockCardID`);

--
-- Indexes for table `supplieritem`
--
ALTER TABLE `supplieritem`
  ADD PRIMARY KEY (`supplierItemID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `orderItemID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `purchase_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `purchase_order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stockcard`
--
ALTER TABLE `stockcard`
  MODIFY `StockCardID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `supplieritem`
--
ALTER TABLE `supplieritem`
  MODIFY `supplierItemID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
