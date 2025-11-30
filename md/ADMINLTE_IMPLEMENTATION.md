# Prompt Detail untuk AI Code Agent: Implementasi AdminLTE pada Proyek CodeIgniter

## Konteks Proyek
Saya memiliki aplikasi Jurnal Guru yang dibangun dengan CodeIgniter 4. Saat ini menggunakan template manual dan perlu dimigrasi ke AdminLTE untuk tampilan yang lebih profesional dan konsisten.

## Struktur Proyek Saat Ini
```
- Framework: CodeIgniter 4
- AdminLTE sudah tersedia di: public/AdminLTE/
- Template manual ada di: app/Views/
- Tiga role utama: Admin, Guru, Kepala Sekolah
```

## File-file yang Perlu Dimodifikasi

### 1. Layouts/Template Files
**Priority: CRITICAL**

Modifikasi file-file berikut untuk menggunakan AdminLTE:

#### Admin Layout
- `app/Views/admin/layouts/header.php`
- `app/Views/admin/layouts/sidebar.php`
- `app/Views/admin/layouts/footer.php`
- `app/Views/admin/layouts/template.php`

**Requirements:**
- Gunakan AdminLTE 3.x structure
- Include CSS dari `/AdminLTE/public/css/adminlte.min.css`
- Include JS dari `/AdminLTE/public/js/adminlte.min.js`
- Tambahkan Bootstrap 4 dependencies
- Tambahkan Font Awesome untuk icons
- Tambahkan jQuery dan Bootstrap JS

#### Guru Layout
- `app/Views/guru/` - buat folder layouts jika belum ada
- Buat file: `header.php`, `sidebar.php`, `footer.php`, `template.php`

#### Kepala Sekolah Layout
- Buat struktur serupa untuk role kepala sekolah

### 2. View Files yang Perlu Diupdate

#### Admin Views
**Dashboard:**
- `app/Views/admin/dashboard/index.php`
- `app/Views/admin/dashboard/new_index.php`

**User Management:**
- `app/Views/admin/users/index.php`
- `app/Views/admin/users/list.php`
- `app/Views/admin/users/create.php`
- `app/Views/admin/users/edit.php`
- `app/Views/admin/users/import.php`

**Kelas Management:**
- `app/Views/admin/kelas/index.php`
- `app/Views/admin/kelas/list.php`
- `app/Views/admin/kelas/create.php`
- `app/Views/admin/kelas/edit.php`
- `app/Views/admin/kelas/view.php`

**Mapel Management:**
- `app/Views/admin/mapel/index.php`
- `app/Views/admin/mapel/create.php`
- `app/Views/admin/mapel/edit.php`

**Laporan:**
- `app/Views/admin/laporan/index.php`
- `app/Views/admin/laporan/guru.php`
- `app/Views/admin/laporan/jurnal.php`
- `app/Views/admin/laporan/statistik.php`
- `app/Views/admin/laporan/export.php`

**Monitoring:**
- `app/Views/admin/monitoring/rekap.php`
- `app/Views/admin/monitoring/detail.php`
- `app/Views/admin/monitoring/jurnal.php`

**Export/Import:**
- `app/Views/admin/export/index.php`
- `app/Views/admin/export/import.php`

**Settings:**
- `app/Views/admin/settings/index.php`
- `app/Views/admin/profile/edit.php`
- `app/Views/admin/kepala_sekolah/edit.php`

#### Guru Views
**Dashboard:**
- `app/Views/guru/dashboard.php`

**Jurnal:**
- `app/Views/guru/jurnal/index.php`
- `app/Views/guru/jurnal/list.php`
- `app/Views/guru/jurnal/create.php`
- `app/Views/guru/jurnal/edit.php`
- `app/Views/guru/jurnal/view.php`
- `app/Views/guru/jurnal/pdf.php`
- `app/Views/guru/jurnal/pdf_template.php`
- `app/Views/guru/jurnal/generate_pdf.php`
- `app/Views/guru/jurnal/export_pdf.php`

**Profile:**
- `app/Views/guru/profile/index.php`
- `app/Views/guru/profile/edit.php`
- `app/Views/guru/profile/change_password.php`

#### Auth Views
- `app/Views/auth/login.php`

## Spesifikasi Implementasi

### 1. Template Structure
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jurnal Guru | Dashboard</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/public/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <!-- Main Sidebar -->
        <!-- Content Wrapper -->
        <!-- Footer -->
    </div>
    
    <!-- Scripts -->
    <script src="<?= base_url('AdminLTE/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('AdminLTE/public/js/adminlte.min.js') ?>"></script>
</body>
</html>
```

### 2. Sidebar Navigation Structure

**Admin Sidebar:**
- Dashboard
- User Management (submenu: List Users, Add User, Import Users)
- Kelas Management (submenu: List Kelas, Add Kelas)
- Mapel Management (submenu: List Mapel, Add Mapel)
- Monitoring (submenu: Rekap, Detail Jurnal)
- Laporan (submenu: Laporan Guru, Laporan Jurnal, Statistik)
- Export/Import
- Settings

**Guru Sidebar:**
- Dashboard
- Jurnal (submenu: List Jurnal, Tambah Jurnal)
- Profile (submenu: View Profile, Edit Profile, Change Password)

### 3. Card & Box Components
Gunakan AdminLTE card components:
```html
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Title</h3>
        <div class="card-tools">
            <!-- buttons/tools -->
        </div>
    </div>
    <div class="card-body">
        <!-- content -->
    </div>
