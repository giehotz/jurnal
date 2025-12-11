<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\KalenderGuruViewModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use App\Libraries\HolidayApi;
use Dompdf\Dompdf;
use Dompdf\Options;

class KalenderMengajarPdf extends BaseController
{
    protected $guruViewModel;
    protected $settingModel;
    protected $userModel;
    protected $holidayApi;

    public function __construct()
    {
        $this->guruViewModel = new KalenderGuruViewModel();
        $this->settingModel = new SettingModel();
        $this->userModel = new UserModel();
        $this->holidayApi = new HolidayApi();
        helper('tanggal');
        helper('number');
    }

    public function download()
    {
        $guruId = session('user_id');
        $lembagaId = session('lembaga_id') ?? 'default';
        $tahunAjaran = $this->request->getVar('tahun_ajaran') ?? (date('m') > 6 ? date('Y') . '/' . (date('Y') + 1) : (date('Y') - 1) . '/' . date('Y'));

        // Fetch Settings & User
        $settings = $this->settingModel->getSettings();
        $user = $this->userModel->getUserById($guruId);

        // Parse Start Year from "2024/2025" -> 2024
        $parts = explode('/', $tahunAjaran);
        $startYear = intval($parts[0]);
        $endYear = intval($parts[1]);

        // We need 12 months starting from July $startYear to June $endYear
        $monthsData = [];
        $totalStats = [
            'hari_efektif' => 0,
            'libur_nasional' => 0,
            'libur_sekolah' => 0,
            'ujian' => 0,
            'event' => 0,
        ];

        // Loop July (7) to December (12) of startYear
        for ($m = 7; $m <= 12; $m++) {
            $monthsData[] = $this->getMonthData($guruId, $lembagaId, $tahunAjaran, $m, $startYear);
        }
        // Loop January (1) to June (6) of endYear
        for ($m = 1; $m <= 6; $m++) {
            $monthsData[] = $this->getMonthData($guruId, $lembagaId, $tahunAjaran, $m, $endYear);
        }

        // Aggregate Stats
        foreach ($monthsData as $md) {
            $totalStats['hari_efektif'] += $md['stats']['hari_efektif'];
            $totalStats['libur_nasional'] += $md['stats']['libur_nasional'];
            // etc if needed, but per-month display is usually preferred for the grid
        }

        $data = [
            'tahun_ajaran' => $tahunAjaran,
            'monthsData' => $monthsData,
            'user' => $user, // Pass full user object/array
            'school_name' => $settings['school_name'] ?? 'MADRASAH',
            'headmaster_name' => $settings['headmaster_name'] ?? '',
            'headmaster_nip' => $settings['headmaster_nip'] ?? '',
            'totalStats' => $totalStats
        ];

        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('guru/kalender_mengajar/export_pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("Kalender_Mengajar_{$tahunAjaran}.pdf", ["Attachment" => false]);
    }

    private function getMonthData($guruId, $lembagaId, $tahunAjaran, $bulan, $tahun)
    {
        // 1. Get DB Calendar (Kaldik + Guru Agenda)
        // Note: passing 1 or 2 for semester doesn't matter much for raw retrieval if model filters correctly by date, 
        // but strictly: July-Dec is Semester 1 (Ganjil), Jan-June is Semester 2 (Genap)
        $semester = ($bulan >= 7) ? 1 : 2;

        $kalender = $this->guruViewModel->getKalenderGuru($guruId, $tahunAjaran, $semester, $lembagaId, $bulan);

        // 2. Get Holidays
        $cacheKey = "holidays_{$tahun}_{$bulan}";
        $holidays = cache($cacheKey);
        if ($holidays === null) {
            $holidays = $this->holidayApi->getHolidays($tahun, $bulan);
            cache()->save($cacheKey, $holidays, 3600 * 24);
        }

        // 3. Merge
        $mergedKalender = $kalender;
        foreach ($holidays as $h) {
            $exists = false;
            foreach ($mergedKalender as $k) {
                if ($k['tanggal'] == $h['tanggal']) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $mergedKalender[] = [
                    'tanggal' => $h['tanggal'],
                    'jenis_hari' => 'libur_nasional',
                    'keterangan' => $h['keterangan'],
                    'warna_kode' => '#dc3545',
                ];
            }
        }

        // 4. Calculate Stats & Weeks
        $stats = $this->calculateStatistik($mergedKalender, $bulan, $tahun);
        $weeks = get_dates_by_week($bulan, $tahun);

        // Map events
        $eventsByDate = [];
        foreach ($mergedKalender as $k) {
            $date = $k['tanggal'];
            if (!isset($eventsByDate[$date])) {
                $eventsByDate[$date] = [];
            }
            $eventsByDate[$date][] = $k;
        }

        return [
            'month_name' => date('F', mktime(0, 0, 0, $bulan, 10)), // English month name, helper might convert to ID
            'bulan' => $bulan,
            'tahun' => $tahun,
            'weeks' => $weeks,
            'events_by_date' => $eventsByDate,
            'stats' => $stats
        ];
    }

    // Copied from original controller but kept private here
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

        $eventsByDate = [];
        foreach ($kalender as $item) {
            $eventsByDate[$item['tanggal']] = $item['jenis_hari'];
        }

        $totalDays = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        for ($d = 1; $d <= $totalDays; $d++) {
            $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
            $isSunday = (date('N', strtotime($date)) == 7);

            if (isset($eventsByDate[$date])) {
                $jenis = $eventsByDate[$date];
                if (isset($stats[$jenis])) {
                    $stats[$jenis]++;
                }
            } else {
                if (!$isSunday) {
                    $stats['hari_efektif']++;
                }
            }
        }

        return $stats;
    }
}
