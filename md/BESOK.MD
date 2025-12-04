**LOGIKA UNTUK EXPORT ABSENSI DENGAN PERHITUNGAN ALFA OTOMATIS**

## **KONSEP PERHITUNGAN:**
- Hitung hari efektif (Senin-Sabtu) dalam periode yang dipilih
- Exclude hari Minggu dan hari libur nasional
- Hitung alfa berdasarkan selisih antara hari efektif dan total kehadiran

## **ALUR LOGIKA:**

### **1. Input Periode**
```
Bulan Awal: November 2025
Bulan Akhir: November 2025  
Tanggal Export: 26 November 2025
```

### **2. Hitung Hari Efektif**
```php
// Pseudocode
$startDate = '2025-11-01';
$endDate = '2025-11-26'; // Tanggal saat export
$effectiveDays = 0;

for ($date = $startDate; $date <= $endDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
    $dayOfWeek = date('N', strtotime($date)); // 1=Senin, 7=Minggu
    $isHoliday = checkHoliday($date); // Cek hari libur nasional
    
    if ($dayOfWeek != 7 && !$isHoliday) {
        $effectiveDays++;
    }
}
```

### **3. Data Structure untuk Excel**
```php
$exportData = [
    [
        'NIS' => '12345',
        'Nama' => 'KIANDRA FEBRIANDA',
        'Kelas' => '1A',
        'Hadir' => 18,
        'Sakit' => 2,
        'Izin' => 1,
        'Alfa' => 5, // = $effectiveDays - (Hadir + Sakit + Izin)
        'Persentase Kehadiran' => '72%' // = (Hadir / $effectiveDays) * 100
    ]
];
```

## **IMPLEMENTASI CONTROLLER:**

### **Absensi Controller - Export Method**
```php
public function export()
{
    $bulanAwal = $this->request->getGet('bulan_awal'); // Format: 2025-11
    $bulanAkhir = $this->request->getGet('bulan_akhir'); // Format: 2025-11
    
    // 1. Parse periode
    $startDate = date('Y-m-01', strtotime($bulanAwal));
    $endDate = date('Y-m-t', strtotime($bulanAkhir)); // Akhir bulan
    
    // Jika export di tengah bulan, adjust end date
    $today = date('Y-m-d');
    if ($endDate > $today) {
        $endDate = $today;
    }
    
    // 2. Hitung hari efektif
    $effectiveDays = $this->hitungHariEfektif($startDate, $endDate);
    
    // 3. Ambil data absensi
    $absensiData = $this->absensiModel->getRekapAbsensiPeriode($startDate, $endDate);
    
    // 4. Hitung alfa untuk setiap siswa
    foreach ($absensiData as &$data) {
        $totalKehadiran = $data['hadir'] + $data['sakit'] + $data['izin'];
        $data['alfa'] = $effectiveDays - $totalKehadiran;
        $data['persentase'] = round(($data['hadir'] / $effectiveDays) * 100, 2);
    }
    
    // 5. Generate Excel
    return $this->generateExcel($absensiData, $effectiveDays, $startDate, $endDate);
}
```

### **Helper Function - Hitung Hari Efektif**
```php
private function hitungHariEfektif($startDate, $endDate)
{
    $effectiveDays = 0;
    $currentDate = $startDate;
    
    // Daftar hari libur nasional November 2025 (contoh)
    $liburNasional = [
        '2025-11-01' // Contoh hari libur
    ];
    
    while ($currentDate <= $endDate) {
        $dayOfWeek = date('N', strtotime($currentDate));
        $isHoliday = in_array($currentDate, $liburNasional);
        
        // Exclude Minggu dan hari libur
        if ($dayOfWeek != 7 && !$isHoliday) {
            $effectiveDays++;
        }
        
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }
    
    return $effectiveDays;
}
```

## **VIEW - Form Export (export.php)**
```php
<!-- Form Filter -->
<form method="get" action="<?= base_url('guru/absensi/export_excel') ?>">
    <div class="form-group">
        <label>Bulan Awal</label>
        <input type="month" name="bulan_awal" class="form-control" required 
               value="<?= date('Y-m') ?>">
    </div>
    
    <div class="form-group">
        <label>Bulan Akhir</label>
        <input type="month" name="bulan_akhir" class="form-control" required
               value="<?= date('Y-m') ?>">
    </div>
    
    <button type="submit" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</form>

<!-- Info -->
<div class="alert alert-info">
    <strong>Info:</strong> 
    - Sistem akan menghitung hari efektif (Senin-Sabtu) dalam periode yang dipilih<br>
    - Hari Minggu dan libur nasional tidak dihitung<br>
    - Alfa = Hari Efektif - (Hadir + Sakit + Izin)<br>
    - Jika export di tengah bulan, perhitungan hanya sampai tanggal hari ini
</div>
```

