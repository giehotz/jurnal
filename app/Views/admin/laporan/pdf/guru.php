<!DOCTYPE html>
<html>
<head>
    <title>Data Guru</title>
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
    <h1>Laporan Data Guru</h1>
    <p>Tanggal: <?= date('d-m-Y H:i:s') ?></p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Total Jurnal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher): ?>
            <tr>
                <td><?= $teacher['id'] ?></td>
                <td><?= $teacher['nip'] ?? '-' ?></td>
                <td><?= $teacher['nama'] ?></td>
                <td><?= $teacher['email'] ?></td>
                <td><?= $teacher['is_active'] ? 'Aktif' : 'Non-Aktif' ?></td>
                <td><?= $teacher['total_jurnal'] ?? 0 ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>