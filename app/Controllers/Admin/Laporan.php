<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JurnalModel;
use App\Models\RombelModel; // Diubah dari KelasModel
use App\Models\MataPelajaranModel;
use App\Models\SettingModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Laporan extends BaseController
{
    protected $jurnalModel;
    protected $rombelModel; // Diubah dari kelasModel
    protected $mapelModel;
    protected $settingModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        // Inisialisasi model yang digunakan dalam laporan
        $this->jurnalModel = new JurnalModel();
        $this->rombelModel = new RombelModel(); // Menggantikan KelasModel dengan RombelModel
        $this->mapelModel = new MataPelajaranModel();
        $this->settingModel = new SettingModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect(); // Koneksi database manual untuk query kompleks
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Get data for "Jurnal per Guru" chart
        $jurnalPerGuru = $this->db->table('jurnal_new j')
            ->select('u.nama as guru_name, COUNT(j.id) as jurnal_count')
            ->join('users u', 'j.user_id = u.id')
            ->groupBy('j.user_id')
            ->orderBy('jurnal_count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Get data for "Jurnal per Mapel" chart
        $jurnalPerMapel = $this->db->table('jurnal_new j')
            ->select('m.nama_mapel as mapel_name, COUNT(j.id) as jurnal_count')
            ->join('mata_pelajaran m', 'j.mapel_id = m.id')
            ->groupBy('j.mapel_id')
            ->orderBy('jurnal_count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Ambil data statistik untuk laporan
        $data = [
            'title' => 'Laporan & Statistik',
            'active' => 'laporan',
            'stats' => [
                'total_guru' => $this->userModel->where('role', 'guru')->countAllResults(),
                'total_jurnal' => $this->jurnalModel->countAllResults(),
                'total_kelas' => $this->rombelModel->countAllResults(),
                'total_mapel' => $this->mapelModel->countAllResults(),
            ],
            'recent_journals' => $this->jurnalModel->orderBy('created_at', 'DESC')->limit(10)->findAll(),
            'active_teachers' => $this->userModel->where('role', 'guru')->where('is_active', 1)->countAllResults(),
            'jurnal_per_guru' => $jurnalPerGuru,
            'jurnal_per_mapel' => $jurnalPerMapel
        ];

        return view('admin/laporan/index', $data);
    }

    public function guru()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data guru
        $teachers = $this->userModel->where('role', 'guru')->findAll();

        // Hitung jumlah jurnal untuk setiap guru
        foreach ($teachers as &$teacher) {
            $teacher['total_jurnal'] = $this->db->table('jurnal_new')
                ->where('user_id', $teacher['id'])
                ->where('materi !=', 'Absensi Kelas')
                ->countAllResults();
        }

        $data = [
            'title' => 'Laporan Guru',
            'active' => 'laporan',
            'teachers' => $teachers
        ];

        return view('admin/laporan/guru', $data);
    }

    public function jurnal()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data jurnal
        $journals = $this->jurnalModel->where('materi !=', 'Absensi Kelas')->orderBy('created_at', 'DESC')->findAll();

        // Tambahkan informasi tambahan
        foreach ($journals as &$journal) {
            $rombelId = $journal['rombel_id'] ?? $journal['kelas_id'] ?? null;
            $rombel = $this->rombelModel->find($rombelId); // Diubah dari kelas_id
            $mapel = $this->mapelModel->find($journal['mapel_id']);
            
            $journal['kelas_nama'] = $rombel ? ($rombel['nama_rombel'] ?? $rombel['nama_kelas'] ?? 'Nama Kelas Tidak Ada') : 'Kelas tidak ditemukan'; // Diubah dari nama_kelas
            $journal['mapel_nama'] = $mapel ? $mapel['nama_mapel'] : 'Mata pelajaran tidak ditemukan';
            
            // Ambil data guru
            $teacher = $this->userModel->find($journal['user_id']);
            $journal['guru_nama'] = $teacher ? $teacher['nama'] : 'Guru tidak ditemukan';
        }

        $data = [
            'title' => 'Laporan Jurnal',
            'active' => 'laporan',
            'journals' => $journals
        ];

        return view('admin/laporan/jurnal', $data);
    }

    public function statistik()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

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

        // Ambil data statistik
        $data = [
            'title' => 'Statistik',
            'active' => 'laporan',
            'stats' => [
                'total_guru' => $this->userModel->where('role', 'guru')->countAllResults(),
                'jurnal_published' => $this->jurnalModel->where('status', 'published')->where('materi !=', 'Absensi Kelas')->countAllResults(),
                'jurnal_draft' => $this->jurnalModel->where('status', 'draft')->where('materi !=', 'Absensi Kelas')->countAllResults(),
                'total_jurnal' => $this->jurnalModel->where('materi !=', 'Absensi Kelas')->countAllResults()
            ],
            'jurnal_per_bulan' => $jurnalPerBulan
        ];

        return view('admin/laporan/statistik', $data);
    }

    public function export()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Cek apakah ada parameter type dan format
        $type = $this->request->getGet('type');
        $format = $this->request->getGet('format');
        $startMonth = $this->request->getGet('start_month');
        $endMonth = $this->request->getGet('end_month');
        $year = $this->request->getGet('year');

        if (!$type || !$format) {
            $data = [
                'title' => 'Export Laporan',
                'active' => 'laporan'
            ];
            return view('admin/laporan/export', $data);
        }

        // Export berdasarkan type
        switch ($type) {
            case 'jurnal':
                $this->exportJurnal($format, $startMonth, $endMonth, $year);
                return; // Tambahkan return untuk menghentikan eksekusi setelah export
            case 'guru':
                $this->exportGuru($format);
                return; // Tambahkan return untuk menghentikan eksekusi setelah export
            default:
                return redirect()->back()->with('error', 'Type laporan tidak valid');
        }
    }

    private function exportJurnal($format, $startMonth = null, $endMonth = null, $year = null)
    {
        // Ambil data jurnal dengan filter bulan jika ada
        $builder = $this->jurnalModel->orderBy('created_at', 'DESC');
        
        // Exclude Absensi Kelas
        $builder->where('materi !=', 'Absensi Kelas');
        
        if ($startMonth && $endMonth && $year) {
            // Filter berdasarkan rentang bulan
            $builder->where('MONTH(tanggal) >=', $startMonth);
            $builder->where('MONTH(tanggal) <=', $endMonth);
            $builder->where('YEAR(tanggal)', $year);
        }
        
        $journals = $builder->findAll();

        // Tambahkan informasi tambahan
        foreach ($journals as &$journal) {
            // Fix for undefined array key error
            $rombelId = $journal['rombel_id'] ?? $journal['kelas_id'] ?? null;
            $kelas = $this->rombelModel->find($rombelId);
            $mapel = $this->mapelModel->find($journal['mapel_id']);
            
            $journal['kelas_nama'] = $kelas ? ($kelas['nama_rombel'] ?? $kelas['nama_kelas'] ?? 'Nama Kelas Tidak Ada') : 'Kelas tidak ditemukan';
            $journal['mapel_nama'] = $mapel ? $mapel['nama_mapel'] : 'Mata pelajaran tidak ditemukan';
            
            // Ambil data guru
            $teacher = $this->userModel->find($journal['user_id']);
            $journal['guru_nama'] = $teacher ? $teacher['nama'] : 'Guru tidak ditemukan';
        }

        // Format nama bulan dalam bahasa Indonesia
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Ambil pengaturan sekolah
        $schoolSettings = $this->settingModel->getSettings();
        
        // Siapkan logo base64 jika ada
        if (!empty($schoolSettings['logo']) && file_exists(FCPATH . 'uploads/logos/' . $schoolSettings['logo'])) {
            $logoPath = FCPATH . 'uploads/logos/' . $schoolSettings['logo'];
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $schoolSettings['logo_base64'] = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }

        if ($format == 'pdf') {
            // Load library DOMPDF
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            
            // Data untuk ditampilkan di PDF
            $data = [
                'journals' => $journals,
                'title' => 'Laporan Jurnal Mengajar',
                'print_date' => date('d F Y'),
                'bulan_range' => ($startMonth && $endMonth && $year) ? $bulan[(int)$startMonth] . ' - ' . $bulan[(int)$endMonth] . ' ' . $year : 'Semua Bulan',
                'school_settings' => $schoolSettings
            ];
            
            // Render view ke HTML
            $html = view('admin/laporan/pdf/jurnal', $data);
            
            // Load HTML ke DOMPDF
            $dompdf->loadHtml($html);
            
            // Setup ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'landscape');
            
            // Render PDF
            $dompdf->render();
            
            // Output PDF ke browser - tampilkan di tab baru, bukan download langsung
            $dompdf->stream('laporan-jurnal-mengajar.pdf', [
                'Attachment' => false
            ]);
            
            // Hentikan eksekusi untuk mencegah output tambahan
            exit();
        } elseif ($format == 'excel') {
            // Membuat objek spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Menambahkan header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Tanggal');
            $sheet->setCellValue('C1', 'Guru');
            $sheet->setCellValue('D1', 'Kelas');
            $sheet->setCellValue('E1', 'Mata Pelajaran');
            $sheet->setCellValue('F1', 'Materi');
            $sheet->setCellValue('G1', 'Jam Ke');
            $sheet->setCellValue('H1', 'Jumlah Jam');
            $sheet->setCellValue('I1', 'Jumlah Peserta');
            $sheet->setCellValue('J1', 'Status');
            
            // Menambahkan data
            $row = 2;
            $no = 1;
            foreach ($journals as $journal) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $journal['tanggal']);
                $sheet->setCellValue('C' . $row, $journal['guru_nama']);
                $sheet->setCellValue('D' . $row, $journal['kelas_nama'] ?? 'Kelas tidak ditemukan');
                $sheet->setCellValue('E' . $row, $journal['mapel_nama']);
                $sheet->setCellValue('F' . $row, $journal['materi']);
                $sheet->setCellValue('G' . $row, $journal['jam_ke']);
                $sheet->setCellValue('H' . $row, $journal['jumlah_jam']);
                $sheet->setCellValue('I' . $row, $journal['jumlah_peserta']);
                $sheet->setCellValue('J' . $row, ucfirst($journal['status']));
                $row++;
            }
            
            // Menyiapkan file untuk diunduh
            $filename = 'laporan-jurnal-' . date('Y-m-d-His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            
            // Hentikan eksekusi untuk mencegah output tambahan
            exit();
        }
    }

    private function exportGuru($format)
    {
        // Ambil data guru
        $teachers = $this->userModel->where('role', 'guru')->findAll();

        // Hitung jumlah jurnal untuk setiap guru
        foreach ($teachers as &$teacher) {
            $teacher['total_jurnal'] = $this->db->table('jurnal_new')
                ->where('user_id', $teacher['id'])
                ->where('materi !=', 'Absensi Kelas')
                ->countAllResults();
        }

        // Ambil pengaturan sekolah
        $schoolSettings = $this->settingModel->getSettings();
        
        // Siapkan logo base64 jika ada
        if (!empty($schoolSettings['logo']) && file_exists(FCPATH . 'uploads/logos/' . $schoolSettings['logo'])) {
            $logoPath = FCPATH . 'uploads/logos/' . $schoolSettings['logo'];
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $schoolSettings['logo_base64'] = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        
        if ($format == 'pdf') {
            // Load library DOMPDF
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new \Dompdf\Dompdf($options);
            
            // Data untuk ditampilkan di PDF
            $data = [
                'teachers' => $teachers,
                'title' => 'Laporan Data Guru',
                'print_date' => date('d F Y'),
                'school_settings' => $schoolSettings
            ];
            
            // Render view ke HTML
            $html = view('admin/laporan/pdf/guru', $data);
            
            // Load HTML ke DOMPDF
            $dompdf->loadHtml($html);
            
            // Setup ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'portrait');
            
            // Render PDF
            $dompdf->render();
            
            // Output PDF ke browser - tampilkan di tab baru, bukan download langsung
            $dompdf->stream('laporan-data-guru.pdf', [
                'Attachment' => false
            ]);
            
            // Hentikan eksekusi untuk mencegah output tambahan
            exit();
        } elseif ($format == 'excel') {
            // Membuat objek spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Menambahkan header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'NIP');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'Materi');
            $sheet->setCellValue('F1', 'Status');
            $sheet->setCellValue('G1', 'Total Jurnal');
            
            // Menambahkan data
            $row = 2;
            $no = 1;
            foreach ($teachers as $teacher) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $teacher['nip']);
                $sheet->setCellValue('C' . $row, $teacher['nama']);
                $sheet->setCellValue('D' . $row, $teacher['email']);
                $sheet->setCellValue('E' . $row, $teacher['mata_pelajaran']);
                $sheet->setCellValue('F' . $row, $teacher['is_active'] ? 'Aktif' : 'Non-aktif');
                $sheet->setCellValue('G' . $row, $teacher['total_jurnal'] ?? 0);
                $row++;
            }
            
            // Menyiapkan file untuk diunduh
            $filename = 'laporan-data-guru-' . date('Y-m-d-His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        } elseif ($format == 'csv') {
            // Membuat objek spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Menambahkan header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'NIP');
            $sheet->setCellValue('C1', 'Nama');
            $sheet->setCellValue('D1', 'Email');
            $sheet->setCellValue('E1', 'Materi');
            $sheet->setCellValue('F1', 'Status');
            $sheet->setCellValue('G1', 'Total Jurnal');
            
            // Menambahkan data
            $row = 2;
            $no = 1;
            foreach ($teachers as $teacher) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $teacher['nip']);
                $sheet->setCellValue('C' . $row, $teacher['nama']);
                $sheet->setCellValue('D' . $row, $teacher['email']);
                $sheet->setCellValue('E' . $row, $teacher['mata_pelajaran']);
                $sheet->setCellValue('F' . $row, $teacher['is_active'] ? 'Aktif' : 'Non-aktif');
                $sheet->setCellValue('G' . $row, $teacher['total_jurnal'] ?? 0);
                $row++;
            }
            
            // Menyiapkan file untuk diunduh
            $filename = 'laporan-data-guru-' . date('Y-m-d-His') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer = new Csv($spreadsheet);
            $writer->save('php://output');
            exit();
        }
    }
    
    // Method untuk export PDF laporan guru
    public function exportGuruToPdf()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        return $this->exportGuru('pdf');
    }
    
    // Method untuk export Excel laporan guru
    public function exportGuruToExcel()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        return $this->exportGuru('excel');
    }
}