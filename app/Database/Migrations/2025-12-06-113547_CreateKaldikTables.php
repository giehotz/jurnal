<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKaldikTables extends Migration
{
    public function up()
    {
        // 1. Master Kalender Pendidikan
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 13,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '9', // 2024/2025
            ],
            'semester' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jenis_hari' => [
                'type'       => 'ENUM',
                'constraint' => ['hari_efektif', 'libur_nasional', 'libur_sekolah', 'ujian', 'event', 'rapat'],
                'default'    => 'hari_efektif',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'warna_kode' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
                'null'       => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Assuming users.id is INT UNSIGNED
            ],
            'lembaga_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
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
        // $this->forge->addUniqueKey(['tahun_ajaran', 'semester', 'tanggal']); // Might be too strict if multiple events per day? Reqs implied unique.
        $this->forge->createTable('master_kalender_pendidikan');

        // 2. Kalender Guru View
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 13,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'master_kalender_id' => [
                'type'       => 'BIGINT',
                'constraint' => 13,
                'unsigned'   => true,
            ],
            'guru_id' => [
                'type'       => 'INT', // Assuming users.id matches this type
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jenis_hari' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'mata_pelajaran_terjadwal' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
                'null'       => true,
            ],
            'semester' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
            ],
            'lembaga_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('master_kalender_id', 'master_kalender_pendidikan', 'id', 'CASCADE', 'CASCADE');
        // Note: Assuming users table exists. If referencing users(id), make sure key type matches.
        // Usually users.id is INT/BIGINT UNSIGNED.
        $this->forge->createTable('kalender_guru_view');

        // 3. Kalender Publish Log
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 13,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
                'null'       => true,
            ],
            'semester' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
            ],
            'published_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'published_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published', 'archived'],
                'default'    => 'draft',
            ],
            'lembaga_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kalender_publish_log');
    }

    public function down()
    {
        $this->forge->dropTable('kalender_publish_log');
        $this->forge->dropTable('kalender_guru_view');
        $this->forge->dropTable('master_kalender_pendidikan');
    }
}
