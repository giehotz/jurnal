<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;

class AddDummyTeacher extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'db:add-dummy-teacher';
    protected $description = 'Add a dummy teacher for testing';

    public function run(array $params)
    {
        $model = new UserModel();
        
        // Check if teacher exists
        if ($model->where('email', 'guru@example.com')->first()) {
            CLI::write('Teacher already exists.', 'yellow');
            return;
        }

        $data = [
            'nama' => 'Guru Budi',
            'email' => 'guru@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'role' => 'guru',
            'is_active' => 1
        ];

        if ($model->save($data)) {
            CLI::write('Dummy teacher added.', 'green');
        } else {
            CLI::write('Failed to add teacher.', 'red');
        }
    }
}
