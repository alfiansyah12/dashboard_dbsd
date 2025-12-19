-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 03:16 AM
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
-- Database: `btn_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `atasan_review`
--

CREATE TABLE `atasan_review` (
  `id` int(11) NOT NULL,
  `pegawai_tugas_id` int(11) NOT NULL,
  `review_status` enum('done','not_yet') NOT NULL,
  `review_by` int(11) NOT NULL,
  `review_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atasan_review`
--

INSERT INTO `atasan_review` (`id`, `pegawai_tugas_id`, `review_status`, `review_by`, `review_at`) VALUES
(1, 1, 'not_yet', 4, '2025-12-18 18:18:35'),
(2, 2, 'not_yet', 4, '2025-12-18 18:47:14'),
(3, 3, 'done', 4, '2025-12-18 18:47:18'),
(4, 5, 'done', 4, '2025-12-18 18:53:36'),
(5, 4, 'not_yet', 4, '2025-12-18 18:53:32'),
(6, 6, 'not_yet', 4, '2025-12-19 02:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_input`
--

CREATE TABLE `dashboard_input` (
  `id` int(11) NOT NULL,
  `pegawai_tugas_id` int(11) NOT NULL,
  `activity` text DEFAULT NULL,
  `pending_matters` text DEFAULT NULL,
  `close_the_path` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_input`
--

INSERT INTO `dashboard_input` (`id`, `pegawai_tugas_id`, `activity`, `pending_matters`, `close_the_path`, `created_at`) VALUES
(1, 1, 'asdasd', 'asdasd', 'asdasd', '2025-12-18 23:43:28'),
(2, 2, 'qweqwe', 'qweqwe', 'weqweqe', '2025-12-19 00:22:05'),
(3, 3, 'asdasd', 'asdasd', 'asdasdasd', '2025-12-19 00:46:14'),
(4, 4, 'zxczxc', 'zxczcx', 'asdasdads', '2025-12-19 00:52:25'),
(5, 5, 'zxczxczcx', 'zxczxczxczxc', 'zxczxczxc', '2025-12-19 00:52:57'),
(6, 6, 'qwqwqwqw', 'qwqwqwq', 'qwqwqw', '2025-12-19 08:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` int(11) NOT NULL,
  `nama_divisi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama_divisi`, `created_at`) VALUES
(2, 'DBSD', '2025-12-18 08:58:24'),
(3, 'SMBD', '2025-12-18 08:58:32');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai_tugas`
--

CREATE TABLE `pegawai_tugas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tugas_id` int(11) DEFAULT NULL,
  `tanggal_ambil` date DEFAULT NULL,
  `status` enum('on going','done','terminated') DEFAULT 'on going',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai_tugas`
--

INSERT INTO `pegawai_tugas` (`id`, `user_id`, `tugas_id`, `tanggal_ambil`, `status`, `created_at`) VALUES
(1, 3, 2, '2025-12-18', 'done', '2025-12-18 09:24:58'),
(2, 3, 2, '2025-12-18', 'terminated', '2025-12-18 11:21:58'),
(3, 3, 4, '2025-12-18', 'done', '2025-12-18 11:43:28'),
(4, 5, 3, '2025-12-18', 'done', '2025-12-18 11:50:17'),
(5, 5, 3, '2025-12-18', 'done', '2025-12-18 11:52:50'),
(6, 3, 4, '2025-12-19', 'on going', '2025-12-18 18:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `nama_tugas` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `divisi_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `nama_tugas`, `deskripsi`, `divisi_id`, `created_at`) VALUES
(2, 'Kelola Data', 'mengelola data dari bank btn', 2, '2025-12-18 08:59:37'),
(3, 'Kelola data bank', 'mengelola data pegawai bank', 3, '2025-12-18 09:05:10'),
(4, 'asdasd', 'asdasd', 2, '2025-12-18 09:43:22'),
(5, 'Monitoring akses', 'monitoring akses pekerja', 3, '2025-12-18 17:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','atasan','pegawai') DEFAULT NULL,
  `divisi_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `divisi_id`, `created_at`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$YDF0VlJ4oShf.KkKtAlxm.KmtPZbrcV1/pV4i05UweSmNBn1kXN2m', 'admin', NULL, '2025-12-18 07:45:23'),
(3, 'Gaspar Ferdiansyah', 'gaspar.ferdiansyah@gmail.com', '$2y$10$Fdeda47sBb8e4i2E/.N5Q.M..wC..FZ7ohX3f3yH74SPwu9WIm3/y', 'pegawai', 2, '2025-12-18 08:54:19'),
(4, 'atasan', 'atasan@gmail.com', '$2y$10$/H5PUm979PzxvztY6KRMrOTxj/Udg54Rour9AZu2Wf9c8SDz2u89a', 'atasan', NULL, '2025-12-18 16:44:11'),
(5, 'alvin', 'alvin@gmail.com', '$2y$10$WkLQipyEus7uSs2kn9A45ec5hqup3JAqV2a2N3gw5/q/jiCcxTdmq', 'pegawai', 3, '2025-12-18 17:48:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atasan_review`
--
ALTER TABLE `atasan_review`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_review` (`pegawai_tugas_id`);

--
-- Indexes for table `dashboard_input`
--
ALTER TABLE `dashboard_input`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_dashboard_input` (`pegawai_tugas_id`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pegawai_tugas`
--
ALTER TABLE `pegawai_tugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `divisi_id` (`divisi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atasan_review`
--
ALTER TABLE `atasan_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dashboard_input`
--
ALTER TABLE `dashboard_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pegawai_tugas`
--
ALTER TABLE `pegawai_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`divisi_id`) REFERENCES `divisi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
