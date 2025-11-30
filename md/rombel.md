# Flow Fitur Rombongan Belajar (Rombel)

## 🗃️ **Struktur Database Tambahan**

### **Tabel `rombel`** (Rombongan Belajar)
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Rombel ID |
| kode_rombel | VARCHAR(10) | UNIQUE NOT NULL | ex: "X-IPA-1" |
| nama_rombel | VARCHAR(50) | NOT NULL | ex: "10 IPA 1" |
| tingkat | ENUM('10','11','12') | NOT NULL | Tingkat kelas |
| jurusan | VARCHAR(50) | NULLABLE | ex: "IPA", "IPS" |
| wali_kelas | INT | FOREIGN NULLABLE | ID guru wali kelas |
| tahun_ajaran | VARCHAR(9) | NOT NULL | ex: "2024/2025" |
| semester | ENUM('1','2') | DEFAULT '1' | Semester |
| kapasitas | INT | DEFAULT 36 | Kapasitas siswa |
| is_active | TINYINT(1) | DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | |

### **Update Tabel `kelas`** (sekarang untuk master kelas)
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Kelas ID |
| kode_kelas | VARCHAR(10) | UNIQUE NOT NULL | ex: "KELAS-10" |
| nama_kelas | VARCHAR(50) | NOT NULL | ex: "Kelas 10" |
| tingkat | ENUM('10','11','12') | NOT NULL | Tingkat kelas |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### **Update Tabel `siswa`**
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Siswa ID |
| nis | VARCHAR(20) | UNIQUE NOT NULL | NIS siswa |
| nisn | VARCHAR(20) | UNIQUE NULLABLE | NISN siswa |
| nama | VARCHAR(100) | NOT NULL | Nama lengkap siswa |
| jenis_kelamin | ENUM('L','P') | NOT NULL | Jenis kelamin |
| tanggal_lahir | DATE | NOT NULL | Tanggal lahir |
| **rombel_id** | INT | FOREIGN NOT NULL | **ID rombel (ganti dari kelas_id)** |
| is_active | TINYINT(1) | DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | |

### **Tabel `rombel_siswa`** (History penempatan siswa)
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | History ID |
| siswa_id | INT | FOREIGN NOT NULL | ID siswa |
| rombel_id | INT | FOREIGN NOT NULL | ID rombel |
| tahun_ajaran | VARCHAR(9) | NOT NULL | Tahun ajaran |
| semester | ENUM('1','2') | NOT NULL | Semester |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

## 📁 **Struktur File Baru**

```
app/
├── Controllers/
│   └── Admin/
│       └── Rombel.php
├── Models/
│   ├── RombelModel.php
│   └── KelasModel.php
└── Views/
    └── admin/
        └── rombel/
            ├── rombel.php          # List rombel
            ├── create.php          # Form tambah rombel
            ├── edit.php            # Form edit rombel
            ├── detail.php          # Detail rombel + list siswa
            ├── manage_siswa.php    # Kelola siswa dalam rombel
            └── import_siswa.php    # Import siswa ke rombel
```

## 🔄 **Flow Lengkap Rombel**

### **1. Admin Management Rombel Flow**

#### **A. Buat Rombel Baru**
```
Admin → Menu Rombel → Click "Tambah Rombel" → 
    ↓
Form: [kode_rombel, nama_rombel, tingkat, jurusan, wali_kelas, tahun_ajaran, semester, kapasitas]
    ↓
Validation → 
    ↓
[Success] → INSERT INTO rombel → Show Success Message
    ↓
[Failed] → Show Error → Return to Form
```

#### **B. List Rombel dengan Filter**
```
Admin → Menu Rombel → 
    ↓
Filter by: [Tahun Ajaran] [Tingkat] [Jurusan] [Semester]
    ↓
Query: SELECT * FROM rombel WHERE tahun_ajaran = ? AND tingkat = ? ...
    ↓
Display Table dengan kolom: Kode, Nama, Tingkat, Jurusan, Wali Kelas, Jumlah Siswa, Kapasitas
```

