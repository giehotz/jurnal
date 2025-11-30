<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckRombel extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:check-rombel';
    protected $description = 'Check rombel data in database';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('=== ROMBEL YANG ADA DI DATABASE ===', 'green');
        CLI::newLine();
        
        $rombel = $db->table('rombel')
            ->select('id, kode_rombel, nama_rombel, tingkat')
            ->orderBy('tingkat, kode_rombel')
            ->get()
            ->getResultArray();
        
        foreach ($rombel as $r) {
            CLI::write(sprintf("ID: %-3s | Kode: %-6s | Nama: %-20s | Tingkat: %s", 
                $r['id'], 
                $r['kode_rombel'], 
                $r['nama_rombel'], 
                $r['tingkat']
            ));
        }
        
        CLI::newLine();
        CLI::write('=== TOTAL: ' . count($rombel) . ' rombel ===', 'yellow');
        
        // Check specifically for tingkat 4
        CLI::newLine(2);
        CLI::write('=== ROMBEL TINGKAT 4 ===', 'green');
        CLI::newLine();
        
        $rombel4 = $db->table('rombel')
            ->select('id, kode_rombel, nama_rombel, tingkat')
            ->where('tingkat', '4')
            ->get()
            ->getResultArray();
        
        if (empty($rombel4)) {
            CLI::error('TIDAK ADA ROMBEL TINGKAT 4!');
            CLI::error('Ini sebabnya error foreign key terjadi.');
        } else {
            foreach ($rombel4 as $r) {
                CLI::write(sprintf("ID: %-3s | Kode: %-6s | Nama: %-20s | Tingkat: %s", 
                    $r['id'], 
                    $r['kode_rombel'], 
                    $r['nama_rombel'], 
                    $r['tingkat']
                ), 'cyan');
            }
        }
    }
}
