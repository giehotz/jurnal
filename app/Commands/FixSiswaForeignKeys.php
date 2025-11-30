<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixSiswaForeignKeys extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'siswa:fix-keys';
    protected $description = 'Memperbaiki foreign key constraints pada tabel siswa untuk menggunakan rombel daripada kelas.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Memperbaiki foreign key constraints pada tabel siswa...', 'yellow');
        
        // 删除旧的外键约束（如果存在）
        try {
            $db->query("ALTER TABLE siswa DROP FOREIGN KEY siswa_kelas_id_foreign");
            CLI::write('Berhasil menghapus constraint siswa_kelas_id_foreign', 'green');
        } catch (\Exception $e) {
            CLI::write('Constraint siswa_kelas_id_foreign tidak ditemukan atau sudah dihapus', 'yellow');
        }
        
        try {
            $db->query("ALTER TABLE siswa DROP FOREIGN KEY siswa_ibfk_1");
            CLI::write('Berhasil menghapus constraint siswa_ibfk_1', 'green');
        } catch (\Exception $e) {
            CLI::write('Constraint siswa_ibfk_1 tidak ditemukan atau sudah dihapus', 'yellow');
        }
        
        // 添加新的外键约束，指向rombel表
        try {
            $db->query("ALTER TABLE siswa ADD CONSTRAINT siswa_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE ON UPDATE CASCADE");
            CLI::write('Berhasil menambahkan constraint siswa_rombel_id_foreign', 'green');
        } catch (\Exception $e) {
            CLI::write('Gagal menambahkan constraint siswa_rombel_id_foreign: ' . $e->getMessage(), 'red');
        }
        
        CLI::write('Proses selesai!', 'blue');
    }
}