-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2025 at 01:59 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateRekapAbsensiHarian` (IN `p_tanggal` DATE, IN `p_rombel_id` INT)   BEGIN
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
(1, NULL, '2025-11-26', 61, 31, 19, 222, 'sakit', '', NULL),
(2, NULL, '2025-11-26', 61, 31, 19, 239, 'hadir', '', NULL),
(3, NULL, '2025-11-26', 61, 31, 19, 221, 'izin', '', NULL),
(4, NULL, '2025-11-26', 61, 31, 19, 238, 'alfa', '', NULL),
(5, NULL, '2025-11-26', 61, 31, 19, 224, 'hadir', '', NULL),
(6, NULL, '2025-11-26', 61, 31, 19, 225, 'hadir', '', NULL),
(7, NULL, '2025-11-26', 61, 31, 19, 223, 'hadir', '', NULL),
(8, NULL, '2025-11-26', 61, 31, 19, 233, 'hadir', '', NULL),
(9, NULL, '2025-11-26', 61, 31, 19, 220, 'hadir', '', NULL),
(10, NULL, '2025-11-26', 61, 31, 19, 219, 'hadir', '', NULL),
(11, NULL, '2025-11-26', 61, 31, 19, 237, 'hadir', '', NULL),
(12, NULL, '2025-11-26', 61, 31, 19, 227, 'hadir', '', NULL),
(13, NULL, '2025-11-26', 61, 31, 19, 218, 'hadir', '', NULL),
(14, NULL, '2025-11-26', 61, 31, 19, 226, 'hadir', '', NULL),
(15, NULL, '2025-11-26', 61, 31, 19, 228, 'hadir', '', NULL),
(16, NULL, '2025-11-26', 61, 31, 19, 235, 'hadir', '', NULL),
(17, NULL, '2025-11-26', 61, 31, 19, 229, 'hadir', '', NULL),
(18, NULL, '2025-11-26', 61, 31, 19, 230, 'hadir', '', NULL),
(19, NULL, '2025-11-26', 61, 31, 19, 231, 'hadir', '', NULL),
(20, NULL, '2025-11-26', 61, 31, 19, 232, 'hadir', '', NULL),
(21, NULL, '2025-11-26', 61, 31, 19, 236, 'hadir', '', NULL),
(22, NULL, '2025-11-26', 61, 31, 19, 234, 'hadir', '', NULL),
(23, NULL, '2025-11-26', 61, 31, 19, 222, 'hadir', '', NULL),
(24, NULL, '2025-11-26', 61, 31, 19, 239, 'hadir', '', NULL),
(25, NULL, '2025-11-26', 61, 31, 19, 221, 'hadir', '', NULL),
(26, NULL, '2025-11-26', 61, 31, 19, 238, 'hadir', '', NULL),
(27, NULL, '2025-11-26', 61, 31, 19, 224, 'hadir', '', NULL),
(28, NULL, '2025-11-26', 61, 31, 19, 225, 'hadir', '', NULL),
(29, NULL, '2025-11-26', 61, 31, 19, 223, 'hadir', '', NULL),
(30, NULL, '2025-11-26', 61, 31, 19, 233, 'hadir', '', NULL),
(31, NULL, '2025-11-26', 61, 31, 19, 220, 'hadir', '', NULL),
(32, NULL, '2025-11-26', 61, 31, 19, 219, 'hadir', '', NULL),
(33, NULL, '2025-11-26', 61, 31, 19, 237, 'hadir', '', NULL),
(34, NULL, '2025-11-26', 61, 31, 19, 227, 'hadir', '', NULL),
(35, NULL, '2025-11-26', 61, 31, 19, 218, 'hadir', '', NULL),
(36, NULL, '2025-11-26', 61, 31, 19, 226, 'hadir', '', NULL),
(37, NULL, '2025-11-26', 61, 31, 19, 228, 'hadir', '', NULL),
(38, NULL, '2025-11-26', 61, 31, 19, 235, 'hadir', '', NULL),
(39, NULL, '2025-11-26', 61, 31, 19, 229, 'hadir', '', NULL),
(40, NULL, '2025-11-26', 61, 31, 19, 230, 'hadir', '', NULL),
(41, NULL, '2025-11-26', 61, 31, 19, 231, 'hadir', '', NULL),
(42, NULL, '2025-11-26', 61, 31, 19, 232, 'hadir', '', NULL),
(43, NULL, '2025-11-26', 61, 31, 19, 236, 'hadir', '', NULL),
(44, NULL, '2025-11-26', 61, 31, 19, 234, 'hadir', '', NULL);

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
CREATE TRIGGER `after_absensi_delete_rekap_fix` AFTER DELETE ON `absensi` FOR EACH ROW BEGIN
    -- Panggil prosedur menggunakan OLD data yang dihapus
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
CREATE TRIGGER `after_absensi_insert_rekap_fix` AFTER INSERT ON `absensi` FOR EACH ROW BEGIN
    -- Panggil prosedur yang sudah diperbaiki
    CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_absensi_update_rekap` AFTER UPDATE ON `absensi` FOR EACH ROW BEGIN
                CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_absensi_update_rekap_fix` AFTER UPDATE ON `absensi` FOR EACH ROW BEGIN
    -- Panggil prosedur untuk NEW data
    CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
    
    -- Jika tanggal atau rombel_id berubah, panggil juga untuk OLD data
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
  `module` varchar(100) NOT NULL,
  `role` enum('admin','super_admin','guru','siswa') NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `allowed_routes`
--

INSERT INTO `allowed_routes` (`id`, `module`, `role`, `enabled`) VALUES
(1, 'siswa', 'admin', 1),
(2, 'guru', 'admin', 1),
(3, 'kelas', 'admin', 1),
(4, 'rombel', 'admin', 1),
(5, 'ruangan', 'admin', 1),
(6, 'absensi', 'admin', 1),
(7, 'jurnal', 'admin', 1),
(8, 'mata_pelajaran', 'admin', 1),
(9, 'settings', 'admin', 1),
(10, 'kepala_sekolah', 'admin', 1),
(11, 'jurnal', 'guru', 1),
(12, 'absensi', 'guru', 1),
(13, 'kelas', 'guru', 1),
(14, 'siswa', 'guru', 1),
(15, 'rombel', 'guru', 1);

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
(1, 1, '::1', 'admin', 'index.php/admin', '', '', 'ignored', '2025-11-23 07:09:02'),
(2, 1, '::1', 'admin', 'index.php/admin', '', '', 'ignored', '2025-11-23 07:09:13'),
(3, 1, '::1', 'admin', 'admin/users/download_template', 'Admin', 'users', 'blocked', '2025-11-23 09:47:52'),
(4, 1, '::1', 'admin', 'admin/users/download_template', 'Admin', 'users', 'blocked', '2025-11-23 09:47:55');

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
(28, 31, '2025-11-26', 61, 19, 2, 'fasfafdaw', 3, NULL, 19, 'asafdsfafa', 'published', '2025-11-26 11:17:52', '2025-11-26 11:17:52');

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
(2, 'Sipulloh, M.Pd', '197005272007011022', '2025-11-23 13:03:20', '2025-11-23 13:03:20');

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
(19, 'IPAS', 'Ilmu Pengetahuan Alam dan Sosial', '', '2025-11-23 16:05:49', '2025-11-23 16:05:49'),
(20, 'PJOK', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', '', '2025-11-23 16:06:05', '2025-11-23 16:06:05');

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
(15, '2025-10-21-085841', 'App\\Database\\Migrations\\CreateKelasTable', 'default', 'App', 1763815519, 1),
(16, '2025-10-23-111155', 'App\\Database\\Migrations\\CreateJurnalNewTable', 'default', 'App', 1763876940, 2),
(17, '2025-10-23-111158', 'App\\Database\\Migrations\\CreateJurnalLampiranTable', 'default', 'App', 1763876940, 2),
(18, '2025-11-18-113300', 'App\\Database\\Migrations\\CreateSiswaTable', 'default', 'App', 1763876940, 2),
(19, '2025-11-18-113400', 'App\\Database\\Migrations\\CreateAbsensiTable', 'default', 'App', 1763876940, 2),
(20, '2025-11-18-200000', 'App\\Database\\Migrations\\CreateRombelTable', 'default', 'App', 1763877310, 3),
(21, '2025-11-18-200100', 'App\\Database\\Migrations\\CreateRombelSiswaTable', 'default', 'App', 1764148835, 4),
(22, '2025-11-18-200200', 'App\\Database\\Migrations\\ModifyKelasTableForRombel', 'default', 'App', 1764148835, 4),
(23, '2025-11-18-200300', 'App\\Database\\Migrations\\ModifySiswaTableForRombel', 'default', 'App', 1764148835, 4),
(24, '2025-11-18-210000', 'App\\Database\\Migrations\\ModifyJurnalNewTable', 'default', 'App', 1764148836, 4),
(25, '2025-11-18-210100', 'App\\Database\\Migrations\\AddForeignKeyConstraints', 'default', 'App', 1764148837, 4),
(26, '2025-11-24-000001', 'App\\Database\\Migrations\\AddFieldsToAbsensiTable', 'default', 'App', 1764148886, 5),
(27, '2025-11-24-000002', 'App\\Database\\Migrations\\AddGuruMapelToAbsensiTable', 'default', 'App', 1764148887, 5),
(28, '2025-11-26-091720', 'App\\Database\\Migrations\\CreateRekapAbsensiHarian', 'default', 'App', 1764155778, 6),
(29, '2025-11-26-182000', 'App\\Database\\Migrations\\FixJurnalNewForeignKey', 'default', 'App', 1764155779, 6),
(30, '2025-11-26-223500', 'App\\Database\\Migrations\\FixUsersIdAutoIncrement', 'default', 'App', 1764171447, 7),
(31, '2025-11-26-224000', 'App\\Database\\Migrations\\AddRekapAbsensiProcedureAndTriggers', 'default', 'App', 1764171447, 7);

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
  `semester` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_ajaran` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekap_absensi`
--

INSERT INTO `rekap_absensi` (`id`, `tanggal`, `rombel_id`, `siswa_id`, `guru_id`, `mapel_id`, `total_hadir`, `total_sakit`, `total_izin`, `total_alfa`, `total_pertemuan`, `persentase_kehadiran`, `bulan`, `tahun`, `semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(67, '2025-11-26', 61, 222, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(68, '2025-11-26', 61, 239, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(69, '2025-11-26', 61, 221, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(70, '2025-11-26', 61, 238, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(71, '2025-11-26', 61, 224, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(72, '2025-11-26', 61, 225, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(73, '2025-11-26', 61, 223, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(74, '2025-11-26', 61, 233, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(75, '2025-11-26', 61, 220, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(76, '2025-11-26', 61, 219, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(77, '2025-11-26', 61, 237, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(78, '2025-11-26', 61, 227, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(79, '2025-11-26', 61, 218, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(80, '2025-11-26', 61, 226, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(81, '2025-11-26', 61, 228, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(82, '2025-11-26', 61, 235, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:45'),
(83, '2025-11-26', 61, 229, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:46'),
(84, '2025-11-26', 61, 230, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:46'),
(85, '2025-11-26', 61, 231, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:46'),
(86, '2025-11-26', 61, 232, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:46'),
(87, '2025-11-26', 61, 236, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:46'),
(88, '2025-11-26', 61, 234, 31, 19, 1, 0, 0, 0, 1, '100.00', 11, 2025, '1', '2025/2026', '2025-11-26 10:13:53', '2025-11-26 11:26:46');

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
  `semester` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `tahun_ajaran` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekap_absensi_harian`
--

INSERT INTO `rekap_absensi_harian` (`id`, `tanggal`, `rombel_id`, `guru_id`, `mapel_id`, `total_siswa`, `total_hadir`, `total_sakit`, `total_izin`, `total_alfa`, `persentase_kehadiran`, `bulan`, `tahun`, `semester`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(25, '2025-11-26', 61, 31, 19, 44, 41, 1, 1, 1, '93.18', 11, 2025, '1', '2025/2026', '2025-11-26 17:13:53', '2025-11-26 18:26:46');

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
(61, '1A', 'Kelas 1A', '1', NULL, 31, 1, NULL, 'Kurikulum Merdeka', 'Reguler', 'Pagi', '2025/2026', '1', 30, 1, '2025-11-22 12:54:50', '2025-11-26 10:12:01'),
(62, '1B', 'Kelas 1B', '1', NULL, 31, 2, NULL, 'Kurikulum Merdeka', 'Reguler', 'Pagi', '2025/2026', '1', 30, 1, '2025-11-22 12:54:50', '2025-11-23 06:31:00'),
(63, '1C', 'Kelas 1C', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(64, '2A', 'Kelas 2A', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(65, '2B', 'Kelas 2B', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(66, '2C', 'Kelas 2C', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(67, '3A', 'Kelas 3A', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(68, '3B', 'Kelas 3B', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(69, '3C', 'Kelas 3C', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(70, '4A', 'Kelas 4A', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(71, '4B', 'Kelas 4B', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(72, '4C', 'Kelas 4C', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(73, '5A', 'Kelas 5A', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(74, '5B', 'Kelas 5B', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(75, '5C', 'Kelas 5C', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(76, '6A', 'Kelas 6A', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(77, '6B', 'Kelas 6B', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(78, '6C', 'Kelas 6C', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(79, '7A', 'Kelas 7A', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(80, '7B', 'Kelas 7B', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(81, '7C', 'Kelas 7C', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(82, '8A', 'Kelas 8A', '8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(83, '8B', 'Kelas 8B', '8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(84, '8C', 'Kelas 8C', '8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(85, '9A', 'Kelas 9A', '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(86, '9B', 'Kelas 9B', '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50'),
(87, '9C', 'Kelas 9C', '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024/2025', '1', 36, 1, '2025-11-22 12:54:50', '2025-11-22 12:54:50');

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
(1, 'Ruang 1A', 30, 'Kelas', '', '2025-11-23 11:17:45', '2025-11-26 17:11:35'),
(2, 'Ruang Kelas 1b', 30, 'Kelas', '', '2025-11-23 13:30:04', '2025-11-23 13:30:04');

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
(2, 'MIN 2 Tanggamus', '2025/2026', 'ganjil', 'Sipulloh, M.Pd', '197005272007011022', '1763814882_6222f1c6cdce701b06cc.png', '', 'SD/MI', 0, '2025-11-22 19:34:42', '2025-11-23 16:48:21');

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
(218, '12345', '3163480661', 'KIANDRA FEBRIANDA', 'L', '2016-02-13', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(219, '12346', '3163377331', 'JELITA FAHIRA', 'P', '2016-04-04', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(220, '12347', '3161247958', 'FITRI ADELIA PUTRI', 'P', '2016-07-06', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(221, '12348', '154347758', 'AL ABROR MUZAKIR', 'L', '2015-11-26', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(222, '12349', '3156042903', 'AHMAD FATHAN FAUDI', 'L', '2015-07-22', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(223, '12350', '165983194', 'ARSYILA INTAN AZKADINA', 'P', '2016-04-02', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(224, '12351', '3158378685', 'ALISHA MAHIRA AKMALUDIN', 'P', '2015-09-30', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(225, '12352', '3150808570', 'ANDRA MOISSANI MANGGALA', 'L', '2015-10-29', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(226, '12353', '3163149108', 'MILA HANIFA', 'P', '2016-03-27', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(227, '12354', '3153139646', 'KHAYLA ALMIRA MARITZA', 'P', '2015-02-22', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(228, '12355', '3154408477', 'MUHAMMAD ANGGER AN-NUR GHANI', 'L', '2015-12-06', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(229, '12356', '3154763778', 'MUHAMMAD IMAM ROFI\'I', 'L', '2015-11-10', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(230, '12357', '3169194284', 'MUHAMMAD YUAN PRATAMA', 'L', '2016-01-27', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(231, '12358', '3161102896', 'PATRIA PRATAMA', 'L', '2016-01-21', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(232, '12359', '3151741396', 'SASKIA NURMALA', 'P', '2015-11-16', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(233, '12360', '3163224577', 'EARLYTA SALSA BILA', 'P', '2016-07-15', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(234, '12361', '3154393457', 'ZHAFIF SANDI IRAWAN', 'L', '2015-10-08', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(235, '12362', '3155839416', 'MUHAMMAD HABIBI AL KAHFI', 'L', '2015-12-25', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(236, '12363', '3168287428', 'TRISTAN ALVINO', 'L', '2016-01-17', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(237, '12364', '3164223329', 'KENZY ALFIERO', 'L', '2016-02-25', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(238, '12365', '3154259546', 'ALESHA BARIZAH', 'P', '2015-12-13', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08'),
(239, '12366', '3151748733', 'AJODHA MAHARANI ARIFA', 'P', '2015-09-30', 61, 1, '2025-11-23 04:50:08', '2025-11-23 04:50:08');

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
(1, '123456789012345', 'Admin Jurnal', 'admin@example.com', '$2y$10$LylQchA9eQZAATuoYnOSZ.4KuB0/.aFvSsWQ8hIUwbdj0mPq6WUfC', 'admin', NULL, '1763890333_c6abcc2e05433ea76545.png', NULL, '', '', 1, NULL, '2025-10-21 01:30:41', '2025-11-23 09:32:13'),
(31, '', 'Guru Budi', 'guru@example.com', '$2y$10$ZPUG0vuEYh/uvd3ybKCokegA38FpoNMZTGK.YfC/U6cOWynyWfa42', 'guru', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2025-11-23 04:25:20', '2025-11-23 11:11:42');

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
  ADD KEY `absensi_rombel_id_foreign` (`rombel_id`);

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
  ADD KEY `jurnal_lampiran_jurnal_id_foreign` (`jurnal_id`);

--
-- Indexes for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurnal_new_user_id_foreign` (`user_id`),
  ADD KEY `jurnal_new_mapel_id_foreign` (`mapel_id`),
  ADD KEY `jurnal_new_rombel_id_foreign` (`rombel_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kode_kelas` (`kode_kelas`);

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
-- Indexes for table `missing_routes`
--
ALTER TABLE `missing_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uri_index` (`uri`),
  ADD KEY `status_index` (`status`);

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
  ADD KEY `rombel_ibfk_1` (`wali_kelas`),
  ADD KEY `rombel_ruangan_id_foreign` (`ruangan_id`);

--
-- Indexes for table `rombel_siswa`
--
ALTER TABLE `rombel_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rombel_siswa_siswa_id_foreign` (`siswa_id`),
  ADD KEY `rombel_siswa_rombel_id_foreign` (`rombel_id`);

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
  ADD UNIQUE KEY `nis_unique` (`nis`),
  ADD KEY `kelas_id` (`rombel_id`);

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
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `allowed_routes`
--
ALTER TABLE `allowed_routes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `autoroute_activity_log`
--
ALTER TABLE `autoroute_activity_log`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `kepala_sekolah`
--
ALTER TABLE `kepala_sekolah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `missing_routes`
--
ALTER TABLE `missing_routes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `rombel`
--
ALTER TABLE `rombel`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rombel_siswa`
--
ALTER TABLE `rombel_siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_new` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `absensi_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `absensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_lampiran`
--
ALTER TABLE `jurnal_lampiran`
  ADD CONSTRAINT `jurnal_lampiran_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_new` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jurnal_new`
--
ALTER TABLE `jurnal_new`
  ADD CONSTRAINT `jurnal_new_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_new_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jurnal_new_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  ADD CONSTRAINT `fk_rekap_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_rombel` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rekap_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rekap_absensi_harian`
--
ALTER TABLE `rekap_absensi_harian`
  ADD CONSTRAINT `rekap_absensi_harian_ibfk_1` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rekap_absensi_harian_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rekap_absensi_harian_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `rombel`
--
ALTER TABLE `rombel`
  ADD CONSTRAINT `rombel_ibfk_1` FOREIGN KEY (`wali_kelas`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rombel_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rombel_wali_kelas_foreign` FOREIGN KEY (`wali_kelas`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `rombel_siswa`
--
ALTER TABLE `rombel_siswa`
  ADD CONSTRAINT `rombel_siswa_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rombel_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siswa_rombel_id_foreign` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
