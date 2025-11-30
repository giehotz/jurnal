<?php

namespace App\Controllers\KepalaSekolah;

use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    public function index()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Monitoring Jurnal',
            'active' => 'monitoring',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }

    public function detail($id = null)
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Detail Monitoring Jurnal',
            'active' => 'monitoring',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }
}