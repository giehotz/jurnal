<!DOCTYPE html>
<html>
<head>
    <title>Data Kelas</title>
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
    <h1>Laporan Data Kelas</h1>
    <p>Tanggal: <?= date('d-m-Y H:i:s') ?></p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Kelas</th>
                <th>Nama Kelas</th>
                <th>Fase</th>
                <th>Wali Kelas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
            <tr>
                <td><?= $class['id'] ?></td>
                <td><?= $class['kode_kelas'] ?></td>
                <td><?= $class['nama_kelas'] ?></td>
                <td><?= $class['fase'] ?></td>
                <td><?= $class['wali_kelas_nama'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>