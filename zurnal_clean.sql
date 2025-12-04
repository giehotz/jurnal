-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2025 at 12:46 AM
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
-- Database: `zurnal`
--

DELIMITER $$
--
-- Procedures
--
CREATE  PROCEDURE `UpdateRekapAbsensiHarian` (IN `p_tanggal` DATE, IN `p_rombel_id` INT)   BEGIN
                DECLARE v_total_siswa INT;
                DECLARE v_total_hadir INT;
                DECLARE v_total_sakit INT;
                DECLARE v_total_izin INT;
                DECLARE v_total_alfa INT;
                DECLARE v_persentase DECIMAL(5,2);
                DECLARE v_bulan TINYINT;
                DECLARE v_tahun YEAR;
                DECLARE v_semester ENUM('1','2');
                DECLARE v_tahun_ajaran VARCHAR(20);
                DECLARE v_guru_id INT;
                DECLARE v_mapel_id INT;

                -- Ambil data semester dan tahun ajaran dari rombel
                SELECT semester, tahun_ajaran, wali_kelas 
                INTO v_semester, v_tahun_ajaran, v_guru_id
                FROM rombel WHERE id = p_rombel_id;

                -- Hitung bulan dan tahun
                SET v_bulan = MONTH(p_tanggal);
                SET v_tahun = YEAR(p_tanggal);

                -- Hitung total siswa di rombel
                SELECT COUNT(*) INTO v_total_siswa
                FROM siswa 
                WHERE rombel_id = p_rombel_id AND is_active = 1;

                -- Hitung total absensi per status
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
                    SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'alfa' THEN 1 ELSE 0 END) as alfa
                INTO 
                    v_total_siswa, 
                    v_total_hadir,
                    v_total_sakit,
                    v_total_izin,
                    v_total_alfa
                FROM absensi 
                WHERE tanggal = p_tanggal AND rombel_id = p_rombel_id;

                -- Ambil mapel_id dari absensi terbaru (jika ada)
                SELECT mapel_id INTO v_mapel_id 
                FROM absensi 
                WHERE tanggal = p_tanggal AND rombel_id = p_rombel_id 
                LIMIT 1;

                -- Hitung persentase kehadiran
                IF v_total_siswa > 0 THEN
                    SET v_persentase = (v_total_hadir / v_total_siswa) * 100;
                ELSE
                    SET v_persentase = 0;
                END IF;

                -- Insert atau update rekap
                INSERT INTO rekap_absensi_harian (
                    tanggal, rombel_id, guru_id, mapel_id,
                    total_siswa, total_hadir, total_sakit, total_izin, total_alfa,
                    persentase_kehadiran, bulan, tahun, semester, tahun_ajaran,
                    created_at, updated_at
                ) VALUES (
                    p_tanggal, p_rombel_id, v_guru_id, v_mapel_id,
                    v_total_siswa, v_total_hadir, v_total_sakit, v_total_izin, v_total_alfa,
                    v_persentase, v_bulan, v_tahun, v_semester, v_tahun_ajaran,
                    NOW(), NOW()
                )
                ON DUPLICATE KEY UPDATE
                    guru_id = VALUES(guru_id),
                    mapel_id = VALUES(mapel_id),
                    total_siswa = VALUES(total_siswa),
                    total_hadir = VALUES(total_hadir),
                    total_sakit = VALUES(total_sakit),
                    total_izin = VALUES(total_izin),
                    total_alfa = VALUES(total_alfa),
                    persentase_kehadiran = VALUES(persentase_kehadiran),
                    updated_at = NOW();
            END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int UNSIGNED NOT NULL,
  `jurnal_id` int UNSIGNED DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `rombel_id` int UNSIGNED DEFAULT NULL,
  `guru_id` int UNSIGNED DEFAULT NULL,
  `mapel_id` int UNSIGNED DEFAULT NULL,
  `siswa_id` int UNSIGNED NOT NULL,
  `status` enum('hadir','sakit','izin','alfa') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'hadir',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `jurnal_id`, `tanggal`, `rombel_id`, `guru_id`, `mapel_id`, `siswa_id`, `status`, `keterangan`, `created_at`) VALUES
