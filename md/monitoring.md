**LOGIKA REDESAIN HALAMAN MONITORING/LAPORAN STATISTIK**

## **STRUKTUR HALAMAN BARU:**

### **1. DASHBOARD OVERVIEW**
- **Cards Summary**: Total Jurnal, Total Absensi, Rata-rata Kehadiran, Kelas Aktif
- **Period Filter**: Hari Ini, Minggu Ini, Bulan Ini, Custom Range
- **Quick Actions**: Export Excel, Refresh Data

### **2. CHARTS & VISUALIZATION**

#### **Chart 1: Aktivitas Jurnal & Absensi Harian (7 Hari Terakhir)**
```
Line Chart:
- Sumbu X: Tanggal (7 hari terakhir)
- Sumbu Y: Jumlah
- Line 1: Jurnal dibuat per hari
- Line 2: Absensi diisi per hari
- Tampilkan trend aktivitas guru
```

#### **Chart 2: Statistik Kehadiran Siswa (Pie Chart)**
```
Pie Chart - Total Semester Ini:
- Hijau: Hadir (65%)
- Kuning: Sakit (10%) 
- Biru: Izin (8%)
- Merah: Alfa (17%)
- Total Siswa: 500
```

#### **Chart 3: Rekap Kehadiran per Kelas (Bar Chart)**
```
Vertical Bar Chart:
- Sumbu X: Nama Kelas (1A, 1B, 1C, 2A, dst)
- Sumbu Y: Persentase Kehadiran (%)
- Setiap bar: Tinggi = % kehadiran kelas
- Warna: Hijau (>80%), Kuning (60-80%), Merah (<60%)
```

#### **Chart 4: Trend Bulanan Jurnal & Absensi**
```
Area Chart - 6 Bulan Terakhir:
- Sumbu X: Bulan (Jul, Aug, Sep, Oct, Nov, Dec)
- Sumbu Y: Jumlah Aktivitas
- Area 1: Jurnal per bulan
- Area 2: Absensi per bulan
- Tampilkan growth/decline trend
```

### **3. DATA TABLES DETAIL**

#### **Table 1: Rekap Harian Jurnal**
```
┌───────────┬────────────┬─────────────┬────────────┬──────────┐
│   Tanggal  │ Jurnal Baru │ Jurnal Edit │ Total Guru │ Status   │
├───────────┼────────────┼─────────────┼────────────┼──────────┤
│ 24 Nov 25 │     15      │      3      │     18     │ ✅ Active │
│ 23 Nov 25 │     12      │      5      │     15     │ ✅ Active │
└───────────┴────────────┴─────────────┴────────────┴──────────┘
```

#### **Table 2: Rekap Kehadiran per Kelas (Detail)**
```
┌─────────┬──────────┬──────┬───────┬──────┬──────┬─────────────┐
│  Kelas  │ Wali Kelas │Hadir│ Sakit │ Izin │ Alfa │ Persentase  │
├─────────┼──────────┼──────┼───────┼──────┼──────┼─────────────┤
│  1A     │ Guru Budi │ 420  │  35   │  28  │  17  │    84% ✅    │
│  1B     │ Guru Ani  │ 385  │  42   │  35  │  38  │    77% ⚠️    │
│  1C     │ Guru Citra│ 350  │  55   │  40  │  55  │    70% ❌    │
└─────────┴──────────┴──────┴───────┴──────┴──────┴─────────────┘
```

#### **Table 3: Monitoring Guru Aktif**
```
┌──────────────┬────────────┬────────────┬──────────────┐
│    Nama Guru  │ Mapel       │ Jurnal (7hr)│ Absensi (7hr) │
├──────────────┼────────────┼────────────┼──────────────┤
│ Guru Budi    │ PJOK       │     7      │      7       │
│ Guru Ani     │ Matematika │     5      │      6       │
│ Guru Citra   │ IPA        │     3      │      4       │
└──────────────┴────────────┴────────────┴──────────────┘
```

## **BUSINESS LOGIC & QUERIES:**

### **Query 1: Statistik Jurnal Harian**
```sql
SELECT 
    DATE(created_at) as tanggal,
    COUNT(*) as total_jurnal,
    COUNT(DISTINCT user_id) as guru_aktif
FROM jurnal_new 
WHERE created_at BETWEEN ? AND ?
GROUP BY DATE(created_at)
ORDER BY tanggal DESC
LIMIT 7
```

