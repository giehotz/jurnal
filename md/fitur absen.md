# Flow Fitur Manajemen Siswa untuk Absensi

## 🗃️ **Struktur Database Tambahan**

### **Tabel `siswa`**
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Siswa ID |
| nis | VARCHAR(20) | UNIQUE NOT NULL | NIS siswa |
| nisn | VARCHAR(20) | UNIQUE NULLABLE | NISN siswa |
| nama | VARCHAR(100) | NOT NULL | Nama lengkap siswa |
| jenis_kelamin | ENUM('L','P') | NOT NULL | Jenis kelamin |
| tanggal_lahir | DATE | NOT NULL | Tanggal lahir |
| kelas_id | INT | FOREIGN NOT NULL | ID kelas |
| is_active | TINYINT(1) | DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | |

### **Tabel `absensi`**
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Absensi ID |
| jurnal_id | INT | FOREIGN NOT NULL | ID jurnal |
| siswa_id | INT | FOREIGN NOT NULL | ID siswa |
| status | ENUM('hadir','sakit','izin','alfa') | DEFAULT 'hadir' | Status kehadiran |
| keterangan | TEXT | NULLABLE | Keterangan tambahan |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

## 📁 **Struktur File Baru**

```
app/
├── Controllers/
│   └── Admin/
│       └── Siswa.php
├── Models/
│   ├── SiswaModel.php
│   └── AbsensiModel.php
└── Views/
    └── admin/
        └── siswa/
            ├── siswa.php          # List siswa
            ├── create.php         # Form tambah siswa
            ├── edit.php           # Form edit siswa
            ├── import.php         # Form import excel
            └── absensi_report.php # Laporan absensi
```

## 🔄 **Flow Lengkap**

### **1. Admin Management Siswa Flow**

#### **A. Tambah Siswa Manual**
```
Admin → Menu Siswa → Click "Tambah Siswa" → 
    ↓
Form:`siswa_id` bigint(13) NOT NULL,
  `siswa_nis` varchar(20) DEFAULT NULL,
  `siswa_nisn` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `siswa_nama` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tingkat_id` char(3) DEFAULT NULL,
  `kelas_id` bigint(20) DEFAULT NULL,
  `siswa_gender` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `siswa_tempat` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `siswa_tgllahir` date DEFAULT NULL,
  `password` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lembaga_id` varchar(100) DEFAULT NULL,
  `i_entry` varchar(300) DEFAULT NULL,
  `d_entry` datetime DEFAULT NULL,
  `i_update` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `d_update` datetime DEFAULT NULL,
  `siswa_foto` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `siswa_folder` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `siswa_agama` varchar(100) DEFAULT NULL,
  `siswa_pendidikan` varchar(100) DEFAULT NULL,
  `siswa_alamat` text DEFAULT NULL,
  `nama_ayah` varchar(300) DEFAULT NULL,
  `nama_ibu` varchar(300) DEFAULT NULL,
  `pekerjaan_ayah` varchar(4) DEFAULT NULL,
  `pekerjaan_ayah_lain` varchar(50) DEFAULT '',
  `pekerjaan_ibu` varchar(4) DEFAULT NULL,
  `pekerjaan_ibu_lain` varchar(50) DEFAULT '',
  `nik_ayah` int(16) DEFAULT NULL,
  `nik_ibu` int(16) DEFAULT NULL,
  `alamat_ortu` text DEFAULT NULL,
  `desa_id` int(11) DEFAULT NULL,
  `kecamatan_id` int(11) DEFAULT NULL,
  `kabupaten_id` int(11) DEFAULT NULL,
  `provinsi_id` int(11) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `pekerjaan_wali` varchar(4) DEFAULT NULL,
  `pekerjaan_wali_lain` varchar(50) DEFAULT '',
  `alamat_wali` text DEFAULT NULL,
  `tahunajaran_id` char(4) DEFAULT NULL,
  `migrasi` int(1) DEFAULT 0,
  `siswa_statuskel` varchar(20) DEFAULT NULL,
  `siswa_anakke` varchar(3) DEFAULT NULL,
  `sekolah_asal` varchar(100) DEFAULT NULL,
  `siswa_telpon` varchar(20) DEFAULT NULL,
  `telpon_ortu` varchar(20) DEFAULT NULL,
  `telpon_wali` varchar(20) DEFAULT NULL,
  `tanggal_terima` date DEFAULT NULL,
  `siswa_kelasterima` int(11) DEFAULT NULL,
  `siswa_alasan_mutasi` varchar(255) DEFAULT NULL,
  `siswa_tahun_mutasi` int(5) DEFAULT NULL,
  `siswa_semester_mutasi` int(4) DEFAULT NULL'
    ↓
Validation → 
    ↓
[Success] → INSERT INTO siswa → Show Success Message
    ↓
[Failed] → Show Error → Return to Form
```

