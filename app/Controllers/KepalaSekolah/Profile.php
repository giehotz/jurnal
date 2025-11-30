<?php

namespace App\Controllers\KepalaSekolah;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    public function index()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Profil Kepala Sekolah',
            'active' => 'profile',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role'),
                'nip' => session()->get('nip')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }

    public function edit()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Edit Profil',
            'active' => 'profile',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role'),
                'nip' => session()->get('nip')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }

    public function update()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        // In a real application, you would update the user profile here
        return redirect()->to('/kepala_sekolah/profile')->with('success', 'Profil berhasil diperbarui');
    }
}