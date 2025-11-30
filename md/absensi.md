## **Struktur MVC untuk Sistem Absensi**

### **MODEL (`app/Models/`)**

#### **1. AbsensiModel**
- `getAbsensiByDate($startDate, $endDate, $rombelId = null)`
- `getRekapAbsensi($tahunAjaran, $semester, $rombelId = null)`
- `inputAbsensiBatch($dataAbsensi)`
- `getStatistikHarian($tanggal)`

#### **2. SiswaAbsensiModel**
- `getSiswaByRombel($rombelId)`
- `getSiswaAktif($tahunAjaran)`

#### **3. RombelAbsensiModel**
- `getRombelAktif($tahunAjaran)`
- `getRombelWithWaliKelas()`

#### **4. KelasAbsensiModel**
- `getAllKelas()`
- `getKelasByTingkat($tingkat)`

---

### **CONTROLLER (`app/Controllers/Admin/`)**

#### **1. DashboardController**
- `index()` - Statistik harian, chart kehadiran

#### **2. AbsensiController**
- `index()` - List semua absensi dengan filter
- `create()` - Form input absensi harian
- `store()` - Simpan data absensi
- `edit($id)` - Form edit absensi
- `update($id)` - Update data absensi
- `delete($id)` - Hapus data absensi

#### **3. RekapController**
- `harian()` - Rekap per hari
- `bulanan()` - Rekap per bulan
- `semester()` - Rekap per semester

#### **4. LaporanAbsensiController**
- `printHarian()` - Cetak laporan harian
- `printBulanan()` - Cetak laporan bulanan
- `exportExcel()` - Export ke Excel

---

### **VIEW (`app/Views/admin/`)**

#### **Layout:**
-`admin/template/adminLTE`
- `admin/template/header`
- `admin/template/sidebar`
- `admin/template/footer`

#### **Pages:**
- `dashboard/index` - Statistik & overview
- `absensi/index` - Tabel data absensi
- `absensi/create` - Form input absensi
- `absensi/edit` - Form edit absensi
- `rekap/harian` - Tabel rekap harian
- `rekap/bulanan` - Chart & tabel bulanan
- `laporan/cetak` - Template cetak laporan

---

## **PERAN ADMIN:**

### **1. Monitoring & Dashboard**
- Melihat statistik kehadiran harian
- Monitoring persentase kehadiran per rombel
- Notifikasi ketidakhadiran mencurigakan

### **2. Manajemen Input Absensi**
- Input data absensi harian per rombel
- Edit koreksi data absensi yang salah
- Hapus data absensi tidak valid
- Input absensi massal/批量

### **3. Manajemen Filter & Pencarian**
- Filter berdasarkan tanggal, rombel, siswa
- Pencarian data absensi spesifik
- Filter periode (harian, mingguan, bulanan)

### **4. Manajemen Laporan & Rekap**
- Generate rekap harian/bulanan
- Cetak laporan kehadiran
- Export data ke Excel/PDF
- Analisis statistik kehadiran

### **5. Manajemen Referensi**
- View data siswa dan rombel (read-only)
- Filter siswa aktif per tahun ajaran

---

## **FLOW ADMIN:**

### **A. FLOW UTAMA:**
```
Login → Dashboard → 
    ├─ Input Absensi Harian
    ├─ Lihat Data Absensi 
    ├─ Rekap & Laporan
    └─ Export Data
```

### **B. FLOW DETAIL:**

#### **1. Input Absensi:**
```
Pilih Tanggal → Pilih Rombel → 
Tampilkan Daftar Siswa → 
Input Status (Hadir/Izin/Sakit/Alpha) → 
Simpan Batch → Konfirmasi Sukses
```

#### **2. Lihat Data Absensi:**
```
Pilih Periode → Pilih Rombel (opsional) → 
Tampilkan Tabel dengan Filter → 
Opsi Edit/Delete per record → 
Konfirmasi Aksi
```

#### **3. Rekap & Laporan:**
```
Pilih Jenis Rekap (Harian/Bulanan/Semester) → 
Pilih Parameter (Tanggal, Rombel) → 
Generate Laporan → 
Opsi Cetak/Export
```