### **Query 2: Rekap Kehadiran per Kelas**
```sql
SELECT 
    r.nama_rombel as kelas,
    u.nama as wali_kelas,
    SUM(CASE WHEN a.status = 'hadir' THEN 1 ELSE 0 END) as hadir,
    SUM(CASE WHEN a.status = 'sakit' THEN 1 ELSE 0 END) as sakit,
    SUM(CASE WHEN a.status = 'izin' THEN 1 ELSE 0 END) as izin,
    SUM(CASE WHEN a.status = 'alfa' THEN 1 ELSE 0 END) as alfa,
    COUNT(DISTINCT a.siswa_id) as total_siswa
FROM absensi a
JOIN rombel r ON a.rombel_id = r.id
LEFT JOIN users u ON r.wali_kelas = u.id
WHERE a.tanggal BETWEEN ? AND ?
GROUP BY r.id, r.nama_rombel, u.nama
```

### **Query 3: Aktivitas Guru**
```sql
SELECT 
    u.nama as guru,
    mp.nama_mapel as mapel,
    COUNT(DISTINCT j.id) as total_jurnal,
    COUNT(DISTINCT a.id) as total_absensi
FROM users u
LEFT JOIN jurnal_new j ON u.id = j.user_id AND j.created_at BETWEEN ? AND ?
LEFT JOIN absensi a ON u.id = a.guru_id AND a.created_at BETWEEN ? AND ?
LEFT JOIN mata_pelajaran mp ON j.mapel_id = mp.id
WHERE u.role = 'guru' AND u.is_active = 1
GROUP BY u.id, u.nama, mp.nama_mapel
```

### **Query 4: Trend Bulanan**
```sql
SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as bulan,
    COUNT(*) as total_aktivitas,
    'jurnal' as tipe
FROM jurnal_new 
WHERE created_at BETWEEN ? AND ?
GROUP BY DATE_FORMAT(created_at, '%Y-%m')

UNION ALL

SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as bulan,
    COUNT(*) as total_aktivitas,
    'absensi' as tipe
FROM absensi 
WHERE created_at BETWEEN ? AND ?
GROUP BY DATE_FORMAT(created_at, '%Y-%m')
```

## **FILTER & INTERAKTIVITAS:**

### **Filter Options:**
- **Rentang Waktu**: Hari Ini, 7 Hari, 30 Hari, Bulan Ini, Custom Date Range
- **Tingkat Kelas**: Filter per tingkat (1-12)
- **Status**: Tampilkan hanya kelas aktif / semua

### **Interaktivitas:**
- **Click Chart** → Filter table berdasarkan data yang di-click
- **Hover Chart** → Tooltip detail informasi
- **Export Button** → Download PDF/Excel laporan lengkap
- **Refresh Button** → Update data real-time

## **LAYOUT DESIGN:**

```
┌─────────────────────────────────────────────────────────────┐
│  📊 DASHBOARD MONITORING JURNAL & ABSENSI                   │
├─────────────────────────────────────────────────────────────┤
│ [Hari Ini] [7 Hari] [Bulan Ini] [Custom] [🔄 Refresh]       │
├──────────────┬──────────────┬──────────────┬───────────────┤
│  📝 45 Jurnal│ 📋 38 Absensi │ 👥 84% Hadir │ 🏫 12 Kelas   │
├──────────────┴──────────────┴──────────────┴───────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐            │
│ │ Aktivitas   │ │ Kehadiran   │ │ Kelas       │            │
│ │ Harian      │ │ Siswa       │ │ Performance │            │
│ │ [Line Chart]│ │ [Pie Chart] │ │ [Bar Chart] │            │
│ └─────────────┘ └─────────────┘ └─────────────┘            │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │                  Trend Bulanan                          │ │
│ │                  [Area Chart]                           │ │
│ └─────────────────────────────────────────────────────────┘ │
│ ┌─────────────────────────┬───────────────────────────────┐ │
│ │ Rekap Jurnal Harian     │ Rekap Kehadiran Kelas         │ │
│ │ [Table]                 │ [Table]                       │ │
│ └─────────────────────────┴───────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │              Monitoring Guru Aktif                      │ │
│ │                     [Table]                             │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## **DATA INTEGRATION LOGIC:**

### **Sinkronisasi Jurnal-Absensi:**
- **Jurnal tanpa Absensi**: Tandai sebagai "perlu absensi"
- **Absensi tanpa Jurnal**: Tandai sebagai "perlu jurnal"  
- **Data lengkap**: Tandai sebagai "✅ complete"

### **Performance Indicators:**
- **Kelas Excellent**: Kehadiran >85%, Jurnal lengkap
- **Kelas Good**: Kehadiran 70-85%, Jurnal >80%
- **Kelas Need Attention**: Kehadiran <70%, Jurnal <80%

**Dengan logika ini, admin bisa memonitor kesehatan akademik sekolah secara real-time dengan visualisasi yang jelas dan actionable insights!** 📊✨