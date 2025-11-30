<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRombelSiswaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
                'null'       => false,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['1', '2'],
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('siswa_id');
        $this->forge->addKey('rombel_id');
        
        $this->forge->addForeignKey('siswa_id', 'siswa', 'id', 'CASCADE', 'UPDATE CASCADE');
        $this->forge->addForeignKey('rombel_id', 'rombel', 'id', 'CASCADE', 'UPDATE CASCADE');
        
        $this->forge->createTable('rombel_siswa');
    }

    public function down()
    {
        $this->forge->dropTable('rombel_siswa');
    }
}
