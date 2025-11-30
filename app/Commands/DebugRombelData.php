<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class DebugRombelData extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'debug:rombel-data';
    protected $description = 'Check settings and teachers data';

    public function run(array $params)
    {
        $db = Database::connect();

        // Check Settings
        $setting = $db->table('settings')->get()->getRowArray();
        CLI::write('Settings:', 'yellow');
        if ($setting) {
            foreach ($setting as $key => $value) {
                CLI::write("$key: $value");
            }
        } else {
            CLI::write('No settings found.', 'red');
        }

        CLI::newLine();

        // Check Teachers
        $teachers = $db->table('users')->where('role', 'guru')->get()->getResultArray();
        CLI::write('Teachers (Count: ' . count($teachers) . '):', 'yellow');
        foreach ($teachers as $teacher) {
            CLI::write("- ID: {$teacher['id']}, Name: {$teacher['nama']}, Email: {$teacher['email']}");
        }
    }
}