(25, 2, '2025-11-29', 1, 3, 1, 1, 'hadir', '', NULL),
(26, 2, '2025-11-29', 1, 3, 1, 2, 'izin', '', NULL),
(27, 2, '2025-11-29', 1, 3, 1, 3, 'sakit', '', NULL),
(28, 2, '2025-11-29', 1, 3, 1, 5, 'alfa', '', NULL),
(29, 2, '2025-11-29', 1, 3, 1, 4, 'hadir', '', NULL),
(30, 2, '2025-11-29', 1, 3, 1, 6, 'hadir', '', NULL),
(31, 2, '2025-11-29', 1, 3, 1, 20, 'hadir', '', NULL),
(32, 2, '2025-11-29', 1, 3, 1, 9, 'hadir', '', NULL),
(33, 2, '2025-11-29', 1, 3, 1, 21, 'hadir', '', NULL),
(34, 2, '2025-11-29', 1, 3, 1, 10, 'hadir', '', NULL),
(35, 2, '2025-11-29', 1, 3, 1, 18, 'hadir', '', NULL),
(36, 2, '2025-11-29', 1, 3, 1, 22, 'hadir', '', NULL),
(37, 2, '2025-11-29', 1, 3, 1, 12, 'hadir', '', NULL),
(38, 2, '2025-11-29', 1, 3, 1, 13, 'hadir', '', NULL),
(39, 2, '2025-11-29', 1, 3, 1, 17, 'hadir', '', NULL),
(40, 2, '2025-11-29', 1, 3, 1, 11, 'hadir', '', NULL),
(41, 2, '2025-11-29', 1, 3, 1, 7, 'hadir', '', NULL),
(42, 2, '2025-11-29', 1, 3, 1, 23, 'hadir', '', NULL),
(43, 2, '2025-11-29', 1, 3, 1, 19, 'hadir', '', NULL),
(44, 2, '2025-11-29', 1, 3, 1, 24, 'hadir', '', NULL),
(45, 2, '2025-11-29', 1, 3, 1, 14, 'hadir', '', NULL),
(46, 2, '2025-11-29', 1, 3, 1, 15, 'hadir', '', NULL),
(47, 2, '2025-11-29', 1, 3, 1, 16, 'hadir', '', NULL),
(48, 2, '2025-11-29', 1, 3, 1, 8, 'hadir', '', NULL);

--
-- Triggers `absensi`
--
DELIMITER $$
CREATE TRIGGER `after_absensi_delete_rekap` AFTER DELETE ON `absensi` FOR EACH ROW BEGIN
                CALL UpdateRekapAbsensiHarian(OLD.tanggal, OLD.rombel_id);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_absensi_insert_rekap` AFTER INSERT ON `absensi` FOR EACH ROW BEGIN
                CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_absensi_update_rekap` AFTER UPDATE ON `absensi` FOR EACH ROW BEGIN
                CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
                
                IF OLD.tanggal != NEW.tanggal OR OLD.rombel_id != NEW.rombel_id THEN
                    CALL UpdateRekapAbsensiHarian(OLD.tanggal, OLD.rombel_id);
                END IF;
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `allowed_routes`
--

