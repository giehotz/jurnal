<?php
// Tentukan Tahun Ajaran. Asumsi berdasarkan tanggal_akhir.
// Jika bulan > Juni (awal tahun ajaran baru), formatnya 2024/2025
// Jika bulan <= Juni (akhir tahun ajaran), formatnya 2023/2024
$tahunAkhir = (int)date('Y', strtotime($tanggal_akhir));
$bulanAkhir = (int)date('m', strtotime($tanggal_akhir));
$tahunAjaran = ($bulanAkhir > 6) ? $tahunAkhir . '/' . ($tahunAkhir + 1) : ($tahunAkhir - 1) . '/' . $tahunAkhir;

// Gunakan tahun ajaran dari settings jika tersedia
if (!empty($school_settings['school_year'])) {
    $tahunAjaran = $school_settings['school_year'];
}

// Tentukan semester dari settings atau default
$semester = !empty($school_settings['semester']) ? $school_settings['semester'] : '';

// Tentukan Nama Peran Pengguna untuk digunakan kembali
$userRoleName = ($user['role'] === 'admin') ? 'Admin' : (($user['is_wali_kelas']) ? 'Wali Kelas' : 'Guru');

// Memuat helper tanggal
helper('tanggal');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Mengajar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 10px;
        }
        
        .info-section {
            margin-bottom: 20px;
            overflow: hidden; /* Clearfix untuk float */
        }
        
        .info-left {
            float: left;
            width: 50%;
        }
        
        .info-right {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .info-item {
            margin-bottom: 5px;
        }
        
        .info-label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }
        
        td {
            font-size: 8px;
        }
        
        /* .journal-info style tidak lagi diperlukan jika section dihapus */
        /* Namun, kita biarkan jika Anda ingin menggunakannya lagi nanti */
        .journal-info {
            background-color: #f2f2f2;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .journal-info table {
            margin-bottom: 0;
            width: auto;
        }
        
        .journal-info th, 
        .journal-info td {
            border: none;
            padding: 3px 8px;
            font-size: 9px;
        }
        
        .journal-info th {
            background-color: transparent;
            font-weight: bold;
            text-align: left;
            width: 120px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 40px;
            line-height: normal;
            
        }
        
        .signature-section {
            width: 100%;
            overflow: hidden;
        }
        
        .signature-left {
            float: left;
            width: 50%;
        }
        
        .signature-right {
            float: right;
            width: 50%;
            text-align: center;
        }
        
        .signature {
            margin-top: 20px;
        }
        
        .signature p {
            margin: 0 0 5px 0;
        }
        
        /* === TAMBAHAN CSS DI SINI === */
        /* Menghapus margin default <p> di dalam blok tanda tangan */
        .signature-left p,
        .signature-right p {
            margin: 0 0 2px 0; /* margin: 0; jika ingin lebih rapat */
        }
        /* === AKHIR TAMBAHAN === */

        
        .signature strong {
            text-decoration: underline;
        }
        
        .signature .signature-line {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #ffffffff;
            margin-top: 60px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
    </style>
</head>
<body>
    <div class="header">
        <?php if (!empty($school_settings['logo_base64'])): ?>
                <img src="<?= $school_settings['logo_base64'] ?>" alt="Logo Sekolah" class="school-logo">
            <?php endif; ?>
        <h1>Laporan Jurnal Mengajar</h1>
        <h2><?= session()->get('nama_sekolah') ?? (!empty($school_settings['school_name']) ? esc($school_settings['school_name']) : 'MIN 2 TANGGAMUS') ?></h2>
        <h3>Tahun Pelajaran: <?= $tahunAjaran ?></h3>
        <?php if (!empty($semester)): ?>
        <h3>Semester: <?= $semester ?></h3>
        <?php endif; ?>
        <h5>Periode: <?= format_tanggal_indonesia($tanggal_awal . ' 00:00:00') ?> - <?= format_tanggal_indonesia($tanggal_akhir . ' 00:00:00') ?></h5>
    </div>
    
    <div class="info-section">
        <div class="info-left">
            <div class="info-item">
                <span class="info-label">Nama <?= $userRoleName ?>:</span>
                <span><?= esc($user['nama']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">NIP:</span>
                <span><?= esc($user['nip']) ?></span>
            </div>
        </div>
        <div class="info-right">
            <div class="info-item">
                <span class="info-label">Periode:</span>
                <span><?= date('d/m/Y', strtotime($tanggal_awal)) ?> - <?= date('d/m/Y', strtotime($tanggal_akhir)) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Jumlah Jurnal:</span>
                <span><?= count($jurnals) ?></span>
            </div>
            <?php if ($user['is_wali_kelas']): ?>
            <div class="info-item">
                <span class="info-label">Kelas Wali:</span>
                <span><?= esc($user['kelas_wali']['nama_kelas']) ?> (<?= esc($user['kelas_wali']['kode_kelas']) ?>)</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($jurnals)): ?>
    <div class="journal-info">
        <?php 
        // Mengambil informasi guru, kelas, tahun pelajaran dari jurnal pertama
        $firstJournal = $jurnals[0];
        // $tahunAjaran = date('Y', strtotime($firstJournal['tanggal'])) . '/' . (date('Y', strtotime($firstJournal['tanggal'])) + 1); // Logika ini sudah pindah ke atas
        ?>
        <table>
            <tr>
                <th>Nama Guru</th>
                <td>: <?= esc($user['nama']) ?></td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td>: <?= esc($firstJournal['nama_kelas']) ?> (<?= esc($firstJournal['kode_kelas']) ?>)</td>
            </tr>
            <tr>
                <th>Tahun Pelajaran</th>
                <td>: <?= $tahunAjaran ?></td>
            </tr>
        </table>
    </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <?php if ($user['role'] === 'admin' || $user['is_wali_kelas']): ?>
                <th>Guru</th>
                <?php endif; ?>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Jam Ke</th>
                <th>Materi</th>
                <th>Jumlah JP</th>
                <th>Jumlah Siswa</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jurnals)): ?>
                <?php $no = 1; foreach ($jurnals as $jurnal): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($jurnal['tanggal'])) ?></td>
                    <?php if ($user['role'] === 'admin' || $user['is_wali_kelas']): ?>
                    <td><?= esc($jurnal['nama_guru']) ?></td>
                    <?php endif; ?>
                    <td><?= esc($jurnal['nama_kelas']) ?> (<?= esc($jurnal['kode_kelas']) ?>)</td>
                    <td><?= esc($jurnal['nama_mapel']) ?></td>
                    <td class="text-center"><?= esc($jurnal['jam_ke']) ?></td>
                    <td><?= esc($jurnal['materi']) ?></td>
                    <td class="text-center"><?= esc($jurnal['jumlah_jam']) ?></td>
                    <td class="text-center"><?= esc($jurnal['jumlah_peserta']) ?></td>
                    <td class="text-center">
                        <?php if ($jurnal['status'] == 'published'): ?>
                            <span>Published</span>
                        <?php else: ?>
                            <span>Draft</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($jurnal['keterangan']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= ($user['role'] === 'admin' || $user['is_wali_kelas']) ? 11 : 10 ?>" class="text-center">Tidak ada data jurnal untuk periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!--foote-->
<div class="footer" style="
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    color: #000;
    line-height: normal
">
    <div class="signature-section" style="display: table; width: 100%;">
        <!-- Tanda tangan kanan (Kepala Sekolah) -->
        <!-- Blok ini akan muncul di KIRI pada PDF karena urutan HTML -->
        <div class="signature-right" style="display: table-cell; width: 50%; text-align: center; vertical-align: top;">
            <!-- Div pembungkus untuk rata kiri -->
            <div style="display: inline-block; text-align: left;">
                <p>Mengetahui,</p>
                <p>Kepala <?= !empty($school_settings['school_name']) ? esc($school_settings['school_name']) : (session()->get('nama_sekolah') ?? 'MIN 2 Tanggamus') ?></p>
                <div class="signature-line" style="border-bottom: 1px solid #fffafaff; width: 150px; margin: 40px auto 10px auto;"></div>
                <p><strong><?= !empty($school_settings['headmaster_name']) ? esc($school_settings['headmaster_name']) : (session()->get('nama_kepala_sekolah') ?? 'Sipulloh, M.Pd') ?></strong></p>
                <p>NIP. <?= !empty($school_settings['headmaster_nip']) ? esc($school_settings['headmaster_nip']) : (session()->get('nip_kepala_sekolah') ?? '197005272007011022') ?></p>
            </div>
        </div>
        <!-- Tanda tangan kiri (Wali Kelas) -->
        <!-- Blok ini akan muncul di KANAN pada PDF -->
        <div class="signature-left" style="display: table-cell; width: 50%; text-align: center; vertical-align: top;">
            <!-- Div pembungkus untuk rata kiri -->
            <div style="display: inline-block; text-align: left;">
                <p><?= !empty($school_settings['city']) ? esc($school_settings['city']) : (session()->get('kota_sekolah') ?? 'Kota Tanggamus') ?>, <?= format_tanggal_indonesia(date('Y-m-d H:i:s')) ?></p>
                <p><?php if ($user['is_wali_kelas']): ?>
                    Wali Kelas: <?= esc($user['kelas_wali']['nama_kelas']) ?>
                    <?php else: ?>
                    <?= $userRoleName ?>: <?= esc($user['nama']) ?>
                    <?php endif; ?></p>
                <!-- Margin atas diubah menjadi 40px agar sejajar dengan blok kiri -->
                <div class="signature-line" style="border-bottom: 1px solid #fff8f8ff; width: 150px; margin: 40px auto 10px auto;"></div>
                <p><strong><?= esc($user['nama']) ?></strong></p>
                <p>NIP. <?= esc($user['nip']) ?></p>
            </div>
        </div>

    </div>

    <!-- Info cetak di bawah tanda tangan -->
    <div style="
        border-top: 1px solid #999;
        margin: 10px auto 0;
        width: 90%;
        text-align: center;
        font-size: 9px;
        color: #555;
        padding-top: 5px;
    ">
        <p>Dokumen ini dicetak pada <?= format_tanggal_indonesia(date('Y-m-d H:i:s')) ?> oleh <?= esc($user['nama']) ?> (<?= esc($user['nip']) ?>)</p>
        <p>© <?= date('Y') ?> <?= session()->get('nama_sekolah') ?? 'Aplikasi Jurnal Guru' ?></p>
    </div>
</div>

</body>
</html>
