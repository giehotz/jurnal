# Blueprint Aplikasi Jurnal Mengajar Guru - Kurikulum Merdeka

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Struktur Database](#struktur-database)
3. [API Endpoints](#api-endpoints)
4. [Struktur Folder](#struktur-folder)
5. [Fitur Modul](#fitur-modul)
6. [Security & Validation](#security--validation)

## 🎯 Overview

### Deskripsi
Aplikasi web berbasis CodeIgniter 4 untuk manajemen jurnal mengajar guru dalam implementasi Kurikulum Merdeka.

### Target Pengguna
- **Guru**: Input dan kelola jurnal mengajar
- **Admin**: Monitoring dan supervisi jurnal
- **Super Admin**: Master administrator

## 🗃️ Struktur Database

### Diagram Relasi
```
users → jurnal → jurnal_asesmen
          ↓
          jurnal_p5
          ↓
          jurnal_lampiran
```

### Tabel `users`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | User ID |
| nip | VARCHAR(20) | UNIQUE NULLABLE | NIP Guru |
| nama | VARCHAR(100) | NOT NULL | Nama lengkap |
| email | VARCHAR(100) | UNIQUE NOT NULL | Email login |
| password | VARCHAR(255) | NOT NULL | Password hash |
| role | ENUM('guru','admin','super_admin') | DEFAULT 'guru' | Role user |
| mata_pelajaran | VARCHAR(100) | NULLABLE | Mapel yang diampu |
| is_active | TINYINT(1) | DEFAULT 1 | Status aktif |
| last_login | DATETIME | NULLABLE | Last login |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | |

### Tabel `kelas`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Kelas ID |
| kode_kelas | VARCHAR(10) | UNIQUE NOT NULL | ex: "X-IPA-1" |
| nama_kelas | VARCHAR(50) | NOT NULL | ex: "10 IPA 1" |
| fase | VARCHAR(5) | NOT NULL | ex: "Fase E" |
| wali_kelas | INT | FOREIGN NULLABLE | ID guru wali kelas |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### Tabel `mata_pelajaran`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Mapel ID |
| kode_mapel | VARCHAR(10) | UNIQUE NOT NULL | Kode mapel |
| nama_mapel | VARCHAR(100) | NOT NULL | Nama mata pelajaran |
| fase | VARCHAR(5) | NOT NULL | Fase kurikulum |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### Tabel `jurnal`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Jurnal ID |
| user_id | INT | FOREIGN NOT NULL | ID guru pembuat |
| tanggal | DATE | NOT NULL | Tanggal mengajar |
| kelas_id | INT | FOREIGN NOT NULL | ID kelas |
| mapel_id | INT | FOREIGN NOT NULL | ID mata pelajaran |
| topik | VARCHAR(200) | NOT NULL | Topik pembelajaran |
| tujuan_pembelajaran | TEXT | NOT NULL | TP dari RPP |
| aktivitas_pembelajaran | TEXT | NOT NULL | Deskripsi aktivitas |
| refleksi_guru | TEXT | NOT NULL | Refleksi guru |
| kendala | TEXT | NULLABLE | Kendala yang dihadapi |
| tindak_lanjut | TEXT | NULLABLE | Rencana tindak lanjut |
| status | ENUM('draft','published') | DEFAULT 'draft' | Status jurnal |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | |

### Tabel `jurnal_p5`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | P5 ID |
| jurnal_id | INT | FOREIGN NOT NULL | ID jurnal |
| dimensi | ENUM('beriman','berakhlak','berkebinekaan','bergotong_royong','mandiri','bernalar_kritis','kreatif') | NOT NULL | Dimensi P5 |
| aktivitas | TEXT | NOT NULL | Deskripsi aktivitas P5 |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### Tabel `jurnal_asesmen`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Asesmen ID |
| jurnal_id | INT | FOREIGN NOT NULL | ID jurnal |
| jenis_asesmen | VARCHAR(100) | NOT NULL | ex: "Kuis", "Observasi" |
| hasil | TEXT | NOT NULL | Hasil asesmen |
| siswa_tuntas | INT | DEFAULT 0 | Jumlah tuntas |
| siswa_total | INT | DEFAULT 0 | Total siswa |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

### Tabel `jurnal_lampiran`
| Field | Type | Constraints | Keterangan |
|-------|------|-------------|------------|
| id | INT | PRIMARY AI | Lampiran ID |
| jurnal_id | INT | FOREIGN NOT NULL | ID jurnal |
| nama_file | VARCHAR(255) | NOT NULL | Nama file asli |
| file_path | VARCHAR(500) | NOT NULL | Path penyimpanan |
| tipe_file | VARCHAR(50) | NOT NULL | MIME type |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | |

## 🌐 API Endpoints

### Authentication
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | /api/auth/login | Login user | Public |
| POST | /api/auth/logout | Logout | All |
| GET | /api/auth/me | Get profile | All |

### Jurnal Management
| Method | Endpoint | Deskripsi | Role |
|--------|----------|-----------|------|
| GET | /api/jurnal | List jurnal | Guru,Admin |
| POST | /api/jurnal | Buat jurnal baru | Guru |
| GET | /api/jurnal/{id} | Detail jurnal | Guru,Admin |
| PUT | /api/jurnal/{id} | Update jurnal | Guru |
| DELETE | /api/jurnal/{id} | Hapus jurnal | Guru |
| GET | /api/jurnal/export | Export jurnal | Guru,Admin |

### Admin Management
| Method | Endpoint | Deskripsi | Role |
|--------|----------|-----------|------|
| GET | /api/admin/dashboard | Stats dashboard | Admin |
| GET | /api/admin/jurnal | Monitoring jurnal | Admin |
| GET | /api/admin/users | List users | Admin |
| POST | /api/admin/users | Tambah user | Admin |

## 📁 Struktur Folder

```
app/
├── Config/
│   ├── Database.php
│   ├── Auth.php
│   └── Validation.php
├── Controllers/
│   ├── Auth.php
│   ├── Guru/
│   │   ├── Dashboard.php
│   │   ├── Jurnal.php
│   │   └── Profile.php
│   └── Admin/
│       ├── Dashboard.php
│       ├── Monitoring.php
│       └── UserManagement.php
├── Models/
│   ├── UserModel.php
│   ├── JurnalModel.php
│   ├── KelasModel.php
│   ├── MapelModel.php
│   └── AsesmenModel.php
├── Entities/
│   ├── UserEntity.php
│   └── JurnalEntity.php
├── Views/
│   ├── templates/
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   └── footer.php
│   ├── auth/
│   │   ├── login.php
│   │   └── forgot_password.php
│   ├── guru/
│   │   ├── dashboard.php
│   │   ├── jurnal/
│   │   │   ├── list.php
│   │   │   ├── create.php
│   │   │   ├── edit.php
│   │   │   └── view.php
│   │   └── profile.php
│   └── admin/
│       ├── dashboard.php
│       ├── monitoring/
│       │   ├── jurnal.php
│       │   └── rekap.php
│       └── users/
│           ├── list.php
│           └── create.php
├── Libraries/
│   ├── AuthLibrary.php
│   └── PdfGenerator.php
├── Helpers/
│   ├── jurnal_helper.php
│   └── validation_helper.php
└── Filters/
    ├── AuthFilter.php
    └── RoleFilter.php
```

## 🎯 Fitur Modul

### Module: Authentication
- Multi-role login system
- Session management
- Auto logout after inactivity
- Password reset functionality

### Module: Guru Dashboard
- Quick stats (jurnal bulan ini, status)
- Recent jurnal activities
- Quick action buttons
- Notifications for incomplete tasks

### Module: Jurnal Management
**Create Jurnal:**
- Form dengan validasi client & server
- Auto-save draft functionality
- Lampiran file upload (image, pdf, doc)
- P5 dimensions selector

**List Jurnal:**
- Pagination & search
- Filter by date, status, kelas
- Bulk actions
- Export to PDF

**View/Edit Jurnal:**
- Read-only view for published
- Edit capability for drafts
- Version history
- Audit trail

### Module: Admin Monitoring
**Dashboard Admin:**
- Total guru aktif
- Jurnal completeness rate
- Recent activities feed
- Guru performance metrics

**Jurnal Monitoring:**
- View all jurnal across teachers
- Filter by guru, date range, status
- Read-only access
- Export capabilities

**User Management:**
- CRUD operations for users
- Role management
- Activation/deactivation
- Password reset

## 🔒 Security & Validation

### Authentication Security
- BCrypt password hashing
- Session timeout 30 minutes
- CSRF protection
- XSS filtering

### Data Validation Rules

**User Registration:**
```php
$rules = [
    'nama' => 'required|min_length[3]',
    'email' => 'required|valid_email|is_unique[users.email]',
    'password' => 'required|min_length[8]',
    'role' => 'required|in_list[guru,admin]'
];
```

**Jurnal Creation:**
```php
$rules = [
    'tanggal' => 'required|valid_date',
    'kelas_id' => 'required|is_not_unique[kelas.id]',
    'mapel_id' => 'required|is_not_unique[mata_pelajaran.id]',
    'tujuan_pembelajaran' => 'required|min_length[10]',
    'aktivitas_pembelajaran' => 'required|min_length[20]'
];
```

### File Upload Security
- MIME type validation (image/jpeg, image/png, application/pdf)
- Max file size: 5MB
- File name sanitization
- Secure storage path

## 📊 Business Logic

### Jurnal Workflow
1. **Draft** → Guru bisa edit/hapus
2. **Published** → Read-only, bisa export
3. **Auto-publish** setelah 7 hari di draft

### Access Control
- Guru hanya bisa akses jurnal milik sendiri
- Admin bisa akses semua jurnal
- Super Admin full system access

### Data Retention
- Jurnal data disimpan selamanya
- Soft delete untuk semua records
- Backup otomatis mingguan

---

**Catatan Development:**
- Menggunakan CodeIgniter 4.3+ 
- Database: MySQL 8.0+
- PHP version: 8.1+
- Responsive design dengan Bootstrap 5
- Localization ready untuk multi-bahasa

Blueprint ini memberikan panduan lengkap untuk mengembangkan aplikasi jurnal mengajar yang scalable dan maintainable.
Models untuk berinteraksi dengan database
Routes khusus untuk aplikasi
Library tambahan seperti PdfGenerator