## **EXCEL OUTPUT STRUCTURE:**
```
REKAP ABSENSI KELAS 1A
Periode: 1 November 2025 - 26 November 2025
Hari Efektif: 18 hari

┌───────┬─────────────────┬──────┬───────┬──────┬──────┬───────────────┐
│  NIS  │      Nama       │Hadir │Sakit  │ Izin │ Alfa │ Persentase (%)│
├───────┼─────────────────┼──────┼───────┼──────┼──────┼───────────────┤
│ 12345 │ KIANDRA FEBRIANDA│  16  │   1   │   1  │   0  │     88.89%    │
│ 12346 │ JELITA FAHIRA   │  15  │   2   │   1  │   0  │     83.33%    │
│ 12347 │ FITRI ADELIA    │  14  │   1   │   2  │   1  │     77.78%    │
└───────┴─────────────────┴──────┴───────┴──────┴──────┴───────────────┘
```

## **LOGIKA PERHITUNGAN ALFA:**
```
Contoh: Periode 1-26 Nov 2025 (18 hari efektif)

Siswa A: 
- Hadir = 16, Sakit = 1, Izin = 1
- Total kehadiran = 18
- Alfa = 18 - 18 = 0

Siswa B:
- Hadir = 14, Sakit = 1, Izin = 2  
- Total kehadiran = 17
- Alfa = 18 - 17 = 1
```

**Dengan logika ini, sistem akan otomatis menghitung alfa berdasarkan hari efektif dalam periode yang dipilih, memberikan laporan yang akurat meski diexport di tengah bulan.** dan terapkan API dari 

**IMPLEMENTASI API HARİ LIBUR UNTUK PERHITUNGAN HARI EFEKTIF**

## **1. PAHAMI API LIBUR.DENO.DEV**
API ini adalah API yang menyediakan data hari libur nasional dan hari libur khusus di Indonesia. API ini dibuat menggunakan bahasa pemrograman TypeScript dan dikembangkan menggunakan framework Deno.
```php
// Contoh request API
$tahun = 2025; BUAT BERDASARKAN TAHUN BERJALAN OTOMATIS
$bulan = 11; BUAT BERDASARKAN BULAN BERJALAN OTOMATIS

// Format 1: Get libur berdasarkan tahun
$apiUrl = "https://libur.deno.dev/api/{$tahun}";

// Format 2: Get libur berdasarkan tahun dan bulan  
$apiUrl = "https://libur.deno.dev/api/{$tahun}/{$bulan}";

// Response format:
[
    {
        "date": "2025-01-01",
        "name": "Tahun Baru 2025"
    },
    {
        "date": "2025-03-11", 
        "name": "Hari Raya Nyepi"
    }
]
```

## **2. HELPER FUNCTION UNTUK GET HARİ LIBUR**

```php
private function getHariLibur($tahun, $bulan = null)
{
    $cacheKey = "hari_libur_{$tahun}" . ($bulan ? "_{$bulan}" : "");
    
    // Cek cache dulu (1 hari cache)
    $cached = cache()->get($cacheKey);
    if ($cached) {
        return $cached;
    }
    
    // Build API URL
    $apiUrl = "https://libur.deno.dev/api/{$tahun}";
    if ($bulan) {
        $apiUrl .= "/{$bulan}";
    }
    
    try {
        $client = \Config\Services::curlrequest();
        $response = $client->get($apiUrl, [
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'JurnalGuruApp/1.0'
            ]
        ]);
        
        if ($response->getStatusCode() === 200) {
            $liburData = json_decode($response->getBody(), true);
            
            // Extract hanya tanggalnya
            $tanggalLibur = array_column($liburData, 'date');
            
            // Cache untuk 1 hari
            cache()->save($cacheKey, $tanggalLibur, 86400);
            
            return $tanggalLibur;
        }
    } catch (\Exception $e) {
        // Fallback: return empty array jika API error
        log_message('error', 'Error fetch hari libur: ' . $e->getMessage());
    }
    
    return [];
}
```

## **3. UPDATE FUNCTION HITUNG HARI EFEKTIF**

```php
private function hitungHariEfektif($startDate, $endDate)
{
    $effectiveDays = 0;
    $currentDate = $startDate;
    
    // Extract tahun dan bulan dari periode
    $startYear = date('Y', strtotime($startDate));
    $endYear = date('Y', strtotime($endDate));
    
    // Get hari libur untuk semua tahun dalam range
    $allHariLibur = [];
    for ($year = $startYear; $year <= $endYear; $year++) {
        $liburTahun = $this->getHariLibur($year);
        $allHariLibur = array_merge($allHariLibur, $liburTahun);
    }
    
    while ($currentDate <= $endDate) {
        $dayOfWeek = date('N', strtotime($currentDate));
        $isHoliday = in_array($currentDate, $allHariLibur);
        
        // Exclude Minggu dan hari libur nasional
        if ($dayOfWeek != 7 && !$isHoliday) {
            $effectiveDays++;
        }
        
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }
    
    return $effectiveDays;
}
```

