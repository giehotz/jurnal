<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MasterKalenderPendidikanModel;
use App\Models\KalenderGuruViewModel;
use App\Models\KalenderPublishLogModel;
use App\Models\SettingModel;
use App\Libraries\ExcelValidator;

class KalenderPendidikan extends BaseController
{
    protected $kalenderModel;
    protected $guruViewModel;
    protected $publishLogModel;
    protected $settingModel;
    protected $db;

    public function __construct()
    {
        $this->kalenderModel = new MasterKalenderPendidikanModel();
        $this->guruViewModel = new KalenderGuruViewModel();
        $this->publishLogModel = new KalenderPublishLogModel();
        $this->settingModel = new SettingModel();
        $this->db = \Config\Database::connect();
        helper('tanggal');
    }

    // LIST KALENDER
    public function index()
    {
        // $lembahaId = session('lembaga_id'); // Assuming session for multi-tenancy
        $lembagaId = 'default'; // Fallback if not used yet
        if (session()->has('lembaga_id')) {
            $lembagaId = session('lembaga_id');
        }

        // Get Global Settings
        $settings = $this->settingModel->getSettings();
        $activeTahunAjaran = $settings['school_year'] ?? (date('m') > 6 ? date('Y') . '/' . (date('Y') + 1) : (date('Y') - 1) . '/' . date('Y'));

        // Use active year from settings
        $tahunAjaran = $activeTahunAjaran;

        // Semester defaulting
        $semester = $this->request->getVar('semester') ?? 1;

        // Month Filter for Calendar View
        $bulan = $this->request->getVar('bulan') ?? date('m');
        $tahun = $this->request->getVar('tahun') ?? date('Y');

        // 1. Get DB Calendar (Semester Data) - For List View
        $kalender = $this->kalenderModel->getByTahunSemester(
            $tahunAjaran,
            $semester,
            $lembagaId
        );

        // 2. Prepare Data for Calendar View (Month Data)
        //    a. Get Holidays from API
        $cacheKey = "holidays_{$tahun}_{$bulan}";
        $holidays = cache($cacheKey);
        if ($holidays === null) {
            $holidayApi = new \App\Libraries\HolidayApi(); // Use FQCN or add use import
            $holidays = $holidayApi->getHolidays($tahun, $bulan);
            cache()->save($cacheKey, $holidays, 3600 * 24);
        }

        //    b. Filter DB Calendar for selected Month AND Merge with Holidays
        $calendarMonthEvents = [];

        // Filter DB events for this month
        foreach ($kalender as $k) {
            $kDate = $k['tanggal'];
            if (date('m', strtotime($kDate)) == $bulan && date('Y', strtotime($kDate)) == $tahun) {
                // Standardization for events_by_date
                $calendarMonthEvents[] = $k;
            }
        }

        // Merge Holidays (avoid duplicates if existing in DB)
        foreach ($holidays as $h) {
            $exists = false;
            foreach ($calendarMonthEvents as $k) {
                if ($k['tanggal'] == $h['tanggal']) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $calendarMonthEvents[] = [
                    'id' => null,
                    'tanggal' => $h['tanggal'],
                    'jenis_hari' => 'libur_nasional',
                    'keterangan' => $h['keterangan'] . ' (API)',
                    'warna_kode' => '#dc3545', // Red
                ];
            }
        }

        //    c. Prepare $eventsByDate
        $eventsByDate = [];
        foreach ($calendarMonthEvents as $k) {
            $date = $k['tanggal'];
            if (!isset($eventsByDate[$date])) {
                $eventsByDate[$date] = [];
            }
            $eventsByDate[$date][] = $k;
        }

        //    d. Calculate Statistics (Dynamic Exception-Based)
        $statistik = $this->calculateStatistik($calendarMonthEvents, $bulan, $tahun);

        //    e. Get Weeks for Widget
        //       Assuming 'get_dates_by_week' is in 'tanggal' helper
        $weeks = get_dates_by_week($bulan, $tahun);


        // Check for unpublished changes
        $lastActivity = null;
        if (!empty($kalender)) {
            foreach ($kalender as $k) {
                $t = $k['updated_at'] ?? $k['created_at'];
                if ($t && ($lastActivity === null || strtotime($t) > strtotime($lastActivity))) {
                    $lastActivity = $t;
                }
            }
        }

        $latestPublish = $this->publishLogModel->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembagaId)
            ->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->first();

        $hasUnpublishedChanges = false;
        if ($lastActivity) {
            if (!$latestPublish) {
                $hasUnpublishedChanges = true;
            } else {
                if (strtotime($lastActivity) > strtotime($latestPublish['published_at'])) {
                    $hasUnpublishedChanges = true;
                }
            }
        }

