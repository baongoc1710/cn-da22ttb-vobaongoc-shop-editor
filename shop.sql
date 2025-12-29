-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 27, 2025 at 03:37 PM
-- Server version: 8.0.44-0ubuntu0.24.04.2
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'home_slider',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image_url`, `link_url`, `position`, `sort_order`, `created_at`) VALUES
(1, 'images/banners/banner-tet.jpg', NULL, 'home_slider', 1, '2025-12-27 06:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `cliparts`
--

CREATE TABLE `cliparts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'General',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cliparts`
--

INSERT INTO `cliparts` (`id`, `name`, `image_url`, `category`, `created_at`) VALUES
(1, 'Sticker 1', 'images/cliparts/title.png', 'Tết', '2025-12-27 06:45:13'),
(2, 'Sticker 2', 'images/cliparts/title-1.png', 'Tết', '2025-12-27 06:45:13'),
(3, 'Sticker 3', 'images/cliparts/title-2.png', 'Tết', '2025-12-27 06:45:13'),
(4, 'Sticker 4', 'images/cliparts/title-3.png', 'Tết', '2025-12-27 06:45:13'),
(5, 'Sticker 5', 'images/cliparts/title-4.png', 'Tết', '2025-12-27 06:45:13'),
(6, 'Sticker 6', 'images/cliparts/title-6.png', 'Tết', '2025-12-27 06:45:13'),
(7, 'Sticker 7', 'images/cliparts/title-7.png', 'Tết', '2025-12-27 06:45:13'),
(8, 'Sticker 8', 'images/cliparts/title-8.png', 'Tết', '2025-12-27 06:45:13'),
(9, 'Sticker 9', 'images/cliparts/title-9.png', 'Tết', '2025-12-27 06:45:13'),
(10, 'Sticker 11', 'images/cliparts/title-11.png', 'Tết', '2025-12-27 06:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_method` enum('COD','Banking','Momo') COLLATE utf8mb4_unicode_ci DEFAULT 'COD',
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `status` enum('pending','confirmed','printing','packing','shipping','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `phone`, `shipping_address`, `note`, `total_amount`, `payment_method`, `payment_status`, `status`, `created_at`, `updated_at`) VALUES
(10, 3, 'Quản trị hệ thống', '012345678', '126, Nguyễn Thiện Thành, Phường Hòa Thuận, Tỉnh Vĩnh Long', NULL, 180000.00, 'COD', 'unpaid', 'packing', '2025-12-27 07:38:45', '2025-12-27 08:16:51'),
(11, 3, 'Quản trị hệ thống', '012345678', '126, Nguyễn Thiện Thành, Phường Hòa Thuận, Tỉnh Vĩnh Long', NULL, 190000.00, 'COD', 'unpaid', 'packing', '2025-12-27 07:42:22', '2025-12-27 08:16:45'),
(12, 4, 'phan minh nhựt', '013456789', 'Hiếu Tử\r\nTiểu Cần', NULL, 570000.00, 'COD', 'unpaid', 'pending', '2025-12-27 08:30:38', '2025-12-27 08:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `saved_design_id` bigint UNSIGNED NOT NULL,
  `size` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL,
  `print_cost` decimal(12,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `saved_design_id`, `size`, `quantity`, `unit_price`, `unit_cost`, `print_cost`, `created_at`, `updated_at`) VALUES
(11, 10, 17, 'L', 1, 180000.00, 80000.00, 15000.00, '2025-12-27 07:38:45', '2025-12-27 14:38:45'),
(12, 11, 17, 'M', 1, 190000.00, 90000.00, 15000.00, '2025-12-27 07:42:22', '2025-12-27 14:42:22'),
(13, 12, 18, 'L', 3, 190000.00, 90000.00, 15000.00, '2025-12-27 08:30:38', '2025-12-27 15:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `type` enum('news','promotion') COLLATE utf8mb4_unicode_ci DEFAULT 'news',
  `is_published` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `thumbnail`, `content`, `type`, `is_published`, `created_at`) VALUES
(1, 'Khuyến mãi khai trương', 'khuyen-mai-khai-truong', NULL, 'Giảm giá 20% cho đơn hàng đầu tiên.', 'promotion', 1, '2025-12-27 06:45:13'),
(2, 'Hướng dẫn chọn size áo', 'huong-dan-chon-size', NULL, 'Bảng size chi tiết cho nam và nữ...', 'news', 1, '2025-12-27 06:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `base_price` int NOT NULL,
  `import_price` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `base_price`, `import_price`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Áo Thun Cotton Basic', 'ao-thun-cotton-basic', 'Chất liệu cotton 100% co giãn 4 chiều, thoáng mát.', 190000, 90000, 1, '2025-12-27 06:45:13', '2025-12-27 07:38:25', NULL),
(5, 'Áo Hodle', 'ao-thun-polo', 'Chất liệu cotton 100% co giãn 4 chiều, thoáng mát.', 200000, 80000, 1, '2025-12-27 06:45:13', '2025-12-27 06:46:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_colors`
--

CREATE TABLE `product_colors` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `color_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_front` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_back` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_colors`
--

INSERT INTO `product_colors` (`id`, `product_id`, `color_name`, `hex_code`, `image_front`, `image_back`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 1, 'Màu Trắng', '#ffffff', 'storage/products/colors/Qc48n7BmLfMP5WEPS5OYNZog1qEY2LAscMRLNShg.jpg', 'storage/products/colors/F7tkHP08izDZAGZsjmMmflq1rJyi5JltPIzeWpCL.jpg', '2025-12-27 06:05:14', '2025-12-27 13:30:52', NULL),
(8, 1, 'Xanh đá', '#93a6bd', 'storage/products/colors/xBwhuvXWHqoHRCeVbkVuXLGNxbGRyEO1kmxtSAvQ.jpg', 'storage/products/colors/EeE3DTrkgzqY3BhmmL4colPOdJ7ZT9swncTwdYbZ.jpg', '2025-12-27 06:06:40', '2025-12-27 13:06:40', NULL),
(9, 1, 'Xám nhạt', '#c4c4c4', 'storage/products/colors/B2IhOIVJ1ySnkLlnwZGSNuMgkdWByQWjbEbR42au.jpg', 'storage/products/colors/evHsxRymErecDcUMTz2M3rv1TAx1HbIrHhzFXA8F.jpg', '2025-12-27 06:10:18', '2025-12-27 13:10:18', NULL),
(10, 1, 'Xanh da trời', '#98c1e7', 'storage/products/colors/mkh71YXalAAHpWKf5Ht9plYrqS1bMj90fveULggY.jpg', 'storage/products/colors/8ayep0fitNR6agsB1DNjfqkzfx7HOZXIqAGRCCFe.jpg', '2025-12-27 06:10:47', '2025-12-27 13:10:47', NULL),
(11, 1, 'Xanh hoàng gia', '#4a67bf', 'storage/products/colors/boAfOH2f4yCoXL0Uy7eoxzhWe94wAKr2ogjJRred.jpg', 'storage/products/colors/KZNhXrS9IFsdmoxigG2Ves5Prd9bZI6b87ULqg57.jpg', '2025-12-27 06:11:34', '2025-12-27 13:11:34', NULL),
(12, 1, 'Màu đỏ cam', '#f03838', 'storage/products/colors/bFpUHb1ZrLt84B57xvmJKkGy7x1CbZpz1mynfG5B.jpg', 'storage/products/colors/DwECuR7oNL66Svfdc1rDb2rrzYK3isq8GfbAoq8S.jpg', '2025-12-27 06:25:51', '2025-12-27 13:28:26', NULL),
(13, 1, 'Màu tím', '#7730a6', 'storage/products/colors/CEGH4YEnvHZD75viP5rWxJ2Vfvfu1dMR8YebOoFe.jpg', 'storage/products/colors/e6xciZOpKGTdszlRRHYWubQON2XbHnFAcDrc0dln.jpg', '2025-12-27 06:28:47', '2025-12-27 13:28:47', NULL),
(14, 1, 'Màu cam', '#fa7900', 'storage/products/colors/ikPgGuoDgW6760ogAXvKUB4C5mozchG0qrnmWKsH.jpg', 'storage/products/colors/zj445xJoNqU9XRAvzKf8q7mZ1u1V9l6nlO6nHAwW.jpg', '2025-12-27 06:29:04', '2025-12-27 13:29:04', NULL),
(15, 1, 'Xanh navi', '#260458', 'storage/products/colors/JpnYFdzHkQpTaQxXJaKC0TthgkmcuERec68nDDsV.jpg', 'storage/products/colors/bPn5jFuFm2FTzE7lCQAIVA9gnLpXnprlis52rd5r.jpg', '2025-12-27 06:29:34', '2025-12-27 13:29:34', NULL),
(16, 1, 'Trắng sữa', '#fff5f5', 'storage/products/colors/C5j5ouPY2ZUBjnNBzMvTKup2B43lnY4xNxXO55sl.jpg', 'storage/products/colors/QoQzVoiDVBcCtzXqNSzffqpNwkGSGhZy1UGXmilF.jpg', '2025-12-27 06:29:58', '2025-12-27 13:30:39', NULL),
(17, 1, 'Xanh lá', '#4ca462', 'storage/products/colors/GjEYBwPeT8fZ8HrrqwEkIFbPfqXoRka2BieII9pS.jpg', 'storage/products/colors/aaK5tWXejIjRAJsj4npSaihdPXA7iXPwKU8sFgPi.jpg', '2025-12-27 06:30:28', '2025-12-27 13:30:28', NULL),
(18, 1, 'Màu vàng', '#ffd505', 'storage/products/colors/5spOtCAiwyPRpQsl9uINS7GsMtVn0SZKffM7QQof.jpg', 'storage/products/colors/r2weM9OKXwK5KXpWwTETlcqp2FnkymlZNDDqavnp.jpg', '2025-12-27 06:31:16', '2025-12-27 13:31:16', NULL),
(19, 1, 'Màu xám tối', '#757575', 'storage/products/colors/Qah0TYRgFjbPkLzfYeqK62iycFJXxYFHiq36q3Yj.jpg', 'storage/products/colors/xQhO5F7KjgZFKyjT69L5yoaEfRT8okvneJmYE3k0.jpg', '2025-12-27 06:31:40', '2025-12-27 13:31:40', NULL),
(20, 1, 'Màu đỏ đô', '#6d3131', 'storage/products/colors/LyvN1nw4qB3DcBAqWn66UHdCFx0rx7sImyFKiXbc.jpg', 'storage/products/colors/LxBfJudgswlx8mJHpExiQ6Tw0Irv7G45krf4j3ey.jpg', '2025-12-27 06:32:05', '2025-12-27 13:32:05', NULL),
(21, 1, 'Màu đen', '#383838', 'storage/products/colors/3wrzjIjIW72iwws2Crbfdj2H5u3YMPmZIDAbeYwI.jpg', 'storage/products/colors/fgNp25avLChfyk35LtJTbA12qICJT5T1UvXmMG8z.jpg', '2025-12-27 06:32:24', '2025-12-27 13:32:24', NULL),
(22, 5, 'Màu trắng', '#ffffff', 'storage/products/colors/3XFsih0uXscL1h49LpCUF0e8ITr5o7Nt3SSOcG8Q.jpg', 'storage/products/colors/LDbYhR8J1TSAVh3jc3aL5hutmXy6UWYIEDomIWIJ.jpg', '2025-12-27 06:51:24', '2025-12-27 13:51:24', NULL),
(23, 5, 'Màu cam quýt', '#df836d', 'storage/products/colors/gHmIJ1gNwcW2hcKZSC4zwfjUN6S1Qvrg1dat4aiW.jpg', 'storage/products/colors/Qdgv4LptUn6poQ5719kapYE21ExYfd3fYL8ephfp.jpg', '2025-12-27 06:52:17', '2025-12-27 13:52:17', NULL),
(24, 5, 'Màu cafe', '#dac6af', 'storage/products/colors/Eu5ZNAJCo5IGpo93EbmY2xrd2S2HGo5vT8jzPt8g.jpg', 'storage/products/colors/c07eVdTSINj0hGi7Wau01f0mCD2wLqxf7kSwI8LY.jpg', '2025-12-27 06:53:20', '2025-12-27 13:53:20', NULL),
(25, 5, 'Màu xanh trà', '#9edcb3', 'storage/products/colors/oOQghTVOouXrqq0P88Ym6epW8R2wVgpwhySm1S0T.jpg', 'storage/products/colors/ohPIiEb3PIz8ipYofrHfoQ6OV4r39DkMTwtfWHiB.jpg', '2025-12-27 06:53:57', '2025-12-27 13:53:57', NULL),
(26, 5, 'Màu xanh đen', '#2f6a44', 'storage/products/colors/tFMQkwn2JDo3dboZQuChZPn9IZQ9ZDEqTGFDfGy3.jpg', 'storage/products/colors/1vGDdRWB4V2bdHDN2UlpX6l4B780bnFbA9nZjLY1.jpg', '2025-12-27 06:54:19', '2025-12-27 13:54:19', NULL),
(27, 5, 'Màu đen', '#303030', 'storage/products/colors/SPihjkOWbHtYm0e4baHKnUxSeQ6y46Zncm31vBZa.jpg', 'storage/products/colors/Vdimh2h0Bv8tJA2aQQnocWr7QhE2ecvIauQubyVe.jpg', '2025-12-27 06:54:37', '2025-12-27 13:54:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` bigint UNSIGNED NOT NULL,
  `size_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_modifier` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `size_name`, `price_modifier`, `created_at`) VALUES
(1, 'M', 0, '2025-12-27 06:45:13'),
(2, 'L', 0, '2025-12-27 06:45:13'),
(3, 'XL', 10000, '2025-12-27 06:45:13'),
(4, 'XXL', 15000, '2025-12-27 06:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `saved_designs`
--

CREATE TABLE `saved_designs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_color_id` bigint UNSIGNED NOT NULL,
  `design_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Untitled Design',
  `front_design_json` longtext COLLATE utf8mb4_unicode_ci,
  `back_design_json` longtext COLLATE utf8mb4_unicode_ci,
  `front_preview_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `back_preview_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_designs`
--

INSERT INTO `saved_designs` (`id`, `user_id`, `product_color_id`, `design_name`, `front_design_json`, `back_design_json`, `front_preview_img`, `back_preview_img`, `created_at`, `updated_at`) VALUES
(17, 3, 14, 'Thiết kế áo cam', '[{\"type\":\"image\",\"version\":\"2.4.0\",\"originX\":\"left\",\"originY\":\"top\",\"left\":116,\"top\":94.25,\"width\":1181,\"height\":1181,\"fill\":\"rgb(0,0,0)\",\"stroke\":null,\"strokeWidth\":0,\"strokeDashArray\":null,\"strokeLineCap\":\"butt\",\"strokeLineJoin\":\"miter\",\"strokeMiterLimit\":4,\"scaleX\":0.28,\"scaleY\":0.28,\"angle\":0,\"flipX\":false,\"flipY\":false,\"opacity\":1,\"shadow\":null,\"visible\":true,\"clipTo\":null,\"backgroundColor\":\"\",\"fillRule\":\"nonzero\",\"paintFirst\":\"fill\",\"globalCompositeOperation\":\"source-over\",\"transformMatrix\":null,\"skewX\":0,\"skewY\":0,\"crossOrigin\":\"\",\"cropX\":0,\"cropY\":0,\"src\":\"http:\\/\\/127.0.0.1:8000\\/images\\/cliparts\\/title-6.png\",\"filters\":[]}]', '[{\"type\":\"image\",\"version\":\"2.4.0\",\"originX\":\"left\",\"originY\":\"top\",\"left\":177,\"top\":76.62,\"width\":2239,\"height\":3109,\"fill\":\"rgb(0,0,0)\",\"stroke\":null,\"strokeWidth\":0,\"strokeDashArray\":null,\"strokeLineCap\":\"butt\",\"strokeLineJoin\":\"miter\",\"strokeMiterLimit\":4,\"scaleX\":0.09,\"scaleY\":0.09,\"angle\":0,\"flipX\":false,\"flipY\":false,\"opacity\":1,\"shadow\":null,\"visible\":true,\"clipTo\":null,\"backgroundColor\":\"\",\"fillRule\":\"nonzero\",\"paintFirst\":\"fill\",\"globalCompositeOperation\":\"source-over\",\"transformMatrix\":null,\"skewX\":0,\"skewY\":0,\"crossOrigin\":\"\",\"cropX\":0,\"cropY\":0,\"src\":\"http:\\/\\/127.0.0.1:8000\\/images\\/cliparts\\/title.png\",\"filters\":[]}]', 'storage/thumbnails/m9bjqyCJBqstlB6gTb7n.png', 'storage/thumbnails/Ldz7Zw5mMiI0rrMYItO2.png', '2025-12-27 07:20:47', '2025-12-27 07:20:47'),
(18, 4, 7, 'Thiết kế của tôi', '[{\"type\":\"image\",\"version\":\"2.4.0\",\"originX\":\"left\",\"originY\":\"top\",\"left\":161,\"top\":215.91,\"width\":2311,\"height\":2270,\"fill\":\"rgb(0,0,0)\",\"stroke\":null,\"strokeWidth\":0,\"strokeDashArray\":null,\"strokeLineCap\":\"butt\",\"strokeLineJoin\":\"miter\",\"strokeMiterLimit\":4,\"scaleX\":0.11,\"scaleY\":0.11,\"angle\":0,\"flipX\":false,\"flipY\":false,\"opacity\":1,\"shadow\":null,\"visible\":true,\"clipTo\":null,\"backgroundColor\":\"\",\"fillRule\":\"nonzero\",\"paintFirst\":\"fill\",\"globalCompositeOperation\":\"source-over\",\"transformMatrix\":null,\"skewX\":0,\"skewY\":0,\"crossOrigin\":\"\",\"cropX\":0,\"cropY\":0,\"src\":\"http:\\/\\/127.0.0.1:8000\\/images\\/cliparts\\/title-1.png\",\"filters\":[]},{\"type\":\"image\",\"version\":\"2.4.0\",\"originX\":\"left\",\"originY\":\"top\",\"left\":319,\"top\":109.58,\"width\":955,\"height\":1024,\"fill\":\"rgb(0,0,0)\",\"stroke\":null,\"strokeWidth\":0,\"strokeDashArray\":null,\"strokeLineCap\":\"butt\",\"strokeLineJoin\":\"miter\",\"strokeMiterLimit\":4,\"scaleX\":0.1,\"scaleY\":0.1,\"angle\":0,\"flipX\":false,\"flipY\":false,\"opacity\":1,\"shadow\":null,\"visible\":true,\"clipTo\":null,\"backgroundColor\":\"\",\"fillRule\":\"nonzero\",\"paintFirst\":\"fill\",\"globalCompositeOperation\":\"source-over\",\"transformMatrix\":null,\"skewX\":0,\"skewY\":0,\"crossOrigin\":\"\",\"cropX\":0,\"cropY\":0,\"src\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/uploads\\/users\\/4\\/eYvq72E4JJqMc06reTK5T4Mt9JzsQeSQZJ7LraHo.png\",\"filters\":[]}]', '[{\"type\":\"image\",\"version\":\"2.4.0\",\"originX\":\"left\",\"originY\":\"top\",\"left\":164,\"top\":84.66,\"width\":2390,\"height\":2634,\"fill\":\"rgb(0,0,0)\",\"stroke\":null,\"strokeWidth\":0,\"strokeDashArray\":null,\"strokeLineCap\":\"butt\",\"strokeLineJoin\":\"miter\",\"strokeMiterLimit\":4,\"scaleX\":0.1,\"scaleY\":0.1,\"angle\":0,\"flipX\":false,\"flipY\":false,\"opacity\":1,\"shadow\":null,\"visible\":true,\"clipTo\":null,\"backgroundColor\":\"\",\"fillRule\":\"nonzero\",\"paintFirst\":\"fill\",\"globalCompositeOperation\":\"source-over\",\"transformMatrix\":null,\"skewX\":0,\"skewY\":0,\"crossOrigin\":\"\",\"cropX\":0,\"cropY\":0,\"src\":\"http:\\/\\/127.0.0.1:8000\\/images\\/cliparts\\/title-2.png\",\"filters\":[]}]', 'storage/thumbnails/3231dsJjzbxATfQnAumV.png', 'storage/thumbnails/AzB5coofBOsXODBYDKjI.png', '2025-12-27 08:29:12', '2025-12-27 08:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` enum('admin','staff','customer') COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `status` enum('active','banned') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Khách Hàng Demo', 'khach@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0909123456', 'TP. Hồ Chí Minh', 'customer', 'active', '2025-12-27 06:45:13', '2025-12-27 06:45:13'),
(3, 'Quản trị hệ thống', 'admin@gmail.com', '$2y$12$pWTutPOlDtuldR4wvlC99.lsuDDiaoiiCmTa/aHc15MRxW4zOtNsy', '012345678', '126, Nguyễn Thiện Thành, Phường Hòa Thuận, Tỉnh Vĩnh Long', 'admin', 'active', '2025-12-27 01:06:38', '2025-12-27 14:19:54'),
(4, 'phan minh nhựt', 'hophan35.hp@gmail.com', '$2y$12$7Fg9Ygr/0K4zoEjXuTJd1.dvMhacFzlHky.qqpp.w/G5cCYf7.Mse', NULL, NULL, 'customer', 'active', '2025-12-27 08:21:39', '2025-12-27 08:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_uploads`
--

CREATE TABLE `user_uploads` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_uploads`
--

INSERT INTO `user_uploads` (`id`, `user_id`, `file_path`, `created_at`, `updated_at`) VALUES
(3, 4, 'storage/uploads/users/4/vDfk10Bl94FxaTwqa4oLxTqitRmnp0ykwXGTg9vm.png', '2025-12-27 08:25:18', '2025-12-27 15:25:18'),
(4, 4, 'storage/uploads/users/4/4vB1pevICz64Bn2oRfYfEv2UL8eAADBEcVS2ogI0.png', '2025-12-27 08:25:42', '2025-12-27 15:25:42'),
(5, 4, 'storage/uploads/users/4/eYvq72E4JJqMc06reTK5T4Mt9JzsQeSQZJ7LraHo.png', '2025-12-27 08:28:23', '2025-12-27 15:28:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cliparts`
--
ALTER TABLE `cliparts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `saved_design_id` (`saved_design_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_designs`
--
ALTER TABLE `saved_designs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_color_id` (`product_color_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_uploads`
--
ALTER TABLE `user_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cliparts`
--
ALTER TABLE `cliparts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `saved_designs`
--
ALTER TABLE `saved_designs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_uploads`
--
ALTER TABLE `user_uploads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`saved_design_id`) REFERENCES `saved_designs` (`id`);

--
-- Constraints for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD CONSTRAINT `product_colors_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_designs`
--
ALTER TABLE `saved_designs`
  ADD CONSTRAINT `saved_designs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_designs_ibfk_2` FOREIGN KEY (`product_color_id`) REFERENCES `product_colors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_uploads`
--
ALTER TABLE `user_uploads`
  ADD CONSTRAINT `user_uploads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