### **C. BUSINESS RULES:**

1. **Validasi Input:**
   - Tidak bisa input absensi untuk tanggal mendatang
   - Batas waktu edit absensi (max 7 hari kebelakang)

2. **Data Consistency:**
   - Absensi hanya untuk siswa aktif
   - Relasi ke rombel tahun ajaran berjalan

3. **Security:**
   - Hanya admin yang bisa edit/hapus
   - Audit trail untuk perubahan data

### **D. NAVIGATION STRUCTURE:**
```
Dashboard
├─ Absensi
│  ├─ Input Harian
│  ├─ Data Absensi
│  └─ Koreksi Data
├─ Rekap
│  ├─ Harian
│  ├─ Bulanan
│  └─ Semesteran
└─ Laporan
   ├─ Cetak Laporan
   └─ Export Excel dan pdf
```

Struktur ini memastikan admin dapat mengelola absensi secara komprehensif dengan workflow yang jelas dan terstruktur.

## Analisis Relasi dan Logika Database

### **Struktur Tabel yang Diperlukan:**

#### **1. Tabel Siswa (Master Data)**
- Primary Key: `id`
- Menyimpan data pribadi siswa (NIS, Nama, dll)
- Status aktif/non-aktif

#### **2. Tabel Kelas (Master Data)** 
- Primary Key: `id`
- Menyimpan definisi kelas (X, XI, XII) dengan kode dan nama
- Struktur tetap tidak berubah per tahun

#### **3. Tabel Rombel (Tahun Ajaran)**
- Primary Key: `id`
- Menyimpan rombongan belajar PER TAHUN AJARAN
- Relasi ke wali kelas (guru)
- Status aktif/non-aktif

#### **4. Tabel Rombel_Siswa (Penempatan)**
- Menghubungkan siswa dengan rombel
- Satu siswa bisa di satu rombel per semester
- Menyimpan tahun ajaran dan semester

#### **5. Tabel Absensi (Transaksi)**
- Primary Key: `id`
- Foreign Key: `siswa_id` → Siswa.id
- Foreign Key: `jurnal_id` → (tabel jurnal/guru jika ada)

### **Logika Relasi:**

```
Siswa ←--- Rombel_Siswa ---→ Rombel
  ↓                              ↓
Absensi (siswa_id)           Kelas (referensi)
```

### **Flow Query untuk Sistem Absensi:**

1. **Input Absensi:**
   - Pilih rombel → tampilkan siswa di rombel tersebut
   - Input status kehadiran per siswa

2. **Lihat Data Absensi:**
   - Join: Absensi → Siswa → Rombel_Siswa → Rombel
   - Filter berdasarkan tanggal, rombel, atau siswa

3. **Rekapitulasi:**
   - Group by rombel, status kehadiran
   - Hitung persentase kehadiran per siswa/rombel

### **Aturan Bisnis:**

1. Satu siswa hanya bisa di satu rombel per semester
2. Absensi terkait dengan siswa, bukan langsung ke rombel
3. Rombel berubah per tahun ajaran, kelas tetap
4. Status kehadiran: Hadir, Izin, Sakit, Alpha
5. Data absensi tidak boleh diubah setelah periode tertentu

### **Struktur Query Utama:**
```sql
-- Untuk menampilkan absensi dengan detail lengkap
SELECT 
    a.*,
    s.nis, s.nama,
    r.nama_rombel, r.tahun_ajaran,
    k.nama_kelas
FROM absensi a
JOIN siswa s ON a.siswa_id = s.id
JOIN rombel_siswa rs ON s.id = rs.siswa_id 
JOIN rombel r ON rs.rombel_id = r.id
JOIN kelas k ON r.kelas_id = k.id  -- asumsi ada relasi ke kelas
WHERE rs.tahun_ajaran = '2024/2025'
AND rs.semester = 1;
```

Ini memastikan data absensi selalu terkait dengan penempatan siswa yang benar di rombel tertentu.