<!DOCTYPE html>
<html>
<head>
    <title>Data Mata Pelajaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Data Mata Pelajaran</h1>
    <p>Tanggal: <?= date('d-m-Y H:i:s') ?></p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Mata Pelajaran</th>
                <th>Nama Mata Pelajaran</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?= $subject['id'] ?></td>
                <td><?= $subject['kode_mapel'] ?></td>
                <td><?= $subject['nama_mapel'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>