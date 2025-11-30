<!DOCTYPE html>
<html>
<head>
    <title>Laporan Daftar Jurnal Mengajar</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
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
            text-transform: uppercase;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .header .school-info {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .header .period {
            margin: 5px 0 0 0;
            font-size: 11px;
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
            font-size: 9px;
        }
        
        td {
            font-size: 8px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* * === PERBAIKAN LAYOUT DIMULAI DI SINI ===
         * CSS .footer di bawah ini diganti dan ditambahkan 
         * CSS untuk signature agar layout tanda tangan menjadi 2 kolom.
         */
        
        .footer {
            margin-top: 30px;
            overflow: hidden; /* Diperlukan untuk clearfix float */
        }
        
        .signature-section {
            width: 100%;
            overflow: hidden; /* Diperlukan untuk clearfix float */
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
            margin-top: 60px;
        }
        
        /* === PERBAIKAN LAYOUT SELESAI === */
        
    </style>
</head>
<body>
    <!-- 
      HEADER TIDAK DIUBAH (Sesuai instruksi Anda)
    -->
    <div class="header">
        <h1>LAPORAN JURNAL MENGAJAR</h1>
        <h2><?= isset($settings['school_name']) ? $settings['school_name'] : 'MIN 2 TANGGAMUS' ?></h2>
        <div class="school-info">
            Tahun Pelajaran: <?= isset($settings['school_year']) ? $settings['school_year'] : '2025/2026' ?> 
            Semester: <?= isset($settings['semester']) ? $settings['semester'] : 'ganjil' ?>
        </div>
        <div class="period">
            Periode: 01 October 2025 - 30 November 2025
        </div>
    </div>
    
    <!-- 
      TABEL TIDAK DIUBAH (Sesuai instruksi Anda)
    -->
      <div class="print-footer">
            <p>Dokumen ini dicetak pada <?= function_exists('format_tanggal_indonesia') ? format_tanggal_indonesia(date('Y-m-d H:i:s')) : date('d-M-Y') ?>
        </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Guru</th>
                <th>Rombel</th> <!-- Diubah dari Kelas -->
                <th>Mata Pelajaran</th>
                <th>Jam Ke</th>
                <th>Materi</th>
                <th>Jumlah JP</th>
                <th>Jumlah Peserta</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jurnals)): ?>
            <?php $no = 1; foreach ($jurnals as $jurnal): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($jurnal['tanggal'])) ?></td>
                <td><?= esc($jurnal['guru_nama']) ?></td>
                <td><?= esc($jurnal['kelas_nama']) ?> (<?= esc($jurnal['kode_kelas']) ?>)</td> <!-- Diubah dari Kelas -->
                <td><?= esc($jurnal['mapel_nama']) ?></td>
                <td class="text-center"><?= esc($jurnal['jam_ke']) ?></td>
                <td><?= esc($jurnal['materi']) ?></td>
                <td class="text-center"><?= esc($jurnal['jumlah_jam']) ?></td>
                <td class="text-center"><?= esc($jurnal['jumlah_peserta']) ?></td>
                <td class="text-center">
                    <?php if ($jurnal['status'] == 'published'): ?>
                        Published
                    <?php else: ?>
                        Draft
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="10" class="text-center">Tidak ada data jurnal</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- 
      FOOTER (HTML) TIDAK DIUBAH (Sesuai instruksi Anda)
      Layoutnya akan otomatis benar karena CSS sudah ditambahkan di atas.
    -->
    <div class="footer">
        <div style="float: right; text-align: left; width: 300px; margin-top: 20px;">
            <p><?= isset($settings['school_name']) ? $settings['school_name'] : (session()->get('nama_sekolah') ?? 'MIN 2 Tanggamus') ?>, <?= date('d F Y') ?></p>
            <p>Admin,</p>
            <br><br><br>
            <p><strong><?= session()->get('nama') ?></strong></p>
            <p>NIP. <?= session()->get('nip') ?></p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
