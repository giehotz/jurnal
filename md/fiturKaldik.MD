Saya akan membantu Anda menganalisis proyek dan menyesuaikan fitur kalender pendidikan guru dengan struktur aplikasi yang ada. Mari saya periksa terlebih dahulu struktur proyek Anda. Baik, saya akan menganalisis struktur proyek Anda dan menyesuaikan fitur Kalender Pendidikan Guru dengan arsitektur aplikasi yang ada. Mari saya lihat struktur folder lebih detail:Baik, saya telah menganalisis struktur proyek **giehotz/jurnal** Anda.  Ini adalah aplikasi **CodeIgniter 4** dengan komposisi bahasa JavaScript (82. 8%), PHP (11.9%), dan CSS (5.3%). 

Sekarang saya akan memformulasikan **Fitur Kalender Pendidikan Guru** yang disesuaikan dengan struktur aplikasi Anda:

---

## 📋 **IMPLEMENTASI FITUR KALENDER PENDIDIKAN GURU - DISESUAIKAN DENGAN JURNAL**

### **1. STRUKTUR DATABASE (SQL)**

Tambahkan tabel berikut ke `zurnal. sql`:

```sql
-- Tabel Master Kalender Pendidikan (Admin Input)
CREATE TABLE master_kalender_pendidikan (
    id BIGINT(13) PRIMARY KEY AUTO_INCREMENT,
    tahun_ajaran VARCHAR(9) NOT NULL COMMENT '2024/2025',
    semester TINYINT(1) NOT NULL COMMENT '1 atau 2',
    tanggal DATE NOT NULL,
    jenis_hari ENUM('hari_efektif','libur_nasional','libur_sekolah','ujian','event','rapat') DEFAULT 'hari_efektif',
    keterangan TEXT,
    warna_kode VARCHAR(7) COMMENT '#hexcolor',
    created_by BIGINT(13) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    lembaga_id VARCHAR(100),
    UNIQUE KEY unique_tanggal_ajaran (tahun_ajaran, semester, tanggal),
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    FOREIGN KEY (lembaga_id) REFERENCES lembaga(lembaga_id)
);

-- Tabel View Kalender Guru (Read-only)
CREATE TABLE kalender_guru_view (
    id BIGINT(13) PRIMARY KEY AUTO_INCREMENT,
    master_kalender_id BIGINT(13) NOT NULL,
    guru_id BIGINT(13) NOT NULL,
    tanggal DATE NOT NULL,
    jenis_hari VARCHAR(50),
    keterangan TEXT,
    mata_pelajaran_terjadwal VARCHAR(255),
    tahun_ajaran VARCHAR(9),
    semester TINYINT(1),
    lembaga_id VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (master_kalender_id) REFERENCES master_kalender_pendidikan(id),
    FOREIGN KEY (guru_id) REFERENCES users(user_id),
    FOREIGN KEY (lembaga_id) REFERENCES lembaga(lembaga_id)
);

-- Tabel untuk Track Publish Status Kalender
CREATE TABLE kalender_publish_log (
    id BIGINT(13) PRIMARY KEY AUTO_INCREMENT,
    tahun_ajaran VARCHAR(9),
    semester TINYINT(1),
    published_by BIGINT(13) NOT NULL,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft','published','archived') DEFAULT 'draft',
    lembaga_id VARCHAR(100),
    FOREIGN KEY (published_by) REFERENCES users(user_id),
    FOREIGN KEY (lembaga_id) REFERENCES lembaga(lembaga_id)
);
```

---

### **2. STRUKTUR FOLDER & FILE (CodeIgniter 4)**

