<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterJenisHari extends Migration
{
    public function up()
    {
        // Table Structure
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'warna' => [
                'type'       => 'VARCHAR',
                'constraint' => '7', // Hex color #RRGGBB
                'default'    => '#6c757d',
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
        $this->forge->createTable('master_jenis_hari');

        // Seeding Initial Data
        $data = [
            ['nama' => 'hari_efektif',   'warna' => '#28a745', 'keterangan' => 'Hari Belajar Efektif'],
            ['nama' => 'libur_nasional', 'warna' => '#dc3545', 'keterangan' => 'Libur Nasional / Cuti Bersama'],
            ['nama' => 'libur_sekolah',  'warna' => '#fd7e14', 'keterangan' => 'Libur Semester / Sekolah'],
            ['nama' => 'ujian',          'warna' => '#ffc107', 'keterangan' => 'PTS / PAS / Ujian Sekolah'],
            ['nama' => 'event',          'warna' => '#007bff', 'keterangan' => 'Kegiatan Sekolah / Lomba'],
            ['nama' => 'rapat',          'warna' => '#6f42c1', 'keterangan' => 'Rapat Guru / Dinas'],
            ['nama' => 'bagi_rapor',     'warna' => '#17a2b8', 'keterangan' => 'Pembagian Rapor'],
        ];

        $this->db->table('master_jenis_hari')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('master_jenis_hari');
    }
}
