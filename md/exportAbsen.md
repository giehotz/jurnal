Berdasarkan struktur database Anda, berikut adalah **logika yang disesuaikan** untuk export rekapitulasi absensi siswa:

## 1. **Logika Utama untuk Export**

```php
// Tentukan periode berdasarkan tanggal
$startMonth = date('n', strtotime($startDate));
$endMonth = date('n', strtotime($endDate));
$startYear = date('Y', strtotime($startDate));
$endYear = date('Y', strtotime($endDate));

// Tentukan judul periode
if ($startMonth == 1 && $endMonth == 12 && $startYear == $endYear) {
    $periodeText = $startYear; // Tahun lengkap
    $isTahunan = true;
} else if ($startMonth == $endMonth && $startYear == $endYear) {
    $periodeText = date('F Y', strtotime($startDate)); // 1 bulan
    $isTahunan = false;
} else {
    $periodeText = date('M Y', strtotime($startDate)) . ' - ' . date('M Y', strtotime($endDate)); // Beberapa bulan
    $isTahunan = false;
}
```

## 2. **Query Data Absensi (Sesuai Database Anda)**

```php
// Query untuk mendapatkan data absensi per siswa
$query = "
    SELECT 
        s.id as siswa_id,
        s.nama,
        s.nisn,
        s.nis,
        MONTH(j.tanggal) as bulan,
        YEAR(j.tanggal) as tahun,
        COUNT(CASE WHEN a.status = 'hadir' THEN 1 END) as hadir,
        COUNT(CASE WHEN a.status = 'izin' THEN 1 END) as izin,
        COUNT(CASE WHEN a.status = 'sakit' THEN 1 END) as sakit,
        COUNT(CASE WHEN a.status = 'alfa' THEN 1 END) as alfa,
        r.nama_rombel,
        r.kode_rombel
    FROM 
        siswa s
    LEFT JOIN 
        absensi a ON s.id = a.siswa_id 
    LEFT JOIN
        jurnal_new j ON a.jurnal_id = j.id
    LEFT JOIN
        rombel r ON s.rombel_id = r.id
    WHERE 
        j.tanggal BETWEEN ? AND ?
        AND s.rombel_id = ?
        AND j.status = 'published'
    GROUP BY 
        s.id, s.nama, s.nisn, s.nis, MONTH(j.tanggal), YEAR(j.tanggal)
    ORDER BY 
        s.nama, YEAR(j.tanggal), MONTH(j.tanggal)
";
```

## 3. **Struktur Data untuk Processing**

```php
$dataSiswa = [];
$currentSiswa = null;

foreach ($absensiData as $row) {
    if ($currentSiswa !== $row['siswa_id']) {
        // Siswa baru
        $currentSiswa = $row['siswa_id'];
        $dataSiswa[$currentSiswa] = [
            'nama' => $row['nama'],
            'nisn' => $row['nisn'],
            'nis' => $row['nis'],
            'rombel' => $row['nama_rombel'],
            'bulanan' => [],
            'total' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0]
        ];
    }
    
    // Data per bulan
    $bulanTahun = $row['bulan'] . '-' . $row['tahun'];
    $dataSiswa[$currentSiswa]['bulanan'][$bulanTahun] = [
        'hadir' => $row['hadir'],
        'izin' => $row['izin'],
        'sakit' => $row['sakit'],
        'alfa' => $row['alfa']
    ];
    
    // Akumulasi total
    $dataSiswa[$currentSiswa]['total']['hadir'] += $row['hadir'];
    $dataSiswa[$currentSiswa]['total']['izin'] += $row['izin'];
    $dataSiswa[$currentSiswa]['total']['sakit'] += $row['sakit'];
    $dataSiswa[$currentSiswa]['total']['alfa'] += $row['alfa'];
}
```

## 4. **Header Kolom Dinamis**

```php
// Set header berdasarkan jenis periode
$header = ['NO', 'Nama', 'NISN', 'NIS', 'Bulan', 'Rekap', 'Hadir', 'Izin', 'Sakit', 'Alfa'];

if ($isTahunan) {
    $header[4] = 'Tahun'; // Ubah "Bulan" menjadi "Tahun"
    $header[5] = $periodeText; // Tahun untuk rekap
} else {
    $header[5] = $periodeText; // Periode untuk rekap
}

// Judul laporan
$judul = "ABSENSI SISWA : " . ($dataSiswa ? $dataSiswa[array_key_first($dataSiswa)]['rombel'] : 'KELAS');
$dicetakPada = "Dicetak pada : " . date('d/m/Y H:i:s');
```

## 5. **Format Output Excel**