```
app/
├── Controllers/
│   ├── Admin/
│   │   └── KalenderPendidikan.php        # Management kalender
│   └── Guru/
│       └── KalenderMengajar.php          # View kalender
│
├── Models/
│   ├── MasterKalenderPendidikanModel.php
│   ├── KalenderGuruViewModel.php
│   ├── KalenderPublishLogModel.php
│   └── SiswaModel.php                    # Existing
│
├── Entities/
│   ├── KalenderEntity.php
│   └── KalenderPublishEntity.php
│
├── Views/
│   ├── admin/
│   │   └── kalender_pendidikan/
│   │       ├── index.php                 # List kalender
│   │       ├── create.php                # Form input manual
│   │       ├── edit.php                  # Form edit
│   │       ├── import_excel.php          # Upload template excel
│   │       ├── preview.php               # Preview sebelum publish
│   │       └── publish.php               # Konfirmasi publish
│   │
│   └── guru/
│       └── kalender_mengajar/
│           ├── index.php                 # Display calendar
│           ├── detail_tanggal.php        # Modal detail
│           ├── export_pdf.php            # Template export
│           └── export_excel.php          # Template export
│
├── Libraries/
│   ├── ExcelValidator.php                # Validasi Excel upload
│   ├── KalenderGenerator.php             # Generate kalender otomatis
│   └── IcsExporter.php                   # Export ke . ics format
│
├── Helpers/
│   └── kalender_helper.php               # Helper functions
│
├── Validation/
│   └── KalenderRules.php                 # Custom validation rules
│
└── Filters/
    └── KalenderFilter.php                # Access control
```

---

### **3. MODELS (PHP)**

#### **A. MasterKalenderPendidikanModel.php**

```php name=MasterKalenderPendidikanModel.php
<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterKalenderPendidikanModel extends Model
{
    protected $table = 'master_kalender_pendidikan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'tahun_ajaran', 'semester', 'tanggal', 'jenis_hari', 
        'keterangan', 'warna_kode', 'created_by', 'lembaga_id'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get kalender by tahun ajaran & semester
    public function getByTahunSemester($tahunAjaran, $semester, $lembahaId)
    {
        return $this->where('tahun_ajaran', $tahunAjaran)
                    ->where('semester', $semester)
                    ->where('lembaga_id', $lembahaId)
                    ->orderBy('tanggal', 'ASC')
                    ->findAll();
    }

    // Check duplicate tanggal
    public function checkDuplicate($tanggal, $tahunAjaran, $semester, $lembahaId, $excludeId = null)
    {
        $query = $this->where('tanggal', $tanggal)
                      ->where('tahun_ajaran', $tahunAjaran)
                      ->where('semester', $semester)
                      ->where('lembaga_id', $lembahaId);
        
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        
        return $query->first();
    }

    // Bulk insert dari excel
    public function bulkInsert($dataArray, $createdBy, $lembahaId)
    {
        $data = array_map(function($row) use ($createdBy, $lembahaId) {
            return [
                'tahun_ajaran' => $row['tahun_ajaran'],
                'semester' => $row['semester'],
                'tanggal' => $row['tanggal'],
                'jenis_hari' => $row['jenis_hari'] ?? 'hari_efektif',
                'keterangan' => $row['keterangan'] ?? null,
                'warna_kode' => $this->getWarnaBerdasarkanJenis($row['jenis_hari']),
                'created_by' => $createdBy,
                'lembaga_id' => $lembahaId,
            ];
        }, $dataArray);

        return $this->insertBatch($data);
    }

    // Get warna default berdasarkan jenis hari
    private function getWarnaBerdasarkanJenis($jenisHari)
    {
        $warnaMap = [
            'hari_efektif' => '#28a745',    // Hijau
            'libur_nasional' => '#dc3545',  // Merah
            'libur_sekolah' => '#fd7e14',   // Orange
            'ujian' => '#ffc107',           // Kuning
            'event' => '#007bff',           // Biru
            'rapat' => '#6f42c1',           // Ungu
        ];
        
        return $warnaMap[$jenisHari] ?? '#6c757d';
    }

    // Get statistik kalender
    public function getStatistik($tahunAjaran, $semester, $bulan, $lembahaId)
    {
        $result = $this->selectCount('id', 'total')
                       ->addSelect('jenis_hari')
                       ->where('tahun_ajaran', $tahunAjaran)
                       ->where('semester', $semester)
                       ->where('lembaga_id', $lembahaId)
                       ->whereRaw("MONTH(tanggal) = $bulan")
                       ->groupBy('jenis_hari')
                       ->findAll();
        
        return $result;
    }
}
```

