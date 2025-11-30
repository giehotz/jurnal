<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\ConnectionInterface;

class CheckKelas extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Ambil semua data kelas
        $result = $db->table('kelas')
                     ->select('id, kode_kelas, nama_kelas, tingkat')
                     ->orderBy('CAST(tingkat AS UNSIGNED)', 'ASC')
                     ->orderBy('kode_kelas', 'ASC')
                     ->get();
        
        echo "<h2>Data Kelas</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Kode Kelas</th><th>Nama Kelas</th><th>Tingkat</th></tr>";
        
        foreach ($result->getResult() as $row) {
            echo "<tr>";
            echo "<td>{$row->id}</td>";
            echo "<td>{$row->kode_kelas}</td>";
            echo "<td>{$row->nama_kelas}</td>";
            echo "<td>{$row->tingkat}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<p>Total kelas: " . $result->getNumRows() . "</p>";
        
        // Tampilkan informasi debug
        echo "<h3>Debug Info</h3>";
        echo "<pre>";
        echo "Database: " . $db->getDatabase() . "\n";
        echo "Hostname: " . $db->hostname . "\n";
        echo "</pre>";
    }
}