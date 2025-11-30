<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use App\Models\SettingModel;
use App\Models\RombelModel; // Diubah dari KelasModel
use App\Models\MataPelajaranModel;
use App\Models\UserModel;

class Monitoring extends BaseController
{
    protected $db;
    protected $settingModel;
    protected $rombelModel; // Diubah dari kelasModel
    protected $mapelModel;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->settingModel = new SettingModel();
        $this->rombelModel = new RombelModel(); // Diubah dari KelasModel
        $this->mapelModel = new MataPelajaranModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Filter Date Range
        $range = $this->request->getGet('range') ?? '7_days';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if ($range == 'today') {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        } elseif ($range == '7_days') {
            $startDate = date('Y-m-d', strtotime('-6 days'));
            $endDate = date('Y-m-d');
        } elseif ($range == 'this_month') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        } elseif ($range == 'custom' && $startDate && $endDate) {
            // Use provided dates
        } else {
            // Default to 7 days
            $startDate = date('Y-m-d', strtotime('-6 days'));
            $endDate = date('Y-m-d');
            $range = '7_days';
        }

        // 1. Summary Cards Data
        $totalJurnal = $this->db->table('jurnal_new')
            ->where('materi !=', 'Absensi Kelas')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->countAllResults();

        $totalAbsensi = $this->db->table('absensi')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->countAllResults();
            
        // Calculate Attendance Rate (Directly from absensi table)
        $attendanceStats = $this->db->table('absensi')
            ->select('COUNT(*) as total, COUNT(CASE WHEN status = "hadir" THEN 1 END) as hadir')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->get()->getRowArray();
            
        $avgAttendance = ($attendanceStats['total'] > 0) 
            ? round(($attendanceStats['hadir'] / $attendanceStats['total']) * 100, 1) 
            : 0;

        // Active Classes (Classes with at least one jurnal or absensi in range)
        $activeClassesJurnal = $this->db->table('jurnal_new')
            ->select('rombel_id')
            ->where('materi !=', 'Absensi Kelas')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->groupBy('rombel_id')
            ->get()->getResultArray();
            
        $activeClassesAbsensi = $this->db->table('absensi')
            ->select('rombel_id')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->groupBy('rombel_id')
            ->get()->getResultArray();
            
        $activeClassIds = array_unique(array_merge(
            array_column($activeClassesJurnal, 'rombel_id'),
            array_column($activeClassesAbsensi, 'rombel_id')
        ));
        $totalActiveClasses = count($activeClassIds);

        // 2. Chart Data: Daily Activity
        $dailyActivity = [];
        $currentDate = $startDate;
        while (strtotime($currentDate) <= strtotime($endDate)) {
            $jurnalCount = $this->db->table('jurnal_new')
                ->where('materi !=', 'Absensi Kelas')
                ->where('DATE(created_at)', $currentDate)
                ->countAllResults();
                
            $absensiCount = $this->db->table('absensi')
                ->select('COUNT(DISTINCT CONCAT(rombel_id, "-", tanggal)) as count') // Count unique class attendance submissions
                ->where('tanggal', $currentDate)
                ->get()->getRow()->count;
                
            $dailyActivity[] = [
                'date' => $currentDate,
                'jurnal' => $jurnalCount,
                'absensi' => $absensiCount
            ];
            
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // 3. Table Data: Rekap Kehadiran per Kelas (Direct from absensi)
        $rekapKehadiranRaw = $this->db->table('absensi a')
            ->select('a.rombel_id, r.nama_rombel, u.nama as wali_kelas')
            ->select('COUNT(CASE WHEN a.status = "hadir" THEN 1 END) as total_hadir')
            ->select('COUNT(CASE WHEN a.status = "sakit" THEN 1 END) as total_sakit')
            ->select('COUNT(CASE WHEN a.status = "izin" THEN 1 END) as total_izin')
            ->select('COUNT(CASE WHEN a.status = "alfa" THEN 1 END) as total_alfa')
            ->select('COUNT(*) as total_entries')
            ->join('rombel r', 'a.rombel_id = r.id')
            ->join('users u', 'r.wali_kelas = u.id', 'left')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate)
            ->groupBy('a.rombel_id')
            ->get()->getResultArray();

        $rekapKehadiran = [];
        foreach ($rekapKehadiranRaw as $row) {
            $row['avg_persentase'] = ($row['total_entries'] > 0) ? ($row['total_hadir'] / $row['total_entries']) * 100 : 0;
            $rekapKehadiran[] = $row;
        }
        
        // Sort by percentage DESC
        usort($rekapKehadiran, function($a, $b) {
            return $b['avg_persentase'] <=> $a['avg_persentase'];
        });

        // --- PHASE 2 ADDITIONS ---

        // 4. Chart Data: Student Attendance (Pie Chart) - Current Semester (Direct from absensi)
        $studentAttendance = $this->db->table('absensi')
            ->select('COUNT(CASE WHEN status = "hadir" THEN 1 END) as total_hadir')
            ->select('COUNT(CASE WHEN status = "sakit" THEN 1 END) as total_sakit')
            ->select('COUNT(CASE WHEN status = "izin" THEN 1 END) as total_izin')
            ->select('COUNT(CASE WHEN status = "alfa" THEN 1 END) as total_alfa')
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->get()->getRowArray();

        // 5. Chart Data: Monthly Trend (Area Chart) - Last 6 Months
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-$i months"));
            $monthEnd = date('Y-m-t', strtotime("-$i months"));
            $monthLabel = date('M Y', strtotime($monthStart));

            $jurnalMonth = $this->db->table('jurnal_new')
                ->where('materi !=', 'Absensi Kelas')
                ->where('DATE(created_at) >=', $monthStart)
                ->where('DATE(created_at) <=', $monthEnd)
                ->countAllResults();

            $absensiMonth = $this->db->table('absensi')
                ->where('tanggal >=', $monthStart)
                ->where('tanggal <=', $monthEnd)
                ->countAllResults();

            $monthlyTrend[] = [
                'month' => $monthLabel,
                'jurnal' => $jurnalMonth,
                'absensi' => $absensiMonth
            ];
        }

        // 6. Table Data: Rekap Jurnal Harian
        $rekapJurnalHarian = $this->db->table('jurnal_new')
            ->select('DATE(created_at) as tanggal, COUNT(*) as total_jurnal, COUNT(DISTINCT user_id) as total_guru')
            ->where('materi !=', 'Absensi Kelas')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->groupBy('DATE(created_at)')
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        // 7. Table Data: Monitoring Guru Aktif
        $guruAktif = $this->db->table('users u')
            ->select('u.nama, u.mata_pelajaran, COUNT(DISTINCT j.id) as total_jurnal, COUNT(DISTINCT a.id) as total_absensi')
            ->join('jurnal_new j', "u.id = j.user_id AND j.materi != 'Absensi Kelas' AND DATE(j.created_at) >= '$startDate' AND DATE(j.created_at) <= '$endDate'", 'left')
            ->join('absensi a', "u.id = a.guru_id AND a.tanggal >= '$startDate' AND a.tanggal <= '$endDate'", 'left')
            ->where('u.role', 'guru')
            ->where('u.is_active', 1)
            ->groupBy('u.id')
            ->orderBy('total_jurnal', 'DESC')
            ->limit(10) // Top 10 active teachers
            ->get()->getResultArray();

        $data = [
            'title' => 'Dashboard Monitoring',
            'active' => 'monitoring',
            'filter' => [
                'range' => $range,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'cards' => [
                'total_jurnal' => $totalJurnal,
                'total_absensi' => $totalAbsensi,
                'avg_attendance' => $avgAttendance,
                'active_classes' => $totalActiveClasses
            ],
            'daily_activity' => $dailyActivity,
            'rekap_kehadiran' => $rekapKehadiran,
            'student_attendance' => $studentAttendance,
            'monthly_trend' => $monthlyTrend,
            'rekap_jurnal_harian' => $rekapJurnalHarian,
            'guru_aktif' => $guruAktif
        ];
        
        return view('admin/monitoring/index', $data);
    }
    
    public function exportToPdf()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        // Fetch actual data from database with filters
        $builder = $this->db->table('jurnal_new j');
        $builder->select('j.id, u.nama as guru_nama, j.tanggal, r.nama_rombel as kelas_nama, r.kode_rombel as kode_kelas, m.nama_mapel as mapel_nama, j.jam_ke, j.materi, j.jumlah_jam, j.jumlah_peserta, j.status');
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('rombel r', 'j.rombel_id = r.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        
        // Apply filters if any
        if ($this->request->getGet('guru_id')) {
            $builder->where('j.user_id', $this->request->getGet('guru_id'));
        }
        
        if ($this->request->getGet('tanggal')) {
            $builder->where('j.tanggal', $this->request->getGet('tanggal'));
        }
        
        if ($this->request->getGet('kelas_id')) {
            $builder->where('j.rombel_id', $this->request->getGet('kelas_id'));
        }

        // Exclude Absensi Kelas
        $builder->where('j.materi !=', 'Absensi Kelas');
        $builder->where('j.mapel_id !=', 18);
        
        $builder->orderBy('j.tanggal', 'DESC');
        $jurnals = $builder->get()->getResultArray();
        
        // Get settings for PDF header
        $settings = $this->settingModel->getSettings();
        
        $data = [
            'jurnals' => $jurnals,
            'settings' => $settings
        ];
        
        // Load the view and convert to PDF
        $html = view('admin/monitoring/pdf_list', $data);
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream('daftar-jurnal-mengajar-' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        
        // Hentikan eksekusi untuk mencegah output tambahan
        exit();
    }
    
    public function exportToExcel()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        // Fetch actual data from database with filters
        $builder = $this->db->table('jurnal_new j');
        $builder->select('j.id, u.nama as guru_nama, j.tanggal, r.nama_rombel as kelas_nama, m.nama_mapel as mapel_nama, j.jam_ke, j.materi, j.jumlah_jam, j.jumlah_peserta, j.status');
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('rombel r', 'j.rombel_id = r.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        
        // Apply filters if any
        if ($this->request->getGet('guru_id')) {
            $builder->where('j.user_id', $this->request->getGet('guru_id'));
        }
        
        if ($this->request->getGet('tanggal')) {
            $builder->where('j.tanggal', $this->request->getGet('tanggal'));
        }
        
        if ($this->request->getGet('kelas_id')) {
            $builder->where('j.rombel_id', $this->request->getGet('kelas_id'));
        }

        // Exclude Absensi Kelas
        $builder->where('j.materi !=', 'Absensi Kelas');
        $builder->where('j.mapel_id !=', 18);
        
        $builder->orderBy('j.tanggal', 'DESC');
        $jurnals = $builder->get()->getResultArray();
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Sistem Jurnal')
            ->setLastModifiedBy('Sistem Jurnal')
            ->setTitle('Data Jurnal Mengajar')
            ->setSubject('Data Jurnal Mengajar')
            ->setDescription('Export Data Jurnal Mengajar');
            
        // Add Header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Guru');
        $sheet->setCellValue('D1', 'Kelas');
        $sheet->setCellValue('E1', 'Mata Pelajaran');
        $sheet->setCellValue('F1', 'Jam Ke');
        $sheet->setCellValue('G1', 'Materi');
        $sheet->setCellValue('H1', 'Jumlah JP');
        $sheet->setCellValue('I1', 'Jumlah Peserta');
        $sheet->setCellValue('J1', 'Status');
        
        // Style Header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE0E0E0'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        // Add Data
        $row = 2;
        foreach ($jurnals as $journal) {
            $sheet->setCellValue('A' . $row, $journal['id']);
            $sheet->setCellValue('B' . $row, $journal['tanggal']);
            $sheet->setCellValue('C' . $row, $journal['guru_nama']);
            $sheet->setCellValue('D' . $row, $journal['kelas_nama']);
            $sheet->setCellValue('E' . $row, $journal['mapel_nama']);
            $sheet->setCellValue('F' . $row, $journal['jam_ke']);
            $sheet->setCellValue('G' . $row, $journal['materi']);
            $sheet->setCellValue('H' . $row, $journal['jumlah_jam']);
            $sheet->setCellValue('I' . $row, $journal['jumlah_peserta']);
            $sheet->setCellValue('J' . $row, ucfirst($journal['status']));
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'monitoring-jurnal-' . date('Y-m-d-His') . '.xlsx';
        
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    public function exportDetailToPdf($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        // Fetch journal detail from database with all necessary fields for the PDF
        $builder = $this->db->table('jurnal_new j');
        $builder->select('
            j.*, 
            u.nama as nama_guru, 
            u.nip, 
            r.nama_rombel, 
            r.kode_rombel, 
            m.nama_mapel
        ');
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('rombel r', 'j.rombel_id = r.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        $builder->where('j.id', $id);
        
        $jurnal = $builder->get()->getRowArray();
        
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Journal with ID $id not found");
        }
        
        $data = [
            'jurnal' => $jurnal
        ];
        
        // Load the view and convert to PDF
        $html = view('admin/monitoring/pdf', $data);
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream('jurnal-detail-' . $id . '.pdf', ['Attachment' => false]);
        
        // Hentikan eksekusi untuk mencegah output tambahan
        exit();
    }
    
    public function detail($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        // Fetch journal detail from database
        $builder = $this->db->table('jurnal_new j');
        $builder->select('j.*, u.nama as nama_guru, r.nama_rombel as nama_kelas, r.kode_rombel as kode_kelas, m.nama_mapel as nama_mapel');
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('rombel r', 'j.rombel_id = r.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        $builder->where('j.id', $id);
        
        $jurnal = $builder->get()->getRowArray();
        
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Journal with ID $id not found");
        }
        
        $data = [
            'jurnal' => $jurnal
        ];
        
        return view('admin/monitoring/detail', $data);
    }
}