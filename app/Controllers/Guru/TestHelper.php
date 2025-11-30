<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class TestHelper extends BaseController
{
    public function index()
    {
        // Memuat helper tanggal
        helper('tanggal');
        
        // Contoh tanggal untuk diuji
        $tanggal_ujian = '2025-10-23 15:30:00';
        
        // Format tanggal menggunakan helper
        $tanggal_terformat = format_tanggal_indonesia($tanggal_ujian);
        
        // Data untuk dikirim ke view
        $data = [
            'tanggal_asli' => $tanggal_ujian,
            'tanggal_terformat' => $tanggal_terformat
        ];
        
        return view('guru/test_helper', $data);
    }
}