#### **B.  KalenderGuruViewModel.php**

```php name=KalenderGuruViewModel.php
<?php

namespace App\Models;

use CodeIgniter\Model;

class KalenderGuruViewModel extends Model
{
    protected $table = 'kalender_guru_view';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'master_kalender_id', 'guru_id', 'tanggal', 'jenis_hari',
        'keterangan', 'mata_pelajaran_terjadwal', 'tahun_ajaran', 
        'semester', 'lembaga_id'
    ];
    protected $useTimestamps = true;

    // Get kalender untuk guru spesifik
    public function getKalenderGuru($guruId, $tahunAjaran, $semester, $lembahaId, $bulan = null)
    {
        $query = $this->where('guru_id', $guruId)
                      ->where('tahun_ajaran', $tahunAjaran)
                      ->where('semester', $semester)
                      ->where('lembaga_id', $lembahaId)
                      ->orderBy('tanggal', 'ASC');
        
        if ($bulan) {
            $query->whereRaw("MONTH(tanggal) = $bulan");
        }
        
        return $query->findAll();
    }

    // Get kalender by tanggal
    public function getByTanggal($tanggal, $guruId)
    {
        return $this->where('tanggal', $tanggal)
                    ->where('guru_id', $guruId)
                    ->first();
    }

    // Update view ketika admin publish kalender
    public function syncFromMasterKalender($tahunAjaran, $semester, $lembahaId)
    {
        $db = \Config\Database::connect();
        $kalenderModel = new MasterKalenderPendidikanModel();
        
        // Get semua guru di lembaga
        $guruList = $db->table('users')
                       ->where('role', 'guru')
                       ->where('lembaga_id', $lembahaId)
                       ->get()
                       ->getResultArray();

        // Get master kalender
        $masterKalender = $kalenderModel->getByTahunSemester($tahunAjaran, $semester, $lembahaId);

        // Insert untuk setiap guru
        foreach ($guruList as $guru) {
            foreach ($masterKalender as $kalender) {
                $this->insert([
                    'master_kalender_id' => $kalender['id'],
                    'guru_id' => $guru['user_id'],
                    'tanggal' => $kalender['tanggal'],
                    'jenis_hari' => $kalender['jenis_hari'],
                    'keterangan' => $kalender['keterangan'],
                    'tahun_ajaran' => $tahunAjaran,
                    'semester' => $semester,
                    'lembaga_id' => $lembahaId,
                ]);
            }
        }

        return true;
    }
}
```

---

### **4.  CONTROLLERS**

#### **A. Admin/KalenderPendidikan.php**

