<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Services\AbsensiService;
use App\Models\RombelModel;
use App\Models\AbsensiModel;
use App\Models\JurnalModel;
use App\Models\SiswaModel;
use App\Models\MataPelajaranModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Absensi extends BaseController
{
    protected $absensiService;
    protected $rombelModel;
    protected $absensiModel;
    protected $jurnalModel;
    protected $siswaModel;
    protected $mapelModel;
    protected $db;

    public function __construct()
    {
        $this->absensiService = new AbsensiService();
        $this->rombelModel = new RombelModel();
        $this->absensiModel = new AbsensiModel();
        $this->jurnalModel = new JurnalModel();
        $this->siswaModel = new SiswaModel();
        $this->mapelModel = new MataPelajaranModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan daftar absensi untuk guru
     * Hanya menampilkan absensi dari kelas yang diajar oleh guru
     */
    public function index()
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        // Ambil parameter filter dari request
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01'); // Awal bulan ini
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t'); // Akhir bulan ini
        $rombelId = $this->request->getGet('rombel_id');

        // Mendapatkan daftar semua kelas untuk dropdown filter
        $kelasGuru = $this->getKelasGuru($userId);

        // Mendapatkan data rekap absensi per kelas (hanya absensi yang dibuat oleh guru ini)
        $rekapKelas = $this->getRekapKelasGuru($startDate, $endDate, $rombelId, $userId);

        // Mendapatkan data rekap harian (grafik)
        $rekapHarian = $this->getRekapHarianGuru($startDate, $endDate, $rombelId, $userId);

        // Data untuk dikirim ke view
        $data = [
            'title' => 'Data Absensi',
            'active' => 'absensi',
            'rekapKelas' => $rekapKelas,
            'rekapHarian' => $rekapHarian,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'rombelId' => $rombelId,
            'rombel' => $kelasGuru
        ];

        $viewPath = $this->isMobile() ? 'mobile/guru/absensi/index' : 'guru/absensi/index';
        return view($viewPath, $data);
    }

    /**
     * Menampilkan form untuk input absensi harian
     */
    public function create()
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        // Ambil data rombel yang diajar oleh guru ini
        $kelasGuru = $this->getKelasGuru($userId);
        
        // Ambil semua data mata pelajaran
        $mapel = $this->mapelModel
            ->orderBy('nama_mapel', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Input Absensi Harian',
            'active' => 'absensi',
            'rombel' => $kelasGuru,
            'mapel' => $mapel,
            'selected_rombel' => $this->request->getGet('rombel_id'),
            'selected_tanggal' => $this->request->getGet('tanggal')
        ];

        $viewPath = $this->isMobile() ? 'mobile/guru/absensi/create' : 'guru/absensi/create';
        return view($viewPath, $data);
    }

    /**
     * Menyimpan data absensi
     */
    public function store()
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $rules = [
            'tanggal' => 'required|valid_date',
            'rombel_id' => 'required|is_not_unique[rombel.id]',
            'mapel_id' => 'required|is_not_unique[mata_pelajaran.id]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid');
        }

        $tanggal = $this->request->getPost('tanggal');
        $rombelId = $this->request->getPost('rombel_id');
        $mapelId = $this->request->getPost('mapel_id');
        $jamKe = $this->request->getPost('jam_ke');
        $materi = $this->request->getPost('materi');
        $absensiData = $this->request->getPost('absensi');

        // Verifikasi bahwa guru mengajar kelas ini
        $userId = session()->get('user_id');
        if (!$this->isGuruMengajarKelas($userId, $rombelId)) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak mengajar kelas ini');
        }

        try {
            // Simpan data absensi menggunakan service
            $this->simpanAbsensiGuru($tanggal, $rombelId, $mapelId, $jamKe, $materi, $absensiData, $userId);
            return redirect()->to('/guru/absensi')->with('success', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback transaksi
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data absensi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail absensi
     */
    public function view($id)
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        // Ambil data jurnal
        $jurnal = $this->jurnalModel->find($id);
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data jurnal tidak ditemukan');
        }

        // Verifikasi bahwa jurnal ini milik guru yang login
        if ($jurnal['user_id'] != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke data ini');
        }

        // Ambil data absensi
        $absensi = $this->absensiModel->getAbsensiByJurnal($id);

        // Ambil data rombel
        $rombel = $this->rombelModel->find($jurnal['rombel_id']);

        // Ambil data mapel
        $mapel = $this->mapelModel->find($jurnal['mapel_id']);

        // Hitung statistik
        $stats = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alfa' => 0
        ];

        foreach ($absensi as $item) {
            if (isset($stats[$item['status']])) {
                $stats[$item['status']]++;
            }
        }

        $data = [
            'title' => 'Detail Absensi',
            'active' => 'absensi',
            'jurnal' => $jurnal,
            'absensi' => $absensi,
            'rombel' => $rombel,
            'mapel' => $mapel,
            'stats' => $stats
        ];

        $viewPath = $this->isMobile() ? 'mobile/guru/absensi/view' : 'guru/absensi/view';
        return view($viewPath, $data);
    }

    /**
     * AJAX Handler untuk mendapatkan siswa berdasarkan rombel
     */
    public function getSiswaByRombel()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $rombelId = $this->request->getPost('rombel_id');
        
        if (!$rombelId) {
            return $this->response->setJSON(['error' => 'Rombel ID is required']);
        }

        // Verifikasi bahwa guru mengajar kelas ini
        $userId = session()->get('user_id');
        if (!$this->isGuruMengajarKelas($userId, $rombelId)) {
            return $this->response->setJSON(['error' => 'Anda tidak mengajar kelas ini']);
        }

        try {
            $siswa = $this->absensiService->getSiswaByRombelId($rombelId);
            return $this->response->setJSON($siswa);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    /**
     * Helper: Mendapatkan daftar kelas yang diajar oleh guru
     */
    private function getKelasGuru($userId)
    {
        // Menampilkan semua kelas yang tersedia
        // Guru bisa mengisi absensi untuk kelas manapun
        $builder = $this->db->table('rombel r');
        $builder->select('r.id, r.kode_rombel, r.nama_rombel, r.tingkat');
        $builder->orderBy('r.kode_rombel', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Helper: Cek apakah guru mengajar kelas tertentu
     * Untuk saat ini, semua guru bisa mengakses semua kelas
     */
    private function isGuruMengajarKelas($userId, $rombelId)
    {
        // Mengizinkan semua guru mengakses semua kelas
        // Validasi bisa diperketat jika diperlukan di masa depan
        return true;
    }

    /**
     * Helper: Mendapatkan rekap absensi per kelas untuk guru
     */
    private function getRekapKelasGuru($startDate, $endDate, $rombelId, $userId)
    {
        $rekapBuilder = $this->db->table('absensi a')
            ->select('r.id as rombel_id, r.kode_rombel, r.nama_rombel, 
                      COUNT(DISTINCT s.id) as jumlah_siswa,
                      SUM(CASE WHEN a.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                      SUM(CASE WHEN a.status = "izin" THEN 1 ELSE 0 END) as izin,
                      SUM(CASE WHEN a.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                      SUM(CASE WHEN a.status = "alfa" THEN 1 ELSE 0 END) as alfa')
            ->join('siswa s', 'a.siswa_id = s.id')
            ->join('rombel r', 'a.rombel_id = r.id')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate)
            ->where('a.guru_id', $userId); // Filter berdasarkan guru_id

        // Filter berdasarkan rombel jika dipilih
        if ($rombelId) {
            $rekapBuilder->where('r.id', $rombelId);
        }

        // Grouping dan ordering
        $rekapBuilder->groupBy('r.id, r.kode_rombel, r.nama_rombel')
            ->orderBy('r.kode_rombel', 'ASC');

        // Eksekusi query
        return $rekapBuilder->get()->getResultArray();
    }

    /**
     * Helper: Mendapatkan rekap harian untuk grafik
     */
    private function getRekapHarianGuru($startDate, $endDate, $rombelId, $userId)
    {
        $builder = $this->db->table('absensi a')
            ->select('DATE(a.tanggal) as tanggal,
                      SUM(CASE WHEN a.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                      SUM(CASE WHEN a.status = "izin" THEN 1 ELSE 0 END) as izin,
                      SUM(CASE WHEN a.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                      SUM(CASE WHEN a.status = "alfa" THEN 1 ELSE 0 END) as alfa')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate)
            ->where('a.guru_id', $userId);

        if ($rombelId) {
            $builder->where('a.rombel_id', $rombelId);
        }

        $builder->groupBy('DATE(a.tanggal)')
            ->orderBy('DATE(a.tanggal)', 'ASC');

        $rekapHarianRaw = $builder->get()->getResultArray();

        $labels = [];
        $hadirData = [];
        $izinData = [];
        $sakitData = [];
        $alfaData = [];

        foreach ($rekapHarianRaw as $row) {
            $labels[] = date('d M', strtotime($row['tanggal']));
            $hadirData[] = (int)$row['hadir'];
            $izinData[] = (int)$row['izin'];
            $sakitData[] = (int)$row['sakit'];
            $alfaData[] = (int)$row['alfa'];
        }

        return [
            'labels' => $labels,
            'hadir' => $hadirData,
            'izin' => $izinData,
            'sakit' => $sakitData,
            'alfa' => $alfaData
        ];
    }

    /**
     * Helper: Simpan absensi untuk guru
     */
    private function simpanAbsensiGuru($tanggal, $rombelId, $mapelId, $jamKe, $materi, $absensiData, $userId)
    {
        // Mulai transaksi database
        $this->db->transBegin();

        try {
            // Simpan data absensi
            foreach ($absensiData as $siswaId => $data) {
                $status = $data['status'];
                $keterangan = $data['keterangan'] ?? '';

                $this->absensiModel->insert([
                    'tanggal' => $tanggal,
                    'rombel_id' => $rombelId,
                    'guru_id' => $userId,
                    'mapel_id' => $mapelId,
                    'siswa_id' => $siswaId,
                    'status' => $status,
                    'keterangan' => $keterangan
                ]);
            }

            // Commit transaksi
            $this->db->transCommit();
            
            return true;
        } catch (\Exception $e) {
            // Rollback transaksi
            $this->db->transRollback();
            
            // Re-enable foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            
            throw $e;
        }
    }
    /**
     * Menampilkan detail absensi per kelas
     */
    public function detail($rombelId)
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Ambil data rombel
        $rombel = $this->rombelModel->find($rombelId);
        if (!$rombel) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Rombel tidak ditemukan');
        }

        // Ambil data detail absensi per kelas
        // Menggunakan service yang sama dengan admin
        $userId = session()->get('user_id');
        $detailAbsensi = $this->absensiService->getDetailAbsensiPerKelas($rombelId, $startDate, $endDate, $userId);

        $data = [
            'title' => 'Detail Absensi Kelas ' . $rombel['nama_rombel'],
            'active' => 'absensi',
            'rombel' => $rombel,
            'detailAbsensi' => $detailAbsensi,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $viewPath = $this->isMobile() ? 'mobile/guru/absensi/detail' : 'guru/absensi/detail';
        return view($viewPath, $data);
    }

    /**
     * Menampilkan form edit absensi
     */
    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        // Ambil data absensi
        $absensi = $this->absensiModel->find($id);
        if (!$absensi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data absensi tidak ditemukan');
        }

        // Ambil data jurnal terkait
        $jurnal = $this->jurnalModel->find($absensi['jurnal_id']);
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data jurnal tidak ditemukan');
        }

        // Verifikasi kepemilikan jurnal
        if ($jurnal['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit absensi ini');
        }

        // Ambil data siswa terkait
        $siswa = $this->siswaModel->find($absensi['siswa_id']);
        if (!$siswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Absensi',
            'active' => 'absensi',
            'absensi' => $absensi,
            'jurnal' => $jurnal,
            'siswa' => $siswa
        ];

        $viewPath = $this->isMobile() ? 'mobile/guru/absensi/edit' : 'guru/absensi/edit';
        return view($viewPath, $data);
    }

    /**
     * Mengupdate data absensi
     */
    public function update($id)
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        // Cek kepemilikan sebelum update
        $absensi = $this->absensiModel->find($id);
        if ($absensi) {
            $jurnal = $this->jurnalModel->find($absensi['jurnal_id']);
            if ($jurnal && $jurnal['user_id'] != $userId) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengupdate absensi ini');
            }
        }

        $data = [
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->absensiModel->update($id, $data)) {
            // Redirect kembali ke detail kelas atau index?
            // Admin redirect ke index. Kita ikuti admin.
            return redirect()->to('/guru/absensi')->with('success', 'Data absensi berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data absensi.');
        }
    }

    /**
     * Menampilkan form export data absensi
     */
    public function export()
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }
        
        $userId = session()->get('user_id');
        
        // Data untuk dropdown rombel (hanya kelas yang diajar)
        $rombelList = $this->getKelasGuru($userId);
        
        $data = [
            'title' => 'Export Data Absensi',
            'active' => 'absensi',
            'rombel' => $rombelList
        ];
        
        $viewPath = $this->isMobile() ? 'mobile/guru/absensi/export' : 'guru/absensi/export';
        return view($viewPath, $data);
    }
    
    /**
     * Memproses export data absensi
     */
    public function process_export()
    {
        // Cek jika user sudah login dan memiliki role guru
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return redirect()->to('/auth/login');
        }
        
        // Validasi input
        $rules = [
            'start_month' => 'required',
            'end_month' => 'required',
            'export_type' => 'required|in_list[pdf,excel]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak lengkap');
        }
        
        $startMonth = $this->request->getPost('start_month');
        $endMonth = $this->request->getPost('end_month');
        $exportType = $this->request->getPost('export_type');
        $rombelId = $this->request->getPost('rombel_id');
        
        // Validasi rentang bulan
        if ($startMonth > $endMonth) {
            return redirect()->back()->withInput()->with('error', 'Bulan awal tidak boleh melewati bulan akhir');
        }
        
        // Tentukan tanggal awal dan akhir berdasarkan bulan
        $currentYear = date('Y');
        $startDate = $currentYear . '-' . str_pad($startMonth, 2, '0', STR_PAD_LEFT) . '-01';
        $endDate = date('Y-m-t', strtotime($currentYear . '-' . str_pad($endMonth, 2, '0', STR_PAD_LEFT) . '-01'));
        
        // Mendapatkan data untuk export
        // Note: getDataExport di service tidak memfilter by user_id. 
        // Ini berarti guru bisa export data absensi guru lain di kelas yang sama?
        // Jika kita ingin membatasi, kita perlu method baru di service atau filter manual.
        // Untuk saat ini kita ikuti service yang ada.
        // Mendapatkan data untuk export
        $userId = session()->get('user_id');
        $absensiData = $this->absensiService->getDataExport($startDate, $endDate, $rombelId, $userId);
        
        if (empty($absensiData)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada data absensi pada periode tersebut');
        }

        // Adjust end date if it's in the future (to avoid counting future days as Alfa)
        $today = date('Y-m-d');
        $calcEndDate = ($endDate > $today) ? $today : $endDate;

        // Hitung hari efektif
        $effectiveDays = $this->hitungHariEfektif($startDate, $calcEndDate);
        $hariLiburDetail = $this->getDetailHariLibur($startDate, $calcEndDate);
        
        // Proses export sesuai tipe
        if ($exportType == 'excel') {
            return $this->exportToExcel($absensiData, $startDate, $endDate, $effectiveDays, $hariLiburDetail);
        } else {
            return $this->exportToPDF($absensiData, $startDate, $endDate);
        }
    }

    /**
     * Helper: Get Hari Libur from API
     */
    private function getHariLibur($tahun, $bulan = null)
    {
        $cacheKey = "hari_libur_{$tahun}" . ($bulan ? "_{$bulan}" : "");
        
        // Cek cache dulu (1 hari cache)
        if ($cached = cache($cacheKey)) {
            return $cached;
        }
        
        // Build API URL
        $apiUrl = "https://libur.deno.dev/api/{$tahun}";
        if ($bulan) {
            $apiUrl .= "/{$bulan}";
        }
        
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get($apiUrl, [
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'JurnalGuruApp/1.0'
                ]
            ]);
            
            if ($response->getStatusCode() === 200) {
                $liburData = json_decode($response->getBody(), true);
                
                // Extract hanya tanggalnya
                $tanggalLibur = array_column($liburData, 'date');
                
                // Cache untuk 1 hari
                cache()->save($cacheKey, $tanggalLibur, 86400);
                
                return $tanggalLibur;
            }
        } catch (\Exception $e) {
            // Fallback: return empty array jika API error or use hardcoded fallback
            log_message('error', 'Error fetch hari libur: ' . $e->getMessage());
            return $this->getHariLiburFallback($tahun);
        }
        
        return $this->getHariLiburFallback($tahun);
    }

    /**
     * Helper: Fallback hari libur jika API gagal
     */
    private function getHariLiburFallback($tahun)
    {
        $fallbackLibur = [
            $tahun . '-01-01', // Tahun Baru
            $tahun . '-05-01', // Buruh
            $tahun . '-08-17', // Kemerdekaan
            $tahun . '-12-25', // Natal
            // Tambahkan tanggal merah statis lainnya jika perlu
        ];
        
        return $fallbackLibur;
    }

    /**
     * Helper: Hitung Hari Efektif
     */
    private function hitungHariEfektif($startDate, $endDate)
    {
        $effectiveDays = 0;
        $currentDate = $startDate;
        
        // Extract tahun dan bulan dari periode
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
        
        // Get hari libur untuk semua tahun dalam range
        $allHariLibur = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $liburTahun = $this->getHariLibur($year);
            $allHariLibur = array_merge($allHariLibur, $liburTahun);
        }
        
        while ($currentDate <= $endDate) {
            $dayOfWeek = date('N', strtotime($currentDate));
            $isHoliday = in_array($currentDate, $allHariLibur);
            
            // Exclude Minggu (7) dan hari libur nasional
            if ($dayOfWeek != 7 && !$isHoliday) {
                $effectiveDays++;
            }
            
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        return $effectiveDays;
    }

    /**
     * Helper: Get Detail Hari Libur untuk Info
     */
    private function getDetailHariLibur($startDate, $endDate)
    {
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
        $allHariLibur = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $apiUrl = "https://libur.deno.dev/api/{$year}";
            
            try {
                $client = \Config\Services::curlrequest();
                $response = $client->get($apiUrl, ['timeout' => 10]);
                
                if ($response->getStatusCode() === 200) {
                    $liburData = json_decode($response->getBody(), true);
                    
                    // Filter hanya yang dalam range periode
                    foreach ($liburData as $libur) {
                        if ($libur['date'] >= $startDate && $libur['date'] <= $endDate) {
                            $allHariLibur[] = $libur;
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Error fetch detail libur: ' . $e->getMessage());
            }
        }
        
        return $allHariLibur;
    }

    /**
     * Export data absensi ke Excel
     */
    private function exportToExcel($data, $startDate, $endDate, $effectiveDays, $hariLiburDetail)
    {
        // Tentukan periode berdasarkan tanggal
        $startMonth = date('n', strtotime($startDate));
        $endMonth = date('n', strtotime($endDate));
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));

        // Cek apakah single month
        $isSingleMonth = ($startMonth == $endMonth && $startYear == $endYear);
        
        // Tentukan judul periode
        if ($startMonth == 1 && $endMonth == 12 && $startYear == $endYear) {
            $periodeText = $startYear; // Tahun lengkap
            $isTahunan = true;
        } else if ($isSingleMonth) {
            $periodeText = date('F Y', strtotime($startDate)); // 1 bulan
            $isTahunan = false;
        } else {
            $periodeText = date('M Y', strtotime($startDate)) . ' - ' . date('M Y', strtotime($endDate)); // Beberapa bulan
            $isTahunan = false;
        }

        // Struktur data untuk processing
        $dataSiswa = [];
        $currentSiswa = null;

        foreach ($data as $row) {
            $siswaId = $row['siswa_id'] ?? $row['nis']; // Gunakan NIS jika ID tidak tersedia
            if ($currentSiswa !== $siswaId) {
                // Siswa baru
                $currentSiswa = $siswaId;
                $dataSiswa[$currentSiswa] = [
                    'nama' => $row['nama_siswa'],
                    'nisn' => $row['nisn'] ?? '',
                    'nis' => $row['nis'],
                    'rombel' => $row['nama_rombel'],
                    'kode_rombel' => $row['kode_rombel'],
                    'bulanan' => [],
                    'total' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0]
                ];
            }
            
            // Data per bulan
            $bulan = date('n', strtotime($row['tanggal']));
            $tahun = date('Y', strtotime($row['tanggal']));
            $bulanTahun = $bulan . '-' . $tahun;
            
            // Inisialisasi data bulan jika belum ada
            if (!isset($dataSiswa[$currentSiswa]['bulanan'][$bulanTahun])) {
                $dataSiswa[$currentSiswa]['bulanan'][$bulanTahun] = [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alfa' => 0
                ];
            }
            
            // Akumulasi data berdasarkan status
            $dataSiswa[$currentSiswa]['bulanan'][$bulanTahun][$row['status']] += 1;
            
            // Akumulasi total
            $dataSiswa[$currentSiswa]['total'][$row['status']] += 1;
        }

        // Hitung Alfa dan Persentase
        foreach ($dataSiswa as &$siswa) {
            $totalKehadiran = $siswa['total']['hadir'] + $siswa['total']['sakit'] + $siswa['total']['izin'];
            // Alfa = Hari Efektif - (Hadir + Sakit + Izin)
            $siswa['total']['alfa'] = max(0, $effectiveDays - $totalKehadiran);
            $siswa['persentase'] = $effectiveDays > 0 ? round(($siswa['total']['hadir'] / $effectiveDays) * 100, 2) : 0;
        }
        unset($siswa); // Break reference

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header columns
        if ($isSingleMonth) {
            $header = ['NO', 'Nama', 'NISN', 'NIS', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'Persentase (%)'];
            $lastCol = 'I';
        } else {
            $header = ['NO', 'Nama', 'NISN', 'NIS', 'Bulan', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'Persentase (%)'];
            $lastCol = 'J';
        }

        // Judul laporan
        $judul = "ABSENSI SISWA : " . (!empty($dataSiswa) ? reset($dataSiswa)['kode_rombel'] . ' - ' . reset($dataSiswa)['rombel'] : 'Semua Kelas');
        $dicetakPada = "Dicetak pada : " . date('d/m/Y H:i:s');
        
        // Header Title
        $sheet->setCellValue('A1', $judul);
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Periode: ' . $periodeText);
        $sheet->mergeCells('A2:' . $lastCol . '2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Info Hari Efektif
        $sheet->setCellValue('A3', 'Hari Efektif: ' . $effectiveDays . ' hari');
        $sheet->mergeCells('A3:' . $lastCol . '3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Info Hari Libur
        $rowInfo = 4;
        if (!empty($hariLiburDetail)) {
            $liburDates = array_map(function($l) { return date('d M', strtotime($l['date'])) . ' (' . $l['name'] . ')'; }, $hariLiburDetail);
            $liburInfo = 'Hari Libur: ' . implode(', ', $liburDates);
            $sheet->setCellValue('A' . $rowInfo, $liburInfo);
            $sheet->mergeCells('A' . $rowInfo . ':' . $lastCol . $rowInfo);
            $sheet->getStyle('A' . $rowInfo)->getFont()->setItalic(true)->setSize(10);
            $sheet->getStyle('A' . $rowInfo)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $rowInfo++;
        }
        
        $sheet->setCellValue('A' . $rowInfo, $dicetakPada);
        $sheet->mergeCells('A' . $rowInfo . ':' . $lastCol . $rowInfo);
        $sheet->getStyle('A' . $rowInfo)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowInfo)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Header tabel
        $rowIndex = $rowInfo + 2;
        $colIndex = 'A';
        foreach ($header as $h) {
            $sheet->setCellValue($colIndex . $rowIndex, $h);
            $colIndex++;
        }
        
        // Style header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E2E2']],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ];
        $sheet->getStyle('A' . $rowIndex . ':' . $lastCol . $rowIndex)->applyFromArray($headerStyle);
        
        // Isi data
        $rowIndex++;
        $rowNumber = 1;

        foreach ($dataSiswa as $siswaId => $siswa) {
            if ($isSingleMonth) {
                // Tampilkan 1 baris per siswa (Single Month)
                $sheet->setCellValue('A' . $rowIndex, $rowNumber++);
                $sheet->setCellValue('B' . $rowIndex, $siswa['nama']);
                $sheet->setCellValue('C' . $rowIndex, $siswa['nisn']);
                $sheet->setCellValue('D' . $rowIndex, $siswa['nis']);
                $sheet->setCellValue('E' . $rowIndex, $siswa['total']['hadir']);
                $sheet->setCellValue('F' . $rowIndex, $siswa['total']['izin']);
                $sheet->setCellValue('G' . $rowIndex, $siswa['total']['sakit']);
                $sheet->setCellValue('H' . $rowIndex, $siswa['total']['alfa']);
                $sheet->setCellValue('I' . $rowIndex, $siswa['persentase'] . '%');
                $rowIndex++;
            } else {
                // Tampilkan multiple baris per siswa (per bulan)
                $firstRow = true;
                foreach ($siswa['bulanan'] as $bulanTahun => $dataBulan) {
                    list($bulan, $tahun) = explode('-', $bulanTahun);
                    $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10)); // Nama bulan
                    
                    $sheet->setCellValue('A' . $rowIndex, $firstRow ? $rowNumber : '');
                    $sheet->setCellValue('B' . $rowIndex, $firstRow ? $siswa['nama'] : '');
                    $sheet->setCellValue('C' . $rowIndex, $firstRow ? $siswa['nisn'] : '');
                    $sheet->setCellValue('D' . $rowIndex, $firstRow ? $siswa['nis'] : '');
                    $sheet->setCellValue('E' . $rowIndex, $namaBulan . ' ' . $tahun);
                    $sheet->setCellValue('F' . $rowIndex, $dataBulan['hadir']);
                    $sheet->setCellValue('G' . $rowIndex, $dataBulan['izin']);
                    $sheet->setCellValue('H' . $rowIndex, $dataBulan['sakit']);
                    $sheet->setCellValue('I' . $rowIndex, $dataBulan['alfa']);
                    $sheet->setCellValue('J' . $rowIndex, ''); // Persentase per bulan belum dihitung
                    
                    $rowIndex++;
                    $firstRow = false;
                }
                
                // Baris total untuk periode jika lebih dari 1 bulan
                if (count($siswa['bulanan']) > 1) {
                    $sheet->setCellValue('A' . $rowIndex, '');
                    $sheet->setCellValue('B' . $rowIndex, '');
                    $sheet->setCellValue('C' . $rowIndex, '');
                    $sheet->setCellValue('D' . $rowIndex, '');
                    $sheet->setCellValue('E' . $rowIndex, 'TOTAL');
                    $sheet->setCellValue('F' . $rowIndex, $siswa['total']['hadir']);
                    $sheet->setCellValue('G' . $rowIndex, $siswa['total']['izin']);
                    $sheet->setCellValue('H' . $rowIndex, $siswa['total']['sakit']);
                    $sheet->setCellValue('I' . $rowIndex, $siswa['total']['alfa']);
                    $sheet->setCellValue('J' . $rowIndex, $siswa['persentase'] . '%');
                    $sheet->getStyle('E' . $rowIndex . ':J' . $rowIndex)->getFont()->setBold(true);
                    $rowIndex++;
                }
                
                // Separator row (optional)
                $rowNumber++;
            }
        }
        
        // Auto size column
        foreach (range('A', $lastCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Border untuk data
        $sheet->getStyle('A' . ($rowInfo + 2) . ':' . $lastCol . ($rowIndex - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        // Center align columns for numbers
        if ($isSingleMonth) {
             $sheet->getStyle('E' . ($rowInfo + 3) . ':I' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('A' . ($rowInfo + 3) . ':A' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
             $sheet->getStyle('F' . ($rowInfo + 3) . ':J' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('A' . ($rowInfo + 3) . ':A' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        
        // Filename
        $filename = 'rekap-absensi-' . date('Y-m-d-His') . '.xlsx';
        
        // Headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Export data absensi ke PDF (placeholder)
     */
    private function exportToPDF($data, $startDate, $endDate)
    {
        // Untuk saat ini kita akan kembali ke form export dengan pesan bahwa PDF belum tersedia
        return redirect()->back()->withInput()->with('error', 'Export ke PDF belum tersedia. Silakan pilih export ke Excel.');
    }

}