</div>
```

### 4. DataTables Integration
Untuk semua tabel (list users, kelas, mapel, jurnal):
```html
<table id="example1" class="table table-bordered table-striped">
    <thead>
        <!-- headers -->
    </thead>
    <tbody>
        <!-- data -->
    </tbody>
</table>

<script>
$(function () {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>
```

### 5. Form Styling
Gunakan AdminLTE form groups:
```html
<div class="form-group">
    <label for="inputName">Nama</label>
    <input type="text" class="form-control" id="inputName" placeholder="Enter name">
</div>
```

### 6. Alert/Notification Styling
```html
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Success!</h5>
    Message here
</div>
```

## Plugins yang Harus Digunakan

### Required Plugins
1. **DataTables** - untuk semua list/table views
2. **Select2** - untuk dropdown (kelas, mapel, guru)
3. **DateRangePicker** - untuk filter tanggal di laporan
4. **Chart.js** - untuk statistik di dashboard
5. **Toastr** - untuk notification messages
6. **SweetAlert2** - untuk konfirmasi delete
7. **Summernote** - untuk text editor (jika ada field deskripsi panjang)

### Plugin Integration Example
```html
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<script src="<?= base_url('AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
```

## Dashboard Components

### Admin Dashboard Requirements
1. **Info Boxes/Small Boxes:**
   - Total Guru
   - Total Kelas
   - Total Mapel
   - Total Jurnal Hari Ini

2. **Charts:**
   - Grafik jurnal per bulan (Line Chart)
   - Jurnal per mata pelajaran (Bar Chart)
   - Top 5 guru paling aktif (Bar Chart)

3. **Recent Activity:**
   - 10 jurnal terakhir dibuat
   - Table dengan link ke detail

### Guru Dashboard Requirements
1. **Info Boxes:**
   - Total Jurnal Bulan Ini
   - Total Jurnal Minggu Ini
   - Total Kelas Diajar
   - Total Mapel

2. **Quick Actions:**
   - Button: Tambah Jurnal Baru
   - Button: Lihat Jurnal Saya

3. **Calendar View:**
   - Calendar dengan jurnal yang sudah dibuat

## Fitur-fitur Khusus

### 1. Profile Picture Upload
- Gunakan AdminLTE user panel di sidebar
- Show profile picture dari `public/uploads/profile_pictures/`
- Default avatar jika belum upload

### 2. Breadcrumb Navigation
```html
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
```

### 3. Loading Overlay
Gunakan AdminLTE overlay untuk proses loading:
```html
<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
```

### 4. Modal Styling
Gunakan Bootstrap modal dengan AdminLTE styling untuk:
- View detail jurnal
- Konfirmasi delete
- Quick add forms

## Color Scheme & Branding
- Primary color: #007bff (blue)
- Success: #28a745 (green)
- Danger: #dc3545 (red)
- Warning: #ffc107 (yellow)
- Info: #17a2b8 (cyan)

## Responsive Design
- Pastikan semua view responsive untuk mobile
- Gunakan AdminLTE responsive utilities
- Test di breakpoint: xs, sm, md, lg, xl

## Migration Steps

### Phase 1: Setup Base Template (PRIORITY 1)
1. Buat admin layout lengkap dengan header, sidebar, footer
2. Buat guru layout lengkap
3. Update login page dengan AdminLTE login template

### Phase 2: Admin Views (PRIORITY 2)
1. Dashboard admin
2. User management views
3. Kelas management views
4. Mapel management views

### Phase 3: Guru Views (PRIORITY 3)
1. Dashboard guru
2. Jurnal management views
3. Profile views

### Phase 4: Additional Features (PRIORITY 4)
1. Laporan views dengan charts
2. Monitoring views
3. Export/Import views
4. Settings views

## Testing Requirements
Setelah implementasi, pastikan:
- [ ] Semua navigation links berfungsi
- [ ] Semua forms submit dengan benar
- [ ] DataTables load dengan benar
- [ ] Charts render dengan benar
- [ ] Responsive di mobile
- [ ] No console errors
- [ ] Session & auth tetap berfungsi
- [ ] File uploads berfungsi
- [ ] PDF generation tetap berfungsi

## Notes Penting
1. **Jangan ubah logic di Controllers** - hanya update Views
2. **Maintain existing routes** - semua route tetap sama
3. **Preserve form names & IDs** - agar JavaScript existing tetap jalan
4. **Keep existing functionality** - hanya ubah tampilan
5. **Test setiap perubahan** - jangan breaking existing features
6. **Backup dulu** - sebelum mulai modifikasi

## Expected Output
Setelah implementasi selesai, aplikasi harus:
- Tampil profesional dengan AdminLTE theme
- Semua fitur existing tetap berfungsi
- User experience lebih baik dengan UI konsisten
- Responsive di semua device
- Loading faster dengan optimized assets

---

**Mulai dari Phase 1 dan lakukan secara bertahap. Jangan langsung modify semua file sekaligus. Test setiap phase sebelum lanjut ke phase berikutnya.**