```php name=KalenderPendidikan.php
<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MasterKalenderPendidikanModel;
use App\Models\KalenderGuruViewModel;
use App\Models\KalenderPublishLogModel;
use App\Libraries\ExcelValidator;

class KalenderPendidikan extends BaseController
{
    protected $kalenderModel;
    protected $guruViewModel;
    protected $publishLogModel;
    protected $db;

    public function __construct()
    {
        $this->kalenderModel = new MasterKalenderPendidikanModel();
        $this->guruViewModel = new KalenderGuruViewModel();
        $this->publishLogModel = new KalenderPublishLogModel();
        $this->db = \Config\Database::connect();
    }

    // LIST KALENDER
    public function index()
    {
        $lembahaId = session('lembaga_id');
        $tahunAjaran = $this->request->getVar('tahun_ajaran') ?? date('Y') . '/' . (date('Y') + 1);
        $semester = $this->request->getVar('semester') ?? 1;

        $kalender = $this->kalenderModel->getByTahunSemester(
            $tahunAjaran, 
            $semester, 
            $lembahaId
        );

        return view('admin/kalender_pendidikan/index', [
            'kalender' => $kalender,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
        ]);
    }

    // CREATE MANUAL
    public function create()
    {
        return view('admin/kalender_pendidikan/create');
    }

    // STORE
    public function store()
    {
        $rules = [
            'tahun_ajaran' => 'required|regex_match[/^\d{4}\/\d{4}$/]',
            'semester' => 'required|in_list[1,2]',
            'tanggal' => 'required|valid_date[Y-m-d]',
            'jenis_hari' => 'required|in_list[hari_efektif,libur_nasional,libur_sekolah,ujian,event,rapat]',
            'keterangan' => 'permit_empty|string',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tahunAjaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $tanggal = $this->request->getPost('tanggal');
        $lembahaId = session('lembaga_id');

        // Check duplicate
        $duplicate = $this->kalenderModel->checkDuplicate(
            $tanggal, 
            $tahunAjaran, 
            $semester, 
            $lembahaId
        );

        if ($duplicate) {
            return redirect()->back()->with('error', 'Tanggal sudah ada dalam kalender ini');
        }

        // Insert
        $this->kalenderModel->insert([
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'tanggal' => $tanggal,
            'jenis_hari' => $this->request->getPost('jenis_hari'),
            'keterangan' => $this->request->getPost('keterangan'),
            'created_by' => session('user_id'),
            'lembaga_id' => $lembahaId,
        ]);

        return redirect()->to('/admin/kalender-pendidikan')
                        ->with('success', 'Data kalender berhasil ditambahkan');
    }

    // EDIT
    public function edit($id)
    {
        $kalender = $this->kalenderModel->find($id);

        if (!$kalender) {
            return redirect()->to('/admin/kalender-pendidikan')->with('error', 'Data tidak ditemukan');
        }

        return view('admin/kalender_pendidikan/edit', ['kalender' => $kalender]);
    }

    // UPDATE
    public function update($id)
    {
        $rules = [
            'jenis_hari' => 'required|in_list[hari_efektif,libur_nasional,libur_sekolah,ujian,event,rapat]',
            'keterangan' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kalenderModel->update($id, [
            'jenis_hari' => $this->request->getPost('jenis_hari'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/admin/kalender-pendidikan')
                        ->with('success', 'Data kalender berhasil diupdate');
    }

    // DELETE
    public function delete($id)
    {
        $this->kalenderModel->delete($id);
        return redirect()->to('/admin/kalender-pendidikan')
                        ->with('success', 'Data kalender berhasil dihapus');
    }

    // IMPORT EXCEL
    public function importExcel()
    {
        $file = $this->request->getFile('excel_file');
        $lembahaId = session('lembaga_id');

        if (! $file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        // Validate & parse Excel
        $validator = new ExcelValidator();
        $data = $validator->parseCalendarExcel($file);

        if (! $data['success']) {
            return redirect()->back()->with('error', $data['message']);
        }

        return view('admin/kalender_pendidikan/preview', [
            'calendar_data' => $data['data'],
            'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
            'semester' => $this->request->getPost('semester'),
        ]);
    }

    // CONFIRM IMPORT
    public function confirmImport()
    {
        $dataJson = $this->request->getPost('calendar_data');
        $tahunAjaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $lembahaId = session('lembaga_id');

        $data = json_decode($dataJson, true);

        try {
            $this->kalenderModel->bulkInsert($data, session('user_id'), $lembahaId);
            
            return redirect()->to('/admin/kalender-pendidikan')
                            ->with('success', 'Data kalender dari excel berhasil diimport');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // PUBLISH KALENDER
    public function publish()
    {
        $tahunAjaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $lembahaId = session('lembaga_id');

        // Archive kalender lama
        $this->db->table('kalender_publish_log')
                 ->where('tahun_ajaran', $tahunAjaran)
                 ->where('semester', $semester)
                 ->where('lembaga_id', $lembahaId)
                 ->where('status', 'published')
                 ->update(['status' => 'archived']);

        // Sync ke guru view
        $this->guruViewModel->syncFromMasterKalender($tahunAjaran, $semester, $lembahaId);

        // Log publish
        $this->publishLogModel->insert([
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'published_by' => session('user_id'),
            'status' => 'published',
            'lembaga_id' => $lembahaId,
        ]);

        return redirect()->to('/admin/kalender-pendidikan')
                        ->with('success', 'Kalender berhasil dipublish ke guru');
    }
}
```

