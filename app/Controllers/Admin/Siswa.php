<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\RombelModel;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $rombelModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->rombelModel = new RombelModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil parameter filter
        $search = $this->request->getGet('search');
        $filterTingkat = $this->request->getGet('tingkat');
        $filterKelas = $this->request->getGet('kelas');
        $perPage = $this->request->getGet('per_page') ?? 30;
        
        if ($perPage == -1) {
             $perPage = 100000; // Angka besar untuk menampilkan semua
        }

        // Ambil school_level dari settings untuk filter otomatis
        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $schoolLevel = $setting['school_level'] ?? 'SMA/MA';

        // Build Query
        $builder = $this->siswaModel->db->table('siswa s');
        $builder->select('s.*, r.nama_rombel, r.kode_rombel, r.tingkat');
        $builder->join('rombel r', 's.rombel_id = r.id', 'left');
        $builder->where('s.is_active', 1);

        // Filter otomatis berdasarkan school_level
        if ($schoolLevel == 'SD/MI') {
            $builder->groupStart()
                    ->whereIn('r.tingkat', ['1', '2', '3', '4', '5', '6'])
                    ->orGroupStart()
                    ->where('r.tingkat IS NULL')
                    ->groupEnd()
                    ->groupEnd();
        } elseif ($schoolLevel == 'SMP/MTs') {
            $builder->groupStart()
                    ->whereIn('r.tingkat', ['7', '8', '9'])
                    ->orGroupStart()
                    ->where('r.tingkat IS NULL')
                    ->groupEnd()
                    ->groupEnd();
        } else { // SMA/MA
            $builder->groupStart()
                    ->whereIn('r.tingkat', ['10', '11', '12'])
                    ->orGroupStart()
                    ->where('r.tingkat IS NULL')
                    ->groupEnd()
                    ->groupEnd();
        }

        // Filter Search
        if ($search) {
            $builder->groupStart()
                    ->like('s.nama', $search)
                    ->orLike('s.nis', $search)
                    ->orLike('s.nisn', $search)
                    ->groupEnd();
        }

        // Filter Tingkat
        if ($filterTingkat) {
            $builder->where('r.tingkat', $filterTingkat);
        }

        // Filter Kelas
        if ($filterKelas) {
            $builder->where('r.id', $filterKelas);
        }

        $builder->orderBy('r.tingkat, r.nama_rombel, s.nama');
        
        // Pagination
        $page = $this->request->getVar('page_siswa') ?? 1;
        $total = $builder->countAllResults(false); // false agar tidak mereset query
        
        $builder->limit($perPage, ($page - 1) * $perPage);
        $students = $builder->get()->getResultArray();
        
        // Buat pager manual
        $pager = \Config\Services::pager();
        $pager->store('siswa', $page, $perPage, $total, 0);
        
        // Ambil data rombel untuk filter dropdown (filter berdasarkan school_level)
        $rombelBuilder = $this->rombelModel->builder();
        $rombelBuilder->where('is_active', 1);
        
        // Filter rombel berdasarkan school_level
        if ($schoolLevel == 'SD/MI') {
            $rombelBuilder->whereIn('tingkat', ['1', '2', '3', '4', '5', '6']);
        } elseif ($schoolLevel == 'SMP/MTs') {
            $rombelBuilder->whereIn('tingkat', ['7', '8', '9']);
        } else { // SMA/MA
            $rombelBuilder->whereIn('tingkat', ['10', '11', '12']);
        }
        
        $rombelBuilder->orderBy('tingkat, nama_rombel');
        $rombel = $rombelBuilder->get()->getResultArray();

        $data = [
            'title' => 'Manajemen Siswa',
            'active' => 'siswa',
            'students' => $students,
            'pager' => $pager,
            'rombel' => $rombel,
            'search' => $search,
            'filter_tingkat' => $filterTingkat,
            'filter_kelas' => $filterKelas,
            'per_page' => $this->request->getGet('per_page') ?? 30,
            'school_level' => $schoolLevel
        ];

        return view('admin/siswa/siswa', $data);
    }

    public function create()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil school_level dari settings
        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $schoolLevel = $setting['school_level'] ?? 'SMA/MA';

        // Ambil data rombel untuk dropdown (filter berdasarkan school_level)
        $rombelBuilder = $this->rombelModel->builder();
        $rombelBuilder->where('is_active', 1);
        
        // Filter rombel berdasarkan school_level
        if ($schoolLevel == 'SD/MI') {
            $rombelBuilder->whereIn('tingkat', ['1', '2', '3', '4', '5', '6']);
        } elseif ($schoolLevel == 'SMP/MTs') {
            $rombelBuilder->whereIn('tingkat', ['7', '8', '9']);
        } else { // SMA/MA
            $rombelBuilder->whereIn('tingkat', ['10', '11', '12']);
        }
        
        $rombelBuilder->orderBy('tingkat, nama_rombel');
        $rombel = $rombelBuilder->get()->getResultArray();

        $data = [
            'title' => 'Tambah Siswa',
            'active' => 'siswa',
            'rombel' => $rombel
        ];

        return view('admin/siswa/create', $data);
    }

    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data siswa dengan rombel_id dari tabel rombel_siswa
        $builder = $this->siswaModel->db->table('siswa s');
        $builder->select('s.*, rs.rombel_id');
        $builder->join('rombel_siswa rs', 's.id = rs.siswa_id');
        $builder->where('s.id', $id);
        $student = $builder->get()->getRowArray();
        
        if (!$student) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Siswa dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Ambil school_level dari settings
        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $schoolLevel = $setting['school_level'] ?? 'SMA/MA';

        // Ambil data rombel untuk dropdown (filter berdasarkan school_level)
        $rombelBuilder = $this->rombelModel->builder();
        $rombelBuilder->where('is_active', 1);
        
        // Filter rombel berdasarkan school_level
        if ($schoolLevel == 'SD/MI') {
            $rombelBuilder->whereIn('tingkat', ['1', '2', '3', '4', '5', '6']);
        } elseif ($schoolLevel == 'SMP/MTs') {
            $rombelBuilder->whereIn('tingkat', ['7', '8', '9']);
        } else { // SMA/MA
            $rombelBuilder->whereIn('tingkat', ['10', '11', '12']);
        }
        
        $rombelBuilder->orderBy('tingkat, nama_rombel');
        $rombel = $rombelBuilder->get()->getResultArray();

        $data = [
            'title' => 'Edit Siswa',
            'active' => 'siswa',
            'student' => $student,
            'rombel' => $rombel
        ];

        return view('admin/siswa/edit', $data);
    }

    public function upload()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Upload Data Siswa',
            'active' => 'siswa'
        ];

        return view('admin/siswa/upload', $data);
    }

    public function processUpload()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi file upload
        $validationRule = [
            'file' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx,csv]|max_size[file,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('error', 'File tidak valid. Pastikan file berupa Excel (xls/xlsx) dan ukuran maksimal 2MB.');
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            // Pindahkan file ke writable/uploads
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'writable/uploads', $newName);

            // Proses file excel
            try {
                $filePath = ROOTPATH . 'writable/uploads/' . $newName;
                
                // Load library spreadsheet
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                // Lewati baris header (baris pertama)
                $dataToInsert = [];
                $invalidRows = [];
                
                // Ambil semua rombel_id yang valid
                $validRombelIds = array_column($this->rombelModel->findAll(), 'id');
                
                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    // Pastikan baris tidak kosong
                    if (!empty(array_filter($row))) {
                        // Validasi format tanggal
                        $tanggal_lahir = $row[5] ?? '';
                        if (!empty($tanggal_lahir)) {
                            try {
                                // Konversi format tanggal jika perlu
                                $tanggal_lahir = date('Y-m-d', strtotime($tanggal_lahir));
                            } catch (\Exception $e) {
                                $tanggal_lahir = null;
                            }
                        }
                        
                        // Validasi rombel_id
                        $rombel_id = $row[6] ?? null;
                        if ($rombel_id !== null && !in_array($rombel_id, $validRombelIds)) {
                            $invalidRows[] = $i + 1; // Simpan nomor baris yang invalid
                            continue;
                        }
                        
                        // Validasi jenis kelamin
                        $jenis_kelamin = $row[3] ?? '';
                        if (!in_array($jenis_kelamin, ['L', 'P'])) {
                            $invalidRows[] = $i + 1; // Simpan nomor baris yang invalid
                            continue;
                        }
                        
                        // Tambahkan data yang valid
                        $dataToInsert[] = [
                            'nis' => $row[0] ?? '',
                            'nisn' => $row[1] ?? '',
                            'nama' => $row[2] ?? '',
                            'jenis_kelamin' => $jenis_kelamin,
                            'tempat_lahir' => $row[4] ?? '',
                            'tanggal_lahir' => $tanggal_lahir,
                            'rombel_id' => $rombel_id,
                            'is_active' => 1, // Default aktif, bisa diubah sesuai kebutuhan
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }

                // Simpan ke database
                if (!empty($dataToInsert)) {
                    // Insert data siswa
                    $this->siswaModel->insertBatch($dataToInsert);
                    
                    // Dapatkan ID siswa yang baru saja diinsert
                    $siswaIds = $this->siswaModel->db->insertID();
                    
                    // Siapkan data untuk tabel rombel_siswa
                    $rombelSiswaData = [];
                    foreach ($dataToInsert as $index => $siswa) {
                        // Karena kita tidak bisa langsung mendapatkan ID dari insertBatch,
                        // kita perlu query ulang untuk mendapatkan ID siswa berdasarkan NIS
                        $siswaRecord = $this->siswaModel
                            ->where('nis', $siswa['nis'])
                            ->where('nisn', $siswa['nisn'])
                            ->where('nama', $siswa['nama'])
                            ->first();
                            
                        if ($siswaRecord && $siswa['rombel_id']) {
                            $rombelSiswaData[] = [
                                'siswa_id' => $siswaRecord['id'],
                                'rombel_id' => $siswa['rombel_id'],
                                'tahun_ajaran' => '2024/2025', // Sesuaikan dengan tahun ajaran aktif
                                'semester' => '1' // Sesuaikan dengan semester aktif
                            ];
                        }
                    }
                    
                    // Insert data relasi rombel_siswa
                    if (!empty($rombelSiswaData)) {
                        $db = \Config\Database::connect();
                        $db->table('rombel_siswa')->insertBatch($rombelSiswaData);
                    }
                    
                    unlink($filePath); // Hapus file setelah diproses
                    
                    $message = count($dataToInsert) . ' data siswa berhasil diupload.';
                    
                    if (!empty($invalidRows)) {
                        $message .= ' Namun, beberapa baris tidak valid dan tidak diproses: ' . 
                                    implode(', ', $invalidRows);
                    }
                    
                    return redirect()->to('/admin/siswa')->with('success', $message);
                } else {
                    unlink($filePath); // Hapus file jika tidak ada data
                    
                    $message = 'Tidak ada data valid yang ditemukan di file.';
                    
                    if (!empty($invalidRows)) {
                        $message .= ' Baris tidak valid: ' . implode(', ', $invalidRows);
                    }
                    
                    return redirect()->back()->with('error', $message);
                }
            } catch (\Exception $e) {
                // Hapus file jika terjadi error
                if (isset($filePath) && file_exists($filePath)) {
                    unlink($filePath);
                }
                return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }
    
    public function downloadTemplate()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        try {
            // Buat spreadsheet baru
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set header
            $sheet->setCellValue('A1', 'NIS');
            $sheet->setCellValue('B1', 'NISN');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Jenis Kelamin (L/P)');
            $sheet->setCellValue('E1', 'Tempat Lahir');
            $sheet->setCellValue('F1', 'Tanggal Lahir (YYYY-MM-DD)');
            $sheet->setCellValue('G1', 'Rombel ID');
            
            // Set contoh data
            $sheet->setCellValue('A2', '1234567890');
            $sheet->setCellValue('B2', '123456789012345');
            $sheet->setCellValue('C2', 'Nama Siswa');
            $sheet->setCellValue('D2', 'L');
            $sheet->setCellValue('E2', 'Bandar Lampung');
            $sheet->setCellValue('F2', '2005-01-01');
            $sheet->setCellValue('G2', '1');
            
            // Set lebar kolom
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Buat file excel
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            // Set nama file
            $filename = 'template_upload_siswa.xlsx';
            
            // Set header untuk download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Tulis file ke output
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
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
            'nis' => 'required',
            'nisn' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tanggal_lahir' => 'required',
            'rombel_id' => 'required|is_not_unique[rombel.id]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali.');
        }

        // Siapkan data
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'rombel_id' => $this->request->getPost('rombel_id'),
            'is_active' => 1
        ];

        // Simpan data
        if ($this->siswaModel->save($data)) {
            // Dapatkan ID siswa yang baru disimpan
            $siswaId = $this->siswaModel->getInsertID();
            
            // Simpan relasi di tabel rombel_siswa
            $rombelSiswaData = [
                'siswa_id' => $siswaId,
                'rombel_id' => $this->request->getPost('rombel_id'),
                'tahun_ajaran' => '2024/2025', // Sesuaikan dengan tahun ajaran aktif
                'semester' => '1' // Sesuaikan dengan semester aktif
            ];
            
            $db = \Config\Database::connect();
            $db->table('rombel_siswa')->insert($rombelSiswaData);
            
            return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil disimpan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data siswa.');
        }
    }

    public function update($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Cek apakah siswa ada
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Validasi input
        $validationRules = [
            'nis' => 'required',
            'nisn' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tanggal_lahir' => 'required',
            'rombel_id' => 'required|is_not_unique[rombel.id]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali.');
        }

        // Siapkan data
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'rombel_id' => $this->request->getPost('rombel_id')
        ];

        // Update data
        if ($this->siswaModel->update($id, $data)) {
            // Update relasi di tabel rombel_siswa
            $rombelSiswaData = [
                'rombel_id' => $this->request->getPost('rombel_id'),
                'tahun_ajaran' => '2024/2025', // Sesuaikan dengan tahun ajaran aktif
                'semester' => '1' // Sesuaikan dengan semester aktif
            ];
            
            $db = \Config\Database::connect();
            $db->table('rombel_siswa')
                     ->where('siswa_id', $id)
                     ->update($rombelSiswaData);
            
            return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data siswa.');
        }
    }

    public function delete($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Cek apakah siswa ada
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Hapus data secara permanen
        if ($this->siswaModel->delete($id)) {
            return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil dihapus.');
        } else {
            return redirect()->to('/admin/siswa')->with('error', 'Gagal menghapus data siswa.');
        }
    }
}