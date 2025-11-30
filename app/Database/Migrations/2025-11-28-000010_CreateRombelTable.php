<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRombelTable extends Migration
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
            'kode_rombel' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false,
            ],
            'nama_rombel' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['1','2','3','4','5','6','7','8','9','10','11','12'],
                'null'       => false,
            ],
            'jurusan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'wali_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'ruangan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nama_ruangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'kurikulum' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'jenis_rombel' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'waktu_mengajar' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
                'null'       => false,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['1', '2'],
                'default'    => '1',
                'null'       => false,
            ],
            'kapasitas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 36,
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 1,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_rombel');
        $this->forge->addKey('wali_kelas');
        $this->forge->addKey('ruangan_id');
        
        $this->forge->addForeignKey('wali_kelas', 'users', 'id', 'SET NULL', 'UPDATE CASCADE');
        $this->forge->addForeignKey('ruangan_id', 'ruangan', 'id', 'SET NULL', 'UPDATE CASCADE');
        
        $this->forge->createTable('rombel');
    }

    public function down()
    {
        $this->forge->dropTable('rombel');
    }
}