#### **B. Guru/KalenderMengajar.php**

```php name=KalenderMengajar.php
<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\KalenderGuruViewModel;

class KalenderMengajar extends BaseController
{
    protected $guruViewModel;

    public function __construct()
    {
        $this->guruViewModel = new KalenderGuruViewModel();
    }

    // DISPLAY KALENDER
    public function index()
    {
        $guruId = session('user_id');
        $lembahaId = session('lembaga_id');
        
        $tahunAjaran = $this->request->getVar('tahun_ajaran') ?? date('Y') . '/' . (date('Y') + 1);
        $semester = $this->request->getVar('semester') ?? 1;
        $bulan = $this->request->getVar('bulan') ?? date('m');

        $kalender = $this->guruViewModel->getKalenderGuru(
            $guruId, 
            $tahunAjaran, 
            $semester, 
            $lembahaId, 
            $bulan
        );

        // Calculate statistics
        $statistik = $this->calculateStatistik($kalender);

        return view('guru/kalender_mengajar/index', [
            'kalender' => $kalender,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'bulan' => $bulan,
            'statistik' => $statistik,
        ]);
    }

    // DETAIL TANGGAL (AJAX)
    public function detailTanggal($tanggal)
    {
        $guruId = session('user_id');
        $detail = $this->guruViewModel->getByTanggal($tanggal, $guruId);

        return $this->response->setJSON($detail);
    }

    // EXPORT PDF
    public function exportPdf()
    {
        // Implementation with DOMPDF
        // ... 
    }

    // EXPORT EXCEL
    public function exportExcel()
    {
        // Implementation with PHPSpreadsheet
        // ...
    }

    private function calculateStatistik($kalender)
    {
        $stats = [
            'hari_efektif' => 0,
            'libur_nasional' => 0,
            'libur_sekolah' => 0,
            'ujian' => 0,
            'event' => 0,
            'rapat' => 0,
        ];

        foreach ($kalender as $item) {
            $stats[$item['jenis_hari']]++;
        }

        return $stats;
    }
}
```

---

### **5.  VIEWS**

#### **A. admin/kalender_pendidikan/index.php**

