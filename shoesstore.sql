-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 17, 2025 lúc 07:41 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shoesstore`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 100000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(2, 2, 200000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(3, 3, 150000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(4, 4, 250000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(5, 5, 300000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(6, 6, 350000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(7, 7, 400000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(8, 8, 450000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(9, 9, 500000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41'),
(10, 10, 550000.00, 1, '2025-03-30 11:55:41', '2025-03-30 11:55:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cartdetail`
--

CREATE TABLE `cartdetail` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL,
  `product_detail_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cartdetail`
--

INSERT INTO `cartdetail` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`, `status`, `product_detail_id`) VALUES
(2, 1, 3, 10, 20000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 1),
(3, 2, 2, 10, 15000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(4, 3, 5, 10, 25000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(5, 4, 4, 10, 30000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(6, 5, 6, 10, 35000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(7, 6, 7, 10, 40000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(8, 7, 8, 10, 45000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(9, 8, 9, 10, 50000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(10, 9, 10, 10, 55000.00, '2025-03-30 11:57:30', '2025-03-30 11:57:30', 1, 0),
(89, 1, 11, 2, 569000.00, '2025-05-08 08:35:00', '2025-05-08 08:35:00', 1, 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Giày 1', '2025-03-30 11:41:41', '2025-03-30 11:41:41'),
(2, 'Giày 2', '2025-03-30 11:41:41', '2025-03-30 11:41:41'),
(3, 'Giày  3', '2025-03-30 11:41:41', '2025-03-30 11:41:41'),
(4, 'Giày 4', '2025-03-30 11:41:41', '2025-03-30 11:41:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer`
--

