<?php

// Fungsi untuk menampilkan badge status
function render_status_badge($status)
{
    if ($status == 'published') {
        return '<span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 10px;">Published</span>';
    }
    return '<span style="background-color: #ffc107; color: black; padding: 3px 8px; border-radius: 3px; font-size: 10px;">Draft</span>';
}

// Fungsi untuk memformat HARI saja
function formatHari($tanggal)
{
    // Pastikan zona waktu benar
    date_default_timezone_set('Asia/Jakarta');

    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $timestamp = is_numeric($tanggal) ? $tanggal : strtotime($tanggal);

    if (!$timestamp) {
        return 'Invalid';
    }

    $dayIndex = date('w', $timestamp);
    return $days[$dayIndex];
}

// CATATAN: Fungsi formatHariTanggal yang asli telah diganti dengan formatHari
// karena tabel sudah memiliki kolom "Tanggal" sendiri, 
// dan kolom "Hari" seharusnya only berisi nama hari.

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Jurnal Mengajar</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Pengaturan Global & Tipografi */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            /* background-color: #f4f7f6; Dihapus */
            color: #000000;
        }

        .container {
            max-width: 960px;
            margin: 20px auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 5px solid #000000;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 20px;
            font-weight: 600;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
            color: #000000;
        }

        /* Bagian Info Guru - Didesain ulang */
        .info-section {
            margin-bottom: 25px;
        }

        .info-row {
            /* display: flex; Dihapus */
            margin-bottom: 5px;
            font-size: 12px;
        }

        /* Menghapus .info-label dan .info-value */

        /* Tabel - Didesain ulang */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11px;
            /* Data tabel bisa sedikit lebih kecil */
        }

        th,
        td {
            border: 1px solid #000000;
            /* Border lebih soft */
            padding: 10px 12px;
            /* Padding lebih besar */
            text-align: left;
            vertical-align: top;
        }

        thead th {
            background-color: #f9f9f9;
            font-weight: 600;
            text-align: center;
            /* Sesuai preferensi asli */
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background-color: #fdfdfd;
            /* Garis zebra halus */
        }

        /* Page break handling for tables */
        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
            /* Memastikan header tabel muncul di setiap halaman */
        }

        tbody {
            display: table-row-group;
        }


        /* Kelas Helper (dari asli) */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Footer Cetak */
        .print-footer {
            width: 100%;
            text-align: left;
            font-size: 10px;
            color: #888;
            margin-top: 40px;
            /* Tambahkan margin untuk jarak di tampilan layar */
            /* Style ini untuk tampilan di layar. Tampilan cetak diatur di @media print */
        }

        /* Optimasi Cetak */
        @media print {
            body {
                font-size: 10pt;
                padding: 0;
                background-color: #fff;
                padding-bottom: 0;
                /* Hapus padding bawah */
            }

            .container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                border: none;
                padding-top: 10px;
                width: 100%;
                /* Pastikan container mengisi lebar cetak */
                max-width: 100%;
            }

            .header {
                border-bottom: 2px solid #333;
            }

            table,
            th,
            td {
                border: 1px solid #333;
                /* Border lebih tegas untuk cetak */
                font-size: 9pt;
                padding: 6px;
            }

            thead th {
                background-color: #f2f2f2;
            }

            .print-footer {
                /* Hapus position: fixed dan properti terkait (bottom, left, right, bg-color) */
                width: 100%;
                text-align: right;
                font-size: 9pt;
                padding: 10px 0;
                margin-top: 40px;
                /* Tambahkan margin untuk jarak dari tanda tangan */
            }

            /* Memastikan badge tetap terbaca saat dicetak (hitam putih) */
            span[style*="background-color"] {
                background-color: transparent !important;
                color: #000 !important;
                border: 1px solid #000;
                padding: 2px 6px !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <table style="width: 100%; border: none;">
                <tr style="border: none;">
                    <?php
                    // Determine logo path and embed as Base64 to prevent ALL deadlock/path issues
                    $logoSrc = null;
                    if (!empty($school_settings['logo'])) {
                        $absolutePath = FCPATH . 'uploads/logos/' . $school_settings['logo'];
                        if (file_exists($absolutePath)) {
                            $type = pathinfo($absolutePath, PATHINFO_EXTENSION);
                            $data = file_get_contents($absolutePath);
                            if ($data !== false) {
                                $base64 = base64_encode($data);
                                $logoSrc = 'data:image/' . $type . ';base64,' . $base64;
                            }
                        }
                    }
                    ?>

                    <?php if ($logoSrc): ?>
                        <td style="width: 80px; text-align: left; vertical-align: middle; border: none; padding-right: 15px;">
                            <!-- Embed Base64 Image directly -->
                            <img src="<?= $logoSrc ?>" style="width: 80px; height: auto;" alt="Logo">
                        </td>
                        <td style="text-align: center; vertical-align: middle; border: none;">
                        <?php else: ?>
                        <td style="text-align: center; vertical-align: middle; border: none;">
                        <?php endif; ?>

                        <h1 style="margin: 0; font-size: 20px;">LAPORAN JURNAL MENGAJAR</h1>
                        <?php if (isset($school_settings['nama_sekolah'])): ?>
                            <p style="margin: 2px 0; font-size: 14px; font-weight: bold;"><?= esc($school_settings['nama_sekolah']) ?></p>
                        <?php elseif (isset($school_settings['school_name'])): ?>
                            <p style="margin: 2px 0; font-size: 14px; font-weight: bold;"><?= esc($school_settings['school_name']) ?></p>
                        <?php endif; ?>

                        <?php if (isset($school_settings['school_address'])): ?>
                            <p style="margin: 0; font-size: 11px;"><?= esc($school_settings['school_address']) ?></p>
                        <?php endif; ?>

                        <p style="margin: 5px 0 0 0; font-size: 12px; border-top: 1px solid #000; padding-top: 2px; display: inline-block;">
                            <?php if (isset($school_settings['school_year'])): ?>
                                Tahun Pelajaran: <?= esc($school_settings['school_year']) ?> |
                            <?php endif; ?>
                            <?php if (isset($school_settings['semester'])): ?>
                                Semester: <?= esc(ucfirst($school_settings['semester'])) ?> |
                            <?php endif; ?>
                            Periode: <?= date('d/m/Y', strtotime($tanggal_awal)) ?> - <?= date('d/m/Y', strtotime($tanggal_akhir)) ?>
                        </p>
                        </td>
                </tr>
            </table>
        </div>

        <!-- Info Section (Struktur HTML diubah sesuai permintaan) -->
        <div class="info-section">
            <div class="info-row">
                <strong>Nama Guru:</strong> <?= esc($user['nama']) ?>
            </div>
            <div class="info-row">
                <strong>NIP:</strong> <?= esc($user['nip']) ?>
            </div>
            <?php if (isset($user['is_wali_kelas']) && $user['is_wali_kelas'] && isset($user['kelas_wali'])): ?>
                <div class="info-row">
                    <strong>Wali Kelas:</strong> <?= esc($user['kelas_wali']['nama_rombel']) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="print-footer">
            <p>Dokumen ini dicetak pada <?= function_exists('format_tanggal_indonesia') ? format_tanggal_indonesia(date('Y-m-d H:i:s')) : date('Y-m-d H:i:s') ?>
        </div>
        <?php if (empty($jurnals)): ?>
            <p class="text-center">Tidak ada data jurnal mengajar.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Jam Ke</th>
                        <th>Materi</th>
                        <th>Jml Siswa</th>
                        <th>Ketuntasan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jurnals as $index => $jurnal): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($jurnal['tanggal'])) ?></td>
                            <td><?= formatHari($jurnal['tanggal']) // Memanggil fungsi 'formatHari' yang baru 
                                ?></td>
                            <td><?= esc($jurnal['nama_rombel']) ?></td>
                            <td><?= esc($jurnal['nama_mapel']) ?></td>
                            <td class="text-center"><?= esc($jurnal['jam_ke']) ?></td>
                            <td><?= esc($jurnal['materi']) ?></td>
                            <td class="text-center"><?= esc($jurnal['jumlah_peserta']) ?></td>
                            <td class="text-center"><?= esc($jurnal['keterangan'] ?? '-') ?></td>
                            <td class="text-center"><?= render_status_badge($jurnal['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

        <div class="signature-section">
            <?php
            // Prepare headmaster data
            $headmasterData = null;
            if (isset($school_settings['nama_kepala_sekolah']) && isset($school_settings['nip_kepala_sekolah'])) {
                $headmasterData = [
                    'nama' => $school_settings['nama_kepala_sekolah'],
                    'nip' => $school_settings['nip_kepala_sekolah']
                ];
            } elseif (isset($school_settings['headmaster_name']) && isset($school_settings['headmaster_nip'])) {
                // Alternative format from settings model
                $headmasterData = [
                    'nama' => $school_settings['headmaster_name'],
                    'nip' => $school_settings['headmaster_nip']
                ];
            }

            // Prepare teacher data
            $teacherData = null;
            if (isset($user['nama']) && isset($user['nip'])) {
                $teacherData = [
                    'nama' => $user['nama'],
                    'nip' => $user['nip']
                ];
            }

            // Manual Signature Table to ensure NO BORDERS
            ?>
            <table cellspacing="0" cellpadding="0" style="width: 100%; border: none !important; margin-top: 20px;">
                <tr style="border: none !important;">
                    <!-- Kepala Sekolah (Left) -->
                    <td style="border: none !important; width: 40%; text-align: center; vertical-align: top;">
                        <p style="margin: 0;">Mengetahui,</p>
                        <p style="margin: 0;">Kepala Sekolah</p>
                        <br><br><br><br>
                        <p style="text-decoration: underline; font-weight: bold; margin: 0;"><?= esc($headmasterData['nama'] ?? '........................................') ?></p>
                        <p style="margin: 0;">NIP. <?= esc($headmasterData['nip'] ?? '..............................') ?></p>
                    </td>

                    <!-- Spacer -->
                    <td style="border: none !important; width: 20%;"></td>

                    <!-- Guru (Right) -->
                    <td style="border: none !important; width: 40%; text-align: center; vertical-align: top;">
                        <p style="margin: 0;">Guru Mata Pelajaran,</p>
                        <br><br><br><br>
                        <p style="text-decoration: underline; font-weight: bold; margin: 0;"><?= esc($teacherData['nama'] ?? '........................................') ?></p>
                        <p style="margin: 0;">NIP. <?= esc($teacherData['nip'] ?? '..............................') ?></p>
                    </td>
                </tr>
            </table>
            <?php
            // End Signature
            ?>
        </div>
    </div>
</body>

</html>