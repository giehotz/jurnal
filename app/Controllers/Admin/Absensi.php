<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AbsensiService;
use App\Models\RombelModel;
use App\Models\AbsensiModel;
use App\Models\JurnalModel;
use App\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Absensi extends BaseController
{
    protected $absensiService;
    protected $rombelModel;
    protected $absensiModel;
    protected $jurnalModel;
    protected $siswaModel;
    protected $db;

    public function __construct()
    {
        $this->absensiService = new AbsensiService();
        $this->rombelModel = new RombelModel();
        $this->absensiModel = new AbsensiModel();
        $this->siswaModel = new SiswaModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan daftar absensi dengan filter
     */
    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil parameter filter dari request
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01'); // Awal bulan ini
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t'); // Akhir bulan ini
        $rombelId = $this->request->getGet('rombel_id');

        // Mendapatkan data rekap absensi per kelas
        $rekapKelas = $this->absensiService->getRekapKelas($startDate, $endDate, $rombelId);

        // Mendapatkan data rekap harian (grafik)
        $rekapHarian = $this->absensiService->getRekapHarian($startDate, $endDate, $rombelId);

        // Data untuk dropdown rombel
        $rombelList = $this->rombelModel->orderBy('kode_rombel', 'ASC')->findAll();

        // Data untuk dikirim ke view
        $data = [
            'title' => 'Data Absensi',
            'active' => 'absensi',
            'rekapKelas' => $rekapKelas,
            'rekapHarian' => $rekapHarian,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'rombelId' => $rombelId,
            'rombel' => $rombelList
        ];

        return view('admin/absensi/index', $data);
    }
    
    /**
     * Menampilkan form export data absensi
     */
    public function export()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        // Data untuk dropdown rombel
        $rombelList = $this->rombelModel->orderBy('kode_rombel', 'ASC')->findAll();
        
        $data = [
            'title' => 'Export Data Absensi',
            'active' => 'absensi',
            'rombel' => $rombelList
        ];
        
        return view('admin/absensi/export', $data);
    }
    
    /**
     * Memproses export data absensi
     */
    public function process_export()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
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
        $absensiData = $this->absensiService->getDataExport($startDate, $endDate, $rombelId);
        
        if (empty($absensiData)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada data absensi pada periode tersebut');
        }
        
        // Proses export sesuai tipe
        if ($exportType == 'excel') {
            return $this->exportToExcel($absensiData, $startDate, $endDate);
        } else {
            return $this->exportToPDF($absensiData, $startDate, $endDate);
        }
    }
    
    /**
     * Export data absensi ke Excel
     */
    /**
     * Export data absensi ke Excel
     */
    private function exportToExcel($data, $startDate, $endDate)
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
        } else if ($isSingleMonth) {
            $periodeText = date('F Y', strtotime($startDate)); // 1 bulan
        } else {
            $periodeText = date('M Y', strtotime($startDate)) . ' - ' . date('M Y', strtotime($endDate)); // Beberapa bulan
        }

        // Group data by rombel first
        $dataByRombel = [];
        foreach ($data as $row) {
            $rombelKey = $row['kode_rombel'] . ' - ' . $row['nama_rombel'];
            $dataByRombel[$rombelKey][] = $row;
        }

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet

        if (empty($dataByRombel)) {
             $sheet = $spreadsheet->createSheet();
             $sheet->setTitle('No Data');
             $sheet->setCellValue('A1', 'Tidak ada data siswa aktif pada periode ini.');
             $sheet->getColumnDimension('A')->setAutoSize(true);
        } else {
            foreach ($dataByRombel as $rombelName => $rombelData) {
                // Create new sheet
                $sheet = $spreadsheet->createSheet();
                $safeTitle = substr(str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $rombelName), 0, 30);
                $sheet->setTitle($safeTitle);

                // Process data for this rombel
                $dataSiswa = [];
                
                foreach ($rombelData as $row) {
                    $siswaId = $row['siswa_id'] ?? $row['nis']; 
                    
                    if (!isset($dataSiswa[$siswaId])) {
                        $dataSiswa[$siswaId] = [
                            'nama' => $row['nama_siswa'],
                            'nisn' => $row['nisn'] ?? '',
                            'nis' => $row['nis'],
                            'rombel' => $row['nama_rombel'],
                            'kode_rombel' => $row['kode_rombel'],
                            'bulanan' => [],
                            'total' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0]
                        ];
                    }
                    
                    // Skip aggregation if no attendance data (status is null)
                    if (empty($row['status'])) {
                        continue;
                    }

                    // Data per bulan
                    $bulan = date('n', strtotime($row['tanggal']));
                    $tahun = date('Y', strtotime($row['tanggal']));
                    $bulanTahun = $bulan . '-' . $tahun;
                    
                    // Inisialisasi data bulan jika belum ada
                    if (!isset($dataSiswa[$siswaId]['bulanan'][$bulanTahun])) {
                        $dataSiswa[$siswaId]['bulanan'][$bulanTahun] = [
                            'hadir' => 0,
                            'izin' => 0,
                            'sakit' => 0,
                            'alfa' => 0
                        ];
                    }
                    
                    // Akumulasi data berdasarkan status
                    $status = strtolower($row['status']);
                    if (isset($dataSiswa[$siswaId]['bulanan'][$bulanTahun][$status])) {
                        $dataSiswa[$siswaId]['bulanan'][$bulanTahun][$status] += 1;
                    }
                    
                    // Akumulasi total
                    if (isset($dataSiswa[$siswaId]['total'][$status])) {
                        $dataSiswa[$siswaId]['total'][$status] += 1;
                    }
                }

                // Populate Sheet
                $this->populateSheet($sheet, $dataSiswa, $periodeText, $rombelName, $isSingleMonth);
            }
        }
        
        // Set active sheet
        $spreadsheet->setActiveSheetIndex(0);

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

    private function populateSheet($sheet, $dataSiswa, $periodeText, $rombelName, $isSingleMonth)
    {
        // Set header columns
        if ($isSingleMonth) {
            $header = ['NO', 'Nama', 'NISN', 'NIS', 'Hadir', 'Izin', 'Sakit', 'Alfa'];
            $lastCol = 'H';
        } else {
            $header = ['NO', 'Nama', 'NISN', 'NIS', 'Bulan', 'Hadir', 'Izin', 'Sakit', 'Alfa'];
            $lastCol = 'I';
        }

        // Judul laporan
        $judul = "ABSENSI SISWA : " . $rombelName;
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
        
        $sheet->setCellValue('A3', $dicetakPada);
        $sheet->mergeCells('A3:' . $lastCol . '3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Header tabel
        $rowIndex = 5;
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
        $sheet->getStyle('A5:' . $lastCol . '5')->applyFromArray($headerStyle);
        
        // Isi data
        $rowIndex = 6;
        $rowNumber = 1;

        foreach ($dataSiswa as $siswa) {
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
                $rowIndex++;
            } else {
                // Tampilkan multiple baris per siswa (per bulan)
                // Jika tidak ada data bulanan (siswa tidak pernah absen), tampilkan baris kosong/total 0
                if (empty($siswa['bulanan'])) {
                     $sheet->setCellValue('A' . $rowIndex, $rowNumber);
                     $sheet->setCellValue('B' . $rowIndex, $siswa['nama']);
                     $sheet->setCellValue('C' . $rowIndex, $siswa['nisn']);
                     $sheet->setCellValue('D' . $rowIndex, $siswa['nis']);
                     $sheet->setCellValue('E' . $rowIndex, '-');
                     $sheet->setCellValue('F' . $rowIndex, 0);
                     $sheet->setCellValue('G' . $rowIndex, 0);
                     $sheet->setCellValue('H' . $rowIndex, 0);
                     $sheet->setCellValue('I' . $rowIndex, 0);
                     $rowIndex++;
                     $rowNumber++;
                } else {
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
                        $sheet->getStyle('E' . $rowIndex . ':I' . $rowIndex)->getFont()->setBold(true);
                        $rowIndex++;
                    }
                    $rowNumber++;
                }
            }
        }
        
        // Auto size column
        foreach (range('A', $lastCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Border untuk data
        $sheet->getStyle('A5:' . $lastCol . ($rowIndex - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);
        
        // Center align columns for numbers
        if ($isSingleMonth) {
             $sheet->getStyle('E6:H' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('A6:A' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
             $sheet->getStyle('F6:I' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('A6:A' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
    }
    
    /**
     * Export data absensi ke PDF (placeholder)
     */
    private function exportToPDF($data, $startDate, $endDate)
    {
        // Untuk saat ini kita akan kembali ke form export dengan pesan bahwa PDF belum tersedia
        return redirect()->back()->withInput()->with('error', 'Export ke PDF belum tersedia. Silakan pilih export ke Excel.');
    }

    /**
     * Menampilkan form untuk input absensi harian
     */
    public function create()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data rombel yang aktif
        $rombel = $this->absensiService->getRombelList();
        
        // Ambil data mata pelajaran
        $mapelModel = new \App\Models\MataPelajaranModel();
        $mapel = $mapelModel->orderBy('nama_mapel', 'ASC')->findAll();

        $data = [
            'title' => 'Input Absensi Harian',
            'active' => 'absensi',
            'rombel' => $rombel,
            'mapel' => $mapel
        ];

        return view('admin/absensi/create', $data);
    }

    /**
     * Menyimpan data absensi
     */
    public function store()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $rules = [
            'tanggal' => 'required|valid_date',
            'rombel_id' => 'required|is_not_unique[rombel.id]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid');
        }

        $tanggal = $this->request->getPost('tanggal');
        $rombelId = $this->request->getPost('rombel_id');
        $absensiData = $this->request->getPost('absensi');

        try {
            // Simpan data absensi menggunakan service
            $this->absensiService->simpanAbsensiHarian($tanggal, $rombelId, $absensiData, session()->get('id'));
            return redirect()->to('/admin/absensi')->with('success', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback transaksi
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data absensi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit absensi
     */
    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data absensi
        $absensi = $this->absensiModel->find($id);
        if (!$absensi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data absensi tidak ditemukan');
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
            'siswa' => $siswa
        ];

        return view('admin/absensi/edit', $data);
    }

    /**
     * Mengupdate data absensi
     */
    public function update($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $statusCode = $this->request->getPost('status');
        
        // Map status code to full word if necessary
        $statusMap = [
            'H' => 'hadir',
            'I' => 'izin',
            'S' => 'sakit',
            'A' => 'alfa'
        ];
        
        // Check if input is code (H,I,S,A) or already full word
        $status = isset($statusMap[$statusCode]) ? $statusMap[$statusCode] : $statusCode;

        $data = [
            'status' => $status,
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->absensiModel->update($id, $data)) {
            return redirect()->to('/admin/absensi')->with('success', 'Data absensi berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data absensi.');
        }
    }

    /**
     * Hapus data absensi
     */
    public function delete($id = null)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Cek apakah absensi ditemukan
        $absensi = $this->absensiModel->find($id);
        if (!$absensi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data absensi dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Cek batas waktu hapus (max 7 hari ke belakang)
        if (strtotime($absensi['tanggal']) < strtotime('-7 days')) {
            return redirect()->to('/admin/absensi')->with('error', 'Batas waktu hapus absensi telah habis (max 7 hari ke belakang).');
        }

        if ($this->absensiModel->delete($id)) {
            return redirect()->to('/admin/absensi')->with('success', 'Data absensi berhasil dihapus.');
        } else {
            return redirect()->to('/admin/absensi')->with('error', 'Gagal menghapus data absensi.');
        }
    }
    
    /**
     * Menampilkan detail absensi per kelas
     */
    public function detail($rombelId)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
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
        $detailAbsensi = $this->getDetailAbsensiPerKelas($rombelId, $startDate, $endDate);

        $data = [
            'title' => 'Detail Absensi Kelas ' . $rombel['nama_rombel'],
            'active' => 'absensi',
            'rombel' => $rombel,
            'detailAbsensi' => $detailAbsensi,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('admin/absensi/detail', $data);
    }
    
    /**
     * Mendapatkan detail absensi per kelas
     */
    private function getDetailAbsensiPerKelas($rombelId, $startDate, $endDate)
    {
        $builder = $this->db->table('siswa s');
        $builder->select('
            a.id,
            a.status,
            a.keterangan,
            a.tanggal,
            s.nis,
            s.nama as nama_siswa,
            r.nama_rombel,
            r.kode_rombel
        ');
        $builder->join('rombel r', 's.rombel_id = r.id');
        $builder->join('absensi a', 'a.siswa_id = s.id', 'left');
        $builder->where('s.rombel_id', $rombelId);
        $builder->where('s.is_active', 1);
        
        if ($startDate && $endDate) {
            $builder->where('a.tanggal >=', $startDate);
            $builder->where('a.tanggal <=', $endDate);
        }
        
        $builder->orderBy('s.nama, a.tanggal');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }
    
    /**
     * Method untuk debugging - memeriksa rombel dan siswa
     */
    public function debugRombelSiswa()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Menggunakan service untuk mendapatkan data rombel dan jumlah siswa
        $rombelData = $this->absensiService->getRombelWithStudentCount();
        
        $data = [
            'title' => 'Debug Rombel dan Siswa',
            'active' => 'absensi',
            'rombel' => $rombelData
        ];

        return view('admin/absensi/debug', $data);
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

        try {
            $siswa = $this->absensiService->getSiswaByRombelId($rombelId);
            return $this->response->setJSON($siswa);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}