INSERT INTO `customer` (`id`, `user_id`, `fullname`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(4, 4, 'Phạm Thị D', '0956345678', '101 Đường D, Quận 4, TP.HCM', '2025-03-30 12:08:46', '2025-03-30 12:08:46'),
(5, 5, 'Hoàng Văn E', '0945234567', '202 Đường E, Quận 5, TP.HCM', '2025-03-30 12:08:46', '2025-03-30 12:08:46'),
(6, 6, 'Đỗ Thị F', '0934123456', '303 Đường F, Quận 6, Ha Noi', '2025-03-30 12:08:46', '2025-03-30 12:08:46'),
(7, 7, 'Vũ Văn G', '0923012345', '404 Đường G, Quận 7, TP.HCM', '2025-03-30 12:08:46', '2025-03-30 12:08:46'),
(8, 8, 'Bùi Thị H', '0912923456', '505 Đường H, Quận 8, Ha Noi', '2025-03-30 12:08:46', '2025-03-30 12:08:46'),
(9, 9, 'Ngô Văn I', '0901834567', '606 Đường I, Quận 9,  Ha Noi', '2025-03-30 12:08:46', '2025-03-30 12:08:46'),
(10, 10, 'Dương Thị K', '0892745678', '707 Đường K, Quận 10, TP.HCM', '2025-03-30 12:08:46', '2025-03-30 12:08:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employee`
--

INSERT INTO `employee` (`id`, `user_id`, `fullname`, `phone`, `address`, `salary`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nguyễn Văn A', '0987654321', '123 Đường A, Quận 1, TP.HCM', 15000000.00, '2025-03-30 12:07:32', '2025-03-30 12:07:32'),
(2, 2, 'Trần Thị B', '0978123456', '456 Đường B, Quận 2, TP.HCM', 17000000.00, '2025-03-30 12:07:32', '2025-03-30 12:07:32'),
(3, 3, 'Lê Văn C', '0967234567', '789 Đường C, Quận 3, TP.HCM', 16000000.00, '2025-03-30 12:10:21', '2025-03-30 12:10:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `importreceipt`
--

CREATE TABLE `importreceipt` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `importreceipt`
--

INSERT INTO `importreceipt` (`id`, `user_id`, `supplier_id`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1500000.00, '2025-05-01 10:00:00', '2025-05-01 10:00:00'),
(2, 2, 1, 2350000.00, '2025-05-02 09:30:00', '2025-05-02 09:30:00'),
(3, 3, 2, 980000.00, '2025-05-03 14:20:00', '2025-05-03 14:20:00'),
(4, 1, 4, 3170000.00, '2025-05-04 08:15:00', '2025-05-04 08:15:00'),
(5, 2, 3, 1245000.00, '2025-05-05 11:45:00', '2025-05-05 11:45:00'),
(6, 1, 2, 2560000.00, '2025-05-06 13:00:00', '2025-05-06 13:00:00'),
(7, 3, 1, 1875000.00, '2025-05-07 15:30:00', '2025-05-07 15:30:00'),
(8, 2, 4, 1050000.00, '2025-05-08 12:10:00', '2025-05-08 12:10:00'),
(9, 1, 3, 990000.00, '2025-05-09 16:45:00', '2025-05-09 16:45:00'),
(10, 3, 2, 2200000.00, '2025-05-10 09:05:00', '2025-05-10 09:05:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `importreceiptdetail`
--

CREATE TABLE `importreceiptdetail` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orderdetail`
--

CREATE TABLE `orderdetail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orderdetail`
--

INSERT INTO `orderdetail` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 500000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(2, 1, 2, 1, 700000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(3, 2, 3, 3, 300000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(4, 3, 4, 1, 1500000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(5, 3, 5, 2, 250000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(6, 4, 6, 5, 100000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(7, 5, 7, 1, 800000.00, '2025-03-30 12:23:01', '2025-03-30 12:23:01'),
(8, 6, 1, 2, 150000.00, '2025-05-14 10:01:00', '2025-05-14 10:01:00'),
(9, 7, 2, 1, 200000.00, '2025-05-14 10:01:30', '2025-05-14 10:01:30'),
(10, 8, 3, 3, 120000.00, '2025-05-14 10:06:00', '2025-05-14 10:06:00'),
(11, 9, 4, 1, 300000.00, '2025-05-14 11:05:00', '2025-05-14 11:05:00'),
(12, 10, 5, 2, 250000.00, '2025-05-14 11:35:00', '2025-05-14 11:35:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `paymethod` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `note`, `status_id`, `created_at`, `updated_at`, `paymethod`) VALUES
(1, 4, 'Giao nhanh trong ngày', 4, '2025-03-30 12:14:37', '2025-03-30 12:14:37', 1),
(2, 5, 'Giao nhanh trong ngày', 5, '2025-03-30 12:14:37', '2025-03-30 12:14:37', 2),
(3, 6, 'Giao nhanh trong ngày', 3, '2025-03-30 12:14:37', '2025-03-30 12:14:37', 1),
(4, 7, 'Giao nhanh trong ngày', 4, '2025-03-30 12:14:37', '2025-03-30 12:14:37', 1),
(5, 8, 'Giao nhanh trong ngày', 1, '2025-03-30 12:14:37', '2025-03-30 12:14:37', 2),
(6, 4, 'Giao nhanh trong ngày', 1, '2025-05-14 10:00:00', '2025-05-14 10:00:00', 1),
(7, 5, 'Giao tại văn phòng', 5, '2025-05-14 10:05:00', '2025-05-14 10:10:00', 2),
(8, 6, 'Không gọi trước', 1, '2025-05-14 11:00:00', '2025-05-14 11:20:00', 2),
(9, 7, 'Cẩn thận sản phẩm dễ vỡ', 5, '2025-05-14 11:30:00', '2025-05-14 11:45:00', 1),
(10, 8, 'Giao buổi chiều', 5, '2025-05-14 12:00:00', '2025-05-14 12:30:00', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders_status`
--

CREATE TABLE `orders_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders_status`
--

INSERT INTO `orders_status` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Order Placed', '2025-03-30 12:27:11', '2025-03-30 12:27:11'),
(2, 'Order Paid', '2025-03-30 12:27:11', '2025-03-30 12:27:11'),
(3, 'Order Shipped Out', '2025-03-30 12:27:11', '2025-03-30 12:27:11'),
(4, 'Order Canceled', '2025-03-30 12:27:11', '2025-03-30 12:27:11'),
(5, 'Order Received', '2025-03-30 12:27:11', '2025-03-30 12:27:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders_status_detail`
--

CREATE TABLE `orders_status_detail` (
  `order_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders_status_detail`
--

INSERT INTO `orders_status_detail` (`order_id`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-03-30 12:28:51', '2025-03-30 12:28:51'),
(2, 2, '2025-03-30 12:28:51', '2025-03-30 12:28:51'),
(3, 3, '2025-03-30 12:28:51', '2025-03-30 12:28:51'),
(4, 4, '2025-03-30 12:28:51', '2025-03-30 12:28:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`) VALUES
(1, 'Online'),
(2, 'Cash');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `name`, `thumbnail`, `supplier_id`, `category_id`, `brand`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Running Shoes', 'running_shoes.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(2, 'Leather Jacket', 'leather_jacket.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(3, 'Sunglasses', 'sunglasses.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(4, 'Backpack', 'backpack.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(5, 'Smartwatch', 'smartwatch.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(6, 'Gold Necklace', 'gold_necklace.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(7, 'Formal Shoes', 'formal_shoes.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(8, 'T-shirt', 'tshirt.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(9, 'Sports Shorts', 'sports_shorts.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(10, 'Casual Sneakers', 'casual_sneakers.jpg', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32'),
(11, 'SHOES TEST', 's', 1, 1, NULL, 0, '2025-03-30 11:46:32', '2025-03-30 11:46:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `productdetail`
--

CREATE TABLE `productdetail` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `color` varchar(50) NOT NULL,
  `material` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `productdetail`
--

INSERT INTO `productdetail` (`id`, `product_id`, `description`, `quantity`, `size`, `color`, `material`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, '', 20, '42', 'Đen', 'Da tổng hợp', 1500000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(2, 2, '', 29, '41', 'Xanh', 'Lưới', 1200000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(3, 3, '', 1, '40', 'Trắng', 'Vải', 1100000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(4, 4, '', 19, '43', 'Nâu', 'Da thật', 5000000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(5, 5, '', 18, '39', 'Đỏ', 'Da lộn', 2500000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(6, 6, '', 30, '42', 'Xám', 'Da tổng hợp', 1800000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(7, 7, '', 60, '44', 'Đen', 'Cao su', 500000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(8, 8, '', 45, '38', 'Be', 'Da PU', 1600000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(9, 9, '', 20, '37', 'Hồng', 'Da thật', 5500000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00'),
(10, 10, '', 15, '44', 'Xanh rêu', 'Vải chống nước', 3200000.00, '2025-03-30 12:36:00', '2025-03-30 12:36:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2025-03-30 11:27:41', '2025-03-30 11:27:41'),
(2, 'Nhân viên nhập hàng', '2025-03-30 11:27:41', '2025-03-30 11:27:41'),
(3, 'Nhân viên duyệt đơn', '2025-03-30 11:27:41', '2025-03-30 11:27:41'),
(4, 'Khách hàng', '2025-03-30 11:27:41', '2025-03-30 11:27:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'ABC Footwear', '0987654321', 'contact@abcfootwear.com', '123 Main Street, City A', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(2, 'XYZ Apparel', '0978123456', 'support@xyzapparel.com', '456 Fashion Avenue, City B', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(3, 'Global Accessories', '0967234567', 'info@globalaccessories.com', '789 Market Road, City C', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(4, 'Style Bags Ltd.', '0956345678', 'sales@stylebags.com', '101 Bag Street, City D', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(5, 'Elite Watches', '0945234567', 'info@elitewatches.com', '202 Time Square, City E', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(6, 'Luxury Jewelry', '0934123456', 'contact@luxuryjewelry.com', '303 Diamond Lane, City F', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(7, 'SunStyle Sunglasses', '0923012345', 'support@sunstyle.com', '404 Vision Drive, City G', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(8, 'Sporty Wear Co.', '0912923456', 'info@sportywear.com', '505 Athletic Blvd, City H', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(9, 'Formal Attire Inc.', '0901834567', 'contact@formalattire.com', '606 Business Street, City I', '2025-03-30 11:43:51', '2025-03-30 11:43:51'),
(10, 'Casual Trends', '0892745678', 'support@casualtrends.com', '707 Relax Road, City J', '2025-03-30 11:43:51', '2025-03-30 11:43:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'password123', 'admin@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(2, 'nv1', 'pass1234', 'manager1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(3, 'nv2', 'editorpass', 'editor1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(4, 'user1', 'userpass1', 'user1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(5, 'user2', 'guestpass', 'guest1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(6, 'user3', 'superpass', 'supervisor1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(7, 'user4', 'operatorpass', 'operator1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(8, 'user5', 'supportpass', 'support1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(9, 'user6', 'devpass', 'developer1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24'),
(10, 'user7', 'analystpass', 'analyst1@example.com', 1, 1, '2025-03-30 11:30:24', '2025-03-30 11:30:24');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Chỉ mục cho bảng `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Chỉ mục cho bảng `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_payment_method` (`paymethod`);

--
-- Chỉ mục cho bảng `orders_status`
--
ALTER TABLE `orders_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders_status_detail`
--
ALTER TABLE `orders_status_detail`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Chỉ mục cho bảng `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `productdetail`
--
ALTER TABLE `productdetail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT cho bảng `cartdetail`
--
ALTER TABLE `cartdetail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT cho bảng `orders_status_detail`
--
ALTER TABLE `orders_status_detail`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `productdetail`
--
ALTER TABLE `productdetail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `cartdetail`
--
ALTER TABLE `cartdetail`
  ADD CONSTRAINT `cartdetail_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`),
  ADD CONSTRAINT `cartdetail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_payment_method` FOREIGN KEY (`paymethod`) REFERENCES `payment_method` (`id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `orders_status_detail`
--
ALTER TABLE `orders_status_detail`
  ADD CONSTRAINT `orders_status_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orders_status_detail_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `orders_status` (`id`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Các ràng buộc cho bảng `productdetail`
--
ALTER TABLE `productdetail`
  ADD CONSTRAINT `productdetail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
