<?php

// File untuk memperbaiki data kelas
require_once 'vendor/autoload.php';

// Menggunakan konfigurasi database CodeIgniter
$db = \Config\Database::connect();

echo "Memperbaiki data kelas...\n";

// Update tingkat kelas
$updates = [
    ['kode' => '1A', 'tingkat' => '1'],
    ['kode' => '1B', 'tingkat' => '1'],
    ['kode' => '1C', 'tingkat' => '1'],
    ['kode' => '2A', 'tingkat' => '2'],
    ['kode' => '2B', 'tingkat' => '2'],
    ['kode' => '2C', 'tingkat' => '2'],
    ['kode' => '3A', 'tingkat' => '3'],
    ['kode' => '3B', 'tingkat' => '3'],
    ['kode' => '4A', 'tingkat' => '4'],
    ['kode' => '4B', 'tingkat' => '4'],
    ['kode' => '4C', 'tingkat' => '4'],
    ['kode' => '5A', 'tingkat' => '5'],
    ['kode' => '5B', 'tingkat' => '5'],
    ['kode' => '5C', 'tingkat' => '5'],
    ['kode' => '6A', 'tingkat' => '6'],
    ['kode' => '6B', 'tingkat' => '6'],
];

foreach ($updates as $update) {
    $db->table('kelas')
       ->where('kode_kelas', $update['kode'])
       ->update(['tingkat' => $update['tingkat']]);
    echo "Memperbarui kelas {$update['kode']} menjadi tingkat {$update['tingkat']}\n";
}

// Tambahkan kelas baru
$kelasBaru = [
    [
        'kode_kelas' => '3C',
        'nama_kelas' => 'Kelas 3C',
        'tingkat' => '3',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '6C',
        'nama_kelas' => 'Kelas 6C',
        'tingkat' => '6',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '7A',
        'nama_kelas' => 'Kelas 7A',
        'tingkat' => '7',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '7B',
        'nama_kelas' => 'Kelas 7B',
        'tingkat' => '7',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '7C',
        'nama_kelas' => 'Kelas 7C',
        'tingkat' => '7',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '8A',
        'nama_kelas' => 'Kelas 8A',
        'tingkat' => '8',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '8B',
        'nama_kelas' => 'Kelas 8B',
        'tingkat' => '8',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '8C',
        'nama_kelas' => 'Kelas 8C',
        'tingkat' => '8',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '9A',
        'nama_kelas' => 'Kelas 9A',
        'tingkat' => '9',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '9B',
        'nama_kelas' => 'Kelas 9B',
        'tingkat' => '9',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '9C',
        'nama_kelas' => 'Kelas 9C',
        'tingkat' => '9',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '10A',
        'nama_kelas' => 'Kelas 10A',
        'tingkat' => '10',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '10B',
        'nama_kelas' => 'Kelas 10B',
        'tingkat' => '10',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '10C',
        'nama_kelas' => 'Kelas 10C',
        'tingkat' => '10',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '11A',
        'nama_kelas' => 'Kelas 11A',
        'tingkat' => '11',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '11B',
        'nama_kelas' => 'Kelas 11B',
        'tingkat' => '11',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '11C',
        'nama_kelas' => 'Kelas 11C',
        'tingkat' => '11',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '12A',
        'nama_kelas' => 'Kelas 12A',
        'tingkat' => '12',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '12B',
        'nama_kelas' => 'Kelas 12B',
        'tingkat' => '12',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ],
    [
        'kode_kelas' => '12C',
        'nama_kelas' => 'Kelas 12C',
        'tingkat' => '12',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]
];

$builder = $db->table('kelas');
foreach ($kelasBaru as $kelas) {
    // Cek apakah kelas sudah ada
    $existing = $builder->where('kode_kelas', $kelas['kode_kelas'])->get()->getRow();
    if (!$existing) {
        $builder->insert($kelas);
        echo "Menambahkan kelas: {$kelas['kode_kelas']}\n";
    }
}

echo "Selesai memperbaiki data kelas.\n";