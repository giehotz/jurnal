<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixSiswaForeignKey extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Drop existing FK
        try {
            $this->db->query("ALTER TABLE siswa DROP FOREIGN KEY siswa_rombel_id_foreign");
        } catch (\Exception $e) { /* Ignore */
        }

        // 2. Modify rombel_id column to allow NULL
        // Note: We need to specify the full definition to change it
        $this->forge->modifyColumn('siswa', [
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Change to TRUE
            ],
        ]);

        // 3. Add FK with SET NULL
        // Using direct query to ensure specific naming and options
        $this->db->query("ALTER TABLE siswa ADD CONSTRAINT siswa_rombel_id_foreign_setnull FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        // Revert not implemented for fix
    }
}
