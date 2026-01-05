-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2025 at 08:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

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
CREATE TABLE
  `atasan_review` (
    `id` int (11) NOT NULL,
    `pegawai_tugas_id` int (11) NOT NULL,
    `review_status` enum ('done', 'not_yet') NOT NULL,
    `review_by` int (11) NOT NULL,
    `review_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `atasan_review`
--
INSERT INTO
  `atasan_review` (
    `id`,
    `pegawai_tugas_id`,
    `review_status`,
    `review_by`,
    `review_at`
  )
VALUES
  (12, 15, 'not_yet', 14, '2025-12-23 06:06:31'),
  (13, 14, 'done', 14, '2025-12-24 00:38:48'),
  (14, 13, 'done', 14, '2025-12-24 00:38:32');

-- --------------------------------------------------------
--
-- Table structure for table `atasan_target`
--
CREATE TABLE
  `atasan_target` (
    `id` int (11) NOT NULL,
    `departemen_id` int (11) DEFAULT NULL,
    `periode` date NOT NULL,
    `target` decimal(18, 2) NOT NULL DEFAULT 0.00,
    `realisasi` decimal(18, 2) NOT NULL DEFAULT 0.00,
    `fee_base_income` decimal(18, 2) DEFAULT NULL,
    `volume_of_agent` decimal(18, 2) DEFAULT NULL,
    `catatan` varchar(255) DEFAULT NULL,
    `created_by` int (11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `atasan_target`
--
INSERT INTO
  `atasan_target` (
    `id`,
    `departemen_id`,
    `periode`,
    `target`,
    `realisasi`,
    `fee_base_income`,
    `volume_of_agent`,
    `catatan`,
    `created_by`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    12,
    NULL,
    '2025-01-01',
    1000.00,
    2000.00,
    12000.00,
    13000.00,
    '',
    1,
    '2025-12-23 11:14:29',
    '2025-12-23 14:21:02'
  ),
  (
    14,
    NULL,
    '2025-02-01',
    1200.00,
    2100.00,
    12000.00,
    14000.00,
    '',
    1,
    '2025-12-23 14:21:29',
    '2025-12-23 14:23:11'
  ),
  (
    18,
    NULL,
    '2025-03-01',
    1400.00,
    2500.00,
    13000.00,
    15000.00,
    '',
    1,
    '2025-12-23 14:23:30',
    '2025-12-23 14:23:30'
  );

-- --------------------------------------------------------
--
-- Table structure for table `dashboard_input`
--
CREATE TABLE
  `dashboard_input` (
    `id` int (11) NOT NULL,
    `pegawai_tugas_id` int (11) NOT NULL,
    `activity` text DEFAULT NULL,
    `pending_matters` text DEFAULT NULL,
    `close_the_path` text DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_input`
--
INSERT INTO
  `dashboard_input` (
    `id`,
    `pegawai_tugas_id`,
    `activity`,
    `pending_matters`,
    `close_the_path`,
    `updated_at`,
    `created_at`
  )
VALUES
  (
    13,
    13,
    'qwert123',
    'asdf123123',
    'qwer123123',
    '2025-12-23 13:03:58',
    '2025-12-23 13:03:31'
  ),
  (
    14,
    14,
    'qweqw123',
    'qeqweasdad123',
    'zxczxcz123',
    '2025-12-23 13:05:31',
    '2025-12-23 13:05:31'
  ),
  (
    15,
    15,
    'zxczxc',
    'czasdasd',
    'asdasdasd',
    '2025-12-23 13:06:18',
    '2025-12-23 13:06:18'
  );

-- --------------------------------------------------------
--
-- Table structure for table `departemen`
--
CREATE TABLE
  `departemen` (
    `id` int (11) NOT NULL,
    `nama_departemen` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--
INSERT INTO
  `departemen` (`id`, `nama_departemen`, `created_at`)
VALUES
  (4, 'DBSD', '2025-12-21 16:53:36'),
  (5, 'SMBD', '2025-12-21 16:53:40'),
  (6, 'QRIS', '2025-12-21 16:53:44');

-- --------------------------------------------------------
--
-- Table structure for table `goals`
--
CREATE TABLE
  `goals` (
    `id` int (11) NOT NULL,
    `pegawai_tugas_id` int (11) NOT NULL,
    `goals` text NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--
INSERT INTO
  `goals` (
    `id`,
    `pegawai_tugas_id`,
    `goals`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    3,
    13,
    'proses',
    '2025-12-23 13:03:31',
    '2025-12-23 13:03:58'
  ),
  (
    4,
    14,
    'berhasil',
    '2025-12-23 13:05:31',
    '2025-12-23 13:05:31'
  ),
  (
    5,
    15,
    'sadsd',
    '2025-12-23 13:06:18',
    '2025-12-23 13:06:18'
  );

-- --------------------------------------------------------
--
-- Table structure for table `pegawai_tugas`
--
CREATE TABLE
  `pegawai_tugas` (
    `id` int (11) NOT NULL,
    `user_id` int (11) DEFAULT NULL,
    `tugas_id` int (11) DEFAULT NULL,
    `tanggal_ambil` date DEFAULT NULL,
    `status` enum ('on going', 'done', 'terminated') DEFAULT 'on going',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `pegawai_tugas`
--
INSERT INTO
  `pegawai_tugas` (
    `id`,
    `user_id`,
    `tugas_id`,
    `tanggal_ambil`,
    `status`,
    `created_at`
  )
VALUES
  (
    13,
    15,
    6,
    '2025-12-23',
    'on going',
    '2025-12-23 06:03:16'
  ),
  (
    14,
    16,
    6,
    '2025-12-23',
    'done',
    '2025-12-23 06:05:15'
  ),
  (
    15,
    17,
    6,
    '2025-12-23',
    'terminated',
    '2025-12-23 06:06:12'
  );

-- --------------------------------------------------------
--
-- Table structure for table `tugas`
--
CREATE TABLE
  `tugas` (
    `id` int (11) NOT NULL,
    `nama_tugas` varchar(150) DEFAULT NULL,
    `deskripsi` text DEFAULT NULL,
    `departemen_id` int (11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--
INSERT INTO
  `tugas` (
    `id`,
    `nama_tugas`,
    `deskripsi`,
    `departemen_id`,
    `created_at`
  )
VALUES
  (
    6,
    'Kelola Data',
    'mengelola data dari bank btn',
    4,
    '2025-12-21 16:53:52'
  ),
  (
    7,
    'Monitoring akses',
    'monitoring akses pekerja',
    5,
    '2025-12-21 16:54:17'
  );

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE
  `users` (
    `id` int (11) NOT NULL,
    `nama` varchar(100) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `reset_at` datetime DEFAULT NULL,
    `role` enum ('admin', 'atasan', 'pegawai') DEFAULT NULL,
    `departemen_id` int (11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `users`
--
INSERT INTO
  `users` (
    `id`,
    `nama`,
    `email`,
    `password`,
    `reset_at`,
    `role`,
    `departemen_id`,
    `created_at`
  )
VALUES
  (
    1,
    'Admin',
    'admin@admin.com',
    '$2y$10$YDF0VlJ4oShf.KkKtAlxm.KmtPZbrcV1/pV4i05UweSmNBn1kXN2m',
    NULL,
    'admin',
    NULL,
    '2025-12-18 07:45:23'
  ),
  (
    14,
    'atasan',
    'atasan@gmail.com',
    '$2y$10$.AjWZjSGLoAnTFxkFTctu.1Cb1OeIVxOMTLtmQcnoMA2RQynJTNfi',
    '2025-12-24 05:44:59',
    'atasan',
    NULL,
    '2025-12-23 02:02:46'
  ),
  (
    15,
    'Gaspar Ferdiansyah',
    'gaspar.ferdiansyah@gmail.com',
    '$2y$10$S2aNZJZwfOj2LtjmdORhAOtslIGc1PdYxB/CxXF8I/grk3vpl23im',
    NULL,
    'pegawai',
    4,
    '2025-12-23 02:03:08'
  ),
  (
    16,
    'alfin',
    'alfin@gmail.com',
    '$2y$10$1MTjF9LQoq2jeu0VF7K.D.WXp3OH2cIk2/XHhOE8aELvnJ2NcKJaK',
    NULL,
    'pegawai',
    4,
    '2025-12-23 06:04:52'
  ),
  (
    17,
    'marcel',
    'marcel@gmail.com',
    '$2y$10$joPKwwfZxsUdCTsVi530IeFC.qIOovaHZ0PBahcku/p8MVqZvjTB.',
    NULL,
    'pegawai',
    4,
    '2025-12-23 06:05:49'
  );

--
-- Indexes for dumped tables
--
--
-- Indexes for table `atasan_review`
--
ALTER TABLE `atasan_review` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `uq_review` (`pegawai_tugas_id`),
ADD UNIQUE KEY `uniq_pegawai_tugas` (`pegawai_tugas_id`);

--
-- Indexes for table `atasan_target`
--
ALTER TABLE `atasan_target` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `uniq_periode` (`periode`),
ADD UNIQUE KEY `uniq_departemen_periode` (`departemen_id`, `periode`);

--
-- Indexes for table `dashboard_input`
--
ALTER TABLE `dashboard_input` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `uq_dashboard_input` (`pegawai_tugas_id`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals` ADD PRIMARY KEY (`id`),
ADD KEY `pegawai_tugas_id` (`pegawai_tugas_id`);

--
-- Indexes for table `pegawai_tugas`
--
ALTER TABLE `pegawai_tugas` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas` ADD PRIMARY KEY (`id`),
ADD KEY `departemen_id` (`departemen_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `atasan_review`
--
ALTER TABLE `atasan_review` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT for table `atasan_target`
--
ALTER TABLE `atasan_target` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 20;

--
-- AUTO_INCREMENT for table `dashboard_input`
--
ALTER TABLE `dashboard_input` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 16;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

--
-- AUTO_INCREMENT for table `pegawai_tugas`
--
ALTER TABLE `pegawai_tugas` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 16;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 18;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas` ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`departemen_id`) REFERENCES `departemen` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;