CREATE TABLE `allowed_routes` (
  `id` int UNSIGNED NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','super_admin','guru','siswa') COLLATE utf8mb4_general_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `autoroute_activity_log`
--

CREATE TABLE `autoroute_activity_log` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `ip` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `controller` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `method` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('generated','ignored','blocked') COLLATE utf8mb4_general_ci DEFAULT 'generated',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `autoroute_activity_log`
--

INSERT INTO `autoroute_activity_log` (`id`, `user_id`, `ip`, `role`, `uri`, `controller`, `method`, `status`, `created_at`) VALUES
(1, 1, '::1', 'admin', 'admin/siswa/upload', 'Admin\\Siswa', 'upload', 'blocked', '2025-11-29 01:29:05'),
(2, 1, '::1', 'admin', 'admin/siswa/create', 'Admin\\Siswa', 'create', 'blocked', '2025-11-29 01:29:16'),
(3, 1, '::1', 'admin', 'admin/siswa/create', 'Admin\\Siswa', 'create', 'blocked', '2025-11-29 01:29:41'),
(4, 1, '::1', 'admin', 'admin/siswa/upload', 'Admin\\Siswa', 'upload', 'blocked', '2025-11-29 01:29:45'),
(5, 1, '::1', 'admin', 'admin/siswa/process-upload', 'Admin', 'siswa', 'blocked', '2025-11-29 01:34:45'),
(6, 1, '::1', 'admin', 'admin/siswa/process-upload', 'Admin', 'siswa', 'blocked', '2025-11-29 01:35:44'),
(7, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:29:37'),
(8, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:30:00'),
(9, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:31:27'),
(10, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:32:04'),
(11, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:32:37'),
(12, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:32:56'),
(13, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:33:34'),
(14, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:34:02'),
(15, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:34:12'),
(16, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:34:20'),
(17, 3, '::1', 'guru', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-29 02:34:25'),
(18, 1, '::1', 'admin', 'admin/monitoring/jurnal', 'Admin\\Monitoring', 'jurnal', 'blocked', '2025-11-29 10:52:02'),
(19, 1, '::1', 'admin', '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', 'blocked', '2025-11-30 01:22:21'),
(20, 1, '::1', 'admin', 'admin/laporan/index', 'Admin\\Laporan', 'index', 'blocked', '2025-11-30 02:06:28'),
(21, 1, '::1', 'admin', 'admin/laporan/index', 'Admin\\Laporan', 'index', 'blocked', '2025-11-30 02:07:33'),
(22, 1, '::1', 'admin', 'admin/laporan/index', 'Admin\\Laporan', 'index', 'blocked', '2025-11-30 02:08:16'),
(23, 1, '::1', 'admin', 'admin/export/master/guru', 'Admin\\Export\\Master', 'guru', 'blocked', '2025-11-30 02:22:42'),
(24, 3, '::1', 'guru', 'guru/qrcode/d91aafcf', 'Guru', 'qrcode', 'blocked', '2025-12-01 13:28:34');

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
  `rombel_id` int UNSIGNED NOT NULL,
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

INSERT INTO `jurnal_new` (`id`, `user_id`, `tanggal`, `rombel_id`, `mapel_id`, `jam_ke`, `materi`, `jumlah_jam`, `bukti_dukung`, `jumlah_peserta`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(2, 3, '2025-11-29', 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 'draft', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(3, 3, '2025-11-29', 1, 1, 1, 'PENJUMLAHAM', 2, NULL, 21, 'BAIK', 'published', '2025-11-29 01:50:26', '2025-11-29 01:50:26');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int UNSIGNED NOT NULL,
  `kode_kelas` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tingkat` enum('1','2','3','4','5','6','7','8','9','10','11','12') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Sipulloh, M.Pd', '197005272007011022', '2025-11-28 21:32:50', '2025-11-28 21:32:50');

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
(1, 'MTK', 'Matematika', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(2, 'AA', 'Akidah Akhlak', '', '2025-11-29 01:44:47', '2025-11-30 07:42:48'),
(3, 'FQH', 'Fikih', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(4, 'SKI', 'Sejarah Kebudayaan Islam', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(5, 'BA', 'Bahasa Arab', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(6, 'PP', 'Pendidikan Pancasila', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(7, 'BI', 'Bahasa Indonesia', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(8, 'IPAS', 'Ilmu Pengetahuan Alam dan Sosial', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(9, 'PJOK', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(10, 'SB', 'Seni dan Budaya', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(11, 'BING', 'Bahasa Inggris', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47'),
(12, 'BLAM', 'Bahasa Lampung', '', '2025-11-29 01:44:47', '2025-11-29 01:44:47');

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
(1, '2025-11-28-000001', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1764339672, 1),
(2, '2025-11-28-000002', 'App\\Database\\Migrations\\CreateMataPelajaranTable', 'default', 'App', 1764339672, 1),
(3, '2025-11-28-000003', 'App\\Database\\Migrations\\CreateRuanganTable', 'default', 'App', 1764339672, 1),
(4, '2025-11-28-000004', 'App\\Database\\Migrations\\CreateKelasTable', 'default', 'App', 1764339673, 1),
(5, '2025-11-28-000005', 'App\\Database\\Migrations\\CreateKepalaSekolahTable', 'default', 'App', 1764339673, 1),
(6, '2025-11-28-000006', 'App\\Database\\Migrations\\CreateSettingsTable', 'default', 'App', 1764339674, 1),
(7, '2025-11-28-000007', 'App\\Database\\Migrations\\CreateAllowedRoutesTable', 'default', 'App', 1764339674, 1),
(8, '2025-11-28-000008', 'App\\Database\\Migrations\\CreateMissingRoutesTable', 'default', 'App', 1764339674, 1),
(9, '2025-11-28-000009', 'App\\Database\\Migrations\\CreateAutoRouteLogTable', 'default', 'App', 1764339674, 1),
(10, '2025-11-28-000010', 'App\\Database\\Migrations\\CreateRombelTable', 'default', 'App', 1764339675, 1),
(11, '2025-11-28-000011', 'App\\Database\\Migrations\\CreateSiswaTable', 'default', 'App', 1764339675, 1),
(12, '2025-11-28-000012', 'App\\Database\\Migrations\\CreateRombelSiswaTable', 'default', 'App', 1764339675, 1),
(13, '2025-11-28-000013', 'App\\Database\\Migrations\\CreateJurnalNewTable', 'default', 'App', 1764339675, 1),
(14, '2025-11-28-000014', 'App\\Database\\Migrations\\CreateJurnalLampiranTable', 'default', 'App', 1764339676, 1),
(15, '2025-11-28-000015', 'App\\Database\\Migrations\\CreateAbsensiTable', 'default', 'App', 1764339676, 1),
(16, '2025-11-28-000016', 'App\\Database\\Migrations\\CreateRekapAbsensiTable', 'default', 'App', 1764339676, 1),
(17, '2025-11-28-000017', 'App\\Database\\Migrations\\CreateRekapAbsensiHarianTable', 'default', 'App', 1764339677, 1),
(18, '2025-11-28-000018', 'App\\Database\\Migrations\\AddTriggersAndProcedures', 'default', 'App', 1764339677, 1),
(19, '2025-11-30-151326', 'App\\Database\\Migrations\\CreateUrlQRFeature', 'default', 'App', 1764515927, 2),
(20, '2025-11-30-160318', 'App\\Database\\Migrations\\AddLabelToQrSettings', 'default', 'App', 1764518619, 3),
(21, '2025-12-01-114728', 'App\\Database\\Migrations\\CreateQRGlobalSettings', 'default', 'App', 1764589690, 4);

-- --------------------------------------------------------

--
-- Table structure for table `missing_routes`
--

CREATE TABLE `missing_routes` (
  `id` int UNSIGNED NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guessed_controller` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guessed_method` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','resolved','ignored') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `missing_routes`
--

INSERT INTO `missing_routes` (`id`, `uri`, `guessed_controller`, `guessed_method`, `status`, `created_at`) VALUES
(1, 'admin/siswa/upload', 'Admin\\Siswa', 'upload', '', '2025-11-29 01:29:05'),
(2, 'admin/siswa/create', 'Admin\\Siswa', 'create', '', '2025-11-29 01:29:16'),
(3, 'admin/siswa/create', 'Admin\\Siswa', 'create', '', '2025-11-29 01:29:41'),
(4, 'admin/siswa/upload', 'Admin\\Siswa', 'upload', '', '2025-11-29 01:29:44'),
(5, 'admin/siswa/process-upload', 'Admin', 'siswa', '', '2025-11-29 01:34:45'),
(6, 'admin/siswa/process-upload', 'Admin', 'siswa', '', '2025-11-29 01:35:44'),
(7, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:29:37'),
(8, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:30:00'),
(9, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:31:27'),
(10, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:32:04'),
(11, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:32:37'),
(12, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:32:56'),
(13, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:33:34'),
(14, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:34:02'),
(15, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:34:12'),
(16, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:34:20'),
(17, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-29 02:34:25'),
(18, 'admin/monitoring/jurnal', 'Admin\\Monitoring', 'jurnal', '', '2025-11-29 10:52:02'),
(19, '.well-known/appspecific/com.chrome.devtools.json', '.well-known', 'appspecific', '', '2025-11-30 01:22:21'),
(20, 'admin/laporan/index', 'Admin\\Laporan', 'index', '', '2025-11-30 02:06:28'),
(21, 'admin/laporan/index', 'Admin\\Laporan', 'index', '', '2025-11-30 02:07:33'),
(22, 'admin/laporan/index', 'Admin\\Laporan', 'index', '', '2025-11-30 02:08:16'),
(23, 'admin/export/master/guru', 'Admin\\Export\\Master', 'guru', '', '2025-11-30 02:22:41'),
(24, 'guru/qrcode/d91aafcf', 'Guru', 'qrcode', '', '2025-12-01 13:28:33');

-- --------------------------------------------------------

--
-- Table structure for table `qr_global_settings`
--

CREATE TABLE `qr_global_settings` (
  `id` int UNSIGNED NOT NULL,
  `default_size` int NOT NULL DEFAULT '300',
  `default_color` varchar(7) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#000000',
  `default_bg_color` varchar(7) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#FFFFFF',
  `default_logo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `allow_custom_logo` tinyint(1) NOT NULL DEFAULT '1',
  `allow_custom_colors` tinyint(1) NOT NULL DEFAULT '1',
  `allow_custom_size` tinyint(1) NOT NULL DEFAULT '1',
  `max_file_size_kb` int NOT NULL DEFAULT '2048',
  `allowed_mime_types` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_global_settings`
--

INSERT INTO `qr_global_settings` (`id`, `default_size`, `default_color`, `default_bg_color`, `default_logo_path`, `allow_custom_logo`, `allow_custom_colors`, `allow_custom_size`, `max_file_size_kb`, `allowed_mime_types`, `created_at`, `updated_at`) VALUES
(1, 300, '#000000', '#ffffff', 'uploads/logos/1764590335_f65b45b073cdb3ec8e51.png', 1, 1, 1, 2048, 'image/png,image/jpeg,image/gif', '2025-12-01 18:48:10', '2025-12-01 18:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `qr_settings`
--

CREATE TABLE `qr_settings` (
  `id` int UNSIGNED NOT NULL,
  `url_id` int UNSIGNED NOT NULL,
  `qr_color` varchar(7) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#000000',
  `bg_color` varchar(7) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#FFFFFF',
  `size` int NOT NULL DEFAULT '300',
  `logo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `frame_style` enum('none','square','circle','rounded') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'none',
  `error_correction` enum('L','M','Q','H') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'L',
  `version` int NOT NULL DEFAULT '5',
  `show_label` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_settings`
--

INSERT INTO `qr_settings` (`id`, `url_id`, `qr_color`, `bg_color`, `size`, `logo_path`, `frame_style`, `error_correction`, `version`, `show_label`, `created_at`) VALUES
(1, 1, '#000000', '#ffffff', 300, NULL, 'rounded', 'L', 5, 0, '2025-11-30 22:20:16'),
(4, 4, '#000000', '#ffffff', 300, 'default_4a5e744ce60129cb.png', 'none', 'L', 5, 0, '2025-12-01 20:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `rekap_absensi`
--

CREATE TABLE `rekap_absensi` (
  `id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `rombel_id` int UNSIGNED NOT NULL,
  `siswa_id` int UNSIGNED NOT NULL,
  `guru_id` int UNSIGNED NOT NULL,
  `mapel_id` int UNSIGNED NOT NULL,
  `total_hadir` int UNSIGNED NOT NULL DEFAULT '0',
  `total_sakit` int UNSIGNED NOT NULL DEFAULT '0',
  `total_izin` int UNSIGNED NOT NULL DEFAULT '0',
  `total_alfa` int UNSIGNED NOT NULL DEFAULT '0',
  `total_pertemuan` int UNSIGNED NOT NULL DEFAULT '0',
  `persentase_kehadiran` decimal(5,2) NOT NULL DEFAULT '0.00',
  `bulan` tinyint UNSIGNED NOT NULL,
  `tahun` year NOT NULL,
  `semester` enum('1','2') COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekap_absensi`
--

INSERT INTO `rekap_absensi` (`id`, `tanggal`, `rombel_id`, `siswa_id`, `guru_id`, `mapel_id`, `total_hadir`, `total_sakit`, `total_izin`, `total_alfa`, `total_pertemuan`, `persentase_kehadiran`, `bulan`, `tahun`, `semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(25, '2025-11-29', 1, 1, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-30 08:29:49'),
(26, '2025-11-29', 1, 2, 3, 1, 0, 0, 1, 0, 1, '0.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-30 01:01:22'),
(27, '2025-11-29', 1, 3, 3, 1, 0, 1, 0, 0, 1, '0.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(28, '2025-11-29', 1, 5, 3, 1, 0, 0, 0, 1, 1, '0.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(29, '2025-11-29', 1, 4, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(30, '2025-11-29', 1, 6, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(31, '2025-11-29', 1, 20, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(32, '2025-11-29', 1, 9, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(33, '2025-11-29', 1, 21, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(34, '2025-11-29', 1, 10, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(35, '2025-11-29', 1, 18, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(36, '2025-11-29', 1, 22, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(37, '2025-11-29', 1, 12, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(38, '2025-11-29', 1, 13, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(39, '2025-11-29', 1, 17, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(40, '2025-11-29', 1, 11, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(41, '2025-11-29', 1, 7, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:49', '2025-11-29 01:49:49'),
(42, '2025-11-29', 1, 23, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50'),
(43, '2025-11-29', 1, 19, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50'),
(44, '2025-11-29', 1, 24, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50'),
(45, '2025-11-29', 1, 14, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50'),
(46, '2025-11-29', 1, 15, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50'),
(47, '2025-11-29', 1, 16, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50'),
(48, '2025-11-29', 1, 8, 3, 1, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-29 01:49:50', '2025-11-29 01:49:50');

-- --------------------------------------------------------

--
-- Table structure for table `rekap_absensi_harian`
--

CREATE TABLE `rekap_absensi_harian` (
  `id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `rombel_id` int UNSIGNED NOT NULL,
  `guru_id` int UNSIGNED DEFAULT NULL,
  `mapel_id` int UNSIGNED DEFAULT NULL,
  `total_siswa` int NOT NULL DEFAULT '0',
  `total_hadir` int NOT NULL DEFAULT '0',
  `total_sakit` int NOT NULL DEFAULT '0',
  `total_izin` int NOT NULL DEFAULT '0',
  `total_alfa` int NOT NULL DEFAULT '0',
  `persentase_kehadiran` decimal(5,2) NOT NULL DEFAULT '0.00',
  `bulan` tinyint NOT NULL,
  `tahun` year NOT NULL,
  `semester` enum('1','2') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekap_absensi_harian`
--

INSERT INTO `rekap_absensi_harian` (`id`, `tanggal`, `rombel_id`, `guru_id`, `mapel_id`, `total_siswa`, `total_hadir`, `total_sakit`, `total_izin`, `total_alfa`, `persentase_kehadiran`, `bulan`, `tahun`, `semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(25, '2025-11-29', 1, 3, 1, 24, 21, 1, 1, 1, '87.50', 11, 2025, '1', '2025/2026', '2025-11-29 08:49:49', '2025-11-30 15:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `rombel`
--

CREATE TABLE `rombel` (
  `id` int UNSIGNED NOT NULL,
  `kode_rombel` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_rombel` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tingkat` enum('1','2','3','4','5','6','7','8','9','10','11','12') COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wali_kelas` int UNSIGNED DEFAULT NULL,
  `ruangan_id` int UNSIGNED DEFAULT NULL,
  `nama_ruangan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kurikulum` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_rombel` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `waktu_mengajar` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `semester` enum('1','2') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `kapasitas` int NOT NULL DEFAULT '36',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rombel`
--

INSERT INTO `rombel` (`id`, `kode_rombel`, `nama_rombel`, `tingkat`, `jurusan`, `wali_kelas`, `ruangan_id`, `nama_ruangan`, `kurikulum`, `jenis_rombel`, `waktu_mengajar`, `tahun_ajaran`, `semester`, `kapasitas`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1A', 'Kelas 1A', '1', 'Umum', 3, 1, NULL, 'Kurikulum Merdeka', 'Reguler', 'Pagi', '2025/2026', '1', 30, 1, '2025-11-29 01:27:59', '2025-11-30 01:07:52'),
(2, '1B', 'Kelas 1B', '1', 'Umum', 4, 2, NULL, 'Kurikulum Merdeka', 'Reguler', 'Pagi', '2025/2026', '1', 30, 1, '2025-11-30 01:07:42', '2025-11-30 01:07:42'),
(5, '1C', 'Kelas 1C', '1', 'Umum', 6, 3, NULL, 'Kurikulum Merdeka', 'Reguler', 'Pagi', '2025/2026', '1', 30, 1, '2025-11-30 01:11:23', '2025-11-30 01:11:47');

-- --------------------------------------------------------

--
-- Table structure for table `rombel_siswa`
--

CREATE TABLE `rombel_siswa` (
  `id` int UNSIGNED NOT NULL,
  `siswa_id` int UNSIGNED NOT NULL,
  `rombel_id` int UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(9) COLLATE utf8mb4_general_ci NOT NULL,
  `semester` enum('1','2') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rombel_siswa`
--

INSERT INTO `rombel_siswa` (`id`, `siswa_id`, `rombel_id`, `tahun_ajaran`, `semester`, `created_at`) VALUES
(1, 1, 1, '2024/2025', '1', NULL),
(2, 2, 1, '2024/2025', '1', NULL),
(3, 3, 1, '2024/2025', '1', NULL),
(4, 4, 1, '2024/2025', '1', NULL),
(5, 5, 1, '2024/2025', '1', NULL),
(6, 6, 1, '2024/2025', '1', NULL),
(7, 7, 1, '2024/2025', '1', NULL),
(8, 8, 1, '2024/2025', '1', NULL),
(9, 9, 1, '2024/2025', '1', NULL),
(10, 10, 1, '2024/2025', '1', NULL),
(11, 11, 1, '2024/2025', '1', NULL),
(12, 12, 1, '2024/2025', '1', NULL),
(13, 13, 1, '2024/2025', '1', NULL),
(14, 14, 1, '2024/2025', '1', NULL),
(15, 15, 1, '2024/2025', '1', NULL),
(16, 16, 1, '2024/2025', '1', NULL),
(17, 17, 1, '2024/2025', '1', NULL),
(18, 18, 1, '2024/2025', '1', NULL),
(19, 19, 1, '2024/2025', '1', NULL),
(20, 20, 1, '2024/2025', '1', NULL),
(21, 21, 1, '2024/2025', '1', NULL),
(22, 22, 1, '2024/2025', '1', NULL),
(23, 23, 1, '2024/2025', '1', NULL),
(24, 24, 1, '2024/2025', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int UNSIGNED NOT NULL,
  `nama_ruangan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kapasitas` int NOT NULL DEFAULT '30',
  `jenis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Kelas',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `nama_ruangan`, `kapasitas`, `jenis`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Ruang Kelas 1A', 30, 'Kelas', '', '2025-11-29 08:24:00', '2025-11-29 08:24:00'),
(2, 'Ruang Kelas 1B', 30, 'Kelas', '', '2025-11-30 08:06:47', '2025-11-30 08:06:47'),
(3, 'Ruang Kelas 1C', 30, 'Kelas', '', '2025-11-30 08:07:02', '2025-11-30 08:07:02');

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
  `school_level` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'SMA/MA',
  `auto_route_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `school_name`, `school_year`, `semester`, `headmaster_name`, `headmaster_nip`, `logo`, `school_address`, `school_level`, `auto_route_enabled`, `created_at`, `updated_at`) VALUES
(1, 'MIN 2 Tanggamus', '2025/2026', 'ganjil', 'Sipulloh, M.Pd', '197005272007011022', '1764340370_84660a2e41997d7006a2.png', 'Jalan Lapangan Ampera No 109', 'SD/MI', 1, '2025-11-28 21:31:55', '2025-11-29 08:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int UNSIGNED NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nisn` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `rombel_id` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `rombel_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '3197655468', '3197655468', 'AHMAD REFALS REVANESD', 'L', '2019-02-16', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(2, '3196720270', '3196720270', 'ALEYA KHAIRA LUBNA KURNIAWAN', 'P', '2019-03-28', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(3, '3195466520', '3195466520', 'ALIKA NAILA PUTRI', 'P', '2019-03-27', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(4, '3183524474', '3183524474', 'ANINDITA KEISHA AZKAYRA', 'P', '2018-09-06', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(5, '3186145736', '3186145736', 'ALVINO NAZRIL RASHAAD', 'L', '2018-08-01', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(6, '3185528662', '3185528662', 'AZAM NUR WAHID', 'L', '2018-09-16', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(7, '3180585720', '3180585720', 'MUTIARA KHOIRUNISA', 'P', '2018-10-09', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(8, '3197487212', '3197487212', 'ZIVANA ALIFYA ANWAR', 'P', '2019-03-05', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(9, '3191958461', '3191958461', 'DINARA FILDZHA DE QUESTA', 'P', '2019-02-20', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(10, '3198222733', '3198222733', 'FATIH RAZKA ANANDETO', 'L', '2019-06-11', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(11, '3192912979', '3192912979', 'MUHAMMAD AMRAN NUR MAULID', 'L', '2019-11-24', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(12, '3189566081', '3189566081', 'MOCHAMAD AL-HABSY AS-BAROZ', 'L', '2018-06-19', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(13, '3186871578', '3186871578', 'MUHAMAD AZKA PRATAMA', 'L', '2018-11-03', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(14, '3191637172', '3191637172', 'SHAFA FAUZIYA ZITNI', 'P', '2019-03-11', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(15, '3191597338', '3191597338', 'SYAKIRA AZZAKIATUN NUFUS', 'P', '2019-09-10', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(16, '3193821013', '3193821013', 'THIO ARSHA VIANDI', 'L', '2019-04-08', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(17, '3181613876', '3181613876', 'MUHAMAD YUSUF MAULANA', 'L', '2018-10-22', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(18, '3189500821', '3189500821', 'JIHAN ANAMI ZAILA', 'P', '2018-09-09', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(19, '3180195612', '3180195612', 'NAHWA SYAREEFA RAMLANI', 'P', '2018-10-23', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(20, '3193055063', '3193055063', 'AZKA ZIYAD AR RAYYAN', 'L', '2019-06-12', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(21, '3182037709', '3182037709', 'FARZAN RAIHAN SHAKIEL', 'L', '2018-05-10', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(22, '3181661368', '3181661368', 'MARYAM AFIFAH', 'P', '2018-05-29', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(23, '3185683320', '3185683320', 'NADHIFA HASNA HUMAIRA', 'P', '2018-11-29', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55'),
(24, '3194053357', '3194053357', 'QUEENEYRA FELICHIA', 'P', '2019-02-22', 1, 1, '2025-11-29 01:35:55', '2025-11-29 01:35:55');

-- --------------------------------------------------------

--
-- Table structure for table `url_entries`
--

CREATE TABLE `url_entries` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `original_url` text COLLATE utf8mb4_general_ci NOT NULL,
  `short_slug` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `custom_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `click_count` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `url_entries`
--

INSERT INTO `url_entries` (`id`, `user_id`, `original_url`, `short_slug`, `custom_name`, `click_count`, `created_at`, `updated_at`) VALUES
(1, 3, 'https://www.youtube.com/', 'adac33d0', 'youtube', 0, '2025-11-30 22:20:16', '2025-12-01 20:27:25'),
(4, 3, 'https://drive.google.com/drive/folders/1xUkdAXOezLMWDAV8tcruGvaDfMsetKdM?usp=drive_link', 'd91aafcf', 'google', 0, '2025-12-01 20:00:08', '2025-12-01 20:01:30');

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
  `role` enum('guru','admin','super_admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'guru',
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
(1, '123456789012345', 'Admin Jurnal', 'admin@example.com', '$2y$10$HHwa1HFcvRy8kfx0zsmC6ePPMIHBq0Uxg.7R.MnS/zmU9FfHn6XYK', 'admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-11-28 14:23:17', '2025-11-28 14:23:17'),
(2, '999999999999999', 'Dr. Bambang Sutrisno', 'kepala.sekolah@example.com', '$2y$10$tPfEx0sNNIAJFexW.JGMSuJHegsPih/Z/WsXoKStkZZAqElywo0eW', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-11-28 14:23:17', '2025-11-28 14:23:17'),
(3, '111111111111111', 'User Test', 'budi.santoso@example.com', '$2y$10$Ib76CvLSxVQofIK7918W.OlWNGay572cNNs8EfPC6gYlIv7UIL/P.', 'guru', 'Matematika', '', NULL, NULL, NULL, 1, NULL, '2025-11-28 14:23:17', '2025-12-01 13:15:33'),
(4, '222222222222222', 'Siti Rahayu', 'siti.rahayu@example.com', '$2y$10$HvQoQj9mqb5zUl2nvSGMiu/kQ9wP68Dt6rqmg.4zl5L.liue8ZL.a', 'guru', 'Bahasa Indonesia', NULL, NULL, NULL, NULL, 1, NULL, '2025-11-28 14:23:17', '2025-11-28 14:23:17'),
(5, '333333333333333', 'Ahmad Fauzi', 'ahmad.fauzi@example.com', '$2y$10$tLkt1PI0.3P1G7S6L2WZ7.UNtElnV3wK8ZfVwtcB.W5A.z.T91TJK', 'guru', 'Ilmu Pengetahuan Alam', NULL, NULL, NULL, NULL, 1, NULL, '2025-11-28 14:23:17', '2025-11-28 14:23:17'),
(6, '444444444444444', 'Dewi Lestari', 'dewi.lestari@example.com', '$2y$10$Kgy4fjhnQbTQCd3ftRJSqe/mjHW3pdS.lbGivV.QOcx2.p4wX9bE2', 'guru', 'Pendidikan Pancasila dan Kewarganegaraan', NULL, NULL, NULL, NULL, 1, NULL, '2025-11-28 14:23:17', '2025-11-28 14:23:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_id` (`jurnal_id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `rombel_id` (`rombel_id`);

--
-- Indexes for table `allowed_routes`
--
ALTER TABLE `allowed_routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `autoroute_activity_log`
--
ALTER TABLE `autoroute_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_id` (`jurnal_id`);

--
-- Indexes for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `rombel_id` (`rombel_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kelas` (`kode_kelas`);

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
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `missing_routes`
--
ALTER TABLE `missing_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uri_index` (`uri`),
  ADD KEY `status_index` (`status`);

--
-- Indexes for table `qr_global_settings`
--
ALTER TABLE `qr_global_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_settings`
--
ALTER TABLE `qr_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qr_settings_url_id_foreign` (`url_id`);

--
-- Indexes for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rekap` (`tanggal`,`rombel_id`,`siswa_id`,`mapel_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_rombel_siswa` (`rombel_id`,`siswa_id`),
  ADD KEY `idx_bulan_tahun` (`bulan`,`tahun`),
  ADD KEY `idx_semester` (`semester`,`tahun_ajaran`),
  ADD KEY `fk_rekap_rombel` (`rombel_id`),
  ADD KEY `fk_rekap_siswa` (`siswa_id`),
  ADD KEY `fk_rekap_guru` (`guru_id`),
  ADD KEY `fk_rekap_mapel` (`mapel_id`);

--
-- Indexes for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tanggal_rombel` (`tanggal`,`rombel_id`),
  ADD KEY `rombel_id` (`rombel_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_bulan_tahun` (`bulan`,`tahun`),
  ADD KEY `idx_semester` (`semester`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indexes for table `rombel`
--
ALTER TABLE `rombel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_rombel` (`kode_rombel`),
  ADD KEY `wali_kelas` (`wali_kelas`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `rombel_siswa`
--
ALTER TABLE `rombel_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `rombel_id` (`rombel_id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `rombel_id` (`rombel_id`);

--
-- Indexes for table `url_entries`
--
ALTER TABLE `url_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_slug` (`short_slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `allowed_routes`
--
ALTER TABLE `allowed_routes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `autoroute_activity_log`
--
ALTER TABLE `autoroute_activity_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `missing_routes`
--
ALTER TABLE `missing_routes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `qr_global_settings`
--
ALTER TABLE `qr_global_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `qr_settings`
--
ALTER TABLE `qr_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `rombel`
--
ALTER TABLE `rombel`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rombel_siswa`
--
ALTER TABLE `rombel_siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `url_entries`
--
ALTER TABLE `url_entries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_new` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `absensi_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `absensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  ADD CONSTRAINT `jurnal_lampiran_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_new` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  ADD CONSTRAINT `jurnal_new_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_new_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_new_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `qr_settings`
--
ALTER TABLE `qr_settings`
  ADD CONSTRAINT `qr_settings_url_id_foreign` FOREIGN KEY (`url_id`) REFERENCES `url_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  ADD CONSTRAINT `fk_rekap_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_rombel` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  ADD CONSTRAINT `rekap_absensi_harian_ibfk_1` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rekap_absensi_harian_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON UPDATE SET NULL,
  ADD CONSTRAINT `rekap_absensi_harian_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON UPDATE SET NULL;

--
-- Constraints for table `rombel`
--
ALTER TABLE `rombel`
  ADD CONSTRAINT `rombel_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON UPDATE SET NULL,
  ADD CONSTRAINT `rombel_wali_kelas_foreign` FOREIGN KEY (`wali_kelas`) REFERENCES `users` (`id`) ON UPDATE SET NULL;

--
-- Constraints for table `rombel_siswa`
--
ALTER TABLE `rombel_siswa`
  ADD CONSTRAINT `rombel_siswa_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rombel_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
