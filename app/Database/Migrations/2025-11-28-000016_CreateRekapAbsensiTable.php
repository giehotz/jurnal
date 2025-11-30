<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRekapAbsensiTable extends Migration
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
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'guru_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'total_hadir' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
            ],
            'total_sakit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
            ],
            'total_izin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
            ],
            'total_alfa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
            ],
            'total_pertemuan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
                'unsigned'   => true,
                'null'       => false,
            ],
            'tahun' => [
                'type' => 'YEAR',
                'null' => false,
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['1', '2'],
                'null'       => false,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['tanggal', 'rombel_id', 'siswa_id', 'mapel_id'], 'unique_rekap');
        $this->forge->addKey('tanggal', false, false, 'idx_tanggal');
        $this->forge->addKey(['rombel_id', 'siswa_id'], false, false, 'idx_rombel_siswa');
        $this->forge->addKey(['bulan', 'tahun'], false, false, 'idx_bulan_tahun');
        $this->forge->addKey(['semester', 'tahun_ajaran'], false, false, 'idx_semester');
        $this->forge->addKey('rombel_id', false, false, 'fk_rekap_rombel');
        $this->forge->addKey('siswa_id', false, false, 'fk_rekap_siswa');
        $this->forge->addKey('guru_id', false, false, 'fk_rekap_guru');
        $this->forge->addKey('mapel_id', false, false, 'fk_rekap_mapel');
        
        $this->forge->addForeignKey('guru_id', 'users', 'id', 'CASCADE', 'UPDATE CASCADE', 'fk_rekap_guru');
        $this->forge->addForeignKey('mapel_id', 'mata_pelajaran', 'id', 'CASCADE', 'UPDATE CASCADE', 'fk_rekap_mapel');
        $this->forge->addForeignKey('rombel_id', 'rombel', 'id', 'CASCADE', 'UPDATE CASCADE', 'fk_rekap_rombel');
        $this->forge->addForeignKey('siswa_id', 'siswa', 'id', 'CASCADE', 'UPDATE CASCADE', 'fk_rekap_siswa');
        
        $this->forge->createTable('rekap_absensi');
    }

    public function down()
    {
        $this->forge->dropTable('rekap_absensi');
    }
}