```php name=admin_kalender_pendidikan_index.php
<?php $this->extend('layouts/admin'); ?>
<? php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manajemen Kalender Pendidikan</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="/admin/kalender-pendidikan/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Manual
            </a>
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                <i class="fas fa-upload"></i> Import Excel
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="tahun">Tahun Ajaran:</label>
                    <select name="tahun_ajaran" id="tahun" class="form-control ml-2">
                        <option value="2024/2025" <?= $tahun_ajaran == '2024/2025' ? 'selected' : ''; ?>>2024/2025</option>
                        <option value="2025/2026" <?= $tahun_ajaran == '2025/2026' ? 'selected' : ''; ?>>2025/2026</option>
                    </select>
                </div>
                <div class="form-group mr-3">
                    <label for="semester">Semester:</label>
                    <select name="semester" id="semester" class="form-control ml-2">
                        <option value="1" <?= $semester == '1' ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?= $semester == '2' ? 'selected' : ''; ?>>2</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Calendar Table -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Hari</th>
                        <th>Keterangan</th>
                        <th>Warna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <? php foreach ($kalender as $row): ?>
                    <tr>
                        <td><?= date('d/m/Y - l', strtotime($row['tanggal'])); ?></td>
                        <td>
                            <span class="badge" style="background-color: <?= $row['warna_kode']; ?>">
                                <?= ucfirst(str_replace('_', ' ', $row['jenis_hari'])); ?>
                            </span>
                        </td>
                        <td><?= $row['keterangan'] ?? '-'; ?></td>
                        <td>
                            <div style="width: 30px; height: 30px; background-color: <?= $row['warna_kode']; ?>; border: 1px solid #ccc;"></div>
                        </td>
                        <td>
                            <a href="/admin/kalender-pendidikan/edit/<?= $row['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                            <a href="/admin/kalender-pendidikan/delete/<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin? ')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Publish Button -->
    <div class="mt-4">
        <form method="POST" action="/admin/kalender-pendidikan/publish">
            <input type="hidden" name="tahun_ajaran" value="<? = $tahun_ajaran; ?>">
            <input type="hidden" name="semester" value="<?= $semester; ?>">
            <button type="submit" class="btn btn-lg btn-success" onclick="return confirm('Publish kalender ke semua guru?')">
                <i class="fas fa-check"></i> Publish Kalender ke Guru
            </button>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Import Kalender dari Excel</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="/admin/kalender-pendidikan/import-excel" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tahun Ajaran:</label>
                        <input type="text" name="tahun_ajaran" class="form-control" placeholder="2024/2025" required>
                    </div>
                    <div class="form-group">
                        <label>Semester:</label>
                        <select name="semester" class="form-control" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>File Excel:</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <p class="small text-muted">
                        <a href="/admin/kalender-pendidikan/download-template">Download Template Excel</a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<? php $this->endSection(); ?>
```

#### **B. guru/kalender_mengajar/index.php**

```php name=guru_kalender_mengajar_index.php
<?php $this->extend('layouts/guru'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Kalender Mengajar</h1>
        </div>
        <div class="col-md-4 text-right">
            <button class="btn btn-primary" onclick="printCalendar()">
                <i class="fas fa-print"></i> Print
            </button>
            <a href="/guru/kalender-mengajar/export-excel" class="btn btn-success">
                <i class="fas fa-download"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label>Bulan:</label>
                    <select name="bulan" class="form-control ml-2" onchange="this.form.submit()">
                        <? php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT); ?>" 
                            <?= $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ? >>
                            <?= strftime('%B', mktime(0, 0, 0, $i, 1)); ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Hari Efektif</h5>
                    <h3><? = $statistik['hari_efektif']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Libur Nasional</h5>
                    <h3><?= $statistik['libur_nasional']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Ujian</h5>
                    <h3><?= $statistik['ujian']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Event</h5>
                    <h3><?= $statistik['event']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-purple text-white">
                <div class="card-body">
                    <h5>Rapat</h5>
                    <h3><?= $statistik['rapat']; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="card">
        <div class="card-body">
            <div id="calendar" class="calendar-grid">
                <? php 
                $bulanNow = intval($bulan);
                $tahunNow = date('Y');
                $firstDay = mktime(0, 0, 0, $bulanNow, 1, $tahunNow);
                $dayOfWeek = date('N', $firstDay);
                $numDays = date('t', $firstDay);
                ?>
                
                <!-- Header Hari -->
                <div class="calendar-header">
                    <div class="day-name">SEN</div>
                    <div class="day-name">SEL</div>
                    <div class="day-name">RAB</div>
                    <div class="day-name">KAM</div>
                    <div class="day-name">JUM</div>
                    <div class="day-name">SAB</div>
                    <div class="day-name">MIN</div>
                </div>

                <!-- Calendar Days -->
                <div class="calendar-days">
                    <?php 
                    // Kosongkan hari pertama
                    for ($i = 1; $i < $dayOfWeek; $i++) {
                        echo '<div class="empty-day"></div>';
                    }

                    // Loop tanggal
                    foreach ($kalender as $item) {
                        $tanggal = date('d', strtotime($item['tanggal']));
                        $jenisHari = $item['jenis_hari'];
                        $warna = $this->getWarna($jenisHari);
                        $icon = $this->getIcon($jenisHari);
                        
                        echo '<div class="calendar-day" style="background-color: ' . $warna . '20;" onclick="showDetail(\'' . $item['tanggal'] . '\')" title="' . $item['keterangan'] . '">';
                        echo '  <div class="day-number">' . $tanggal . '</div>';
                        echo '  <div class="day-icon">' . $icon . '</div>';
                        echo '  <div class="day-text">' . substr(str_replace('_', ' ', $jenisHari), 0, 3) . '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                Loading...
            </div>
        </div>
    </div>
</div>

<style>
.calendar-header, .calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-top: 15px;
}

.day-name {
    font-weight: bold;
    text-align: center;
    padding: 10px;
    background-color: #f0f0f0;
    border-radius: 5px;
}

.calendar-day {
    aspect-ratio: 1;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 12px;
}

.calendar-day:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transform: translateY(-2px);
}

.day-number {
    font-weight: bold;
    font-size: 16px;
}

.day-icon {
    font-size: 20px;
    margin: 5px 0;
}

. empty-day {
    background-color: transparent;
}
</style>

<script>
function showDetail(tanggal) {
    fetch(`/guru/kalender-mengajar/detail-tanggal/${tanggal}`)
        .then(res => res.json())
        . then(data => {
            document.getElementById('modalTitle').textContent = new Date(tanggal).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'});
            document.getElementById('modalBody').innerHTML = `
                <p><strong>Jenis Hari:</strong> ${data.jenis_hari}</p>
                <p><strong>Keterangan:</strong> ${data. keterangan || '-'}</p>
                <p><strong>Mata Pelajaran:</strong> ${data.mata_pelajaran_terjadwal || '-'}</p>
            `;
            $('#detailModal').modal('show');
        });
}

function printCalendar() {
    window.print();
}
</script>

<? php $this->endSection(); ?>
```

