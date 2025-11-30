<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Libraries\JurnalLibrary;
use App\Models\JurnalModel;
use App\Models\RombelModel; // Diubah dari KelasModel
use App\Models\MapelModel;
use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Models\JurnalDetailModel;
use App\Models\SettingModel;
use App\Services\JurnalExportService;
use App\Services\JurnalPdfService;
use App\Services\JurnalQueryService;
use CodeIgniter\HTTP\ResponseInterface;

class Jurnal extends BaseController
{
    protected $jurnalModel;
    protected $rombelModel; // Diubah dari kelasModel
    protected $mapelModel;
    protected $siswaModel;
    protected $absensiModel;
    protected $jurnalDetailModel;
    protected $settingModel;
    protected $db;
    protected $jurnalLibrary;
    protected $jurnalQueryService;
    protected $jurnalExportService;
    protected $jurnalPdfService;

    public function __construct()
    {
        $this->jurnalModel = new JurnalModel();
        $this->rombelModel = new RombelModel(); // Diubah dari KelasModel
        $this->mapelModel = new MapelModel();
        $this->siswaModel = new SiswaModel();
        $this->absensiModel = new AbsensiModel();
        $this->jurnalDetailModel = new JurnalDetailModel();
        $this->settingModel = new SettingModel();
        $this->db = \Config\Database::connect();
        
        // Initialize services
        $this->jurnalLibrary = new JurnalLibrary(service('validation'));
        $this->jurnalQueryService = new JurnalQueryService($this->jurnalModel, $this->rombelModel, $this->mapelModel); // Diubah dari kelasModel
        $this->jurnalExportService = new JurnalExportService($this->jurnalModel, $this->rombelModel); // Diubah dari kelasModel
        $this->jurnalPdfService = new JurnalPdfService($this->jurnalModel, $this->rombelModel, $this->settingModel); // Diubah dari kelasModel
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role guru
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        
        // Ambil data jurnal dengan detail
        $jurnals = $this->jurnalModel->getJurnalWithDetails($userId); // Diubah untuk menggunakan method baru
        
        // Cek apakah user adalah wali kelas
        $isWaliKelas = $this->rombelModel->where('wali_kelas', $userId)->first(); // Diubah dari kelasModel
        
        $data = [
            'title' => 'Daftar Jurnal Mengajar',
            'active' => 'jurnal',
            'jurnals' => $jurnals,
            'is_wali_kelas' => $isWaliKelas,
            'kelas_perwalian' => $isWaliKelas
        ];

        // Detect if mobile device
        if ($this->isMobile()) {
            return view('mobile/guru/jurnal/index', $data);
        }

        return view('guru/jurnal/index', $data);
    }

    public function create()
    {
        // Menampilkan form tambah jurnal
        helper(['form']);
        
        $formOptions = $this->jurnalQueryService->getFormOptions();
        
        $data['rombel'] = $formOptions['kelas']; // Diubah dari kelas
        $data['mapel'] = $formOptions['mapel'];
        
        // Ambil jurnal terakhir untuk ditampilkan
        $userId = session()->get('user_id');
        // Pre-fill data if parameters exist
        $selectedRombel = $this->request->getGet('rombel_id');
        $selectedTanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $jumlahHadir = 0;
        
        if ($selectedRombel && $selectedTanggal) {
            $rekapHarianModel = new \App\Models\RekapAbsensiHarianModel();
            $rekap = $rekapHarianModel->where('rombel_id', $selectedRombel)
                                      ->where('tanggal', $selectedTanggal)
                                      ->first();
            if ($rekap) {
                $jumlahHadir = $rekap['total_hadir'];
                // Use total_siswa from rekap if available
                $totalSiswa = $rekap['total_siswa']; 
            } else {
                // Jika belum ada rekap, hitung dari data siswa aktif
                $siswaModel = new \App\Models\SiswaModel();
                $totalSiswa = $siswaModel->where('rombel_id', $selectedRombel)
                                        ->where('is_active', 1)
                                        ->countAllResults();
            }
        } else {
            $totalSiswa = 0;
        }

        $data['selected_rombel'] = $selectedRombel;
        $data['selected_tanggal'] = $selectedTanggal;
        $data['jumlah_hadir'] = $jumlahHadir;
        $data['total_siswa'] = $totalSiswa;
        
        $data['recent_jurnal'] = $this->jurnalModel
            ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->where('user_id', $userId)
            ->where('mapel_id !=', 18) // Exclude Absensi
            ->notLike('materi', 'Absensi Kelas') // Exclude Absensi
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();
        
        if ($this->isMobile()) {
            return view('mobile/guru/jurnal/create', $data);
        }
        
        return view('guru/jurnal/create', $data);
    }

