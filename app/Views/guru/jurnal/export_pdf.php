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
    <title>Jurnal Mengajar - Semua Jurnal</title>
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
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th, table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 8px;
        }

        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
        }

        .status-published {
            color: green;
            font-weight: bold;
        }

        .status-draft {
            color: orange;
            font-weight: bold;
        }
        
        .journal-separator {
            page-break-before: always;
            border-top: 1px dashed #ccc;
            margin: 30px 0;
        }
        
        .journal-header {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
        }
        
        .journal-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .journal-meta {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="header">
            <h1>LAPORAN JURNAL MENGAJAR</h1>
            <p>Guru: <?= esc($guru_nama ?? 'Tidak diketahui') ?></p>
            <p>Tanggal Ekspor: <?= esc($export_time ?? date('d F Y H:i')) ?></p>
        </div>
        
        <?php if (empty($jurnals)): ?>
            <div class="section">
                <p>Tidak ada data jurnal mengajar.</p>
            </div>
        <?php else: ?>
            <div class="section">
                <div class="section-title">Daftar Jurnal</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Topik</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jurnals as $index => $jurnal): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= date('d/m/Y', strtotime($jurnal['tanggal'])) ?></td>
                            <td><?= esc($jurnal['kelas_nama'] ?? 'Tidak diketahui') ?></td>
                            <td><?= esc($jurnal['mapel_nama'] ?? 'Tidak diketahui') ?></td>
                            <td><?= esc($jurnal['topik'] ?? '-') ?></td>
                            <td>
                                <?php if ($jurnal['status'] == 'published'): ?>
                                    <span class="status-published">Published</span>
                                <?php else: ?>
                                    <span class="status-draft">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($jurnal['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="page-break-before: always;"></div>
            
            <div class="section">
                <div class="section-title">Detail Jurnal</div>
                <?php foreach ($jurnals as $index => $jurnal): ?>
                <div class="journal-header">
                    <h3 class="journal-title">Jurnal #<?= $jurnal['id'] ?> - <?= esc($jurnal['topik'] ?? 'Tanpa Topik') ?></h3>
                    <div class="journal-meta">
                        Tanggal: <?= date('d F Y', strtotime($jurnal['tanggal'])) ?> | 
                        Kelas: <?= esc($jurnal['kelas_nama'] ?? 'Tidak diketahui') ?> | 
                        Mata Pelajaran: <?= esc($jurnal['mapel_nama'] ?? 'Tidak diketahui') ?> | 
                        Status: <?= ucfirst($jurnal['status']) ?>
                    </div>
                </div>
                
                <div class="section">
                    <h4>Informasi Jurnal</h4>
                    <table class="info-table">
                        <tr>
                            <td>Tanggal Mengajar</td>
                            <td><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td><?= esc($jurnal['kelas_nama'] ?? 'Tidak diketahui') ?></td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td><?= esc($jurnal['mapel_nama'] ?? 'Tidak diketahui') ?></td>
                        </tr>
                        <tr>
                            <td>Topik</td>
                            <td><?= esc($jurnal['topik'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <?php if ($jurnal['status'] == 'published'): ?>
                                    <span class="status-published">Published</span>
                                <?php else: ?>
                                    <span class="status-draft">Draft</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="section">
                    <h4>Rencana Pembelajaran</h4>
                    <p><strong>Tujuan Pembelajaran:</strong><br>
                    <?= display_text($jurnal['tujuan_pembelajaran'] ?? '-') ?></p>
                    
                    <p><strong>Aktivitas Pembelajaran:</strong><br>
                    <?= display_text($jurnal['aktivitas_pembelajaran'] ?? '-') ?></p>
                </div>
                
                <div class="section">
                    <h4>Refleksi Guru</h4>
                    <p><strong>Refleksi:</strong><br>
                    <?= display_text($jurnal['refleksi_guru'] ?? '-') ?></p>
                    
                    <?php if (!empty($jurnal['kendala'])): ?>
                    <p><strong>Kendala:</strong><br>
                    <?= display_text($jurnal['kendala']) ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($jurnal['tindak_lanjut'])): ?>
                    <p><strong>Tindak Lanjut:</strong><br>
                    <?= display_text($jurnal['tindak_lanjut']) ?></p>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($jurnal['p5'])): ?>
                <div class="section">
                    <h4>Dimensi P5</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Dimensi</th>
                                <th>Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jurnal['p5'] as $item): ?>
                            <tr>
                                <td><?= esc(ucfirst(str_replace('_', ' ', $item['dimensi']))) ?></td>
                                <td><?= esc($item['aktivitas']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($jurnal['asesmen'])): ?>
                <div class="section">
                    <h4>Asesmen</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Jenis Asesmen</th>
                                <th>Hasil</th>
                                <th>Siswa Tuntas</th>
                                <th>Total Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jurnal['asesmen'] as $item): ?>
                            <tr>
                                <td><?= esc($item['jenis_asesmen']) ?></td>
                                <td><?= esc($item['hasil']) ?></td>
                                <td><?= esc($item['siswa_tuntas']) ?></td>
                                <td><?= esc($item['siswa_total']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($jurnal['lampiran'])): ?>
                <div class="section">
                    <h4>Lampiran</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama File</th>
                                <th>Tipe File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jurnal['lampiran'] as $item): ?>
                            <tr>
                                <td><?= esc($item['nama_file']) ?></td>
                                <td><?= esc($item['tipe_file']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <?php if ($index < count($jurnals) - 1): ?>
                <div class="journal-separator"></div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="signature-section">
                <table class="signature-table">
                    <tr>
                        <td>
                            Mengetahui,<br>
                            Kepala Madrasah
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
        <?php endif; ?>
    </div>
</body>
</html>

<style>
/* Signature section styles */
.signature-section {
    margin-top: 50px;
    width: 100%;
}

.signature-table {
    width: 100%;
    border-collapse: collapse;
}

.signature-table td {
    width: 50%;
    text-align: center;
    padding: 20px;
    border: none;
}

.signature-box {
    margin-top: 80px;
    font-weight: bold;
}

.signature-box strong {
    display: block;
    margin-bottom: 5px;
    text-decoration: underline;
}
</style>
