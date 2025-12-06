<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\KalenderGuruViewModel;
use App\Libraries\HolidayApi;

class KalenderMengajar extends BaseController
{
    protected $guruViewModel;
    protected $holidayApi;

    public function __construct()
    {
        $this->guruViewModel = new KalenderGuruViewModel();
        $this->holidayApi = new HolidayApi();
        helper('tanggal');
    }

    public function index()
    {
        $guruId = session('user_id'); // Assuming logged in as guru
        $lembagaId = session('lembaga_id') ?? 'default';

        $tahunAjaran = $this->request->getVar('tahun_ajaran') ?? (date('m') > 6 ? date('Y') . '/' . (date('Y') + 1) : (date('Y') - 1) . '/' . date('Y'));
        $semester = $this->request->getVar('semester') ?? 1; // Default semester

        // Month Filter
        $bulan = $this->request->getVar('bulan') ?? date('m');
        $tahun = $this->request->getVar('tahun') ?? date('Y');

        // Get Database Calendar (Kaldik + Guru Agenda)
        $kalender = $this->guruViewModel->getKalenderGuru($guruId, $tahunAjaran, $semester, $lembagaId, $bulan);

        // Get API Holidays with Caching (1 day)
        $cacheKey = "holidays_{$tahun}_{$bulan}";
        $holidays = cache($cacheKey);

        if ($holidays === null) {
            $holidays = $this->holidayApi->getHolidays($tahun, $bulan);
            cache()->save($cacheKey, $holidays, 3600 * 24);
        }

        // Merge Holidays into Calendar for unified list
        $mergedKalender = $kalender;
        foreach ($holidays as $h) {
            // Check if date already exists in DB calendar
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
                    'keterangan' => $h['keterangan'] . ' (API)',
                    'warna_kode' => '#dc3545', // Red
                    // Fill dummy
                    'id' => null,
                    'mata_pelajaran_terjadwal' => null,
                ];
            }
        }

        // Sort merged calendar
        usort($mergedKalender, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        // Statistics
        $stats = $this->calculateStatistik($mergedKalender, $bulan, $tahun);

        // Prepare Calendar Widget Data
        // check if helper function exists, if not usage of it might fail.
        // Assuming helper('tanggal') loads it.
        $weeks = get_dates_by_week($bulan, $tahun);

        // Map calendar events by date for widget
        $eventsByDate = [];
        foreach ($mergedKalender as $k) {
            $date = $k['tanggal'];
            if (!isset($eventsByDate[$date])) {
                $eventsByDate[$date] = [];
            }
            $eventsByDate[$date][] = $k;
        }

        return view('guru/kalender_mengajar/index', [
            'kalender' => $mergedKalender,
            'weeks' => $weeks,
            'events_by_date' => $eventsByDate,
            'statistik' => $stats, // Changed from 'stats' to 'statistik' to match view
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester, // Added missing var
            'active' => 'kalender_mengajar',
            'title' => 'Kalender Mengajar'
        ]);
    }

    public function detailTanggal($tanggal)
    {
        $guruId = session('user_id');
        $detail = $this->guruViewModel->getByTanggal($tanggal, $guruId);

        return $this->response->setJSON($detail);
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

                // Tambahkan counter sesuai jenis
                // Note: Jika satu tanggal ada multiple events, logic ini mungkin perlu disesuaikan (misal array checking)
                // Tapi saat ini asumsi satu tanggal satu jenis utama untuk statistik
                if (isset($stats[$jenis])) {
                    $stats[$jenis]++;
                }

                // Jika event adalah 'hari_efektif' (jika masih ada manual input)
                if ($jenis == 'hari_efektif') {
                    // Do nothing special, already counted above
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
}
