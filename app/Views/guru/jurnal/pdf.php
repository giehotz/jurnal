<?php

// --- Helper Functions ---
// Fungsi ini untuk membersihkan dan memformat teks dari database
function display_text($text) {
    // esc() untuk keamanan (mencegah XSS)
    // nl2br() untuk mengubah newline (\n) menjadi tag <br>
    echo nl2br(esc($text ?? ''));
}

// Fungsi untuk menampilkan badge status
function render_status_badge($status) {
    if ($status == 'published') {
        return '<span class="badge badge-success">Published</span>';
    }
    return '<span class="badge badge-warning">Draft</span>';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Jurnal Mengajar - <?= esc($jurnal['id']) ?></title>
    <meta charset="UTF-8">
    <style>
        /* --- Pengaturan Global & Variabel --- */
        :root {
            --font-family: Arial, sans-serif;
            --primary-border: 1px solid #ccc;
            --header-bg: #f2f2f2;
            --text-color: #333;
        }

        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: var(--font-family);
            font-size: 11px;
            line-height: 1.4;
            color: var(--text-color);
            margin: 0;
        }

        /* --- Header, Footer & Kontainer Utama --- */
        .page-container {
            width: 100%;
        }
        
        main {
            padding-bottom: 40px; /* Memberi ruang agar konten tidak tertutup footer */
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        
        .header h2 {
            margin: 4px 0 0;
            font-size: 14px;
            font-weight: normal;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid #ccc;
            padding: 8px 1.5cm; /* Padding vertikal & horizontal (sesuai margin halaman) */
            text-align: right;
            font-size: 9px;
            color: #777;
        }
        
        /* --- Struktur Section --- */
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid; /* Mencegah section terpotong di antara halaman */
        }
        
        .section-title {
            background-color: var(--header-bg);
            padding: 7px 10px;
            margin: 0 0 10px 0;
            border: var(--primary-border);
            font-size: 12px;
            font-weight: bold;
        }
        
        .section-content {
            padding: 0 5px;
        }

        .content-block {
            margin-bottom: 10px;
        }

        .content-block strong {
            display: block;
            margin-bottom: 4px;
        }
        
        /* --- Tabel --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Memastikan lebar kolom konsisten */
        }
        
        .data-table th, .data-table td {
            padding: 7px;
            text-align: left;
            border: var(--primary-border);
            vertical-align: top;
            word-wrap: break-word; /* Memastikan teks panjang tidak merusak layout */
        }
        
        .data-table thead th {
            background-color: var(--header-bg);
            font-weight: bold;
        }

        /* Tabel khusus untuk info utama */
        .info-table th { width: 18%; }
        .info-table td { width: 32%; }
        
        /* --- Komponen Lain --- */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            border-radius: 4px;
        }
        
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        
        .text-center { text-align: center; }

        /* --- Blok Tanda Tangan --- */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-table {
            width: 100%;
            border: none;
        }
        
        .signature-table td {
            width: 50%;
            text-align: center;
            border: none;
            padding: 0 10px;
        }
        
        .signature-box {
            padding-top: 65px; /* Memberi ruang untuk TTD */
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <header class="header">
            <h1>JURNAL MENGAJAR</h1>
            <h2><?= esc(session()->get('nama') ?? 'Guru') ?></h2>
        </header>

        <main>
            <table class="data-table info-table">
                <tr>
                    <th>Tanggal</th>
                    <td><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                    <th>Rombel</th>
                    <td><?= esc($jurnal['kelas_nama']) ?></td>
                </tr>
                <tr>
                    <th>Mata Pelajaran</th>
                    <td><?= esc($jurnal['mapel_nama']) ?></td>
                    <th>Topik</th>
                    <td><?= esc($jurnal['topik']) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= render_status_badge($jurnal['status']) ?></td>
                    <th>ID Jurnal</th>
                    <td><?= esc($jurnal['id']) ?></td>
                </tr>
            </table>
            
            <div class="section">
                <h3 class="section-title">Rencana Pembelajaran</h3>
                <div class="section-content">
                    <div class="content-block">
                        <strong>Tujuan Pembelajaran:</strong>
                        <?php display_text($jurnal['tujuan_pembelajaran']); ?>
                    </div>
                    <div class="content-block">
                        <strong>Aktivitas Pembelajaran:</strong>
                        <?php display_text($jurnal['aktivitas_pembelajaran']); ?>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h3 class="section-title">Refleksi Guru</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Refleksi</th>
                            <th>Kendala</th>
                            <th>Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php display_text($jurnal['refleksi_guru']); ?></td>
                            <td><?php display_text($jurnal['kendala']); ?></td>
                            <td><?php display_text($jurnal['tindak_lanjut']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($p5)): ?>
            <div class="section">
                <h3 class="section-title">Dimensi P5</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Dimensi</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($p5 as $item): ?>
                        <tr>
                            <td><?= esc(ucfirst(str_replace('_', ' ', $item['dimensi']))) ?></td>
                            <td><?= esc($item['aktivitas']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php if (!empty($asesmen)): ?>
            <div class="section">
                <h3 class="section-title">Asesmen</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Jenis Asesmen</th>
                            <th>Hasil</th>
                            <th class="text-center">Siswa Tuntas</th>
                            <th class="text-center">Total Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asesmen as $item): ?>
                        <tr>
                            <td><?= esc($item['jenis_asesmen']) ?></td>
                            <td><?= esc($item['hasil']) ?></td>
                            <td class="text-center"><?= esc($item['siswa_tuntas']) ?></td>
                            <td class="text-center"><?= esc($item['siswa_total']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php if (!empty($lampiran)): ?>
            <div class="section">
                <h3 class="section-title">Lampiran</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Tipe File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lampiran as $item): ?>
                        <tr>
                            <td><?= esc($item['nama_file']) ?></td>
                            <td><?= esc($item['tipe_file']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <div class="signature-section">
                <table class="signature-table">
                    <tr>
                        <td>
                            Mengetahui,<br>
                            Kepala Sekolah
                            <div class="signature-box">
                                <strong><?= esc($kepala_sekolah['nama'] ?? '........................................') ?></strong><br>
                                NIP: <?= esc($kepala_sekolah['nip'] ?? '..............................') ?>
                            </div>
                        </td>
                        <td>
                            Guru Mata Pelajaran,
                            <div class="signature-box">
                                <strong><?= esc(session()->get('nama') ?? '........................................') ?></strong><br>
                                NIP: <?= esc(session()->get('nip') ?? '..............................') ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </main>
        
        <footer class="footer">
            Dicetak pada: <?= date('d F Y H:i:s') ?> | Sistem Jurnal Guru
        </footer>
    </div>
</body>
</html>