### **2. Kelola Siswa dalam Rombel Flow**

#### **A. Tambah Siswa ke Rombel**
```
Admin → Rombel List → Click "Kelola Siswa" → 
    ↓
Tampilkan: [List Siswa dalam Rombel] [Form Tambah Siswa] [Import Excel]
    ↓
Form Tambah: Pilih dari daftar siswa yang belum punya rombel
    ↓
INSERT INTO siswa SET rombel_id = ? WHERE id = ?
INSERT INTO rombel_siswa (siswa_id, rombel_id, tahun_ajaran, semester)
```

#### **B. Pindah/Keluar Siswa dari Rombel**
```
Rombel Detail → Click "Pindah Siswa" → 
    ↓
Pilih siswa → Pilih rombel tujuan → 
    ↓
UPDATE siswa SET rombel_id = ? WHERE id = ?
INSERT INTO rombel_siswa (history record)
```

### **3. Integrasi dengan Jurnal Guru**

#### **A. Guru Pilih Rombel saat Buat Jurnal**
```
Guru → Buat Jurnal → Pilih Rombel (bukan kelas) → 
    ↓
Auto-load siswa berdasarkan `rombel_id` → 
    ↓
Tampilkan list siswa untuk absensi
```

#### **B. Query untuk Get Siswa by Rombel**
```sql
-- Di JurnalController
SELECT * FROM siswa 
WHERE rombel_id = ? AND is_active = 1 
ORDER BY nama;
```

## 🎯 **Fitur Utama Views**

### **Views/admin/rombel/rombel.php**
```php
// Fitur:
- [Tambah Rombel] button
- Filter: Tahun Ajaran, Tingkat, Jurusan, Semester
- Table List Rombel dengan kolom: 
  * Kode Rombel
  * Nama Rombel 
  * Tingkat
  * Jurusan
  * Wali Kelas
  * Jumlah Siswa / Kapasitas
  * Status
- Action: [Edit] [Detail] [Kelola Siswa] [Non-Aktifkan]
```

### **Views/admin/rombel/create.php**
```php
// Form fields:
- Kode Rombel (required, unique) - ex: "X-IPA-1"
- Nama Rombel (required) - ex: "10 IPA 1"
- Tingkat (dropdown: 10, 11, 12)
- Jurusan (dropdown: IPA, IPS, Bahasa, dll)
- Wali Kelas (dropdown dari tabel users where role='guru')
- Tahun Ajaran (dropdown: 2024/2025, 2025/2026)
- Semester (radio: 1, 2)
- Kapasitas (number, default: 36)
```

### **Views/admin/rombel/detail.php**
```php
// Menampilkan:
- Info Rombel (kode, nama, wali kelas, dll)
- Statistik: Total Siswa, Kapasitas, Persentase
- List Siswa dalam rombel dengan kolom:
  * NIS
  * Nama Siswa
  * Jenis Kelamin
  * Action: [Pindah] [Keluar]
- [Tambah Siswa] button
- [Import Siswa] button
```

### **Views/admin/rombel/manage_siswa.php**
```php
// Form tambah siswa ke rombel:
- Pilih dari daftar siswa yang belum memiliki rombel
- Atau tambah siswa baru langsung
- Validation: tidak melebihi kapasitas
```

## 🔧 **API Endpoints Baru**

### **Rombel Management**
| Method | Endpoint | Deskripsi | Role |
|--------|----------|-----------|------|
| GET | /admin/rombel | List rombel | Admin |
| POST | /admin/rombel | Tambah rombel | Admin |
| GET | /admin/rombel/{id} | Detail rombel | Admin |
| PUT | /admin/rombel/{id} | Update rombel | Admin |
| DELETE | /admin/rombel/{id} | Non-aktifkan rombel | Admin |
| GET | /admin/rombel/{id}/siswa | Get siswa rombel | Admin |

