<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyJenisHariToVarchar extends Migration
{
    public function up()
    {
        // Modify master_kalender_pendidikan column jenis_hari
        // From ENUM to VARCHAR(100) to allow free text
        $this->forge->modifyColumn('master_kalender_pendidikan', [
            'jenis_hari' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'hari_efektif',
            ],
        ]);
    }

    public function down()
    {
        // Revert back to ENUM if needed (Caution: Data loss for custom strings)
        $this->forge->modifyColumn('master_kalender_pendidikan', [
            'jenis_hari' => [
                'type'       => 'ENUM',
                'constraint' => ['hari_efektif', 'libur_nasional', 'libur_sekolah', 'ujian', 'event', 'rapat'],
                'default'    => 'hari_efektif',
            ],
        ]);
    }
}