```php
$rowIndex = 5; // Mulai dari baris 5 untuk data
$rowNumber = 1;

foreach ($dataSiswa as $siswaId => $siswa) {
    if ($isTahunan) {
        // Tampilkan 1 baris per siswa (rekap tahunan)
        $sheet->setCellValue('A' . $rowIndex, $rowNumber++);
        $sheet->setCellValue('B' . $rowIndex, $siswa['nama']);
        $sheet->setCellValue('C' . $rowIndex, $siswa['nisn']);
        $sheet->setCellValue('D' . $rowIndex, $siswa['nis']);
        $sheet->setCellValue('E' . $rowIndex, $periodeText); // Tahun
        $sheet->setCellValue('F' . $rowIndex, 'Rekap Tahunan');
        $sheet->setCellValue('G' . $rowIndex, $siswa['total']['hadir']);
        $sheet->setCellValue('H' . $rowIndex, $siswa['total']['izin']);
        $sheet->setCellValue('I' . $rowIndex, $siswa['total']['sakit']);
        $sheet->setCellValue('J' . $rowIndex, $siswa['total']['alfa']);
        $rowIndex++;
    } else {
        // Tampilkan multiple baris per siswa (per bulan)
        $firstRow = true;
        foreach ($siswa['bulanan'] as $bulanTahun => $dataBulan) {
            list($bulan, $tahun) = explode('-', $bulanTahun);
            $namaBulan = DateTime::createFromFormat('!m', $bulan)->format('F');
            
            $sheet->setCellValue('A' . $rowIndex, $firstRow ? $rowNumber : '');
            $sheet->setCellValue('B' . $rowIndex, $firstRow ? $siswa['nama'] : '');
            $sheet->setCellValue('C' . $rowIndex, $firstRow ? $siswa['nisn'] : '');
            $sheet->setCellValue('D' . $rowIndex, $firstRow ? $siswa['nis'] : '');
            $sheet->setCellValue('E' . $rowIndex, $namaBulan . ' ' . $tahun);
            $sheet->setCellValue('F' . $rowIndex, 'Rekap Bulanan');
            $sheet->setCellValue('G' . $rowIndex, $dataBulan['hadir']);
            $sheet->setCellValue('H' . $rowIndex, $dataBulan['izin']);
            $sheet->setCellValue('I' . $rowIndex, $dataBulan['sakit']);
            $sheet->setCellValue('J' . $rowIndex, $dataBulan['alfa']);
            
            $rowIndex++;
            $firstRow = false;
        }
        
        // Baris total untuk periode
        if (count($siswa['bulanan']) > 1) {
            $sheet->setCellValue('A' . $rowIndex, '');
            $sheet->setCellValue('B' . $rowIndex, '');
            $sheet->setCellValue('C' . $rowIndex, '');
            $sheet->setCellValue('D' . $rowIndex, '');
            $sheet->setCellValue('E' . $rowIndex, 'Total Periode');
            $sheet->setCellValue('F' . $rowIndex, $periodeText);
            $sheet->setCellValue('G' . $rowIndex, $siswa['total']['hadir']);
            $sheet->setCellValue('H' . $rowIndex, $siswa['total']['izin']);
            $sheet->setCellValue('I' . $rowIndex, $siswa['total']['sakit']);
            $sheet->setCellValue('J' . $rowIndex, $siswa['total']['alfa']);
            $rowIndex++;
        }
        
        $rowIndex++; // Beri jarak antar siswa
        $rowNumber++;
    }
}
```

## 6. **Kondisi Khusus Berdasarkan Database**

### **Kondisi 1: Jan-Des Tahun Sama**
- **Bulan** → "Tahun"
- **Rekap** → Tahun (contoh: "2024")
- **Output**: 1 baris per siswa dengan total tahunan

### **Kondisi 2: 1 Bulan**
- **Bulan** → Nama bulan (contoh: "Januari 2024")
- **Rekap** → "Rekap Bulanan"
- **Output**: 1 baris per siswa

### **Kondisi 3: Beberapa Bulan**
- **Bulan** → Nama bulan per baris
- **Rekap** → "Rekap Bulanan" per bulan + "Total Periode"
- **Output**: Multiple baris per siswa + baris total

## 7. **Filter Tambahan (Sesuai Database)**

```php
// Filter berdasarkan rombel_id jika dipilih
$rombelFilter = "";
$params = [$startDate, $endDate];

if (!empty($rombelId)) {
    $rombelFilter = " AND s.rombel_id = ?";
    $params[] = $rombelId;
}

// Query final dengan filter
$query = "
    SELECT 
        s.id as siswa_id,
        s.nama,
        s.nisn,
        s.nis,
        MONTH(j.tanggal) as bulan,
        YEAR(j.tanggal) as tahun,
        COUNT(CASE WHEN a.status = 'hadir' THEN 1 END) as hadir,
        COUNT(CASE WHEN a.status = 'izin' THEN 1 END) as izin,
        COUNT(CASE WHEN a.status = 'sakit' THEN 1 END) as sakit,
        COUNT(CASE WHEN a.status = 'alfa' THEN 1 END) as alfa,
        r.nama_rombel,
        r.kode_rombel
    FROM 
        siswa s
    LEFT JOIN 
        absensi a ON s.id = a.siswa_id 
    LEFT JOIN
        jurnal_new j ON a.jurnal_id = j.id
    LEFT JOIN
        rombel r ON s.rombel_id = r.id
    WHERE 
        j.tanggal BETWEEN ? AND ?
        AND j.status = 'published'
        $rombelFilter
    GROUP BY 
        s.id, s.nama, s.nisn, s.nis, MONTH(j.tanggal), YEAR(j.tanggal)
    ORDER BY 
        r.nama_rombel, s.nama, YEAR(j.tanggal), MONTH(j.tanggal)
";
```

Logika ini sudah disesuaikan dengan struktur database Anda yang menghubungkan `absensi` dengan `jurnal_new` melalui `jurnal_id`, dan `siswa` dengan `rombel` melalui `rombel_id`.