---

### **6.  HELPER & LIBRARIES**

#### **A. Helpers/kalender_helper.php**

```php name=kalender_helper.php
<? php

function getWarnaBerdasarkanJenis($jenisHari)
{
    $warnaMap = [
        'hari_efektif' => '#28a745',
        'libur_nasional' => '#dc3545',
        'libur_sekolah' => '#fd7e14',
        'ujian' => '#ffc107',
        'event' => '#007bff',
        'rapat' => '#6f42c1',
    ];
    
    return $warnaMap[$jenisHari] ??  '#6c757d';
}

function getIconBerdasarkanJenis($jenisHari)
{
    $iconMap = [
        'hari_efektif' => '✓',
        'libur_nasional' => '✗',
        'libur_sekolah' => '✗',
        'ujian' => '📝',
        'event' => '★',
        'rapat' => '👥',
    ];
    
    return $iconMap[$jenisHari] ?? '•';
}

function getNamaJenisHari($jenisHari)
{
    return ucfirst(str_replace('_', ' ', $jenisHari));
}

function getMonthName($month)
{
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    
    return $months[$month] ?? '';
}
```

#### **B. Libraries/ExcelValidator.php**

```php name=ExcelValidator.php
<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelValidator
{
    public function parseCalendarExcel($file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            $errors = [];
            
            foreach ($sheet->getRowIterator(2) as $rowIndex => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $tanggal = $sheet->getCell('A' . $rowIndex)->getValue();
                $jenisHari = $sheet->getCell('B' . $rowIndex)->getValue();
                $keterangan = $sheet->getCell('C' . $rowIndex)->getValue();
                
                if (empty($tanggal)) break;
                
                // Validate
                if (!$this->isValidDate($tanggal)) {
                    $errors[] = "Row $rowIndex: Format tanggal tidak valid";
                    continue;
                }
                
                if (!in_array($jenisHari, ['hari_efektif','libur_nasional','libur_sekolah','ujian','event','rapat'])) {
                    $errors[] = "Row $rowIndex: Jenis hari tidak valid";
                    continue;
                }
                
                $data[] = [
                    'tanggal' => $this->parseDate($tanggal),
                    'jenis_hari' => $jenisHari,
                    'keterangan' => $keterangan ??  '',
                ];
            }
            
            if (! empty($errors)) {
                return ['success' => false, 'message' => implode('<br>', $errors)];
            }
            
            return ['success' => true, 'data' => $data];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function isValidDate($date)
    {
        // Accept DD/MM/YYYY, YYYY-MM-DD, etc.
        return preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$|^\d{4}-\d{1,2}-\d{1,2}$/', $date);
    }
    
    private function parseDate($date)
    {
        // Convert DD/MM/YYYY to YYYY-MM-DD
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        return $date;
    }
}
```

