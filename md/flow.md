# Flow Aplikasi Jurnal Mengajar Berdasarkan Database

## 🔄 **Authentication Flow**

### **1. Login Process**
```
User Access App → Login Form → Validation → Check Credentials → 
    ↓
[Success] → Create Session → Redirect to Dashboard
    ↓
[Failed] → Show Error → Return to Login Form
```

### **2. Session Management**
```
Every Request → AuthMiddleware → Check Session → 
    ↓
[Valid] → Check Role Permissions → Continue to Page
    ↓
[Invalid] → Redirect to Login → Clear Session
```

## 👨‍🏫 **Guru Flow**

### **3. Dashboard Guru**
```
Login Success → GuruDashboard → 
    ↓
Display: [Stats Jurnal Bulan Ini] [Jurnal Terakhir] [Quick Actions]
    ↓
Quick Actions: [Buat Jurnal Baru] [Lihat Jurnal Saya] [Edit Profil]
```

### **4. Buat Jurnal Baru Flow**
```
Click "Buat Jurnal" → Show Jurnal Form → 
    ↓
Step 1: Fill Basic Info (tanggal, kelas_id, mapel_id, topik)
    ↓
Step 2: Fill Pembelajaran (tujuan_pembelajaran, aktivitas_pembelajaran)
    ↓
Step 3: Fill Refleksi (refleksi_guru, kendala, tindak_lanjut)
    ↓
Step 4: Add P5 Dimensions (jurnal_p5 - multiple records)
    ↓
Step 5: Add Asesmen (jurnal_asesmen - multiple records)
    ↓
Step 6: Upload Lampiran (jurnal_lampiran - optional)
    ↓
Validation → Save to Database → 
    ↓
[Success] → Show Success Message → Redirect to Jurnal List
    ↓
[Failed] → Show Error → Return to Form with Data
```

### **5. Lihat/Edit Jurnal Flow**
```
Jurnal List → Click Jurnal → Check Status → 
    ↓
If status = 'draft' → Show Edit Form → Update jurnal table
    ↓
If status = 'published' → Show Read-Only View → 
    ↓
Options: [Export PDF] [Kembali ke List]
```

### **6. Pencarian & Filter Jurnal**
```
Jurnal List Page → Apply Filters (tanggal, kelas, status) → 
    ↓
Build Query → Execute → Display Results → 
    ↓
Pagination → Export Options (PDF/Excel)
```

## 👨‍💼 **Admin Flow**

### **7. Dashboard Admin**
```
Login Success → AdminDashboard → 
    ↓
Query: 
- SELECT COUNT(*) FROM users WHERE role='guru' AND is_active=1
- SELECT COUNT(*) FROM jurnal WHERE tanggal BETWEEN ... 
- SELECT * FROM jurnal WHERE status='draft' ORDER BY created_at DESC
    ↓
Display: [Total Guru] [Jurnal Bulan Ini] [Guru Belum Input] [Chart Stats]
```

### **8. Monitoring Jurnal Flow**
```
Admin Dashboard → Click "Monitoring Jurnal" → 
    ↓
Query: SELECT j.*, u.nama FROM jurnal j JOIN users u ON j.user_id = u.id
    ↓
Apply Filters (user_id, tanggal, kelas_id) → 
    ↓
Display Table → Options: [View Detail] [Export]
```

### **9. View Jurnal Detail (Admin)**
```
Jurnal List → Click View → 
    ↓
Query: 
- SELECT * FROM jurnal WHERE id = ?
- SELECT * FROM jurnal_p5 WHERE jurnal_id = ?
- SELECT * FROM jurnal_asesmen WHERE jurnal_id = ?
- SELECT * FROM jurnal_lampiran WHERE jurnal_id = ?
    ↓
Display Complete Jurnal Data (Read-Only)
```

### **10. Manajemen User Flow**
```
Admin Dashboard → Click "Kelola User" → 
    ↓
Query: SELECT * FROM users WHERE role IN ('guru','admin')
    ↓
Display User List → Options: [Tambah User] [Edit] [Reset Password] [Non-Aktif]
```

## 🗃️ **Database Operation Flow**

### **11. CREATE Jurnal Process**
```sql
-- 1. Insert main jurnal record
INSERT INTO jurnal (user_id, tanggal, kelas_id, mapel_id, topik, tujuan_pembelajaran, ...)
VALUES (?, ?, ?, ?, ?, ?, ...);

-- 2. Get last insert ID
SET @jurnal_id = LAST_INSERT_ID();

-- 3. Insert P5 dimensions (multiple)
INSERT INTO jurnal_p5 (jurnal_id, dimensi, aktivitas) VALUES
(@jurnal_id, 'bernalar_kritis', 'Analisis kasus...'),
(@jurnal_id, 'kreatif', 'Membuat proyek...');

-- 4. Insert asesmen records
INSERT INTO jurnal_asesmen (jurnal_id, jenis_asesmen, hasil, siswa_tuntas, siswa_total)
VALUES (@jurnal_id, 'Kuis', 'Hasil kuis baik...', 25, 30);

-- 5. Insert lampiran if exists
INSERT INTO jurnal_lampiran (jurnal_id, nama_file, file_path, tipe_file)
VALUES (@jurnal_id, 'foto_kegiatan.jpg', '/uploads/...', 'image/jpeg');
```

