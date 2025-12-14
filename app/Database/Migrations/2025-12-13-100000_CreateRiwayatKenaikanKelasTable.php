<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatKenaikanKelasTable extends Migration
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
            ],
            'rombel_id_asal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'rombel_id_tujuan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tahun_ajaran_asal' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'tahun_ajaran_tujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'tanggal_proses' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'user_id' => [ // ID User yang melakukan proses
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('siswa_id');
        $this->forge->addKey('rombel_id_asal');
        $this->forge->addKey('rombel_id_tujuan');

        // Foreign Keys aman di-skip dulu untuk menghindari masalah constraint order, cukup logic level
        // Atau add constraint jika yakin urutan tabel aman. Kita pakai logic level saja biar fleksibel.

        $this->forge->createTable('riwayat_kenaikan_kelas');
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_kenaikan_kelas');
    }
}
