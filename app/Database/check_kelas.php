<?php

// File untuk memeriksa data kelas
require_once '../vendor/autoload.php';
require_once '../app/Config/Paths.php';
require_once '../system/bootstrap.php';

// Menggunakan konfigurasi database CodeIgniter
$db = \Config\Database::connect();

echo "Memeriksa data kelas...\n";

// Ambil semua data kelas
$result = $db->table('kelas')
             ->select('id, kode_kelas, nama_kelas, tingkat')
             ->orderBy('CAST(tingkat AS UNSIGNED)', 'ASC')
             ->orderBy('kode_kelas', 'ASC')
             ->get();

echo "Data kelas:\n";
echo str_repeat('-', 50) . "\n";
printf("%-3s %-10s %-15s %-10s\n", "ID", "Kode", "Nama", "Tingkat");
echo str_repeat('-', 50) . "\n";

foreach ($result->getResult() as $row) {
    printf("%-3s %-10s %-15s %-10s\n", $row->id, $row->kode_kelas, $row->nama_kelas, $row->tingkat);
}

echo str_repeat('-', 50) . "\n";
echo "Total kelas: " . $result->getNumRows() . "\n";