#### **B. Import Siswa dari Excel**
```
Admin → Menu Siswa → Click "Import Excel" → 
    ↓
Upload File Excel → Validate Format → 
    ↓
Process Each Row → Validate Data → 
    ↓
Bulk Insert → Show Result (Success/Failed Rows) → 
    ↓
Download Template if Needed
```

#### **C. Edit/Update Siswa**
```
Siswa List → Click "Edit" → Load Data from `siswa` table → 
    ↓
Pre-fill Form → User Edit → Validation → 
    ↓
UPDATE siswa SET ... WHERE id = ? → Show Result
```

### **2. Guru Absensi Flow**

#### **A. Integrasi dengan Jurnal**
```
Guru → Buat Jurnal Baru → Step: Data Pembelajaran → 
    ↓
Auto-load siswa based on `kelas_id` → 
    ↓
Tampilkan List Siswa dengan radio button: [Hadir] [Sakit] [Izin] [Alfa]
    ↓
Guru pilih status untuk setiap siswa → 
    ↓
Saat Simpan Jurnal → Simpan ke tabel `absensi`
```

#### **B. Proses Simpan Absensi**
```sql
-- Setelah jurnal dibuat, simpan absensi
SET @jurnal_id = LAST_INSERT_ID();

-- Untuk setiap siswa dalam kelas
INSERT INTO absensi (jurnal_id, siswa_id, status, keterangan) VALUES
(@jurnal_id, 1, 'hadir', NULL),
(@jurnal_id, 2, 'sakit', 'Surat dokter terlampir'),
(@jurnal_id, 3, 'izin', 'Ijin keluarga');
```

### **3. Laporan Absensi Flow**

#### **A. View Absensi per Jurnal**
```
Guru/Admin → View Jurnal Detail → 
    ↓
Query: SELECT a.*, s.nama FROM absensi a JOIN siswa s ON a.siswa_id = s.id WHERE jurnal_id = ?
    ↓
Tampilkan tabel absensi dengan status warna:
- Hadir: Hijau
- Sakit: Kuning  
- Izin: Biru
- Alfa: Merah
```

#### **B. Laporan Rekap Absensi**
```
Admin → Menu Laporan → Pilih Kelas & Periode → 
    ↓
Query Complex: 
SELECT 
    s.nis, s.nama,
    COUNT(CASE WHEN a.status = 'hadir' THEN 1 END) as total_hadir,
    COUNT(CASE WHEN a.status = 'sakit' THEN 1 END) as total_sakit,
    COUNT(CASE WHEN a.status = 'izin' THEN 1 END) as total_izin,
    COUNT(CASE WHEN a.status = 'alfa' THEN 1 END) as total_alfa
FROM siswa s
LEFT JOIN absensi a ON s.id = a.siswa_id 
LEFT JOIN jurnal j ON a.jurnal_id = j.id
WHERE s.kelas_id = ? AND j.tanggal BETWEEN ? AND ?
GROUP BY s.id
    ↓
Tampilkan Rekap → Export Excel/PDF
```

## 🎯 **Fitur Utama Views**

