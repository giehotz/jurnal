<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupHariEfektif extends Migration
{
    public function up()
    {
        // Exception-Based Calendar Implementation
        // Delete all records where jenis_hari is 'hari_efektif' from master_kalender_pendidikan
        // Deleting from master will eventually sync to guru view on next publish

        $this->db->table('master_kalender_pendidikan')
            ->where('jenis_hari', 'hari_efektif')
            ->delete();

        // Also cleanup from guru view just in case to be immediate
        // Although guru view usually follows master, it's safer to clean it too if it's not a view but a table (it is a table 'kalender_guru_view')
        $this->db->table('kalender_guru_view')
            ->where('jenis_hari', 'hari_efektif')
            ->delete();
    }

    public function down()
    {
        // Data deletion is irreversible
    }
}