### **12. READ Jurnal Process**
```sql
-- Get complete jurnal data
SELECT 
    j.*,
    u.nama as guru_nama,
    k.nama_kelas,
    m.nama_mapel
FROM jurnal j
JOIN users u ON j.user_id = u.id
JOIN kelas k ON j.kelas_id = k.id
JOIN mata_pelajaran m ON j.mapel_id = m.id
WHERE j.id = ?;

-- Get P5 dimensions
SELECT * FROM jurnal_p5 WHERE jurnal_id = ?;

-- Get asesmen data
SELECT * FROM jurnal_asesmen WHERE jurnal_id = ?;

-- Get lampiran
SELECT * FROM jurnal_lampiran WHERE jurnal_id = ?;
```

### **13. UPDATE Jurnal Process**
```sql
-- Start transaction
START TRANSACTION;

-- 1. Update main jurnal record
UPDATE jurnal SET 
    topik = ?, 
    tujuan_pembelajaran = ?,
    updated_at = NOW()
WHERE id = ? AND user_id = ?;

-- 2. Delete existing P5 records
DELETE FROM jurnal_p5 WHERE jurnal_id = ?;

-- 3. Insert new P5 records
INSERT INTO jurnal_p5 (jurnal_id, dimensi, aktivitas) VALUES ...;

-- 4. Similar process for asesmen and lampiran
COMMIT;
```

### **14. DELETE Jurnal Process**
```sql
-- Soft delete (recommended)
UPDATE jurnal SET status = 'deleted' WHERE id = ? AND user_id = ?;

-- OR Hard delete with transaction
START TRANSACTION;
DELETE FROM jurnal_p5 WHERE jurnal_id = ?;
DELETE FROM jurnal_asesmen WHERE jurnal_id = ?;
DELETE FROM jurnal_lampiran WHERE jurnal_id = ?;
DELETE FROM jurnal WHERE id = ? AND user_id = ?;
COMMIT;
```

## 📊 **Reporting Flow**

### **15. Export Jurnal PDF**
```
User Request Export → Build Data Query → Generate PDF Template → 
    ↓
Query: Complete jurnal data with all relations → 
    ↓
Format PDF → Include: [Header Sekolah] [Data Jurnal] [P5 Dimensions] [Asesmen] [Lampiran List]
    ↓
Download/Save PDF → Log Activity
```

### **16. Statistik & Analytics Flow**
```
Dashboard Load → Execute Multiple Queries → 
    ↓
-- Jurnal completeness rate
SELECT COUNT(*) as total, 
       COUNT(CASE WHEN status = 'published' THEN 1 END) as published
FROM jurnal 
WHERE user_id = ? AND MONTH(tanggal) = ?;

-- P5 dimensions distribution
SELECT dimensi, COUNT(*) as count 
FROM jurnal_p5 jp 
JOIN jurnal j ON jp.jurnal_id = j.id 
WHERE j.user_id = ? 
GROUP BY dimensi;

-- Asesmen success rate
SELECT AVG(siswa_tuntas / siswa_total) * 100 as success_rate
FROM jurnal_asesmen ja 
JOIN jurnal j ON ja.jurnal_id = j.id 
WHERE j.user_id = ?;
```

## 🔐 **Security Flow**

### **17. Authorization Check**
```
Each Page Request → AuthFilter → 
    ↓
Check Session → Get User Role → 
    ↓
Check Route Permissions → 
    ↓
[Allowed] → Continue to Controller
    ↓
[Denied] → Show 403 Error Page
```

### **18. Data Ownership Validation**
```
Jurnal Access Request (View/Edit/Delete) → 
    ↓
Query: SELECT user_id FROM jurnal WHERE id = ? → 
    ↓
If Current_User_ID = jurnal.user_id OR Current_User_Role = 'admin' → Allow Access
    ↓
Else → Show 403 Error
```

## 📱 **UI/UX Flow**

### **19. Form Validation Flow**
```
User Input Form → Client-Side Validation → 
    ↓
[Valid] → Submit to Server → Server-Side Validation → 
    ↓
[Valid] → Process Data → Show Success
    ↓
[Invalid] → Show Error Messages → Highlight Invalid Fields
```

### **20. File Upload Flow**
```
Select File → Client Check (size, type) → Upload to Temp → 
    ↓
Server Validation → Move to Permanent Storage → 
    ↓
Save to jurnal_lampiran table → Return Success/Error
```

## 🔄 **Batch Operations Flow**

### **21. Bulk Export Flow**
```
Select Multiple Jurnal → Choose Export Format (PDF/Excel) → 
    ↓
Process Each Jurnal → Generate Individual Files → 
    ↓
Compress to ZIP → Provide Download Link → Cleanup Temp Files
```

### **22. Data Backup Flow**
```
Scheduled Job (Cron) → Export Database → 
    ↓
Generate SQL Dump → Compress → Upload to Cloud/Backup Server → 
    ↓
Log Backup Activity → Cleanup Old Backups
```

## ⚠️ **Error Handling Flow**

### **23. Database Error Flow**
```
Database Operation → Catch Exception → 
    ↓
Log Error (with context) → Show User-Friendly Message → 
    ↓
Rollback Transaction if Needed → Return to Safe State
```

### **24. File Operation Error Flow**
```
File Operation → Check Permissions → Check Disk Space → 
    ↓
[Success] → Continue Process
    ↓
[Failed] → Log Error → Show Message → Cleanup Partial Files
```

Flow ini mencakup seluruh proses bisnis aplikasi berdasarkan struktur database yang telah dirancang, memastikan konsistensi data dan pengalaman pengguna yang optimal.