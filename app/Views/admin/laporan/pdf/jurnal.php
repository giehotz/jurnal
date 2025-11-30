<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .school-logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .school-info {
            text-align: center;
        }
        .school-info h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .school-info p {
            margin: 2px 0;
            font-size: 12px;
        }
        .separator {
            height: 2px;
            background-color: #000;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .signature-section {
            margin-top: 40px;
        }
        .signature {
            width: 50%;
            float: left;
            text-align: center;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-header">
            <?php if (!empty($school_settings['logo_base64'])): ?>
                <img src="<?= $school_settings['logo_base64'] ?>" alt="Logo Sekolah" class="school-logo">
            <?php endif; ?>
            <div class="school-info">
                <h1><?= !empty($school_settings['school_name']) ? esc($school_settings['school_name']) : 'SDN DEMO PUTRA' ?></h1>
                <p><?= !empty($school_settings['school_address']) ? esc($school_settings['school_address']) : 'Alamat Sekolah' ?></p>
                <p>Telp: <?= !empty($school_settings['phone']) ? esc($school_settings['phone']) : '-' ?></p>
            </div>
        </div>
        <div class="separator"></div>
        <h2 style="text-align: center;"><?= $title ?></h2>
        <p>Dicetak pada: <?= $print_date ?></p>
        <?php if (isset($bulan_range)): ?>
            <p>Periode: <?= $bulan_range ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Materi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($journals)): ?>
                <?php foreach ($journals as $journal): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($journal['tanggal'])) ?></td>
                    <td><?= esc($journal['guru_nama']) ?></td>
                    <td><?= esc($journal['kelas_nama']) ?></td>
                    <td><?= esc($journal['mapel_nama']) ?></td>
                    <td><?= esc($journal['materi']) ?></td>
                    <td class="text-center">
                        <?php if ($journal['status'] == 'published'): ?>
                            <span style="color: green;">Published</span>
                        <?php else: ?>
                            <span style="color: orange;">Draft</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data jurnal</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section clearfix">
        <div class="signature">
            <p>Guru,</p>
            <br><br><br>
            <p>_______________________</p>
        </div>
        <div class="signature">
            <p>Kepala Sekolah,</p>
            <br><br><br>
            <p>_______________________</p>
        </div>
    </div>
</body>
</html>