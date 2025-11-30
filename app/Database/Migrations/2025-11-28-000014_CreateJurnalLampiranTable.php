<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJurnalLampiranTable extends Migration
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
            'jurnal_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => false,
            ],
            'tipe_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('jurnal_id');
        
        $this->forge->addForeignKey('jurnal_id', 'jurnal_new', 'id', 'CASCADE', 'UPDATE CASCADE');
        
        $this->forge->createTable('jurnal_lampiran');
    }

    public function down()
    {
        $this->forge->dropTable('jurnal_lampiran');
    }
}