    public function edit($id = null)
    {
        // Menampilkan form edit jurnal
        helper(['form']);
        
        $userId = session()->get('user_id');
        
        // Cek apakah jurnal ditemukan dan dimiliki oleh user
        $jurnal = $this->jurnalQueryService->getJurnalForEdit($id);
        
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jurnal dengan ID ' . $id . ' tidak ditemukan.');
        }
        
        // Cek apakah user memiliki akses ke jurnal ini
        if ($jurnal['user_id'] != $userId) {
            return redirect()->to('/guru/jurnal')->with('error', 'Anda tidak memiliki akses ke jurnal ini.');
        }
        
        $formOptions = $this->jurnalQueryService->getFormOptions();
        $data['jurnal'] = $jurnal;
        $data['rombel'] = $formOptions['kelas']; // Diubah dari kelas
        $data['mapel'] = $formOptions['mapel'];
        
        if ($this->isMobile()) {
            return view('mobile/guru/jurnal/edit', $data);
        }
        
        return view('guru/jurnal/edit', $data);
    }

    public function update($id)
    {
        // Cek apakah user sudah login
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah jurnal milik user ini
        $jurnal = $this->jurnalModel->find($id);
        if (!$jurnal || $jurnal['user_id'] != $userId) {
            return redirect()->to('/guru/jurnal')->with('error', 'Anda tidak memiliki akses ke jurnal ini.');
        }

        // Validate input (Use global request to include FILES)
        $validationResult = $this->jurnalLibrary->validateInput();
        
        if ($validationResult !== true) {
             // Log validation errors
            log_message('error', '[Jurnal::update] Validation failed: ' . json_encode($validationResult));
            
            // Set flashdata error for the view
            $errorMsg = 'Validasi gagal: ' . implode(', ', $validationResult);
            session()->setFlashdata('error', $errorMsg);
            
            return redirect()->back()->withInput();
        }

        // Validate File separately
        $fileValidation = $this->jurnalLibrary->validateFile();
        if ($fileValidation !== true) {
             // Log validation errors
             log_message('error', '[Jurnal::update] File Validation failed: ' . json_encode($fileValidation));
             
             // Set flashdata error for the view
             $errorMsg = 'Validasi file gagal: ' . implode(', ', $fileValidation);
             session()->setFlashdata('error', $errorMsg);
             
             return redirect()->back()->withInput();
        }
        
        try {
            $data = [
                'tanggal' => $this->request->getPost('tanggal'),
                'rombel_id' => $this->request->getPost('rombel_id'),
                'mapel_id' => $this->request->getPost('mapel_id'),
                'jam_ke' => $this->request->getPost('jam_ke'),
                'materi' => $this->request->getPost('materi'),
                'jumlah_jam' => $this->request->getPost('jumlah_jam'),
                'jumlah_peserta' => $this->request->getPost('jumlah_peserta'),
                'keterangan' => $this->request->getPost('keterangan'),
                'status' => $this->request->getPost('status'),
            ];
            
            // Handle File Upload
            $file = $this->request->getFile('bukti_dukung');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $this->jurnalLibrary->handleFileUpload($file, $jurnal['bukti_dukung']);
                if ($fileName) {
                    $data['bukti_dukung'] = $fileName;
                }
            }
            
            // Simpan data jurnal
            if ($this->jurnalModel->update($id, $data)) {
                return redirect()->to('/guru/jurnal')->with('success', 'Jurnal berhasil diupdate.');
            } else {
                $dbErrors = $this->jurnalModel->errors();
                log_message('error', '[Jurnal::update] Model update failed: ' . json_encode($dbErrors));
                throw new \Exception('Gagal mengupdate jurnal: ' . implode(', ', $dbErrors));
            }
        } catch (\Exception $e) {
            log_message('error', '[Jurnal::update] Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function getAvailableHours()
    {
        $rombelId = $this->request->getPost('rombel_id'); // Diubah dari kelas_id
        $tanggal = $this->request->getPost('tanggal');
        $editJurnalId = $this->request->getPost('edit_jurnal_id');

        if (!$rombelId || !$tanggal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel dan tanggal harus diisi']); // Diubah dari Kelas
        }
        
        $availableHours = $this->jurnalQueryService->getAvailableHours($rombelId, $tanggal, $editJurnalId); // Diubah dari kelasId

        return $this->response->setJSON([
            'status' => 'success',
            'used_hours' => $availableHours['used_hours'],
            'available_hours' => $availableHours['available_hours'],
            'next_hour' => $availableHours['next_hour']
        ]);
    }

    /**
     * Check if daily attendance (Absensi) exists for a specific rombel and date
     * Uses RekapAbsensiHarianModel for performance
     */
    public function checkDailyAttendance()
    {
        $rombelId = $this->request->getPost('rombel_id');
        $tanggal = $this->request->getPost('tanggal');
        
        if (!$rombelId || !$tanggal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Rombel dan tanggal harus diisi']);
        }
        
        // Use RekapAbsensiHarianModel for instant lookup
        $rekapHarianModel = new \App\Models\RekapAbsensiHarianModel();
        $rekap = $rekapHarianModel->where('rombel_id', $rombelId)
                                  ->where('tanggal', $tanggal)
                                  ->first();
            
        if ($rekap) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'total_hadir' => (int)$rekap['total_hadir'],
                    'total_siswa' => (int)$rekap['total_siswa']
                ],
                'message' => 'Absensi ditemukan'
            ]);
        } else {
            // Fallback: Check manual absensi table directly (for legacy data or if rekap missing)
            $db = \Config\Database::connect();
            $builder = $db->table('absensi');
            $builder->selectCount('id', 'total_count');
            $builder->selectSum("CASE WHEN status = 'hadir' THEN 1 ELSE 0 END", 'total_hadir');
            $builder->where('rombel_id', $rombelId);
            $builder->where('tanggal', $tanggal);
            $result = $builder->get()->getRow();

            if ($result && $result->total_count > 0) {
                // Found in absensi table!
                // Get total siswa from SiswaModel to be accurate
                $siswaModel = new \App\Models\SiswaModel();
                $totalSiswa = $siswaModel->where('rombel_id', $rombelId)
                                        ->where('is_active', 1)
                                        ->countAllResults();

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'total_hadir' => (int)$result->total_hadir,
                        'total_siswa' => $totalSiswa
                    ],
                    'message' => 'Absensi ditemukan (Legacy)'
                ]);
            }

            // Jika benar-benar tidak ada
            $siswaModel = new \App\Models\SiswaModel();
            $totalSiswa = $siswaModel->where('rombel_id', $rombelId)
                                    ->where('is_active', 1)
                                    ->countAllResults();
            
            return $this->response->setJSON([
                'status' => 'warning',
                'data' => [
                    'total_hadir' => 0,
                    'total_siswa' => $totalSiswa
                ],
                'message' => 'Absensi belum ada'
            ]);
        }
    }

    public function debugTest()
    {
        return view('guru/jurnal/debug_test');
    }

    public function store()
    {
        // Menyimpan data jurnal
        // Cek apakah user sudah login
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah absensi sudah terisi untuk kelas dan tanggal ini
        $rombelId = $this->request->getPost('rombel_id');
        $tanggal = $this->request->getPost('tanggal');
        
        // Cek via rekap absensi harian
        $rekapHarianModel = new \App\Models\RekapAbsensiHarianModel();
        $absensiExists = $rekapHarianModel
            ->where('rombel_id', $rombelId)
            ->where('tanggal', $tanggal)
            ->countAllResults() > 0;

        if (!$absensiExists) {
            return redirect()->to('/guru/absensi/create')
                ->withInput()
                ->with('error', 'Harap lakukan absensi terlebih dahulu untuk kelas ini pada tanggal tersebut.');
        }
        
        // Validate input (Use global request to include FILES)
        $validationResult = $this->jurnalLibrary->validateInput();
        
        if ($validationResult !== true) {
            // Log validation errors
            log_message('error', '[Jurnal::store] Validation failed: ' . json_encode($validationResult));
            
            // Set flashdata error for the view
            $errorMsg = 'Validasi gagal: ' . implode(', ', $validationResult);
            session()->setFlashdata('error', $errorMsg);

            $formOptions = $this->jurnalQueryService->getFormOptions();
            $data['validation'] = (object)['getErrors' => function() use ($validationResult) { return $validationResult; }];
            $data['rombel'] = $formOptions['kelas'];
            $data['mapel'] = $formOptions['mapel'];
            $data['recent_jurnal'] = $this->jurnalModel
                ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel')
                ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
                ->where('user_id', $userId)
                ->where('mapel_id !=', 18)
                ->notLike('materi', 'Absensi Kelas')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll();
            
            if ($this->isMobile()) {
                return view('mobile/guru/jurnal/create', $data);
            }
            
            return view('guru/jurnal/create', $data);
        }

        // Validate File separately
        $fileValidation = $this->jurnalLibrary->validateFile();
        if ($fileValidation !== true) {
             // Log validation errors
             log_message('error', '[Jurnal::store] File Validation failed: ' . json_encode($fileValidation));
             
             // Set flashdata error for the view
             $errorMsg = 'Validasi file gagal: ' . implode(', ', $fileValidation);
             session()->setFlashdata('error', $errorMsg);
             
             return redirect()->back()->withInput();
        }
        
        try {
            // Ambil jumlah peserta dari rekap harian untuk konsistensi
            // Kita sudah cek eksistensi di atas, jadi aman untuk ambil datanya
            $rekap = $rekapHarianModel->where('rombel_id', $this->request->getPost('rombel_id'))
                                      ->where('tanggal', $this->request->getPost('tanggal'))
                                      ->first();
            $jumlahPeserta = $rekap ? $rekap['total_hadir'] : 0;

            // Prepare data
            $data = [
                'user_id' => $userId,
                'tanggal' => $this->request->getPost('tanggal'),
                'rombel_id' => $this->request->getPost('rombel_id'),
                'mapel_id' => $this->request->getPost('mapel_id'),
                'jam_ke' => $this->request->getPost('jam_ke'),
                'materi' => $this->request->getPost('materi'),
                'jumlah_jam' => $this->request->getPost('jumlah_jam'),
                'jumlah_peserta' => $jumlahPeserta, // Gunakan data dari rekap, bukan input user
                'keterangan' => $this->request->getPost('keterangan'),
                'status' => $this->request->getPost('status'),
            ];

            // Handle File Upload
            $file = $this->request->getFile('bukti_dukung');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $this->jurnalLibrary->handleFileUpload($file);
                if ($fileName) {
                    $data['bukti_dukung'] = $fileName;
                }
            }

            // Simpan data ke database
            if (!$this->jurnalModel->save($data)) {
                $dbErrors = $this->jurnalModel->errors();
                log_message('error', '[Jurnal::store] Model save failed: ' . json_encode($dbErrors));
                throw new \Exception('Gagal menyimpan data ke database: ' . implode(', ', $dbErrors));
            }
            
            $jurnalId = $this->jurnalModel->insertID();
            
            return redirect()->to('/guru/jurnal/view/' . $jurnalId)->with('success', 'Jurnal berhasil disimpan');

        } catch (\Exception $e) {
            log_message('error', '[Jurnal::store] Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function view($id = null)
    {
        // Menampilkan detail jurnal
        // Cek apakah user sudah login
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/auth/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Ambil data jurnal dengan detail rombel, guru, dan mata pelajaran
        $builder = $this->db->table('jurnal_new j');
        $builder->select('j.*, r.nama_rombel as nama_kelas, r.kode_rombel as kode_kelas, u.nama as nama_guru, m.nama_mapel'); // Diubah untuk menggunakan rombel
        $builder->join('rombel r', 'j.rombel_id = r.id'); // Diubah dari kelas
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        $builder->where('j.id', $id);
        $jurnal = $builder->get()->getRowArray();

        // Cek apakah jurnal ditemukan
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jurnal dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Cek apakah user memiliki akses ke jurnal ini
        if ($jurnal['user_id'] != $userId) {
            return redirect()->to('/guru/jurnal')->with('error', 'Anda tidak memiliki akses ke jurnal ini.');
        }

        // Cek apakah ini adalah data Absensi (bukan Jurnal Mengajar)
        // Jika ya, redirect ke halaman Absensi atau tolak akses
        if ($jurnal['mapel_id'] == 18 || $jurnal['materi'] == 'Absensi Kelas') {
             return redirect()->to('/guru/jurnal')->with('error', 'Data ini adalah data Absensi, silakan akses melalui menu Absensi.');
        }

        // Ambil data absensi terkait
        // Ambil data absensi terkait
        $absensi = $this->db->table('absensi a')
            ->select('a.*, s.nis, s.nama as nama_siswa')
            ->join('siswa s', 'a.siswa_id = s.id')
            ->where('a.rombel_id', $jurnal['rombel_id'])
            ->where('a.tanggal', $jurnal['tanggal'])
            ->orderBy('s.nama', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Detail Jurnal Mengajar',
            'active' => 'jurnal',
            'jurnal' => $jurnal,
            'absensi' => $absensi
        ];

        if ($this->isMobile()) {
            return view('mobile/guru/jurnal/view', $data);
        }

        return view('guru/jurnal/view', $data);
    }

    public function delete($id = null)
    {
        // Hapus jurnal
        $userId = session()->get('user_id');
        
        $jurnal = $this->jurnalModel->find($id);
        
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jurnal dengan ID ' . $id . ' tidak ditemukan.');
        }
        
        // Cek apakah user memiliki akses ke jurnal ini
        if ($jurnal['user_id'] != $userId) {
            return redirect()->to('/guru/jurnal')->with('error', 'Anda tidak memiliki akses ke jurnal ini.');
        }
        
        // Hapus data terkait
        // $this->absensiModel->where('jurnal_id', $id)->delete(); // REMOVED: Absensi is now independent
        // $this->jurnalDetailModel->where('jurnal_id', $id)->delete();
        
        // Hapus jurnal
        if ($this->jurnalModel->delete($id)) {
            return redirect()->to('/guru/jurnal')->with('success', 'Jurnal berhasil dihapus.');
        } else {
            return redirect()->to('/guru/jurnal')->with('error', 'Gagal menghapus jurnal.');
        }
    }

    /**
     * Method untuk mengambil siswa berdasarkan rombel
     * Digunakan untuk form absensi
     */
    public function getSiswaByKelas()
    {
        $rombel_id = $this->request->getPost('rombel_id'); // Diubah dari kelas_id
        $siswa = $this->siswaModel->getSiswaByRombel($rombel_id); // Diubah dari getSiswaByKelas
        
        // Format data untuk response JSON
        $formattedSiswa = [];
        foreach ($siswa as $s) {
            $formattedSiswa[] = [
                'siswa_id' => $s['id'],
                'siswa_nis' => $s['nis'],
                'siswa_nama' => $s['nama']
            ];
        }
        
        return $this->response->setJSON($formattedSiswa);
    }

    // Method untuk memproses data absensi
    private function processAttendanceData($jurnalId, $attendanceData)
    {
        if ($attendanceData && is_array($attendanceData)) {
            foreach ($attendanceData as $siswaId => $status) {
                $absensiData = [
                    'jurnal_id' => $jurnalId,
                    'siswa_id' => $siswaId,
                    'status' => $status
                ];
                $this->absensiModel->insert($absensiData);
            }
        }
    }

    // Method untuk memproses detail jurnal
    private function processJurnalDetails($jurnalId, $jurnalDetails)
    {
        if ($jurnalDetails && is_array($jurnalDetails)) {
            foreach ($jurnalDetails as $detail) {
                $detailData = [
                    'jurnal_id' => $jurnalId,
                    'deskripsi' => $detail['deskripsi'] ?? '',
                    'catatan' => $detail['catatan'] ?? ''
                ];
                $this->jurnalDetailModel->insert($detailData);
            }
        }
    }

    private function isAdmin()
    {
        $role = session()->get('role');
        return in_array($role, ['admin', 'super_admin']);
    }
    
    /**
     * Menampilkan form untuk generate PDF
     */
    public function generatePdf()
    {
        // Cek jika user sudah login dan memiliki role guru
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }
        
        $data = [
            'title' => 'Generate Laporan PDF',
            'active' => 'jurnal'
        ];
        
        return view('guru/jurnal/generate_pdf', $data);
    }
    
    /**
     * Mengekspor jurnal ke PDF berdasarkan filter bulan
     */
    public function exportPdf()
    {
        // Cek jika user sudah login dan memiliki role guru
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }
        
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        // Ambil data dari form
        $bulanAwal = $this->request->getPost('bulan_awal');
        $bulanAkhir = $this->request->getPost('bulan_akhir');
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        
        if (!$bulanAwal || !$bulanAkhir) {
            return redirect()->back()->with('error', 'Bulan awal dan bulan akhir harus dipilih.');
        }
        
        try {
            // Siapkan filter
            $filters = [
                'bulan_awal' => $bulanAwal,
                'bulan_akhir' => $bulanAkhir,
                'tahun' => $tahun
            ];
            
            // Ambil data user
            $userModel = new \App\Models\UserModel();
            $userData = $userModel->find($userId);
            
            // Tambahkan informasi apakah user adalah wali kelas
            $rombelModel = new \App\Models\RombelModel();
            $kelasWali = $rombelModel->where('wali_kelas', $userId)->first();
            $userData['is_wali_kelas'] = $kelasWali ? true : false;
            $userData['kelas_wali'] = $kelasWali;
            
            // Generate PDF
            $pdfContent = $this->jurnalPdfService->generateReportPdf($filters, $userId, $role, $userData);
            
            // Set nama file
            $fileName = 'jurnal_mengajar_' . $userData['nama'] . '_' . $bulanAwal . '-' . $bulanAkhir . '_' . $tahun . '.pdf';
            
            // Return PDF sebagai response
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->setBody($pdfContent);
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}