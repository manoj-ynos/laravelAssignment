-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2021 at 12:14 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1),
(3, 'App\\User', 132),
(3, 'App\\User', 133);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage-users', 'web', '2020-11-11 02:35:58', '2020-11-11 02:35:58'),
(2, 'view-users', 'web', '2020-11-11 02:35:58', '2020-11-11 02:35:58'),
(3, 'create-users', 'web', '2020-11-11 02:35:58', '2020-11-11 02:35:58'),
(4, 'edit-users', 'web', '2020-11-11 02:35:58', '2020-11-11 02:35:58'),
(5, 'delete-users', 'web', '2020-11-11 02:35:59', '2020-11-11 02:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2020-11-11 02:35:58', '2020-11-11 02:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `u_type` int(11) NOT NULL DEFAULT 2 COMMENT '1-Admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `u_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `u_type`, `remember_token`, `created_at`, `updated_at`, `user_image`, `u_status`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$10$OSKNtwavJdtbVd0enUxz7u/YrERLhyFSDFKDvBLrSaoQC7.8EZTVm', 1, '8V9HHbrR1jApIuHBi3OWd3pTvSGMaBLoBrizlWa7bdBuCdHOhQuByZSCq398', '2020-11-11 02:35:59', '2020-11-11 02:35:59', NULL, 1),
(129, NULL, 'ree@dispostable.com', NULL, NULL, 2, 'P7WcqhU8g0ySwye5ut2U0KkqzcyJko63', '2021-03-23 02:13:56', '2021-03-23 02:13:56', NULL, 0),
(130, NULL, 'reeddd@dispostable.com', NULL, NULL, 2, 'UVeWQWonCgDKUYODx4JDYCYX8VzQRQDl', '2021-03-23 02:14:48', '2021-03-24 05:38:43', NULL, 0),
(131, 'sdfsdfsdfsdf', 'demouser@dispostable.com', NULL, '$2y$10$kF/sPsa4OH/kheIuxoaiXu4TDDglueLwWCCL6URdiMLhgsI.nHs7i', 2, 'rzGZdMrlEAdUF2ZUI7wsSioSX3vk4RizWv8lDqbR1ibulZwvCFTQ3eVSXn98', '2021-03-23 02:36:31', '2021-03-24 05:03:14', '1616581993.png', 1),
(132, 'fsdfsdfdsf', 'sdsdasd@dispostable.com', NULL, '$2y$10$ZRh0tZw3CgMylYRaRwR.GOfZ0WkcFxA6GkzBqY6NWvwloQkeSh7cq', 2, NULL, '2021-03-23 03:05:36', '2021-03-23 03:05:36', NULL, 1),
(133, 'sdfsadfsdaf', 'demoussdfsadfsder@dispostable.com', NULL, '$2y$10$bDBmNuBTE8mu6A6O2Ln7Uuf0u/o5OzYdleSIwu1or.TvKf0tZdRti', 2, NULL, '2021-03-23 03:06:24', '2021-03-23 03:06:24', NULL, 1),
(134, 'userdemo123', 'userdemo@dispostable.com', NULL, '$2y$10$0DzauaFo00qDOfOBcwScPOAYlU./nEhrXxbd6b.neRNDJ10kKXZDu', 2, 'axP3Otlouw3kTSpZfBtPETSb3eNmgOw728cp91hun9vBNJyDS5meO8JglEKq', '2021-03-24 04:23:52', '2021-03-24 04:52:04', NULL, 1),
(135, NULL, 'demouser123@dispostable.com', NULL, NULL, 2, 'RfZ1bmIyhiM3fIKwychuJpQMdH7dEKm2', '2021-03-24 04:50:20', '2021-03-24 04:50:20', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