---

### **7. ROUTES**

Tambahkan ke `app/Config/Routes.php`:

```php
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('kalender-pendidikan', 'Admin\KalenderPendidikan::index');
    $routes->get('kalender-pendidikan/create', 'Admin\KalenderPendidikan::create');
    $routes->post('kalender-pendidikan', 'Admin\KalenderPendidikan::store');
    $routes->get('kalender-pendidikan/edit/(:num)', 'Admin\KalenderPendidikan::edit/$1');
    $routes->post('kalender-pendidikan/update/(:num)', 'Admin\KalenderPendidikan::update/$1');
    $routes->get('kalender-pendidikan/delete/(:num)', 'Admin\KalenderPendidikan::delete/$1');
    $routes->post('kalender-pendidikan/import-excel', 'Admin\KalenderPendidikan::importExcel');
    $routes->post('kalender-pendidikan/confirm-import', 'Admin\KalenderPendidikan::confirmImport');
    $routes->post('kalender-pendidikan/publish', 'Admin\KalenderPendidikan::publish');
    $routes->get('kalender-pendidikan/download-template', 'Admin\KalenderPendidikan::downloadTemplate');
});

$routes->group('guru', ['filter' => 'auth:guru'], function($routes) {
    $routes->get('kalender-mengajar', 'Guru\KalenderMengajar::index');
    $routes->get('kalender-mengajar/detail-tanggal/(:any)', 'Guru\KalenderMengajar::detailTanggal/$1');
    $routes->get('kalender-mengajar/export-pdf', 'Guru\KalenderMengajar::exportPdf');
    $routes->get('kalender-mengajar/export-excel', 'Guru\KalenderMengajar::exportExcel');
});
```

---

### **8. TEMPLATE EXCEL**

Download template di: `/public/assets/templates/template_kalender_pendidikan. xlsx`

| TANGGAL    | JENIS_HARI        | KETERANGAN                |
|-----------|-------------------|---------------------------|
| 01/01/2024| libur_nasional    | Tahun Baru 2024          |
| 02/01/2024| hari_efektif      |                          |
| 03/01/2024| ujian             | Ujian Tengah Semester    |

---

### **9. INTEGRASI DENGAN FITUR LAIN**

**Potensi link dengan jurnal/absensi:**

```php
// Di Guru/JurnalController - saat input jurnal
$tanggal = $this->request->getPost('tanggal');
$guruId = session('user_id');

$guruViewModel = new \App\Models\KalenderGuruViewModel();
$hariInfo = $guruViewModel->getByTanggal($tanggal, $guruId);

if ($hariInfo['jenis_hari'] !== 'hari_efektif') {
    return redirect()->back()->with('warning', 'Tanggal ini bukan hari efektif!');
}
```

---

Sekarang proyek Anda memiliki **fitur Kalender Pendidikan Guru** yang lengkap dan terintegrasi dengan baik dengan struktur CodeIgniter 4 yang sudah ada!  🎉