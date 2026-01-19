-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 08:50 AM
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
(12, 15, 'not_yet', 14, '2025-12-23 06:06:31'),
(13, 14, 'done', 14, '2025-12-24 00:38:48'),
(14, 13, 'done', 14, '2025-12-24 00:38:32'),
(15, 16, 'not_yet', 14, '2026-01-14 07:35:30'),
(16, 19, 'done', 14, '2026-01-14 07:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `atasan_target`
--

CREATE TABLE `atasan_target` (
  `id` int(11) NOT NULL,
  `departemen_id` int(11) DEFAULT NULL,
  `periode` date NOT NULL,
  `target_voa` decimal(18,2) NOT NULL DEFAULT 0.00,
  `real_voa` decimal(18,2) NOT NULL DEFAULT 0.00,
  `target_fbi` decimal(18,2) NOT NULL DEFAULT 0.00,
  `real_fbi` decimal(18,2) NOT NULL DEFAULT 0.00,
  `target_transaksi` decimal(18,2) NOT NULL DEFAULT 0.00,
  `real_transaksi` decimal(18,2) NOT NULL DEFAULT 0.00,
  `catatan` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tgl_target_final` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atasan_target`
--

INSERT INTO `atasan_target` (`id`, `departemen_id`, `periode`, `target_voa`, `real_voa`, `target_fbi`, `real_fbi`, `target_transaksi`, `real_transaksi`, `catatan`, `created_by`, `created_at`, `updated_at`, `tgl_target_final`) VALUES
(12, NULL, '2025-01-01', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, '2025-12-23 11:14:29', '2025-12-23 14:21:02', NULL),
(14, NULL, '2025-02-01', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, '2025-12-23 14:21:29', '2025-12-23 14:23:11', NULL),
(18, NULL, '2025-03-01', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, '2025-12-23 14:23:30', '2026-01-05 00:47:46', NULL),
(22, NULL, '2025-08-01', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 1, '2026-01-05 00:58:33', '2026-01-05 00:58:33', NULL),
(23, NULL, '2026-01-01', 10000000.00, 50000.00, 40000.00, 10000.00, 100000.00, 4000.00, '', 1, NULL, '2026-01-08 05:40:08', '2026-02-07'),
(25, NULL, '2026-02-01', 12312.00, 1231.00, 12311.00, 123213.00, 1231.00, 12313.00, 'Kesalahan Input data', 1, NULL, '2026-01-08 13:59:13', '2026-01-08'),
(27, NULL, '2026-01-08', 234234.00, 2343.00, 23423.00, 23424.00, 234243.00, 23432.00, '', 1, NULL, '2026-01-08 05:50:21', '2026-02-28'),
(29, NULL, '2026-01-09', 4324.00, 2342.00, 3242.00, 23424.00, 2342.00, 2342.00, '', 1, NULL, '2026-01-08 05:55:29', '2026-02-07'),
(30, NULL, '2026-01-10', 24324.00, 2342.00, 234.00, 23423.00, 23424.00, 2342.00, '', 1, NULL, '2026-01-08 11:56:28', '2026-01-11'),
(31, NULL, '2026-01-07', 345353.00, 435541.00, 34545.00, 3454.00, 3454.00, 3454.00, '', 1, '2026-01-08 14:44:08', '2026-01-08 14:44:08', '2026-01-06');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `progress_nilai` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_input`
--

INSERT INTO `dashboard_input` (`id`, `pegawai_tugas_id`, `activity`, `pending_matters`, `close_the_path`, `updated_at`, `created_at`, `progress_nilai`) VALUES
(13, 13, 'qwert123', 'asdf123123', 'qwer123123', '2025-12-23 13:03:58', '2025-12-23 13:03:31', 0),
(14, 14, 'qweqw123', 'qeqweasdad123', 'zxczxcz123', '2025-12-23 13:05:31', '2025-12-23 13:05:31', 0),
(15, 15, 'zxczxc', 'czasdasd', 'asdasdasd', '2025-12-23 13:06:18', '2025-12-23 13:06:18', 0),
(16, 16, 'Monitoring partnership', 'Menunggu konfirmasi dari DBPD\r\nhuhuhu', '', '2026-01-14 14:35:30', '2026-01-04 18:34:51', 9),
(17, 17, 'Validasi Agen Sulampua', '', '', '2026-01-14 02:39:18', '2026-01-14 02:38:40', 0),
(18, 18, 'sudah selesai', '', '', '2026-01-14 03:17:18', '2026-01-14 03:17:18', 0),
(19, 19, 'salah ambil', '', '', '2026-01-14 03:22:09', '2026-01-14 03:22:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id` int(11) NOT NULL,
  `nama_departemen` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`id`, `nama_departemen`, `created_at`) VALUES
(7, 'Biller Partnership', '2026-01-02 03:13:01'),
(8, 'Switching/ Principal & Partnership', '2026-01-02 03:13:19'),
(9, 'Sector Solution Partnership', '2026-01-02 03:13:33'),
(10, 'Agent Banking & PPOB  Business', '2026-01-02 03:14:09'),
(11, 'hghgh', '2026-01-14 07:29:04');

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
(3, 13, 'proses', '2025-12-23 13:03:31', '2025-12-23 13:03:58'),
(4, 14, 'berhasil', '2025-12-23 13:05:31', '2025-12-23 13:05:31'),
(5, 15, 'sadsd', '2025-12-23 13:06:18', '2025-12-23 13:06:18'),
(6, 16, 'Monitoring partnership  dan agen', '2026-01-05 00:34:51', '2026-01-14 14:33:30');

-- --------------------------------------------------------

--
-- Table structure for table `kpi_realizations`
--

CREATE TABLE `kpi_realizations` (
  `id` int(11) NOT NULL,
  `periode` date DEFAULT NULL,
  `real_voa` int(11) DEFAULT 0,
  `real_fbi` int(11) DEFAULT 0,
  `real_transaksi` int(11) DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpi_realizations`
--

INSERT INTO `kpi_realizations` (`id`, `periode`, `real_voa`, `real_fbi`, `real_transaksi`, `catatan`, `created_at`) VALUES
(2, '2026-01-11', 31231, 2313, 23434, '', '2026-01-12 02:40:54'),
(3, '2026-01-10', 23432, 243243, 24324, '', '2026-01-12 03:31:45'),
(5, '2026-01-14', 2343244, 4564634, 4564365, '', '2026-01-12 03:49:31'),
(6, '2026-01-13', 35352, 34532, 65343, '', '2026-01-12 03:50:32'),
(7, '2026-01-12', 232424, 346236, 35235, '', '2026-01-12 03:51:33'),
(8, '2026-01-09', 34533253, 32453525, 324535, '', '2026-01-12 03:51:49'),
(9, '2026-01-15', 646535253, 345352452, 345243545, '', '2026-01-12 03:52:28'),
(10, '2026-01-16', 345635546, 45656435, 4356346, '', '2026-01-12 03:53:06'),
(11, '2026-01-17', 23424224, 34525234, 354634561, 'Data kurang', '2026-01-12 04:03:32'),
(12, '2026-01-18', 324253, 34523534, 3452342, 'Tidak masuk catatan', '2026-01-12 04:06:51'),
(13, '2026-01-19', 4353434, 34543, 345354345, '', '2026-01-12 07:32:19');

-- --------------------------------------------------------

--
-- Table structure for table `kpi_targets`
--

CREATE TABLE `kpi_targets` (
  `id` int(11) NOT NULL,
  `periode` date DEFAULT NULL,
  `target_voa` int(11) DEFAULT 0,
  `target_fbi` int(11) DEFAULT 0,
  `target_transaksi` int(11) DEFAULT 0,
  `tgl_target_final` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpi_targets`
--

INSERT INTO `kpi_targets` (`id`, `periode`, `target_voa`, `target_fbi`, `target_transaksi`, `tgl_target_final`, `catatan`, `created_at`) VALUES
(2, '2026-01-12', 100000000, 124235235, 345432523, '2026-02-01', NULL, '2026-01-12 03:50:03');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `target_nilai` int(11) DEFAULT 0,
  `deadline_tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai_tugas`
--

INSERT INTO `pegawai_tugas` (`id`, `user_id`, `tugas_id`, `tanggal_ambil`, `status`, `created_at`, `updated_at`, `target_nilai`, `deadline_tanggal`) VALUES
(13, 15, 6, '2025-12-23', 'on going', '2025-12-23 06:03:16', NULL, 0, NULL),
(14, 16, 6, '2025-12-23', 'done', '2025-12-23 06:05:15', NULL, 0, NULL),
(15, 17, 6, '2025-12-23', 'terminated', '2025-12-23 06:06:12', NULL, 0, NULL),
(16, 18, 8, '2026-01-04', 'on going', '2026-01-04 11:34:30', NULL, 10, '2026-01-31'),
(17, 18, 12, '2026-01-14', 'done', '2026-01-13 19:38:11', NULL, 0, NULL),
(18, 18, 12, '2026-01-14', 'terminated', '2026-01-13 20:16:47', NULL, 0, NULL),
(19, 18, 12, '2026-01-14', 'terminated', '2026-01-13 20:21:21', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `nama_tugas` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `departemen_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `nama_tugas`, `deskripsi`, `departemen_id`, `created_at`) VALUES
(8, 'Mengelola Partnership', 'Mengelola partnership dengan: Billers, switching (GPN, dll), principle (Visa, MC, JCB, UP, etc)', 7, '2026-01-02 03:14:56'),
(9, 'Business/transactional Partnership', 'Business/transactional Partnership dengan berbagai sektor: healthcare, government, educations, etc', 8, '2026-01-02 03:15:24'),
(10, 'Mengelola bisnis agent', 'Mengelola bisnis agent banking & PPOB', 10, '2026-01-02 03:15:48'),
(11, 'API dan Virtual Account', 'Mengelola API dan Virtual Account', 9, '2026-01-02 03:16:28'),
(12, 'Mengelola Partnership Sulampua', 'Mengelola Partnership Sulampua', 7, '2026-01-05 01:03:50'),
(13, 'yuzi cico', 'indomaret', 7, '2026-01-14 07:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `role` enum('admin','atasan','pegawai') DEFAULT NULL,
  `departemen_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `reset_at`, `role`, `departemen_id`, `created_at`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$YDF0VlJ4oShf.KkKtAlxm.KmtPZbrcV1/pV4i05UweSmNBn1kXN2m', NULL, 'admin', NULL, '2025-12-18 07:45:23'),
(14, 'atasan', 'atasan@gmail.com', '$2y$10$11YijLJWM53gCp9csd.M6ugarmhaEfpoVkPP0sZd5.Q8tAQ5vvbfm', '2026-01-01 14:02:05', 'atasan', NULL, '2025-12-23 02:02:46'),
(15, 'Gaspar Ferdiansyah', 'gaspar.ferdiansyah@gmail.com', '$2y$10$S2aNZJZwfOj2LtjmdORhAOtslIGc1PdYxB/CxXF8I/grk3vpl23im', NULL, 'pegawai', 9, '2025-12-23 02:03:08'),
(17, 'marcel', 'marcel@gmail.com', '$2y$10$joPKwwfZxsUdCTsVi530IeFC.qIOovaHZ0PBahcku/p8MVqZvjTB.', NULL, 'pegawai', 8, '2025-12-23 06:05:49'),
(18, 'pegawai', 'pegawai@gmail.com', '$2y$10$2S7GYel6Ii13vRnV3V5XeuOekg9hWOxloU2GoFjV4voEf3wYErlbq', NULL, 'pegawai', 7, '2026-01-01 13:07:08');

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
  ADD UNIQUE KEY `uniq_periode` (`periode`),
  ADD UNIQUE KEY `uniq_divisi_periode` (`departemen_id`,`periode`);

--
-- Indexes for table `dashboard_input`
--
ALTER TABLE `dashboard_input`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_dashboard_input` (`pegawai_tugas_id`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pegawai_tugas_id` (`pegawai_tugas_id`);

--
-- Indexes for table `kpi_realizations`
--
ALTER TABLE `kpi_realizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_targets`
--
ALTER TABLE `kpi_targets`
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
  ADD KEY `divisi_id` (`departemen_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `atasan_target`
--
ALTER TABLE `atasan_target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `dashboard_input`
--
ALTER TABLE `dashboard_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kpi_realizations`
--
ALTER TABLE `kpi_realizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kpi_targets`
--
ALTER TABLE `kpi_targets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pegawai_tugas`
--
ALTER TABLE `pegawai_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
