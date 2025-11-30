<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTriggersAndProcedures extends Migration
{
    public function up()
    {
        // Procedure UpdateRekapAbsensiHarian
        $procedure = "
            CREATE PROCEDURE `UpdateRekapAbsensiHarian` (IN `p_tanggal` DATE, IN `p_rombel_id` INT)
            BEGIN
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
            END
        ";
        $this->db->query($procedure);

        // Trigger after_absensi_delete_rekap
        $this->db->query("
            CREATE TRIGGER `after_absensi_delete_rekap` AFTER DELETE ON `absensi` FOR EACH ROW 
            BEGIN
                CALL UpdateRekapAbsensiHarian(OLD.tanggal, OLD.rombel_id);
            END
        ");

        // Trigger after_absensi_insert_rekap
        $this->db->query("
            CREATE TRIGGER `after_absensi_insert_rekap` AFTER INSERT ON `absensi` FOR EACH ROW 
            BEGIN
                CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
            END
        ");

        // Trigger after_absensi_update_rekap
        $this->db->query("
            CREATE TRIGGER `after_absensi_update_rekap` AFTER UPDATE ON `absensi` FOR EACH ROW 
            BEGIN
                CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
            END
        ");
        
        // Additional triggers from SQL dump (with _fix suffix in dump, but we can consolidate or just use the main ones if they do the same thing. 
        // The dump has duplicates like `after_absensi_delete_rekap` and `after_absensi_delete_rekap_fix`.
        // I will implement the logic from the `_fix` versions as they seem more robust, but name them standardly or include them if they are distinct.
        // Looking at the SQL, the `_fix` versions call the same procedure. 
        // `after_absensi_update_rekap_fix` handles date/rombel changes. I will incorporate that logic into `after_absensi_update_rekap`.
        
        $this->db->query("DROP TRIGGER IF EXISTS `after_absensi_update_rekap`");
        $this->db->query("
            CREATE TRIGGER `after_absensi_update_rekap` AFTER UPDATE ON `absensi` FOR EACH ROW 
            BEGIN
                CALL UpdateRekapAbsensiHarian(NEW.tanggal, NEW.rombel_id);
                
                IF OLD.tanggal != NEW.tanggal OR OLD.rombel_id != NEW.rombel_id THEN
                    CALL UpdateRekapAbsensiHarian(OLD.tanggal, OLD.rombel_id);
                END IF;
            END
        ");
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS `after_absensi_delete_rekap`");
        $this->db->query("DROP TRIGGER IF EXISTS `after_absensi_insert_rekap`");
        $this->db->query("DROP TRIGGER IF EXISTS `after_absensi_update_rekap`");
        $this->db->query("DROP PROCEDURE IF EXISTS `UpdateRekapAbsensiHarian`");
    }
}
