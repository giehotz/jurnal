<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Jurnal Mengajar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
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
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        
        .info-section {
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .info-left {
            float: left;
            width: 50%;
        }
        
        .info-right {
            float: right;
            width: 50%;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 40px;
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
        
        .signature strong {
            text-decoration: underline;
        }
        
        .signature-line {
            display: inline-block;
            width: 200px;
            border-top: 1px solid #333;
            margin-top: 60px;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        
        .bg-success {
            background-color: #28a745;
            color: #fff;
        }
        
        .bg-warning {
            background-color: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Jurnal Mengajar</h1>
        <p><?= session()->get('nama_sekolah') ?? 'Sekolah Dasar Negeri 123' ?></p>
    </div>
    
    <div class="info-section">
        <div class="info-left">
            <div class="info-item">
                <span class="info-label">Nama Guru:</span>
                <span><?= esc($jurnal['nama_guru']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">NIP:</span>
                <span><?= esc($jurnal['nip']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Mengajar:</span>
                <span><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Jam Ke:</span>
                <span><?= esc($jurnal['jam_ke']) ?></span>
            </div>
        </div>
        <div class="info-right">
            <div class="info-item">
                <span class="info-label">Rombel:</span> <!-- Diubah dari Kelas -->
                <span><?= esc($jurnal['nama_kelas']) ?> (<?= esc($jurnal['kode_kelas']) ?>)</span> <!-- Tidak perlu diubah karena field sama -->
            </div>
            <div class="info-item">
                <span class="info-label">Mata Pelajaran:</span>
                <span><?= esc($jurnal['nama_mapel']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Jumlah JP:</span>
                <span><?= esc($jurnal['jumlah_jam']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Jumlah Peserta:</span>
                <span><?= esc($jurnal['jumlah_peserta']) ?></span>
            </div>
        </div>
    </div>
    
    <div class="form-group mb-3">
        <label><strong>Materi Pembelajaran:</strong></label>
        <p><?= esc($jurnal['materi']) ?></p>
    </div>
    
    <div class="form-group mb-3">
        <label><strong>Keterangan:</strong></label>
        <p><?= esc($jurnal['keterangan']) ?></p>
    </div>
    
    <div class="form-group mb-3">
        <label><strong>Status:</strong></label>
        <p>
            <?php if ($jurnal['status'] == 'published'): ?>
                <span class="badge bg-success">Published</span>
            <?php else: ?>
                <span class="badge bg-warning">Draft</span>
            <?php endif; ?>
        </p>
    </div>
    
    <div class="footer">
        <div class="signature-section">
            <div class="signature-left">
                <div class="signature">
                    <p><?= session()->get('lokasi_sekolah') ?? 'Lokasi Sekolah' ?>, <?= date('d F Y') ?></p>
                    <p>Guru Mata Pelajaran,</p>
                    <div style="margin: 60px 0 5px 0;">
                        <div class="signature-line"></div>
                    </div>
                    <p><strong><?= esc($jurnal['nama_guru']) ?></strong></p>
                    <p>NIP. <?= esc($jurnal['nip']) ?></p>
                </div>
            </div>
            
            <div class="signature-right">
                <div class="signature">
                    <p>Mengetahui,</p>
                    <p>Kepala Sekolah</p>
                    <div style="margin: 60px 0 5px 0;">
                        <div class="signature-line"></div>
                    </div>
                    <p><strong><?= session()->get('nama_kepala_sekolah') ?? '[Nama Kepala Sekolah]' ?></strong></p>
                    <p>NIP. <?= session()->get('nip_kepala_sekolah') ?? '[NIP Kepala Sekolah]' ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 30px; font-size: 8px; color: #666; text-align: center;">
        <p>Dokumen ini dicetak pada <?= date('d F Y H:i:s') ?></p>
        <p>© <?= date('Y') ?> <?= session()->get('nama_sekolah') ?? 'Aplikasi Jurnal Guru' ?></p>
    </div>
</body>
</html>