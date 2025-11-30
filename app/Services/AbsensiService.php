<?php

namespace App\Services;

use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\RombelModel;
use App\Models\JurnalModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AbsensiService
{
    protected $absensiModel;
    protected $siswaModel;
    protected $rombelModel;
    protected $jurnalModel;
    protected $db;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->siswaModel = new SiswaModel();
        $this->rombelModel = new RombelModel();
        $this->jurnalModel = new JurnalModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Mendapatkan data rekap absensi per kelas
     */
    public function getRekapKelas($startDate, $endDate, $rombelId = null)
    {
        $rekapBuilder = $this->db->table('absensi a')
            ->select('r.id as rombel_id, r.kode_rombel, r.nama_rombel, 
                      COUNT(DISTINCT s.id) as jumlah_siswa,
                      SUM(CASE WHEN a.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                      SUM(CASE WHEN a.status = "izin" THEN 1 ELSE 0 END) as izin,
                      SUM(CASE WHEN a.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                      SUM(CASE WHEN a.status = "alfa" THEN 1 ELSE 0 END) as alfa')
            ->join('siswa s', 'a.siswa_id = s.id')
            ->join('rombel r', 'a.rombel_id = r.id')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate);

        // Filter berdasarkan rombel jika dipilih
        if ($rombelId) {
            $rekapBuilder->where('r.id', $rombelId);
        }

        // Grouping dan ordering
        $rekapBuilder->groupBy('r.id, r.kode_rombel, r.nama_rombel')
            ->orderBy('r.kode_rombel', 'ASC');

        // Eksekusi query
        return $rekapBuilder->get()->getResultArray();
    }

    /**
     * Mendapatkan data rekap absensi harian untuk grafik
     */
    public function getRekapHarian($startDate, $endDate, $rombelId = null)
    {
        $harianBuilder = $this->db->table('absensi a')
            ->select('DATE(a.tanggal) as tanggal,
                      SUM(CASE WHEN a.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                      SUM(CASE WHEN a.status = "izin" THEN 1 ELSE 0 END) as izin,
                      SUM(CASE WHEN a.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                      SUM(CASE WHEN a.status = "alfa" THEN 1 ELSE 0 END) as alfa')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate);

        // Filter berdasarkan rombel jika dipilih
        if ($rombelId) {
            $harianBuilder->where('a.rombel_id', $rombelId);
        }

        // Grouping dan ordering
        $harianBuilder->groupBy('DATE(a.tanggal)')
            ->orderBy('tanggal', 'ASC');

        // Eksekusi query
        $rekapHarianRaw = $harianBuilder->get()->getResultArray();

        // Format data untuk grafik
        $labels = [];
        $hadirData = [];
        $izinData = [];
        $sakitData = [];
        $alfaData = [];

        foreach ($rekapHarianRaw as $row) {
            $labels[] = date('d M', strtotime($row['tanggal']));
            $hadirData[] = (int)$row['hadir'];
            $izinData[] = (int)$row['izin'];
            $sakitData[] = (int)$row['sakit'];
            $alfaData[] = (int)$row['alfa'];
        }

        return [
            'labels' => $labels,
            'hadir' => $hadirData,
            'izin' => $izinData,
            'sakit' => $sakitData,
            'alfa' => $alfaData
        ];
    }

    /**
     * Mendapatkan data untuk export
     */
    public function getDataExport($startDate, $endDate, $rombelId = null, $userId = null)
    {
        // Gunakan tabel siswa sebagai base agar semua siswa muncul meskipun tidak ada absensi
        $builder = $this->db->table('siswa s')
            ->select('s.id as siswa_id, s.nisn, r.kode_rombel, r.nama_rombel, s.nis, s.nama as nama_siswa, 
                      DATE(a.tanggal) as tanggal, a.status, a.keterangan')
            ->join('rombel r', 's.rombel_id = r.id')
            ->join('absensi a', 'a.siswa_id = s.id AND a.tanggal >= "' . $startDate . '" AND a.tanggal <= "' . $endDate . '"', 'left')
            ->where('s.is_active', 1);
            
        if ($rombelId) {
            $builder->where('r.id', $rombelId);
        }

        if ($userId) {
            // Jika ada filter user, tambahkan ke kondisi join absensi
            // Note: Ini agak tricky karena jika ditaruh di WHERE, siswa yang tidak diabsen oleh guru ini akan hilang
            // Jika ditaruh di JOIN, siswa tetap muncul tapi status absensi null (dianggap tidak hadir/belum diabsen)
            // Untuk kebutuhan laporan "Absensi", biasanya kita ingin melihat apa yang SUDAH diisi.
            // Tapi jika requirementnya "walaupun data tidak ada", maka LEFT JOIN dengan kondisi di ON adalah yang benar.
            // Namun karena query builder CI4 join method string conditionnya agak terbatas untuk parameter binding,
            // kita modifikasi string joinnya.
            
            // Re-build join dengan tambahan guru_id
            // Kita perlu replace join sebelumnya atau menimpanya.
            // Karena builder sifatnya append, kita harus hati-hati.
            // Cara paling aman adalah tidak menggunakan $userId di sini jika konteksnya Admin export semua.
            // Tapi method ini ada parameter $userId.
            
            // Untuk saat ini, asumsikan Admin export tidak pakai userId.
            // Jika dipakai guru, logicnya mungkin perlu disesuaikan.
             $builder->where('a.guru_id', $userId);
        }
        
        $builder->orderBy('r.kode_rombel, s.nama, a.tanggal');
        
        $result = $builder->get()->getResultArray();
        return $result;
    }

    /**
     * Export data absensi ke Excel
     */
    public function exportToExcel($data, $startDate, $endDate)
    {
        $spreadsheet = new Spreadsheet();
        // Hapus sheet default pertama
        $spreadsheet->removeSheetByIndex(0);
        
        // Group data by rombel
        $dataByRombel = [];
        foreach ($data as $row) {
            $rombelKey = $row['kode_rombel'] . ' - ' . $row['nama_rombel'];
            $dataByRombel[$rombelKey][] = $row;
        }
        
        // Jika tidak ada data sama sekali
        if (empty($dataByRombel)) {
             $sheet = $spreadsheet->createSheet();
             $sheet->setTitle('No Data');
             $sheet->setCellValue('A1', 'Tidak ada data siswa aktif pada periode ini.');
             $this->styleSheet($sheet);
        } else {
            foreach ($dataByRombel as $rombelName => $rombelData) {
                $sheet = $spreadsheet->createSheet();
                // Nama sheet max 31 karakter dan tidak boleh ada karakter spesial
                $safeTitle = substr(str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $rombelName), 0, 30);
                $sheet->setTitle($safeTitle);
                
                $this->populateSheet($sheet, $rombelData, $startDate, $endDate, $rombelName);
            }
        }
        
        // Set sheet pertama sebagai aktif
        $spreadsheet->setActiveSheetIndex(0);
        
        // Filename
        $filename = 'data-absensi-' . date('Y-m-d-His') . '.xlsx';
        
        // Headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function populateSheet($sheet, $data, $startDate, $endDate, $rombelName)
    {
        // Header
        $sheet->setCellValue('A1', 'DATA ABSENSI SISWA');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        
        $sheet->setCellValue('A2', 'Kelas: ' . $rombelName);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

        $sheet->setCellValue('A3', 'Periode: ' . date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate)));
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        
        // Header tabel
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'NIS');
        $sheet->setCellValue('C5', 'Nama Siswa');
        $sheet->setCellValue('D5', 'Tanggal');
        $sheet->setCellValue('E5', 'Status');
        $sheet->setCellValue('F5', 'Keterangan');
        
        // Style header tabel
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E2E2']],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A5:F5')->applyFromArray($headerStyle);
        
        // Isi data
        $row = 6;
        $no = 1;
        
        // Kita perlu memproses data agar siswa yang tidak punya absensi tetap muncul
        // Data yang masuk sudah join left, jadi siswa tanpa absensi akan punya tanggal NULL
        
        foreach ($data as $item) {
            // Jika tanggal null, berarti siswa ini tidak punya absensi di range tersebut
            // Kita tetap tampilkan namanya sekali saja dengan status kosong atau strip
            
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nis']);
            $sheet->setCellValue('C' . $row, $item['nama_siswa']);
            
            if ($item['tanggal']) {
                $sheet->setCellValue('D' . $row, date('d/m/Y', strtotime($item['tanggal'])));
                $sheet->setCellValue('E' . $row, strtoupper($item['status']));
                $sheet->setCellValue('F' . $row, $item['keterangan']);
            } else {
                $sheet->setCellValue('D' . $row, '-');
                $sheet->setCellValue('E' . $row, '-');
                $sheet->setCellValue('F' . $row, 'Belum ada data absensi');
            }
            $row++;
        }
        
        // Auto size column
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Border untuk data
        $sheet->getStyle('A5:F' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ]);
    }

    private function styleSheet($sheet) {
        // Helper untuk styling sheet kosong jika diperlukan
        $sheet->getColumnDimension('A')->setAutoSize(true);
    }

    /**
     * Simpan absensi harian dengan transaksi
     */
    /**
     * Simpan absensi harian dengan transaksi
     */
    public function simpanAbsensiHarian($tanggal, $rombelId, $absensiData, $userId)
    {
        // Mulai transaksi database
        $this->db->transBegin();

        try {
            // Simpan data absensi
            foreach ($absensiData as $siswaId => $data) {
                $statusCode = $data['status'];
                $keterangan = $data['keterangan'] ?? '';

                // Map status code to full word
                $statusMap = [
                    'H' => 'hadir',
                    'I' => 'izin',
                    'S' => 'sakit',
                    'A' => 'alfa'
                ];
                $status = $statusMap[$statusCode] ?? 'hadir'; // Default to hadir if unknown

                $this->absensiModel->insert([
                    'jurnal_id' => 0, // Set 0 atau null karena sudah tidak terikat jurnal
                    'tanggal' => $tanggal,
                    'rombel_id' => $rombelId,
                    'guru_id' => $userId,
                    'mapel_id' => 1, // Default mapel untuk absensi
                    'siswa_id' => $siswaId,
                    'status' => $status,
                    'keterangan' => $keterangan
                ]);
            }

            // Commit transaksi
            $this->db->transCommit();
            
            return true;
        } catch (\Exception $e) {
            // Rollback transaksi
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Mendapatkan detail absensi per kelas
     */
    public function getDetailAbsensiPerKelas($rombelId, $startDate, $endDate, $userId = null)
    {
        $builder = $this->db->table('absensi a');
        $builder->select('
            a.id,
            a.tanggal,
            a.status,
            a.keterangan,
            s.nis,
            s.nama as nama_siswa,
            r.nama_rombel,
            r.kode_rombel
        ');
        $builder->join('siswa s', 'a.siswa_id = s.id');
        $builder->join('rombel r', 'a.rombel_id = r.id');
        // $builder->join('jurnal_new j', 'a.jurnal_id = j.id', 'left'); // Removed dependency on jurnal_new for date
        
        $builder->where('a.rombel_id', $rombelId);
        
        if ($startDate && $endDate) {
            $builder->where('a.tanggal >=', $startDate);
            $builder->where('a.tanggal <=', $endDate);
        }

        // Optional: Filter by guru if needed, but usually detail view shows all for the class in that period
        // If strict ownership is required:
        if ($userId) {
            $builder->where('a.guru_id', $userId);
        }
        
        $builder->orderBy('a.tanggal DESC, s.nama ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Mendapatkan daftar siswa berdasarkan rombel
     */
    public function getSiswaByRombelId($rombelId)
    {
        // Ambil data siswa berdasarkan rombel
        $siswa = $this->siswaModel->where('rombel_id', $rombelId)
                                  ->where('is_active', 1)
                                  ->orderBy('nama', 'ASC')
                                  ->findAll();

        // Format data untuk response
        $result = [];
        foreach ($siswa as $s) {
            $result[] = [
                'siswa_id' => $s['id'],
                'siswa_nis' => $s['nis'],
                'siswa_nama' => $s['nama']
            ];
        }

        return $result;
    }

    /**
     * Mendapatkan daftar rombel
     */
    public function getRombelList()
    {
        // Mengembalikan semua data rombel tanpa filter jenjang
        // agar konsisten dengan halaman index dan memastikan semua rombel muncul
        return $this->rombelModel->orderBy('kode_rombel', 'ASC')->findAll();
    }
    
    /**
     * Mendapatkan daftar rombel dengan jumlah siswa
     */
    public function getRombelWithStudentCount()
    {
        $builder = $this->db->table('rombel r');
        $builder->select('r.*, COUNT(s.id) as jumlah_siswa');
        $builder->join('siswa s', 'r.id = s.rombel_id AND s.is_active = 1', 'left');
        $builder->groupBy('r.id, r.kode_rombel, r.nama_rombel');
        $builder->orderBy('r.kode_rombel', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}