### **Siswa Rombel Management**
| Method | Endpoint | Deskripsi | Role |
|--------|----------|-----------|------|
| POST | /admin/rombel/{id}/siswa | Tambah siswa ke rombel | Admin |
| PUT | /admin/rombel/{id}/siswa/{siswaId} | Pindah siswa | Admin |
| DELETE | /admin/rombel/{id}/siswa/{siswaId} | Keluarkan siswa | Admin |

## 📊 **Business Logic Rombel**

### **Validation Rules Rombel**
```php
$rules = [
    'kode_rombel' => 'required|is_unique[rombel.kode_rombel]',
    'nama_rombel' => 'required',
    'tingkat' => 'required|in_list[10,11,12]',
    'tahun_ajaran' => 'required|regex_match[/\d{4}\/\d{4}/]',
    'semester' => 'required|in_list[1,2]',
    'kapasitas' => 'required|numeric|greater_than[0]'
];
```

### **Auto-generate Kode Rombel (Opsional)**
```php
// Di RombelModel
public function generateKodeRombel($tingkat, $jurusan)
{
    $count = $this->where('tingkat', $tingkat)
                 ->where('jurusan', $jurusan)
                 ->countAllResults();
    
    $urutan = $count + 1;
    return $tingkat . '-' . $jurusan . '-' . $urutan;
}
```

### **Check Kapasitas Rombel**
```php
// Sebelum tambah siswa ke rombel
public function checkKapasitas($rombel_id)
{
    $rombel = $this->find($rombel_id);
    $totalSiswa = $this->db->table('siswa')
                          ->where('rombel_id', $rombel_id)
                          ->where('is_active', 1)
                          ->countAllResults();
    
    return $totalSiswa < $rombel['kapasitas'];
}
```

## 🔄 **Flow Naik Tingkat/Kelas**

### **4. Proses Kenaikan Tingkat**
```
Akhir Tahun Ajaran → Admin → Menu "Kenaikan Tingkat" → 
    ↓
Pilih Rombel Awal (ex: X-IPA-1) → Pilih Rombel Tujuan (ex: XI-IPA-1) → 
    ↓
Process: 
- UPDATE siswa SET rombel_id = [tujuan] WHERE rombel_id = [awal]
- INSERT INTO rombel_siswa (history untuk tahun baru)
- Non-aktifkan rombel lama jika needed
```

### **5. History Penempatan Siswa**
```
View Siswa Detail → Tab "History Kelas" → 
    ↓
Query: SELECT * FROM rombel_siswa rs JOIN rombel r ON rs.rombel_id = r.id WHERE siswa_id = ? ORDER BY tahun_ajaran, semester
    ↓
Tampilkan timeline perpindahan rombel
```

## 🚀 **Integration dengan Existing Flow**

### **Update Jurnal Flow**
```php
// Di JurnalController - ganti kelas_id menjadi rombel_id
public function create()
{
    // Get rombel based on guru's assignment or all active rombel
    $rombelModel = new RombelModel();
    $data['rombel_list'] = $rombelModel->where('is_active', 1)->findAll();
    
    // Saat rombel dipilih, load siswanya
    $rombel_id = $this->request->getPost('rombel_id');
    $siswaModel = new SiswaModel();
    $data['siswa_rombel'] = $siswaModel->where('rombel_id', $rombel_id)
                                      ->where('is_active', 1)
                                      ->findAll();
}
```

### **Update Absensi Flow**
```sql
-- Query absensi sekarang berdasarkan rombel
SELECT a.*, s.nama, r.nama_rombel 
FROM absensi a
JOIN siswa s ON a.siswa_id = s.id
JOIN rombel r ON s.rombel_id = r.id
WHERE a.jurnal_id = ?;
```

Dengan implementasi rombel ini, sistem menjadi lebih fleksibel untuk mengelola penempatan siswa berdasarkan tahun ajaran dan semester, serta memudahkan proses kenaikan tingkat.