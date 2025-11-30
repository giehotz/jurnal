<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class ManualRuanganMigration extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'db:manual-ruangan';
    protected $description = 'Manually create ruangan table and add column';

    public function run(array $params)
    {
        $db = Database::connect();
        $forge = Database::forge();

        // Create Ruangan Table
        if (!$db->tableExists('ruangan')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama_ruangan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'kapasitas' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 30,
                ],
                'jenis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'default'    => 'Kelas',
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
            $forge->addKey('id', true);
            $forge->createTable('ruangan');
            CLI::write('Table ruangan created.', 'green');
        } else {
            CLI::write('Table ruangan already exists.', 'yellow');
        }

        // Add ruangan_id to rombel
        if (!$db->fieldExists('ruangan_id', 'rombel')) {
            $fields = [
                'ruangan_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'wali_kelas',
                ],
            ];
            $forge->addColumn('rombel', $fields);
            
            // Add FK manually via SQL because forge might fail if index exists
            try {
                $db->query('ALTER TABLE rombel ADD CONSTRAINT rombel_ruangan_id_foreign FOREIGN KEY (ruangan_id) REFERENCES ruangan(id) ON DELETE SET NULL ON UPDATE CASCADE');
                CLI::write('Foreign key added.', 'green');
            } catch (\Exception $e) {
                CLI::write('Error adding FK: ' . $e->getMessage(), 'red');
            }
            
            CLI::write('Column ruangan_id added.', 'green');
        } else {
            CLI::write('Column ruangan_id already exists.', 'yellow');
        }
    }
}
