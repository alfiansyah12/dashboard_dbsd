-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 01:54 AM
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
  `review_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atasan_review`
--

INSERT INTO `atasan_review` (`id`, `pegawai_tugas_id`, `review_status`, `review_by`, `review_at`) VALUES
(1, 1, 'not_yet', 4, '2025-12-18 11:18:35'),
(2, 2, 'not_yet', 4, '2025-12-18 11:47:14'),
(3, 3, 'done', 4, '2025-12-18 11:47:18'),
(4, 5, 'not_yet', 4, '2025-12-18 22:16:39'),
(5, 4, 'not_yet', 4, '2025-12-18 11:53:32'),
(6, 6, 'done', 4, '2025-12-18 22:16:08'),
(7, 8, 'done', 4, '2025-12-21 11:30:16'),
(8, 9, 'not_yet', 4, '2025-12-21 18:22:56'),
(9, 10, 'not_yet', 4, '2025-12-21 18:22:59'),
(10, 11, 'done', 4, '2025-12-22 00:47:58'),
(11, 12, 'done', 4, '2025-12-22 00:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `atasan_target`
--

CREATE TABLE `atasan_target` (
  `id` int(11) NOT NULL,
  `divisi_id` int(11) DEFAULT NULL,
  `periode` date NOT NULL,
  `target` int(11) NOT NULL DEFAULT 0,
  `realisasi` int(11) NOT NULL DEFAULT 0,
  `catatan` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atasan_target`
--

INSERT INTO `atasan_target` (`id`, `divisi_id`, `periode`, `target`, `realisasi`, `catatan`, `created_by`, `created_at`, `updated_at`) VALUES
(2, NULL, '2025-11-01', 100000, 75000, 'asdasd', 4, '2025-12-22 07:45:01', '2025-12-22 07:45:01');

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
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_input`
--

INSERT INTO `dashboard_input` (`id`, `pegawai_tugas_id`, `activity`, `pending_matters`, `close_the_path`, `updated_at`, `created_at`) VALUES
(8, 8, 'zxczxc', 'zxczxc', 'zxczcx', '2025-12-22 01:22:24', '2025-12-21 23:55:09'),
(9, 9, 'iiiiiiiiiiiiii', 'iiiiiiiiiiiiiiii', 'iiiiiiiiiiiiiiiiiii', '2025-12-22 01:22:21', '2025-12-21 23:58:34'),
(10, 10, 'zxczxc', 'zxzx', 'zxzx', '2025-12-22 01:22:17', '2025-12-22 01:09:14'),
(11, 11, 'asdasda', 'sdasd', 'asdasd', '2025-12-22 07:45:54', '2025-12-22 07:45:54'),
(12, 12, 'zxczxczxc', 'zxczxczc', 'zxczxczxc', '2025-12-22 07:49:23', '2025-12-22 07:49:23');

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
(4, 'DBSD', '2025-12-21 16:53:36'),
(5, 'SMBD', '2025-12-21 16:53:40'),
(6, 'QRIS', '2025-12-21 16:53:44');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `pegawai_tugas_id` int(11) NOT NULL,
  `goals` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `pegawai_tugas_id`, `goals`, `created_at`, `updated_at`) VALUES
(1, 11, 'asdasd', '2025-12-22 07:45:54', '2025-12-22 07:45:54'),
(2, 12, 'zxczxc', '2025-12-22 07:49:23', '2025-12-22 07:49:23');

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
(6, 3, 4, '2025-12-19', 'done', '2025-12-18 18:44:10'),
(7, 5, 5, '2025-12-19', 'on going', '2025-12-18 22:17:47'),
(8, 3, 6, '2025-12-21', 'terminated', '2025-12-21 10:55:00'),
(9, 3, 6, '2025-12-21', 'terminated', '2025-12-21 10:58:29'),
(10, 3, 6, '2025-12-22', 'terminated', '2025-12-21 18:09:06'),
(11, 3, 6, '2025-12-22', 'terminated', '2025-12-22 00:45:49'),
(12, 3, 6, '2025-12-22', 'on going', '2025-12-22 00:49:14');

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
(6, 'Kelola Data', 'mengelola data dari bank btn', 4, '2025-12-21 16:53:52'),
(7, 'Monitoring akses', 'monitoring akses pekerja', 5, '2025-12-21 16:54:17');

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
(3, 'Gaspar Ferdiansyah', 'gaspar.ferdiansyah@gmail.com', '$2y$10$Fdeda47sBb8e4i2E/.N5Q.M..wC..FZ7ohX3f3yH74SPwu9WIm3/y', 'pegawai', 4, '2025-12-18 08:54:19'),
(4, 'atasan', 'atasan@gmail.com', '$2y$10$/H5PUm979PzxvztY6KRMrOTxj/Udg54Rour9AZu2Wf9c8SDz2u89a', 'atasan', NULL, '2025-12-18 16:44:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atasan_review`
--
ALTER TABLE `atasan_review`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_review` (`pegawai_tugas_id`),
  ADD UNIQUE KEY `uniq_pegawai_tugas` (`pegawai_tugas_id`);

--
-- Indexes for table `atasan_target`
--
ALTER TABLE `atasan_target`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_divisi_periode` (`divisi_id`,`periode`);

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
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pegawai_tugas_id` (`pegawai_tugas_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `atasan_target`
--
ALTER TABLE `atasan_target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dashboard_input`
--
ALTER TABLE `dashboard_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pegawai_tugas`
--
ALTER TABLE `pegawai_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