        return view('admin/kalender_pendidikan/index', [
            'kalender' => $kalender, // Full semester list
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'active' => 'kalender_pendidikan',
            'title' => 'Kalender Pendidikan',
            'settings' => $settings,
            'hasUnpublishedChanges' => $hasUnpublishedChanges,
            'latestPublish' => $latestPublish,
            // New Variables for Calendar View
            'weeks' => $weeks,
            'events_by_date' => $eventsByDate,
            'statistik' => $statistik,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    private function calculateStatistik($kalender, $bulan, $tahun)
    {
        $stats = [
            'hari_efektif' => 0,
            'libur_nasional' => 0,
            'libur_sekolah' => 0,
            'ujian' => 0,
            'event' => 0,
            'rapat' => 0,
        ];

        // Map events by date for O(1) lookup
        $eventsByDate = [];
        foreach ($kalender as $item) {
            $eventsByDate[$item['tanggal']] = $item['jenis_hari'];
        }

        // Get total days in month
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        for ($d = 1; $d <= $totalDays; $d++) {
            $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
            $isSunday = (date('N', strtotime($date)) == 7);

            // Periksa jika ada event pada tanggal ini
            if (isset($eventsByDate[$date])) {
                $jenis = $eventsByDate[$date];
                if (isset($stats[$jenis])) {
                    $stats[$jenis]++;
                }
            } else {
                // Tidak ada event -> Default Hari Efektif (kecuali Minggu)
                if (!$isSunday) {
                    $stats['hari_efektif']++;
                }
            }
        }

        return $stats;
    }

    // CREATE MANUAL
    public function create()
    {
        $settings = $this->settingModel->getSettings();

        return view('admin/kalender_pendidikan/create', [
            'active' => 'kalender_pendidikan',
            'title' => 'Tambah Agenda Kalender',
            'settings' => $settings
        ]);
    }

    // STORE
    public function store()
    {
        $rules = [
            'tahun_ajaran' => 'required|regex_match[/^\d{4}\/\d{4}$/]',
            'semester' => 'required|in_list[1,2]',
            'tanggal' => 'required|valid_date[Y-m-d]',
            'jenis_hari' => 'required', // Removed strict in_list to allow 'lainnya' but logic below handles it
            'keterangan' => 'permit_empty|string',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tahunAjaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $tanggal = $this->request->getPost('tanggal');
        $lembagaId = session('lembaga_id') ?? 'default';

        // Determine Jenis Hari
        $jenisHari = $this->request->getPost('jenis_hari');
        if ($jenisHari === 'lainnya') {
            $jenisHari = $this->request->getPost('jenis_hari_manual');
            if (empty($jenisHari)) {
                return redirect()->back()->withInput()->with('error', 'Jenis Hari manual harus diisi');
            }
        }

        // Check duplicate
        $duplicate = $this->kalenderModel->checkDuplicate(
            $tanggal,
            $tahunAjaran,
            $semester,
            $lembagaId
        );

        if ($duplicate) {
            return redirect()->back()->withInput()->with('error', 'Tanggal sudah ada dalam kalender ini');
        }

        // Insert
        $this->kalenderModel->insert([
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'tanggal' => $tanggal,
            'jenis_hari' => $jenisHari,
            'keterangan' => $this->request->getPost('keterangan'),
            'warna_kode' => $this->kalenderModel->getWarnaBerdasarkanJenis($jenisHari),
            'created_by' => session('user_id'),
            'lembaga_id' => $lembagaId,
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

        return view('admin/kalender_pendidikan/edit', [
            'kalender' => $kalender,
            'active' => 'kalender_pendidikan',
            'title' => 'Edit Agenda Kalender'
        ]);
    }

    // UPDATE
    public function update($id)
    {
        $rules = [
            'jenis_hari' => 'required',
            'keterangan' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Determine Jenis Hari
        $jenisHari = $this->request->getPost('jenis_hari');
        if ($jenisHari === 'lainnya') {
            $jenisHari = $this->request->getPost('jenis_hari_manual');
            if (empty($jenisHari)) {
                return redirect()->back()->withInput()->with('error', 'Jenis Hari manual harus diisi');
            }
        }

        $this->kalenderModel->update($id, [
            'jenis_hari' => $jenisHari,
            'keterangan' => $this->request->getPost('keterangan'),
            'warna_kode' => $this->kalenderModel->getWarnaBerdasarkanJenis($jenisHari),
            'tanggal'    => $this->request->getPost('tanggal'), // Allow changing date?
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

    // DELETE BULK
    public function deleteBulk()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        $this->kalenderModel->delete($ids);
        return redirect()->to('/admin/kalender-pendidikan')
            ->with('success', count($ids) . ' data kalender berhasil dihapus');
    }

    // IMPORT EXCEL
    public function importExcel()
    {
        $file = $this->request->getFile('excel_file');
        $tahunAjaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $lembagaId = session('lembaga_id') ?? 'default';

        if (! $file || !$file->isValid()) {
            $errorMsg = $file ? $file->getErrorString() : 'File tidak ditemukan';
            log_message('error', 'Import Excel Failed: ' . $errorMsg);
            session()->setFlashdata('error', 'File tidak valid: ' . $errorMsg);
            return redirect()->back()->withInput();
        }

        try {
            $validator = new ExcelValidator();
            log_message('info', 'Starting Excel Validation for file: ' . $file->getTempName());

            $result = $validator->validateAndParse($file->getTempName());

            if (!$result['valid']) {
                $errorStr = implode(', ', $result['errors']);
                log_message('error', 'Excel Validation Failed: ' . $errorStr);
                session()->setFlashdata('error', 'Import gagal: ' . $errorStr);
                return redirect()->back()->withInput();
            }

            $countSuccess = 0;
            $countUpdated = 0;

            foreach ($result['data'] as $row) {
                // Skip 'hari_efektif' as it's the default state (Exception-Based)
                if ($row['jenis_hari'] == 'hari_efektif') {
                    continue;
                }

                // Check duplicates
                $isDuplicate = $this->kalenderModel->checkDuplicate(
                    $row['tanggal'],
                    $tahunAjaran,
                    $semester,
                    $lembagaId
                );

                if ($isDuplicate) {
                    $existing = $this->kalenderModel->where('tanggal', $row['tanggal'])
                        ->where('tahun_ajaran', $tahunAjaran)
                        ->where('semester', $semester)
                        ->where('lembaga_id', $lembagaId)
                        ->first();

                    $this->kalenderModel->update($existing['id'], [
                        'jenis_hari' => $row['jenis_hari'],
                        'keterangan' => $row['keterangan'],
                        'warna_kode' => $this->kalenderModel->getWarnaBerdasarkanJenis($row['jenis_hari']),
                    ]);
                    $countUpdated++;
                } else {
                    $this->kalenderModel->insert([
                        'tahun_ajaran' => $tahunAjaran,
                        'semester' => $semester,
                        'tanggal' => $row['tanggal'],
                        'jenis_hari' => $row['jenis_hari'],
                        'keterangan' => $row['keterangan'],
                        'warna_kode' => $this->kalenderModel->getWarnaBerdasarkanJenis($row['jenis_hari']),
                        'created_by' => session('user_id'),
                        'lembaga_id' => $lembagaId,
                    ]);
                    $countSuccess++;
                }
            }

            $msg = "Import selesai. $countSuccess data baru ditambahkan, $countUpdated data diupdate.";
            log_message('info', 'Import Success: ' . $msg);
            session()->setFlashdata('success', $msg);

            return redirect()->to('/admin/kalender-pendidikan');
        } catch (\Exception $e) {
            log_message('error', 'Import Excel Exception: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // DOWNLOAD TEMPLATE
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $sheet->setCellValue('A1', 'Tanggal (YYYY-MM-DD)');
            $sheet->setCellValue('B1', 'Jenis Hari');
            $sheet->setCellValue('C1', 'Keterangan');

            // Example Data
            $sheet->setCellValue('A2', date('Y-m-d'));
            $sheet->setCellValue('B2', 'libur_nasional');
            $sheet->setCellValue('C2', 'Contoh Keterangan Kegiatan');

            $sheet->setCellValue('A3', date('Y-m-d', strtotime('+1 day')));
            $sheet->setCellValue('B3', 'libur_sekolah');
            $sheet->setCellValue('C3', 'Libur Semester');

            // Set Column Widths
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);

            // Add Note for Valid Values
            $sheet->setCellValue('E1', 'Petunjuk Pengisian Jenis Hari (Copy salah satu):');
            // 'hari_efektif' removed (Exception-Based)
            $sheet->setCellValue('E2', 'libur_nasional');
            $sheet->setCellValue('E3', 'libur_sekolah');
            $sheet->setCellValue('E4', 'ujian');
            $sheet->setCellValue('E5', 'event');
            $sheet->setCellValue('E6', 'rapat');
            $sheet->getColumnDimension('E')->setAutoSize(true);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'template_kalender_pendidikan.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0'); // no cache

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    // PUBLISH KALENDER
    public function publish()
    {
        $tahunAjaran = $this->request->getPost('tahun_ajaran');
        $semester = $this->request->getPost('semester');
        $lembagaId = session('lembaga_id') ?? 'default';

        // Archive old logs
        $this->db->table('kalender_publish_log')
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembagaId)
            ->where('status', 'published')
            ->update(['status' => 'archived']);

        // Sync to guru view
        $syncResult = $this->guruViewModel->syncFromMasterKalender($tahunAjaran, $semester, $lembagaId);

        // Log publish
        $this->publishLogModel->insert([
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
            'published_by' => session('user_id'),
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s'),
            'lembaga_id' => $lembagaId,
        ]);

        return redirect()->to('/admin/kalender-pendidikan')
            ->with('success', 'Kalender berhasil dipublish ke guru');
    }
}