### **Views/admin/siswa/siswa.php**
```php
// Fitur:
- [Tambah Siswa] button
- [Import Excel] button  
- [Export Excel] button
- Table List Siswa dengan kolom: NIS, Nama, Kelas, Status
- Action: [Edit] [Non-Aktifkan]
- Search & Filter by Kelas
- Pagination
```

### **Views/admin/siswa/create.php**
```php
// Form fields:
- NIS (required, unique)
- NISN (optional, unique) 
- Nama Lengkap (required)
- Jenis Kelamin (radio: L/P)
- Tanggal Lahir (date picker)
- Kelas (dropdown from `kelas` table)
```

### **Integrasi dengan View Jurnal Guru**
```php
// Di views/guru/jurnal/create.php - tambah section absensi:
<section class="absensi-section">
    <h5>Data Absensi Siswa</h5>
    <table class="table">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($siswa_kelas as $siswa): ?>
            <tr>
                <td><?= $siswa['nis'] ?></td>
                <td><?= $siswa['nama'] ?></td>
                <td>
                    <select name="absensi[<?= $siswa['id'] ?>][status]">
                        <option value="hadir">Hadir</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                        <option value="alfa">Alfa</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="absensi[<?= $siswa['id'] ?>][keterangan]" 
                           placeholder="Keterangan...">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
```

## 🔧 **API Endpoints Baru**

### **Siswa Management**
| Method | Endpoint | Deskripsi | Role |
|--------|----------|-----------|------|
| GET | /admin/siswa | List siswa | Admin |
| POST | /admin/siswa | Tambah siswa | Admin |
| PUT | /admin/siswa/{id} | Update siswa | Admin |
| DELETE | /admin/siswa/{id} | Non-aktifkan siswa | Admin |
| POST | /admin/siswa/import | Import Excel | Admin |

### **Absensi Management**  
| Method | Endpoint | Deskripsi | Role |
|--------|----------|-----------|------|
| POST | /api/absensi | Simpan absensi | Guru |
| GET | /api/absensi/jurnal/{id} | Get absensi by jurnal | Guru,Admin |
| GET | /api/absensi/report | Laporan absensi | Admin |

## 📊 **Business Logic Baru**

### **Validation Rules Siswa**
```php
$rules = [
    'nis' => 'required|is_unique[siswa.nis]',
    'nisn' => 'permit_empty|is_unique[siswa.nisn]',
    'nama' => 'required|min_length[3]',
    'jenis_kelamin' => 'required|in_list[L,P]',
    'tanggal_lahir' => 'required|valid_date',
    'kelas_id' => 'required|is_not_unique[kelas.id]'
];
```

### **Auto-load Siswa di Jurnal**
```php
// Di JurnalController - method create()
$kelas_id = $this->request->getPost('kelas_id');
$siswaModel = new SiswaModel();
$data['siswa_kelas'] = $siswaModel->where('kelas_id', $kelas_id)
                                 ->where('is_active', 1)
                                 ->findAll();
```

### **Process Absensi di JurnalController**
```php
// Setelah jurnal berhasil disimpan
if($jurnal_id) {
    $absensiData = $this->request->getPost('absensi');
    $absensiModel = new AbsensiModel();
    
    foreach($absensiData as $siswa_id => $data) {
        $absensiModel->insert([
            'jurnal_id' => $jurnal_id,
            'siswa_id' => $siswa_id,
            'status' => $data['status'],
            'keterangan' => $data['keterangan']
        ]);
    }
}
```

## 🚀 **Flow Export Laporan**

### **Export Rekap Absensi**
```
Admin → Pilih: Kelas, Tanggal Mulai, Tanggal Akhir → 
    ↓
Query Data → Format Excel → 
    ↓
Kolom: NIS, Nama, Total Hadir, Sakit, Izin, Alfa, Persentase Kehadiran
    ↓
Download File → Auto-delete setelah 24 jam
```

Flow ini mengintegrasikan fitur siswa dan absensi secara lengkap ke dalam sistem jurnal yang sudah ada, memberikan kemampuan lengkap untuk manajemen kehadiran siswa.