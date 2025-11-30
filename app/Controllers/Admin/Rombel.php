<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RombelModel;
use App\Models\UserModel;
use App\Models\SiswaModel;
use App\Models\RuanganModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rombel extends BaseController
{
    protected $rombelModel;
    protected $userModel;
    protected $siswaModel;
    protected $ruanganModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->userModel = new UserModel();
        $this->siswaModel = new SiswaModel();
        $this->ruanganModel = new RuanganModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data rombel untuk ditampilkan
        $rombels = $this->rombelModel->getRombel();
        
        // Tambahkan informasi wali kelas
        foreach ($rombels as &$rombel) {
            if (!empty($rombel['wali_kelas'])) {
                $waliKelas = $this->userModel->find($rombel['wali_kelas']);
                if ($waliKelas) {
                    $rombel['wali_kelas_nama'] = $waliKelas['nama'];
                } else {
                    $rombel['wali_kelas_nama'] = 'Tidak diketahui';
                }
            } else {
                $rombel['wali_kelas_nama'] = 'Belum ditentukan';
            }
        }

        $data = [
            'title' => 'Manajemen Rombel',
            'active' => 'rombel',
            'rombels' => $rombels
        ];

        return view('admin/rombel/index', $data);
    }

    public function create()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        helper('form');

        $data = [
            'title' => 'Tambah Rombel',
            'active' => 'rombel',
            'teachers' => $this->userModel->where('role', 'guru')->findAll(),
            'rooms' => $this->ruanganModel->findAll()
        ];

        // Ambil pengaturan jenjang sekolah dan tahun ajaran
        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $data['school_level'] = $setting['school_level'] ?? 'SMA/MA';
        $data['school_year'] = $setting['school_year'] ?? date('Y') . '/' . (date('Y') + 1);

        return view('admin/rombel/create', $data);
    }

    public function store()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $validationRules = [
            'kode_rombel' => 'required|is_unique[rombel.kode_rombel]',
            'nama_rombel' => 'required',
            'tingkat' => 'required|integer|greater_than[0]|less_than_equal_to[12]',
            'tahun_ajaran' => 'required'
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Gagal menambahkan rombel. Silakan periksa kembali data yang dimasukkan.');
            return redirect()->back()->withInput();
        }

        // Validasi penggunaan ruangan
        $ruanganId = $this->request->getPost('ruangan_id');
        if ($ruanganId) {
            $existingRombel = $this->rombelModel->where('ruangan_id', $ruanganId)->where('is_active', 1)->first();
            if ($existingRombel) {
                session()->setFlashdata('error', 'Ruangan ini sudah digunakan oleh kelas ' . $existingRombel['nama_rombel']);
                return redirect()->back()->withInput();
            }
        }

        // Simpan data rombel
        $data = [
            'kode_rombel' => $this->request->getPost('kode_rombel'),
            'nama_rombel' => $this->request->getPost('nama_rombel'),
            'tingkat' => $this->request->getPost('tingkat'),
            'jurusan' => $this->request->getPost('jurusan'),
            'wali_kelas' => $this->request->getPost('wali_kelas'),
            'ruangan_id' => $this->request->getPost('ruangan_id') ?: null,
            'nama_ruangan' => null, // Deprecated or use as fallback if needed, but we use ID now
            'kurikulum' => $this->request->getPost('kurikulum'),
            'jenis_rombel' => $this->request->getPost('jenis_rombel'),
            'waktu_mengajar' => $this->request->getPost('waktu_mengajar'),
            'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
            'semester' => $this->request->getPost('semester'),
            'kapasitas' => $this->request->getPost('kapasitas') ?: 30,
            'is_active' => 1
        ];

        if ($this->rombelModel->save($data)) {
            session()->setFlashdata('success', 'Rombel berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan rombel.');
        }

        return redirect()->to('/admin/rombel');
    }

    public function view($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data rombel berdasarkan ID
        $rombel = $this->rombelModel->getRombelById($id);

        if (!$rombel) {
            session()->setFlashdata('error', 'Rombel tidak ditemukan!');
            return redirect()->to('/admin/rombel');
        }

        // Tambahkan informasi wali kelas
        if (!empty($rombel['wali_kelas'])) {
            $waliKelas = $this->userModel->find($rombel['wali_kelas']);
            if ($waliKelas) {
                $rombel['wali_kelas_nama'] = $waliKelas['nama'];
            } else {
                $rombel['wali_kelas_nama'] = 'Tidak diketahui';
            }
        } else {
            $rombel['wali_kelas_nama'] = 'Belum ditentukan';
        }

        // Hitung jumlah siswa dalam rombel
        $jumlahSiswa = $this->rombelModel->countSiswaInRombel($id);
        
        // Ambil daftar siswa dalam rombel
        $siswaList = $this->siswaModel->getSiswaByRombel($id);

        $data = [
            'title' => 'Detail Rombel',
            'active' => 'rombel',
            'rombel' => $rombel,
            'jumlah_siswa' => $jumlahSiswa,
            'siswa_list' => $siswaList
        ];

        return view('admin/rombel/view', $data);
    }

    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        helper('form');

        // Ambil data rombel berdasarkan ID
        $rombel = $this->rombelModel->getRombelById($id);

        if (!$rombel) {
            session()->setFlashdata('error', 'Rombel tidak ditemukan!');
            return redirect()->to('/admin/rombel');
        }

        $data = [
            'title' => 'Edit Rombel',
            'active' => 'rombel',
            'rombel' => $rombel,
            'teachers' => $this->userModel->where('role', 'guru')->findAll(),
            'rooms' => $this->ruanganModel->findAll()
        ];

        // Ambil pengaturan jenjang sekolah
        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $data['school_level'] = $setting['school_level'] ?? 'SMA/MA';

        return view('admin/rombel/edit', $data);
    }

    public function update($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $validationRules = [
            'kode_rombel' => 'required|is_unique[rombel.kode_rombel,id,' . $id . ']',
            'nama_rombel' => 'required',
            'tingkat' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Gagal mengupdate rombel. Silakan periksa kembali data yang dimasukkan.');
            return redirect()->back()->withInput();
        }

        // Validasi penggunaan ruangan
        $ruanganId = $this->request->getPost('ruangan_id');
        if ($ruanganId) {
            $existingRombel = $this->rombelModel
                ->where('ruangan_id', $ruanganId)
                ->where('is_active', 1)
                ->where('id !=', $id) // Exclude current rombel
                ->first();
                
            if ($existingRombel) {
                session()->setFlashdata('error', 'Ruangan ini sudah digunakan oleh kelas ' . $existingRombel['nama_rombel']);
                return redirect()->back()->withInput();
            }
        }

        // Update data rombel
        $data = [
            'kode_rombel' => $this->request->getPost('kode_rombel'),
            'nama_rombel' => $this->request->getPost('nama_rombel'),
            'tingkat' => $this->request->getPost('tingkat'),
            'jurusan' => $this->request->getPost('jurusan'),
            'ruangan_id' => $this->request->getPost('ruangan_id') ?: null,
            'nama_ruangan' => null,
            'kurikulum' => $this->request->getPost('kurikulum'),
            'jenis_rombel' => $this->request->getPost('jenis_rombel'),
            'waktu_mengajar' => $this->request->getPost('waktu_mengajar'),
            'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
            'semester' => $this->request->getPost('semester'),
            'kapasitas' => $this->request->getPost('kapasitas') ?: 30,
        ];

        // Hanya menambahkan wali_kelas ke data jika tidak kosong
        $waliKelas = $this->request->getPost('wali_kelas');
        if ($waliKelas !== '' && $waliKelas !== null) {
            $data['wali_kelas'] = $waliKelas;
        } else {
            $data['wali_kelas'] = null;
        }

        if ($this->rombelModel->update($id, $data)) {
            session()->setFlashdata('success', 'Rombel berhasil diupdate.');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate rombel.');
        }

        return redirect()->to('/admin/rombel');
    }

    public function delete($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Hapus data rombel
        if ($this->rombelModel->delete($id)) {
            session()->setFlashdata('success', 'Rombel berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus rombel.');
        }

        return redirect()->to('/admin/rombel');
    }

    /**
     * Menampilkan form untuk menetapkan siswa ke rombel
     */
    public function assignStudents($rombelId)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Pastikan rombel ada
        $rombel = $this->rombelModel->find($rombelId);
        if (!$rombel) {
            session()->setFlashdata('error', 'Rombel tidak ditemukan!');
            return redirect()->to('/admin/rombel');
        }

        // Ambil semua siswa yang belum memiliki rombel atau berada di rombel yang sama
        $students = $this->siswaModel
            ->where('rombel_id IS NULL')
            ->orWhere('rombel_id', $rombelId)
            ->where('is_active', 1)
            ->findAll();

        $data = [
            'title' => 'Kelola Siswa di Rombel',
            'active' => 'rombel',
            'rombel' => $rombel,
            'students' => $students
        ];

        return view('admin/rombel/assign_students', $data);
    }

    /**
     * Menyimpan penetapan siswa ke rombel
     */
    public function saveStudentAssignments($rombelId)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Pastikan rombel ada
        $rombel = $this->rombelModel->find($rombelId);
        if (!$rombel) {
            session()->setFlashdata('error', 'Rombel tidak ditemukan!');
            return redirect()->to('/admin/rombel');
        }

        // Ambil data siswa yang dipilih
        $studentIds = $this->request->getPost('students');

        try {
            // Reset semua siswa di rombel ini
            $this->siswaModel->where('rombel_id', $rombelId)->set(['rombel_id' => null])->update();

            // Validasi bahwa semua siswa ada sebelum mengupdate
            if (!empty($studentIds) && is_array($studentIds)) {
                $validStudents = $this->siswaModel->whereIn('id', $studentIds)->findAll();
                $validStudentIds = array_column($validStudents, 'id');
                
                if (!empty($validStudentIds)) {
                    $this->siswaModel->whereIn('id', $validStudentIds)->set(['rombel_id' => $rombelId])->update();
                }
            }

            session()->setFlashdata('success', 'Berhasil menyimpan penetapan siswa ke rombel.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Error saving student assignments: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menyimpan penetapan siswa ke rombel: ' . $e->getMessage());
        }

        return redirect()->to('/admin/rombel/view/' . $rombelId);
    }

    /**
     * Download template Excel untuk upload siswa
     */
    public function downloadTemplate()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Columns
        $headers = ['NIS', 'NISN', 'Nama Lengkap', 'Jenis Kelamin (L/P)', 'Tanggal Lahir (YYYY-MM-DD)'];
        
        // Set Header
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E2E2']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Contoh Data
        $sheet->setCellValue('A2', '12345');
        $sheet->setCellValue('B2', '0012345678');
        $sheet->setCellValue('C2', 'Contoh Siswa');
        $sheet->setCellValue('D2', 'L');
        $sheet->setCellValue('E2', '2010-01-01');

        // Set Validation for Jenis Kelamin
        $validation = $sheet->getCell('D2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"L,P"');

        // Apply validation to column D (rows 2-100)
        for ($i = 3; $i <= 100; $i++) {
            $sheet->getCell('D' . $i)->setDataValidation(clone $validation);
        }

        $filename = 'template_upload_siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Preview data upload siswa
     */
    public function previewUpload($rombelId)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $rombel = $this->rombelModel->find($rombelId);
        if (!$rombel) {
            return redirect()->back()->with('error', 'Rombel tidak ditemukan');
        }

        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        $ext = $file->getClientExtension();
        if (!in_array($ext, ['xls', 'xlsx'])) {
            return redirect()->back()->with('error', 'Format file harus Excel (.xls, .xlsx)');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Hapus header
            array_shift($rows);

            $students = [];
            foreach ($rows as $row) {
                // Skip empty rows
                if (empty($row[0]) && empty($row[2])) continue;

                $students[] = [
                    'nis' => $row[0],
                    'nisn' => $row[1],
                    'nama' => $row[2],
                    'jenis_kelamin' => $row[3],
                    'tanggal_lahir' => $row[4]
                ];
            }

            $data = [
                'title' => 'Preview Upload Siswa',
                'active' => 'rombel',
                'rombel' => $rombel,
                'students' => $students
            ];

            return view('admin/rombel/upload_preview', $data);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    /**
     * Simpan data upload siswa
     */
    public function storeUpload($rombelId)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Periksa apakah rombel ada
        $rombel = $this->rombelModel->find($rombelId);
        if (!$rombel) {
            return redirect()->to('/admin/rombel/view/' . $rombelId)->with('error', 'Rombel tidak ditemukan');
        }

        $students = $this->request->getPost('students');

        if (empty($students)) {
            return redirect()->to('/admin/rombel/view/' . $rombelId)->with('error', 'Tidak ada data siswa yang disimpan');
        }

        $successCount = 0;
        $failCount = 0;
        $failedStudents = [];

        foreach ($students as $student) {
            // Cek duplikasi NIS
            $existingSiswa = $this->siswaModel->where('nis', $student['nis'])->first();
            
            $dataSiswa = [
                'nis' => $student['nis'],
                'nisn' => $student['nisn'],
                'nama' => $student['nama'],
                'jenis_kelamin' => $student['jenis_kelamin'],
                'tanggal_lahir' => $student['tanggal_lahir'],
                'rombel_id' => $rombelId,
                'is_active' => 1
            ];

            try {
                if ($existingSiswa) {
                    // Update jika sudah ada (opsional, atau skip)
                    // Disini kita update saja rombel_id nya dan data lainnya
                    $this->siswaModel->update($existingSiswa['id'], $dataSiswa);
                    $successCount++;
                } else {
                    // Insert baru
                    if ($this->siswaModel->insert($dataSiswa)) {
                        $successCount++;
                    } else {
                        $failCount++;
                        $failedStudents[] = $dataSiswa['nama'];
                    }
                }
            } catch (\Exception $e) {
                $failCount++;
                $failedStudents[] = $dataSiswa['nama'] . ' (' . $e->getMessage() . ')';
                log_message('error', 'Error inserting student: ' . $e->getMessage());
            }
        }

        $message = "Berhasil memproses upload. Sukses: $successCount, Gagal: $failCount";
        if (!empty($failedStudents)) {
            $message .= ". Siswa yang gagal: " . implode(', ', $failedStudents);
        }

        return redirect()->to('/admin/rombel/view/' . $rombelId)->with('success', $message);
    }
}