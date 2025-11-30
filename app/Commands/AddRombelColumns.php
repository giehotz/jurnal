<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class AddRombelColumns extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'db:add-rombel-columns';
    protected $description = 'Add missing columns to rombel table';

    public function run(array $params)
    {
        $db = Database::connect();
        $forge = Database::forge();

        $fields = [
            'nama_ruangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'wali_kelas',
            ],
            'kurikulum' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'nama_ruangan',
            ],
            'jenis_rombel' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'kurikulum',
            ],
            'waktu_mengajar' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'jenis_rombel',
            ],
        ];

        try {
            $forge->addColumn('rombel', $fields);
            CLI::write('Columns added successfully.', 'green');
        } catch (\Exception $e) {
            CLI::write('Error adding columns: ' . $e->getMessage(), 'red');
        }
    }
}
