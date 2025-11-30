<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\JurnalModel;
use App\Models\RombelModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Cek jika user sudah login dan memiliki role guru
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }
        
        // Inisialisasi model
        $jurnalModel = new JurnalModel();
        $rombelModel = new RombelModel();
        
        // Ambil ID user dan nama yang sedang login
        $userId = session()->get('user_id');
        $userName = session()->get('nama');
        
        // Cek apakah user adalah wali rombel
        $isWaliKelas = $rombelModel->where('wali_kelas', $userId)->first();
        
        // Ambil bulan dan tahun dari request atau default ke saat ini
        $selectedMonth = $this->request->getGet('month') ? (int)$this->request->getGet('month') : (int)date('n');
        $selectedYear = $this->request->getGet('year') ? (int)$this->request->getGet('year') : (int)date('Y');
        
        // Validasi
        $selectedMonth = max(1, min(12, $selectedMonth));
        $selectedYear = max(2000, min(2100, $selectedYear));

        // Hitung statistik jurnal (Tetap menggunakan bulan saat ini untuk stats card)
        $totalJurnal = $jurnalModel->where('user_id', $userId)->countAllResults();
        $jurnalBulanIni = $jurnalModel
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', date('m'))
            ->where('YEAR(tanggal)', date('Y'))
            ->countAllResults();
            
        // Hitung jurnal minggu ini (7 hari terakhir)
        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');
        $jurnalMingguIni = $jurnalModel
            ->where('user_id', $userId)
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->countAllResults();
            
        $jurnalPublished = $jurnalModel
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->countAllResults();
        $jurnalDraft = $jurnalModel
            ->where('user_id', $userId)
            ->where('status', 'draft')
            ->countAllResults();
        
        // Hitung total kelas dan mapel yang diajar (memperbaiki error only_full_group_by)
        // Menggunakan subquery untuk menghindari error only_full_group_by
        $totalKelasResult = $jurnalModel->query("
            SELECT COUNT(DISTINCT rombel_id) as total 
            FROM jurnal_new 
            WHERE user_id = ?
        ", [$userId])->getRow();
        $totalKelas = $totalKelasResult->total ?? 0;
        
        $totalMapelResult = $jurnalModel->query("
            SELECT COUNT(DISTINCT mapel_id) as total 
            FROM jurnal_new 
            WHERE user_id = ?
        ", [$userId])->getRow();
        $totalMapel = $totalMapelResult->total ?? 0;
        
        // Ambil daftar kelas yang diajar
        $kelasDiajar = $jurnalModel
            ->select('rombel.id as id, rombel.nama_rombel as nama_rombel, rombel.kode_rombel as kode_rombel')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->where('jurnal_new.user_id', $userId)
            ->groupBy('rombel.id, rombel.nama_rombel, rombel.kode_rombel')
            ->orderBy('rombel.nama_rombel', 'ASC')
            ->findAll();
            
        // Pastikan hasilnya adalah array kosong jika tidak ada data
        if ($kelasDiajar === null) {
            $kelasDiajar = [];
        }
        
        // Ambil daftar mata pelajaran yang diajar
        $mapelDiajar = $jurnalModel
            ->select('mata_pelajaran.id as id, mata_pelajaran.nama_mapel as nama_mapel, mata_pelajaran.kode_mapel as kode_mapel')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->where('jurnal_new.user_id', $userId)
            ->groupBy('mata_pelajaran.id, mata_pelajaran.nama_mapel, mata_pelajaran.kode_mapel')
            ->orderBy('mata_pelajaran.nama_mapel', 'ASC')
            ->findAll();
            
        // Pastikan hasilnya adalah array kosong jika tidak ada data
        if ($mapelDiajar === null) {
            $mapelDiajar = [];
        }
        
        // Ambil data aktivitas mengajar per minggu dalam sebulan (Gunakan Selected Month)
        $weeklyActivities = [];
        
        // Dapatkan jumlah minggu dalam bulan yang dipilih
        $daysInSelectedMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
        $weeksInMonth = ceil($daysInSelectedMonth / 7);
        
        // Hitung jumlah jurnal per minggu (Optimized)
        // Ambil semua tanggal jurnal bulan ini dalam satu query
        $monthlyJournals = $jurnalModel
            ->select('tanggal')
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', $selectedMonth)
            ->where('YEAR(tanggal)', $selectedYear)
            ->findAll();

        // Inisialisasi array minggu
        for ($week = 1; $week <= $weeksInMonth; $week++) {
            $startDay = ($week - 1) * 7 + 1;
            $endDay = min($week * 7, $daysInSelectedMonth);
            
            $weeklyActivities[$week] = [
                'week' => $week,
                'start_day' => $startDay,
                'end_day' => $endDay,
                'count' => 0
            ];
        }

        // Agregasi data di PHP
        foreach ($monthlyJournals as $jurnal) {
            $day = (int)date('j', strtotime($jurnal['tanggal']));
            // Tentukan minggu ke berapa (1-7 = week 1, 8-14 = week 2, dst)
            $weekNum = ceil($day / 7);
            
            if (isset($weeklyActivities[$weekNum])) {
                $weeklyActivities[$weekNum]['count']++;
            }
        }
        
        // Reset keys agar index mulai dari 0 untuk JSON/Chart.js
        $weeklyActivities = array_values($weeklyActivities);

        // Ambil data jurnal harian untuk kalender (Gunakan Selected Month)
        $jurnalHarian = $jurnalModel
            ->select('DATE(tanggal) as date, COUNT(*) as count')
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', $selectedMonth)
            ->where('YEAR(tanggal)', $selectedYear)
            ->groupBy('DATE(tanggal)')
            ->findAll();
            
        $jurnalByDate = [];
        foreach ($jurnalHarian as $row) {
            $jurnalByDate[$row['date']] = $row['count'];
        }

        // Ambil Data Hari Libur
        $hariLiburService = new \App\Services\HariLiburService();
        $startMonthDate = date('Y-m-d', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
        $endMonthDate = date('Y-m-d', mktime(0, 0, 0, $selectedMonth, $daysInSelectedMonth, $selectedYear));
        $holidays = $hariLiburService->getDetailHariLibur($startMonthDate, $endMonthDate);
        
        $holidaysByDate = [];
        foreach ($holidays as $h) {
            // Asumsi API mengembalikan 'date' dan 'holiday_name' atau 'summary'
            // Sesuaikan dengan response API yang sebenarnya. 
            // Jika HariLiburService mengembalikan array dari API langsung:
            $holidaysByDate[$h['date']] = $h['holiday_name'] ?? $h['summary'] ?? 'Libur';
        }

        // Hitung Navigasi Bulan
        $prevMonth = $selectedMonth - 1;
        $prevYear = $selectedYear;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }

        $nextMonth = $selectedMonth + 1;
        $nextYear = $selectedYear;
        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }
        
        // Siapkan data untuk dikirim ke view
        $data = [
            'title' => 'Dashboard Guru',
            'userName' => $userName,
            'isWaliKelas' => $isWaliKelas,
            'stats' => [
                'total_jurnal' => $totalJurnal,
                'jurnal_bulan_ini' => $jurnalBulanIni,
                'jurnal_minggu_ini' => $jurnalMingguIni,
                'jurnal_published' => $jurnalPublished,
                'jurnal_draft' => $jurnalDraft,
            ],
            'total_kelas' => $totalKelas,
            'total_mapel' => $totalMapel,
            'kelas_diajar' => $kelasDiajar,
            'mapel_diajar' => $mapelDiajar,
            'weekly_activities' => $weeklyActivities,
            'jurnal_by_date' => $jurnalByDate,
            'current_month' => $selectedMonth,
            'current_year' => $selectedYear,
            'prev_month' => $prevMonth,
            'prev_year' => $prevYear,
            'next_month' => $nextMonth,
            'next_year' => $nextYear,
            'holidays' => $holidaysByDate
        ];
        
        if ($this->isMobile()) {
            return view('mobile/guru/dashboard', $data);
        }

        return view('guru/dashboard', $data);
    }
}