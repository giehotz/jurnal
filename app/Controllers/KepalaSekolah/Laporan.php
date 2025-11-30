<?php

namespace App\Controllers\KepalaSekolah;

use App\Controllers\BaseController;

class Laporan extends BaseController
{
    public function index()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Laporan & Statistik',
            'active' => 'laporan',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }

    public function guru()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Laporan Guru',
            'active' => 'laporan',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }

    public function jurnal()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Laporan Jurnal',
            'active' => 'laporan',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }

    public function statistik()
    {
        // Check if user is logged in and is a kepala sekolah
        if (!session()->get('logged_in') || session()->get('role') != 'kepala_sekolah') {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Statistik Jurnal',
            'active' => 'laporan',
            'user' => [
                'nama' => session()->get('nama'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        return view('kepala_sekolah/layouts/template', $data);
    }
}