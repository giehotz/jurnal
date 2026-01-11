<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixRombelForeignKeysV2 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Rekap Absensi Harian (Explicit name found in migration)
        try {
            $this->db->query("ALTER TABLE rekap_absensi_harian DROP FOREIGN KEY rekap_absensi_harian_ibfk_1");
        } catch (\Exception $e) { /* Ignore if not exists */
        }

        // Try dropping the one added by V1 if exists (in case V1 partly succeeded)
        try {
            $this->db->query("ALTER TABLE rekap_absensi_harian DROP FOREIGN KEY rekap_absensi_harian_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore */
        }

        // Add back with Cascade
        $this->db->query("ALTER TABLE rekap_absensi_harian ADD CONSTRAINT rekap_absensi_harian_rombel_id_foreign_cascade FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");


        // 2. Rekap Absensi (Explicit name found in migration: fk_rekap_rombel)
        try {
            $this->db->query("ALTER TABLE rekap_absensi DROP FOREIGN KEY fk_rekap_rombel");
        } catch (\Exception $e) { /* Ignore */
        }

        try {
            $this->db->query("ALTER TABLE rekap_absensi DROP FOREIGN KEY rekap_absensi_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore */
        }

        $this->db->query("ALTER TABLE rekap_absensi ADD CONSTRAINT fk_rekap_rombel_cascade FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");


        // 3. Absensi (Auto-generated in migration)
        try {
            $this->db->query("ALTER TABLE absensi DROP FOREIGN KEY absensi_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore */
        }

        // Also try ibfk style just in case
        try {
            $this->db->query("ALTER TABLE absensi DROP FOREIGN KEY absensi_ibfk_1"); // Guessing
        } catch (\Exception $e) { /* Ignore */
        }

        // If V1 succeeded for absensi, it added 'absensi_rombel_id_foreign' with cascade. 
        // We will drop strict ones and ensure cascade exists.
        // To be safe, let's drop potential existing ones and add a definitive cascade one.

        try {
            $this->db->query("ALTER TABLE absensi ADD CONSTRAINT absensi_rombel_id_foreign_cascade FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");
        } catch (\Exception $e) {
            // If it fails, maybe it already exists or name conflict. 
            // If V1 worked, we are good. If V1 failed, we need this.
        }


        // 4. Jurnal New
        try {
            $this->db->query("ALTER TABLE jurnal_new DROP FOREIGN KEY jurnal_new_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore */
        }
        try {
            $this->db->query("ALTER TABLE jurnal_new DROP FOREIGN KEY jurnal_new_ibfk_1"); // Guessing
        } catch (\Exception $e) { /* Ignore */
        }

        try {
            $this->db->query("ALTER TABLE jurnal_new ADD CONSTRAINT jurnal_new_rombel_id_foreign_cascade FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");
        } catch (\Exception $e) {
        }


        // 5. Rombel Siswa
        try {
            $this->db->query("ALTER TABLE rombel_siswa DROP FOREIGN KEY rombel_siswa_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore */
        }

        try {
            $this->db->query("ALTER TABLE rombel_siswa ADD CONSTRAINT rombel_siswa_rombel_id_foreign_cascade FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");
        } catch (\Exception $e) {
        }
    }

    public function down()
    {
        // No down needed for fix
    }
}
