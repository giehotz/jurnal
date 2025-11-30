<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRuanganTable extends Migration
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
            'nama_ruangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'kapasitas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 30,
                'null'       => false,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Kelas',
                'null'       => false,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('ruangan');
    }

    public function down()
    {
        $this->forge->dropTable('ruangan');
    }
}
