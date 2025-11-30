-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 14, 2025 at 02:54 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jurnalguru`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int UNSIGNED NOT NULL,
  `kelas_id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `status` enum('hadir','izin','alpha') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'hadir',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `kelas_id`, `tanggal`, `student_id`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, '2025-11-02', 1, 'alpha', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(5, 1, '2025-11-02', 2, 'izin', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(6, 1, '2025-11-02', 3, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(7, 1, '2025-11-02', 4, 'alpha', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(8, 1, '2025-11-02', 5, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(9, 1, '2025-11-02', 10, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(10, 1, '2025-11-02', 11, 'alpha', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(11, 1, '2025-11-02', 12, 'alpha', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(12, 1, '2025-11-02', 13, 'alpha', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(13, 1, '2025-11-02', 14, 'alpha', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(14, 1, '2025-11-02', 15, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(15, 1, '2025-11-02', 16, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(16, 1, '2025-11-02', 17, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(17, 1, '2025-11-02', 18, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(18, 1, '2025-11-02', 19, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(19, 1, '2025-11-02', 20, 'hadir', '2025-11-02 12:43:20', '2025-11-02 12:43:20'),
(20, 1, '2025-11-02', 21, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(21, 1, '2025-11-02', 22, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(22, 1, '2025-11-02', 23, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(23, 1, '2025-11-02', 24, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(24, 1, '2025-11-02', 25, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(25, 1, '2025-11-02', 26, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(26, 1, '2025-11-02', 27, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(27, 1, '2025-11-02', 28, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21'),
(28, 1, '2025-11-02', 29, 'hadir', '2025-11-02 12:43:21', '2025-11-02 12:43:21');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_lampiran`
--

CREATE TABLE `jurnal_lampiran` (
  `id` int UNSIGNED NOT NULL,
  `jurnal_id` int UNSIGNED NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_file` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_new`
--

CREATE TABLE `jurnal_new` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `kelas_id` int UNSIGNED NOT NULL,
  `mapel_id` int UNSIGNED NOT NULL,
  `jam_ke` int UNSIGNED DEFAULT NULL,
  `materi` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_jam` int UNSIGNED DEFAULT NULL,
  `bukti_dukung` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_peserta` int UNSIGNED DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` enum('draft','published') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurnal_new`
--

INSERT INTO `jurnal_new` (`id`, `user_id`, `tanggal`, `kelas_id`, `mapel_id`, `jam_ke`, `materi`, `jumlah_jam`, `bukti_dukung`, `jumlah_peserta`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(2, 3, '2025-10-23', 1, 2, 3, 'Membaca Cerita Anak', 2, NULL, 25, 'Melanjutkan pembahasan cerita anak dengan fokus pada pemahaman isi', 'published', '2025-10-23 12:39:14', '2025-10-23 12:39:14'),
(3, 4, '2025-10-23', 2, 3, 1, 'Pengenalan Makhluk Hidup', 2, NULL, 28, 'Membahas ciri-ciri makhluk hidup dan perbedaannya dengan benda mati', 'published', '2025-10-23 12:39:14', '2025-10-23 12:39:14'),
(4, 5, '2025-10-23', 3, 9, 1, 'Keberagaman Budaya Indonesia', 2, NULL, 30, 'Mempelajari pentingnya menjaga keberagaman budaya di Indonesia', 'draft', '2025-10-23 12:39:14', '2025-10-23 12:39:14'),
(5, 2, '2025-10-23', 1, 1, 1, 'Pengenalan Bilangan Bulat', 2, NULL, 25, 'Membahas konsep dasar bilangan bulat positif dan negatif', 'published', '2025-10-23 16:05:16', '2025-10-23 16:05:16'),
(6, 21, '2025-10-24', 8, 1, 1, 'test input jurnal', 2, NULL, 25, 'apakah jurnal sesuai dengan yang di inginkan', 'published', '2025-10-24 10:30:16', '2025-10-24 10:30:16'),
(7, 21, '2025-11-14', 8, 12, 1, 'tambah tambahan', 2, NULL, 20, 'sukses', 'published', '2025-11-14 04:26:46', '2025-11-14 04:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int UNSIGNED NOT NULL,
  `kode_kelas` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `fase` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `wali_kelas` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `kode_kelas`, `nama_kelas`, `fase`, `wali_kelas`, `created_at`, `updated_at`) VALUES
(1, '1A', 'Kelas 1A', 'A', 16, '2025-10-21 01:30:41', '2025-10-24 02:32:24'),
(2, '1B', 'Kelas 1B', 'A', 22, '2025-10-21 01:30:41', '2025-10-24 12:26:18'),
(3, '1C', 'Kelas 1C', 'A', 27, '2025-10-21 01:30:41', '2025-10-24 12:27:05'),
(4, '2A', 'Kelas 2A', 'A', NULL, '2025-10-21 01:30:41', '2025-10-24 02:11:07'),
(5, '2B', 'Kelas 2B', 'A', NULL, '2025-10-21 01:30:41', '2025-10-24 02:32:41'),
(6, '3A', 'Kelas 3A', 'B', NULL, '2025-10-21 01:30:41', '2025-10-24 02:33:53'),
(7, '3B', 'Kelas 3B', 'B', NULL, '2025-10-21 01:30:41', '2025-10-24 02:34:02'),
(8, '4A', 'Kelas 4A', 'B', 21, '2025-10-21 01:30:41', '2025-10-24 10:28:41'),
(9, '4B', 'Kelas 4B', 'B', NULL, '2025-10-21 01:30:41', '2025-10-24 02:34:39'),
(10, '4C', 'Kelas 4C', 'B', NULL, '2025-10-21 01:30:41', '2025-10-24 02:37:53'),
(11, '5A', 'Kelas 5A', 'C', NULL, '2025-10-21 01:30:41', '2025-10-24 02:35:56'),
(12, '5B', 'Kelas 5B', 'C', NULL, '2025-10-21 01:30:41', '2025-10-24 02:38:06'),
(13, '5C', 'Kelas 5C', 'C', 5, '2025-10-21 08:42:30', '2025-10-24 02:38:28'),
(16, '6A', 'Kelas 6A', 'C', 15, '2025-10-24 02:39:00', '2025-10-24 02:39:00'),
(17, '6B', 'Kelas 6B', 'C', 19, '2025-10-24 02:39:50', '2025-10-24 02:39:50'),
(18, '2C', 'Kelas 2C', 'A', NULL, '2025-10-24 12:26:54', '2025-10-24 12:26:54');

-- --------------------------------------------------------

--
-- Table structure for table `kepala_sekolah`
--

CREATE TABLE `kepala_sekolah` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nip` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kepala_sekolah`
--

INSERT INTO `kepala_sekolah` (`id`, `nama`, `nip`, `created_at`, `updated_at`) VALUES
(1, 'Drs. Bambang Susanto, M.Pd', '196512011990031001', '2025-10-21 15:11:16', '2025-10-21 15:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id` int UNSIGNED NOT NULL,
  `kode_mapel` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_mapel` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `fase` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id`, `kode_mapel`, `nama_mapel`, `fase`, `created_at`, `updated_at`) VALUES
(1, 'MTK', 'Matematika', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:53:36'),
(2, 'BInd_SD', 'Bahasa Indonesia', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(3, 'IPA_SD', 'Ilmu Pengetahuan Alam', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(4, 'IPS_SD', 'Ilmu Pengetahuan Sosial', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(5, 'SBdP_SD', 'Seni Budaya dan Prakarya', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(6, 'PJOK_SD', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(7, 'BIng_SD', 'Bahasa Inggris', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(9, 'PKr_SD', 'Pendidikan Pancasila dan Kewarganegaraan', 'Fase ', '2025-10-21 01:30:41', '2025-10-21 08:30:41'),
(10, 'B ARB', 'Bahasa Arab', '', '2025-10-21 08:53:59', '2025-10-21 08:53:59'),
(11, 'SKI', 'Sejarah Kebudayaan Islam', '', '2025-10-22 03:26:18', '2025-10-22 03:26:18'),
(12, 'MTK_SD', 'Matematika', 'Fase ', '2025-10-23 03:58:35', '2025-10-23 10:58:35'),
(14, 'PAI_SD', 'Pendidikan Agama Islam', 'Fase ', '2025-10-23 03:59:05', '2025-10-23 10:59:05'),
(15, 'AQH', 'Al Qur\'an Hadis', '', '2025-10-23 13:06:06', '2025-10-23 13:06:06'),
(16, 'FQH', 'Fiqih', '', '2025-10-23 13:06:25', '2025-10-23 13:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(3, '2025-10-21-085841', 'App\\Database\\Migrations\\CreateKelasTable', 'default', 'App', 1761217036, 1),
(4, '2025-10-23-111155', 'App\\Database\\Migrations\\CreateJurnalNewTable', 'default', 'App', 1761218024, 2),
(5, '2025-10-23-111158', 'App\\Database\\Migrations\\CreateJurnalLampiranTable', 'default', 'App', 1761271549, 3),
(6, '2025-11-02-111159', 'App\\Database\\Migrations\\CreateAttendanceTable', 'default', 'App', 1762061993, 4);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `school_year` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `semester` enum('ganjil','genap') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `headmaster_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `headmaster_nip` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `school_address` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `school_name`, `school_year`, `semester`, `headmaster_name`, `headmaster_nip`, `logo`, `school_address`, `created_at`, `updated_at`) VALUES
(1, 'MIN 2 Tanggamus', '2025/2026', 'ganjil', 'Sipulloh, M.Pd', '197005272007011022', '1763106109_029f4086d7802a7a18fc.png', 'Jalan Lapangan Ampera, Nomor 109, Purwodadi', NULL, '2025-11-14 14:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `nip` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('guru','admin','super_admin') COLLATE utf8mb4_general_ci DEFAULT 'guru',
  `mata_pelajaran` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `no_telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nip`, `nama`, `email`, `password`, `role`, `mata_pelajaran`, `profile_picture`, `tanggal_lahir`, `alamat`, `no_telepon`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, '123456789012345', 'Admin Jurnal', 'admin@example.com', '$2y$10$LylQchA9eQZAATuoYnOSZ.4KuB0/.aFvSsWQ8hIUwbdj0mPq6WUfC', 'admin', NULL, '1763107265_c5a11871f217083e7044.png', NULL, '', '', 1, NULL, '2025-10-21 01:30:41', '2025-11-14 08:01:05'),
(2, '197025272007011022', 'Budi Santoso', 'budi.santoso@example.com', '$2y$10$N1D3clzX/lvuDyz47Nqvce3OSjShMXzE4k6iEnp6iwiD9.XWJ4kgK', 'guru', 'Matematika', '1761271749_7cea130e598b7c96c93f.png', '2005-01-24', NULL, NULL, 1, NULL, '2025-10-21 01:30:41', '2025-11-14 08:14:40'),
(3, '', 'Siti Rahayu', 'siti.rahayu@example.com', '$2y$10$OupvDE3J5dWVR4ON9S.VLuHvAIr0qISJxq0GuNhtAcI5EliQYfjHy', 'guru', 'Bahasa Indonesia', NULL, NULL, NULL, NULL, 1, NULL, '2025-10-21 01:30:41', '2025-10-24 12:29:03'),
(4, '333333333333333', 'Ahmad Fauzi', 'ahmad.fauzi@example.com', '$2y$10$3k7yJvooRU2DdnhPvs7aYuLdhev7TKOb4X/H9PckiGtf5pTlKyZ5S', 'guru', 'Ilmu Pengetahuan Alam', NULL, NULL, NULL, NULL, 1, NULL, '2025-10-21 01:30:42', '2025-10-21 01:30:42'),
(5, '199006282025211021', 'Dewi Lestari', 'dewi.lestari@example.com', '$2y$10$Zfu6iRDDq1lYR/65gOsU4uzJkwNXTXo3mVcd5coKpKDohwzU9GPJW', 'guru', 'Pendidikan Pancasila dan Kewarganegaraan', NULL, NULL, NULL, NULL, 1, NULL, '2025-10-21 01:30:42', '2025-10-21 08:35:47'),
(10, '197005272007011022', 'Sipulloh, M.Pd', 'sipulloh@min2tanggamus.sch.id', '$2y$10$MVN04r7jER/WM8eCMvu/TOeBkzGm/.LrKuP9BMIfrYNPGGCQY4NVe', 'admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:18', '2025-10-24 02:22:18'),
(11, '196701022005011002', 'Samarudin, S.Pd.I', 'samarudin@min2tanggamus.sch.id', '$2y$10$a8hCeDkvE/Ctsohzb5Z2u.OCS0Wju5UO5dsWb3fuujrBTbk0TwZQ.', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:18', '2025-10-24 02:22:18'),
(12, '196707232003121002', 'Ridwan, S.Pd.I', 'ridwan@min2tanggamus.sch.id', '$2y$10$o78RI/9lpZ2ulzYJuymF6e3xhrW3KtNsTG8SjdnWKwFCGHcRu772.', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:18', '2025-10-24 02:22:18'),
(13, '196703072005011005', 'Muhammad Nur Syafi\'i,S.Pd.SD', 'nursyafi\'i@min2tanggamus.sch.id', '$2y$10$nXOS0mSLGlDfnzLQEwHZAOrpOYQTCfZ3A1DQuYQechafb1nEY208O', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:18', '2025-10-24 02:22:18'),
(14, '198104202007101002', 'Dian Suherman, S.Pd.I', 'dian@min2tanggamus.sch.id', '$2y$10$Ei2SUlvJqJQgwxB5yeqaIOkKAbf9ZtGKzQMiAoFLOd4.ME9Pe.WjS', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(15, '197702022007101005', 'Ismangil, S.Pd.I', 'ismangil@min2tanggamus.sch.id', '$2y$10$tkGHzzRyn9GHfh2yV6kAP.h/i2kg9HBKP/B84V0gPgRuOTl8CvOky', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(16, '196908212007012029', 'Sri Hadna, S.Pd.I', 'sri@min2tanggamus.sch.id', '$2y$10$idfpXJg6X1K6J6ti1C6NU.zag4f8sM997oPRferFMUhiL95fS.Jde', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(17, '196605082007012029', 'Sa\'diyah, S.Pd.I', 'sadiyah@min2tanggamus.sch.id', '$2y$10$JfQNUDLQgzZE7iRLO0pt1.W5QNbOw4lZxENnMHyTiim/OJrCWneXK', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(18, '196908102007012053', 'Neti Herawati, S,Pd', 'neti@min2tanggamus.sch.id', '$2y$10$Jqw4/ZbuydNI4GVedruYGusmQc0zm6Jkiv7DqNukM/AcpFXS3RdnW', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(19, '197703232007102004', 'Misri Kurniati, S.Pd.I', 'misri@min2tanggamus.sch.id', '$2y$10$bFk5LgHC1J2gls3a.04v5u2WqOqKEkPL4R2..YskSdJcofLFnBwl6', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(20, '198801152019032011', 'Ariyani, S.Pd.I', 'ariyani@min2tanggamus.sch.id', '$2y$10$mqqlQZkfmvi1aCK/ERdKF.eiyl9UIobnuDuhPfk2GG8a.OFjjXtv.', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-10-24 02:22:19'),
(21, '198405032023212030', 'Aan Rozanah S, S.Pd.I', 'aan@min2tanggamus.sch.id', '$2y$10$ZVUDiQZzuUOCYq/j9i/m1eap3htTsXwO8Oq4dpL7vHIcBkPS1insG', 'guru', NULL, '1763090693_dcae33dd19b55d773112.jpeg', NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:19', '2025-11-14 03:27:33'),
(22, '197009162023212005', 'Napiah, S.Pd.I', 'napiah@min2tanggamus.sch.id', '$2y$10$BVExmv3rkSjxqKgMCfO2bOH16TXFqL/vmUiI2255enlPTyMbFwFNO', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(23, '199305312023212036', 'Dwi Putri Ayu Andari,S.Pd', 'dwi@min2tanggamus.sch.id', '$2y$10$orwUp6bdFfBTo1OQkR9SJ.fvk4QhOGdkUdbVEbAanhGwzI5dY7xYK', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(24, '199304112023212042', 'Fitria Sani, S.Pd.I', 'fitria@min2tanggamus.sch.id', '$2y$10$JYVx0.Ksje4ADCnH3ZZ6YOEi4bIVFXj4C5tRdOU86CpvKwHpiz.gC', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(25, '199304052023212046', 'DEVI APRIANI.S.Pd', 'devi@min2tanggamus.sch.id', '$2y$10$3aQGo4e7Y5tt.9PX2PNVY.KOKqtjA4rR49Steol9qQYQWaXLjV1w6', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(26, '196802122025212001', 'Helmaini, S.Pd.I', 'helmaini@min2tanggamus.sch.id', '$2y$10$TVnmIVY65opHO4vdu3USdugnJc89UVYG4DQYETNzaeXoEcJwvzrDC', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(27, '199706122025212001', 'Rosa linda, S.Pd', 'rosa@min2tanggamus.sch.id', '$2y$10$reP7ECwmFdqKxHymKT2vb.Y.xfKSiKnuxJL84ZOX9rUUWSisN2SFC', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(28, '199512052025212008', 'Angita Eka Rostianti, S.Pd', 'angita@min2tanggamus.sch.id', '$2y$10$8YUTciHsSqaoKhwgkZPcaOM01u/h2InVfigzfXae0U4l7KK6S.SZW', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-24 02:22:20', '2025-10-24 02:22:20'),
(29, NULL, 'Suci Maharani, S.Pd', 'Suci@min2tanggamus.sch.id', '$2y$10$z.40tdsoc8EFgZPTyRm5guYvWKx3seIGiQeCj1dGNURod25gqTlzC', 'guru', NULL, 'default.png', NULL, NULL, NULL, 1, NULL, '2025-10-24 12:28:41', '2025-11-14 14:30:35'),
(30, '999999999999999', 'Dr. Bambang Sutrisno', 'kepala.sekolah@example.com', '$2y$10$fUnELDtJgejW6ltMWSoiBOlTQfQih.sr7VCtb1CIlkC4eaxfOtS4q', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-11-13 17:02:12', '2025-11-13 17:02:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_kelas_id_foreign` (`kelas_id`),
  ADD KEY `attendance_student_id_foreign` (`student_id`);

--
-- Indexes for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_lampiran_jurnal_id_foreign` (`jurnal_id`);

--
-- Indexes for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_new_user_id_foreign` (`user_id`),
  ADD KEY `jurnal_new_kelas_id_foreign` (`kelas_id`),
  ADD KEY `jurnal_new_mapel_id_foreign` (`mapel_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kode_kelas` (`kode_kelas`),
  ADD KEY `fk_wali_kelas` (`wali_kelas`);

--
-- Indexes for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kode_mapel` (`kode_mapel`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD UNIQUE KEY `unique_nip` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  ADD CONSTRAINT `jurnal_lampiran_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_new` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  ADD CONSTRAINT `jurnal_new_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_new_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_new_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_wali_kelas` FOREIGN KEY (`wali_kelas`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
