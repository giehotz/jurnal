<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin user
            [
                'nip'            => '123456789012345',
                'nama'           => 'Admin Jurnal',
                'email'          => 'admin@example.com',
                'password'       => password_hash('password123', PASSWORD_DEFAULT),
                'role'           => 'admin',
                'mata_pelajaran' => null,
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            
            // Kepala Sekolah user
            [
                'nip'            => '999999999999999',
                'nama'           => 'Dr. Bambang Sutrisno',
                'email'          => 'kepala.sekolah@example.com',
                'password'       => password_hash('password123', PASSWORD_DEFAULT),
                'role'           => 'kepala_sekolah',
                'mata_pelajaran' => null,
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            
            // Guru users
            [
                'nip'            => '111111111111111',
                'nama'           => 'Budi Santoso',
                'email'          => 'budi.santoso@example.com',
                'password'       => password_hash('password123', PASSWORD_DEFAULT),
                'role'           => 'guru',
                'mata_pelajaran' => 'Matematika',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nip'            => '222222222222222',
                'nama'           => 'Siti Rahayu',
                'email'          => 'siti.rahayu@example.com',
                'password'       => password_hash('password123', PASSWORD_DEFAULT),
                'role'           => 'guru',
                'mata_pelajaran' => 'Bahasa Indonesia',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nip'            => '333333333333333',
                'nama'           => 'Ahmad Fauzi',
                'email'          => 'ahmad.fauzi@example.com',
                'password'       => password_hash('password123', PASSWORD_DEFAULT),
                'role'           => 'guru',
                'mata_pelajaran' => 'Ilmu Pengetahuan Alam',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nip'            => '444444444444444',
                'nama'           => 'Dewi Lestari',
                'email'          => 'dewi.lestari@example.com',
                'password'       => password_hash('password123', PASSWORD_DEFAULT),
                'role'           => 'guru',
                'mata_pelajaran' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        // Check if data already exists before inserting
        foreach ($data as $user) {
            $exists = $this->db->table('users')
                ->where('nip', $user['nip'])
                ->orWhere('email', $user['email'])
                ->countAllResults();
            
            if ($exists == 0) {
                $this->db->table('users')->insert($user);
            }
        }
    }
}