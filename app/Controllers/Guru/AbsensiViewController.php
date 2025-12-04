<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Repositories\AbsensiRepository;
use App\Repositories\JurnalRepository;
use App\Repositories\RombelRepository;
use App\Repositories\MataPelajaranRepository;
use App\Repositories\SiswaRepository;
use App\Helpers\MobileDetection;
use App\Helpers\AuthorizationHelper;

class AbsensiViewController extends BaseController
{
    protected $authService;
    protected $absensiRepo;
    protected $jurnalRepo;
    protected $rombelRepo;
    protected $mapelRepo;
    protected $siswaRepo;
    protected $hariLiburService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->absensiRepo = new AbsensiRepository();
        $this->jurnalRepo = new JurnalRepository();
        $this->rombelRepo = new RombelRepository();
        $this->mapelRepo = new MataPelajaranRepository();
        $this->siswaRepo = new SiswaRepository();
        $this->hariLiburService = new \App\Services\HariLiburService();
    }

    public function view($id)
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        $userId = $this->authService->getUserId();
        $jurnal = $this->jurnalRepo->find($id);
        
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data jurnal tidak ditemukan');
        }

        if ($jurnal['user_id'] != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke data ini');
        }

        $absensi = $this->absensiRepo->getAbsensiByJurnal($id);
        $rombel = $this->rombelRepo->find($jurnal['rombel_id']);
        $mapel = $this->mapelRepo->find($jurnal['mapel_id']);

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

        $viewPath = MobileDetection::isMobile() ? 'mobile/guru/absensi/view' : 'guru/absensi/view';
        return view($viewPath, $data);
    }

    public function detail($rombelId)
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        // Ambil filter tanggal, default ke hari ini jika tidak ada
        $filterDate = $this->request->getGet('filter_date');
        
        // Jika tidak ada filter_date, cek start_date (legacy support) atau default today
        if (!$filterDate) {
            $filterDate = $this->request->getGet('start_date') ?? date('Y-m-d');
        }

        $rombel = $this->rombelRepo->find($rombelId);
        if (!$rombel) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Rombel tidak ditemukan');
        }

        $userId = $this->authService->getUserId();
        
        // Cek Hari Libur
        $isHoliday = false;
        $holidayName = '';
        
        // Gunakan getDetailHariLibur untuk cek range 1 hari
        $liburData = $this->hariLiburService->getDetailHariLibur($filterDate, $filterDate);
        if (!empty($liburData)) {
            $isHoliday = true;
            $holidayName = $liburData[0]['holiday_name'];
        }

        // Use Repository instead of legacy service
        // Kita filter per hari sesuai request user
        $detailAbsensi = $this->absensiRepo->getDetailAbsensiPerKelas($rombelId, $filterDate, $filterDate, $userId);

        $data = [
            'title' => 'Detail Absensi Kelas ' . $rombel['nama_rombel'],
            'active' => 'absensi',
            'rombel' => $rombel,
            'detailAbsensi' => $detailAbsensi,
            'filterDate' => $filterDate,
            'isHoliday' => $isHoliday,
            'holidayName' => $holidayName,
            // Keep start/end for back button compatibility if needed, but UI will focus on filterDate
            'startDate' => $filterDate, 
            'endDate' => $filterDate
        ];

        $viewPath = MobileDetection::isMobile() ? 'mobile/guru/absensi/detail' : 'guru/absensi/detail';
        return view($viewPath, $data);
    }

    public function getSiswaByRombel()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $rombelId = $this->request->getPost('rombel_id');
        
        if (!$rombelId) {
            return $this->response->setJSON(['error' => 'Rombel ID is required']);
        }

        $userId = $this->authService->getUserId();
        if (!AuthorizationHelper::isGuruMengajarKelas($userId, $rombelId)) {
            return $this->response->setJSON(['error' => 'Anda tidak mengajar kelas ini']);
        }

        try {
            // Use Repository instead of legacy service
            $siswa = $this->siswaRepo->getSiswaByRombelId($rombelId);
            return $this->response->setJSON($siswa);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}
