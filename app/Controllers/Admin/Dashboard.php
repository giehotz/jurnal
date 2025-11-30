<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RombelModel;
use App\Models\UserModel;
use App\Models\JurnalModel;
use App\Models\MataPelajaranModel;

class Dashboard extends BaseController
{
    protected $rombelModel;
    protected $userModel;
    protected $jurnalModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->userModel = new UserModel();
        $this->jurnalModel = new JurnalModel();
        $this->mapelModel = new MataPelajaranModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Mengambil data statistik dari database
        $totalGuru = $this->userModel->where('role', 'guru')->countAllResults();
        $totalKelas = $this->rombelModel->countAllResults();
        $totalMapel = $this->mapelModel->countAllResults();
        $guruAktif = $this->userModel->where('role', 'guru')->where('is_active', 1)->countAllResults();
        
        // Menghitung jurnal bulan ini
        $builder = $this->jurnalModel->builder();
        $builder->where('MONTH(created_at)', date('m'));
        $builder->where('YEAR(created_at)', date('Y'));
        $builder->where('materi !=', 'Absensi Kelas');
        $jurnalBulanIni = $builder->countAllResults();
        
        // Menghitung jurnal hari ini
        $builder = $this->jurnalModel->builder();
        $builder->where('DATE(created_at)', date('Y-m-d'));
        $builder->where('materi !=', 'Absensi Kelas');
        $jurnalHariIni = $builder->countAllResults();
        
        // Menghitung jurnal draft
        $jurnalDraft = $this->jurnalModel->where('status', 'draft')->where('materi !=', 'Absensi Kelas')->countAllResults();
        
        // Menghitung jurnal published
        $jurnalPublished = $this->jurnalModel->where('status', 'published')->where('materi !=', 'Absensi Kelas')->countAllResults();

        // Mengambil data jurnal per bulan (12 bulan terakhir)
        $jurnalPerBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = date('m', strtotime("-$i months"));
            $tahun = date('Y', strtotime("-$i months"));
            
            $count = $this->jurnalModel
                ->where('MONTH(created_at)', $bulan)
                ->where('YEAR(created_at)', $tahun)
                ->where('materi !=', 'Absensi Kelas')
                ->countAllResults();
                
            $jurnalPerBulan[] = $count;
        }

        // Untuk debugging - menampilkan data jurnal per bulan
        // log_message('debug', 'Jurnal per bulan data: ' . json_encode($jurnalPerBulan));

        // Mengambil data jurnal per mata pelajaran
        $jurnalPerMapel = $this->jurnalModel
            ->select('mata_pelajaran.nama_mapel, COUNT(*) as jumlah')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->where('materi !=', 'Absensi Kelas')
            ->groupBy('mata_pelajaran.id, mata_pelajaran.nama_mapel')
            ->orderBy('jumlah', 'DESC')
            ->limit(5)
            ->findAll();

        // Mengambil data guru paling aktif
        $guruAktifData = $this->jurnalModel
            ->select('users.nama, COUNT(*) as jumlah_jurnal')
            ->join('users', 'users.id = jurnal_new.user_id')
            ->where('users.role', 'guru')
            ->where('materi !=', 'Absensi Kelas')
            ->groupBy('users.id, users.nama')
            ->orderBy('jumlah_jurnal', 'DESC')
            ->limit(5)
            ->findAll();

        // Mengambil statistik absensi hari ini
        $absensiModel = new \App\Models\AbsensiModel();
        $today = date('Y-m-d');
        $rekapAbsensiHariIni = $absensiModel->getRekapAbsensiPerTanggal($today, $today);
        
        $absensiStats = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alfa' => 0
        ];

        if (!empty($rekapAbsensiHariIni)) {
            $absensiStats['hadir'] = $rekapAbsensiHariIni[0]['hadir'];
            $absensiStats['sakit'] = $rekapAbsensiHariIni[0]['sakit'];
            $absensiStats['izin'] = $rekapAbsensiHariIni[0]['izin'];
            $absensiStats['alfa'] = $rekapAbsensiHariIni[0]['alfa'];
        }

        // Mengambil 10 jurnal terakhir
        $recentJurnals = $this->jurnalModel
            ->select('jurnal_new.*, users.nama as nama_guru, mata_pelajaran.nama_mapel, rombel.nama_rombel, rombel.kode_rombel')
            ->join('users', 'users.id = jurnal_new.user_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->where('materi !=', 'Absensi Kelas')
            ->orderBy('jurnal_new.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'title' => 'Dashboard',
            'page_title' => 'Dashboard',
            'active_menu' => 'dashboard',
            'total_guru' => $totalGuru,
            'total_kelas' => $totalKelas,
            'total_mapel' => $totalMapel,
            'guru_aktif' => $guruAktif,
            'jurnal_hari_ini' => $jurnalHariIni,
            'jurnal_bulan_ini' => $jurnalBulanIni,
            'jurnal_published' => $jurnalPublished,
            'jurnal_draft' => $jurnalDraft,
            'jurnal_per_bulan' => $jurnalPerBulan,
            'jurnal_per_mapel' => $jurnalPerMapel,
            'guru_aktif_data' => $guruAktifData,
            'absensi_stats' => $absensiStats,
            'recent_jurnals' => $recentJurnals
        ];

        if ($this->isMobile()) {
            return view('mobile/admin/dashboard', $data);
        }

        return view('admin/dashboard', $data);
    }

    public function classes()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        $data = [
            'title' => 'Manajemen Kelas',
            'page_title' => 'Manajemen Kelas',
            'active_menu' => 'kelas',
            'classes' => $this->rombelModel->getRombel()
        ];
        
        return view('admin/dashboard/classes', $data);
    }
}