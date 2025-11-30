<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Repositories\AbsensiRepository;
use App\Repositories\RombelRepository;
use App\Services\AbsensiExportService;
use App\Services\AbsensiCalculationService;
use App\Services\HariLiburService;
use App\Helpers\MobileDetection;
use App\Validation\ExportValidation;
use App\DTO\ExportConfigDTO;

class AbsensiReportController extends BaseController
{
    protected $authService;
    protected $absensiRepo;
    protected $rombelRepo;
    protected $exportService;
    protected $calculationService;
    protected $hariLiburService;
    protected $exportValidation;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->absensiRepo = new AbsensiRepository();
        $this->rombelRepo = new RombelRepository();
        $this->exportService = new AbsensiExportService();
        $this->calculationService = new AbsensiCalculationService();
        $this->hariLiburService = new HariLiburService();
        $this->exportValidation = new ExportValidation();
    }

    public function index()
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        $userId = $this->authService->getUserId();
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');
        $rombelId = $this->request->getGet('rombel_id');

        // Jika rombel_id kosong, cek apakah guru adalah wali kelas
        if (empty($rombelId)) {
            $kelasWali = $this->rombelRepo->getKelasWali($userId);
            if ($kelasWali) {
                $rombelId = $kelasWali['id'];
            }
        }

        $kelasGuru = $this->rombelRepo->getKelasGuru($userId);
        $rekapKelas = $this->absensiRepo->getRekapKelasGuru($startDate, $endDate, $rombelId, $userId);
        $rekapHarian = $this->absensiRepo->getRekapHarianGuru($startDate, $endDate, $rombelId, $userId);

        // Format rekap harian for chart
        $labels = [];
        $hadirData = [];
        $izinData = [];
        $sakitData = [];
        $alfaData = [];

        foreach ($rekapHarian as $row) {
            $labels[] = date('d M', strtotime($row['tanggal']));
            $hadirData[] = (int)$row['hadir'];
            $izinData[] = (int)$row['izin'];
            $sakitData[] = (int)$row['sakit'];
            $alfaData[] = (int)$row['alfa'];
        }

        $chartData = [
            'labels' => $labels,
            'hadir' => $hadirData,
            'izin' => $izinData,
            'sakit' => $sakitData,
            'alfa' => $alfaData
        ];

        $data = [
            'title' => 'Data Absensi',
            'active' => 'absensi',
            'rekapKelas' => $rekapKelas,
            'rekapHarian' => $chartData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'rombelId' => $rombelId,
            'rombel' => $kelasGuru
        ];

        $viewPath = MobileDetection::isMobile() ? 'mobile/guru/absensi/index' : 'guru/absensi/index';
        return view($viewPath, $data);
    }

    public function export()
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }
        
        $userId = $this->authService->getUserId();
        $rombelList = $this->rombelRepo->getKelasGuru($userId);
        
        $data = [
            'title' => 'Export Data Absensi',
            'active' => 'absensi',
            'rombel' => $rombelList
        ];
        
        $viewPath = MobileDetection::isMobile() ? 'mobile/guru/absensi/export' : 'guru/absensi/export';
        return view($viewPath, $data);
    }

    public function process_export()
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }
        
        if (!$this->validate($this->exportValidation->getRules())) {
            return redirect()->back()->withInput()->with('error', 'Data tidak lengkap');
        }
        
        $dto = new ExportConfigDTO($this->request->getPost());
        
        if ($dto->startMonth > $dto->endMonth) {
            return redirect()->back()->withInput()->with('error', 'Bulan awal tidak boleh melewati bulan akhir');
        }
        
        $currentYear = date('Y');
        $startDate = $currentYear . '-' . str_pad($dto->startMonth, 2, '0', STR_PAD_LEFT) . '-01';
        $endDate = date('Y-m-t', strtotime($currentYear . '-' . str_pad($dto->endMonth, 2, '0', STR_PAD_LEFT) . '-01'));
        
        $userId = $this->authService->getUserId();
        $absensiData = $this->absensiRepo->getDataExport($startDate, $endDate, $dto->rombelId, $userId);
        
        if (empty($absensiData)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada data absensi pada periode tersebut');
        }

        $today = date('Y-m-d');
        $calcEndDate = ($endDate > $today) ? $today : $endDate;

        $effectiveDays = $this->calculationService->hitungHariEfektif($startDate, $calcEndDate);
        $hariLiburDetail = $this->hariLiburService->getDetailHariLibur($startDate, $calcEndDate);
        
        if ($dto->exportType == 'excel') {
            return $this->exportService->exportToExcel($absensiData, $startDate, $endDate, $effectiveDays, $hariLiburDetail);
        } else {
            return $this->exportService->exportToPDF($absensiData, $startDate, $endDate);
        }
    }
}
