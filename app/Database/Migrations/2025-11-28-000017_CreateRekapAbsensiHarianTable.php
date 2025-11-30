<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRekapAbsensiHarianTable extends Migration
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
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'total_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_hadir' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_sakit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_izin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_alfa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'persentase_kehadiran' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00',
                'null'       => false,
            ],
            'bulan' => [
                'type'       => 'TINYINT',
                'null'       => false,
            ],
            'tahun' => [
                'type' => 'YEAR',
                'null' => false,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['1', '2'],
                'default'    => '1',
                'null'       => false,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
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
        $this->forge->addUniqueKey(['tanggal', 'rombel_id'], 'unique_tanggal_rombel');
        $this->forge->addKey('rombel_id');
        $this->forge->addKey('tanggal', false, false, 'idx_tanggal');
        $this->forge->addKey(['bulan', 'tahun'], false, false, 'idx_bulan_tahun');
        $this->forge->addKey('semester', false, false, 'idx_semester');
        $this->forge->addKey('guru_id');
        $this->forge->addKey('mapel_id');
        
        $this->forge->addForeignKey('rombel_id', 'rombel', 'id', 'CASCADE', 'UPDATE CASCADE', 'rekap_absensi_harian_ibfk_1');
        $this->forge->addForeignKey('guru_id', 'users', 'id', 'SET NULL', 'UPDATE CASCADE', 'rekap_absensi_harian_ibfk_2');
        $this->forge->addForeignKey('mapel_id', 'mata_pelajaran', 'id', 'SET NULL', 'UPDATE CASCADE', 'rekap_absensi_harian_ibfk_3');
        
        $this->forge->createTable('rekap_absensi_harian');
    }

    public function down()
    {
        $this->forge->dropTable('rekap_absensi_harian');
    }
}
