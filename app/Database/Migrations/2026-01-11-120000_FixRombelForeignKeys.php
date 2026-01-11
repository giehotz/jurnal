<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixRombelForeignKeys extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Absensi
        try {
            $this->db->query("ALTER TABLE absensi DROP FOREIGN KEY absensi_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore if not exists */
        }
        $this->db->query("ALTER TABLE absensi ADD CONSTRAINT absensi_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");

        // 2. Jurnal New
        try {
            $this->db->query("ALTER TABLE jurnal_new DROP FOREIGN KEY jurnal_new_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore if not exists */
        }
        $this->db->query("ALTER TABLE jurnal_new ADD CONSTRAINT jurnal_new_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");

        // 3. Rombel Siswa
        try {
            $this->db->query("ALTER TABLE rombel_siswa DROP FOREIGN KEY rombel_siswa_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore if not exists */
        }
        $this->db->query("ALTER TABLE rombel_siswa ADD CONSTRAINT rombel_siswa_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");

        // 4. Rekap Absensi
        try {
            $this->db->query("ALTER TABLE rekap_absensi DROP FOREIGN KEY rekap_absensi_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore if not exists */
        }
        $this->db->query("ALTER TABLE rekap_absensi ADD CONSTRAINT rekap_absensi_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");

        // 5. Rekap Absensi Harian
        try {
            $this->db->query("ALTER TABLE rekap_absensi_harian DROP FOREIGN KEY rekap_absensi_harian_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore if not exists */
        }
        $this->db->query("ALTER TABLE rekap_absensi_harian ADD CONSTRAINT rekap_absensi_harian_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        // Revert to restrict/no cascade if needed, but for now we keep cascade as it's the intended behavior
    }
}
