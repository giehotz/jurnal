<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TimeTest extends BaseController
{
    public function index()
    {
        // Menampilkan waktu saat ini dalam berbagai format
        echo "Waktu Server Saat Ini:<br>";
        echo "Tanggal dan Waktu: " . date('Y-m-d H:i:s') . "<br>";
        echo "Zona Waktu Server: " . date_default_timezone_get() . "<br>";
        echo "Waktu UTC: " . gmdate('Y-m-d H:i:s') . "<br>";
        echo "<br>";
        
        // Menampilkan waktu dengan fungsi CodeIgniter
        echo "Dengan Fungsi CodeIgniter:<br>";
        echo "Tanggal dan Waktu: " . now() . "<br>";
        echo "Zona Waktu Aplikasi: " . app_timezone() . "<br>";
        echo "Waktu dengan Zona Waktu Aplikasi: " . date('Y-m-d H:i:s', now()) . "<br>";
    }
}