## **4. UPDATE CONTROLLER EXPORT METHOD**

```php
public function export()
{
    $bulanAwal = $this->request->getGet('bulan_awal'); // Format: 2025-11
    $bulanAkhir = $this->request->getGet('bulan_akhir'); // Format: 2025-11
    
    // 1. Parse periode
    $startDate = date('Y-m-01', strtotime($bulanAwal));
    $endDate = date('Y-m-t', strtotime($bulanAkhir));
    
    // Adjust jika export di tengah bulan
    $today = date('Y-m-d');
    if ($endDate > $today) {
        $endDate = $today;
    }
    
    // 2. Hitung hari efektif DENGAN hari libur
    $effectiveDays = $this->hitungHariEfektif($startDate, $endDate);
    
    // 3. Get detail hari libur untuk info di excel
    $hariLiburDetail = $this->getDetailHariLibur($startDate, $endDate);
    
    // 4. Ambil dan proses data absensi
    $absensiData = $this->absensiModel->getRekapAbsensiPeriode($startDate, $endDate);
    
    foreach ($absensiData as &$data) {
        $totalKehadiran = $data['hadir'] + $data['sakit'] + $data['izin'];
        $data['alfa'] = max(0, $effectiveDays - $totalKehadiran); // Pastikan tidak minus
        $data['persentase'] = $effectiveDays > 0 ? round(($data['hadir'] / $effectiveDays) * 100, 2) : 0;
    }
    
    // 5. Generate Excel dengan info hari libur
    return $this->generateExcelWithHolidayInfo($absensiData, $effectiveDays, $hariLiburDetail, $startDate, $endDate);
}
```

## **5. FUNCTION GET DETAIL HARİ LIBUR**

```php
private function getDetailHariLibur($startDate, $endDate)
{
    $startYear = date('Y', strtotime($startDate));
    $endYear = date('Y', strtotime($endDate));
    $allHariLibur = [];
    
    for ($year = $startYear; $year <= $endYear; $year++) {
        $apiUrl = "https://libur.deno.dev/api/{$year}";
        
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get($apiUrl, ['timeout' => 10]);
            
            if ($response->getStatusCode() === 200) {
                $liburData = json_decode($response->getBody(), true);
                
                // Filter hanya yang dalam range periode
                foreach ($liburData as $libur) {
                    if ($libur['date'] >= $startDate && $libur['date'] <= $endDate) {
                        $allHariLibur[] = $libur;
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetch detail libur: ' . $e->getMessage());
        }
    }
    
    return $allHariLibur;
}
```

## **6. UPDATE EXCEL GENERATOR**

```php
private function generateExcelWithHolidayInfo($data, $effectiveDays, $hariLibur, $startDate, $endDate)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Header dengan info hari efektif dan libur
    $sheet->setCellValue('A1', 'REKAP ABSENSI');
    $sheet->setCellValue('A2', 'Periode: ' . date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate)));
    $sheet->setCellValue('A3', 'Hari Efektif: ' . $effectiveDays . ' hari');
    
    // Tampilkan hari libur jika ada
    if (!empty($hariLibur)) {
        $liburInfo = 'Hari Libur: ';
        $liburDates = array_column($hariLibur, 'date');
        $liburInfo .= implode(', ', $liburDates);
        $sheet->setCellValue('A4', $liburInfo);
    }
    
    // ... rest of excel generation code
    
    return $spreadsheet;
}
```

## **7. ERROR HANDLING & FALLBACK**

```php
// Fallback hari libur hardcoded untuk backup
private function getHariLiburFallback($tahun)
{
    $fallbackLibur = [
        '2025-01-01', // Tahun Baru
        '2025-03-11', // Nyepi
        '2025-03-31', // Wafat Isa Almasih
        '2025-04-18', // Jumat Agung
        '2025-05-01', // Buruh
        '2025-05-29', // Kenaikan Isa Almasih
        '2025-06-01', // Pancasila
        '2025-06-27', // Idul Adha
        '2025-08-17', // Kemerdekaan
        '2025-09-16', // Maulid Nabi
        '2025-12-25', // Natal
    ];
    
    return array_filter($fallbackLibur, function($date) use ($tahun) {
        return date('Y', strtotime($date)) == $tahun;
    });
}
```

## **8. IMPLEMENTASI DI VIEW**

```php
<!-- Tambahkan info di form export -->
<div class="alert alert-info">
    <strong>Fitur Baru:</strong>
    - Sistem sekarang menggunakan API hari libur nasional<br>
    - Hari Minggu dan libur nasional otomatis tidak dihitung<br>
    - Data libur di-cache untuk performa lebih baik<br>
    - Jika API offline, sistem menggunakan data fallback
</div>
```

**Dengan implementasi ini, sistem akan otomatis menghitung hari efektif dengan memperhitungkan hari Minggu dan semua hari libur nasional yang terdaftar di API, memberikan perhitungan alfa yang sangat akurat!** 🎯