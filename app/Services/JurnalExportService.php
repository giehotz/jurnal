<?php

namespace App\Services;

use App\Models\JurnalModel;
use App\Models\RombelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JurnalExportService
{
    protected $jurnalModel;
    protected $rombelModel;

    public function __construct(JurnalModel $jurnalModel, RombelModel $rombelModel)
    {
        $this->jurnalModel = $jurnalModel;
        $this->rombelModel = $rombelModel;
    }

    /**
     * Export jurnal data to Excel
     *
     * @param array $filters
     * @param int $userId
     * @param string $role
     * @return void
     */
    public function exportToExcel(array $filters, int $userId, string $role)
    {
        // Parse dates
        $dates = $this->parseDates($filters);
        
        if (isset($dates['error'])) {
            throw new \Exception($dates['error']);
        }
        
        $formattedTanggalAwal = $dates['start'];
        $formattedTanggalAkhir = $dates['end'];

        // Get journals based on user role
        $jurnals = $this->getFilteredJurnals(
            $formattedTanggalAwal,
            $formattedTanggalAkhir,
            $userId,
            $role
        );

        if (empty($jurnals)) {
            throw new \Exception('Tidak ada data jurnal dalam periode tersebut.');
        }

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Laporan Jurnal Mengajar');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Sub Header
        $sheet->setCellValue('A2', 'Periode: ' . date('d F Y', strtotime($formattedTanggalAwal)) . ' - ' . date('d F Y', strtotime($formattedTanggalAkhir)));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setBold(true);

        // Table Header
        $header = ['ID', 'Tanggal', 'Hari', 'Kelas', 'Mapel', 'Jam Ke', 'Materi', 'Deskripsi', 'Jml Siswa', 'Ketuntasan'];
        $columnIndex = 'A';
        $rowIndex = 4;

        foreach ($header as $h) {
            $sheet->setCellValue($columnIndex.$rowIndex, $h);
            $sheet->getStyle($columnIndex.$rowIndex)->getFont()->setBold(true);
            $columnIndex++;
        }

        // Table Body
        $rowIndex = 5;
        foreach ($jurnals as $jurnal) {
            $columnIndex = 'A';
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['id']);
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['tanggal']);
            $sheet->setCellValue($columnIndex++.$rowIndex, $this->formatHariTanggal($jurnal['tanggal']));
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['nama_rombel']);
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['nama_mapel']);
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['jam_ke']);
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['materi']);
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['deskripsi'] ?? '');
            $sheet->setCellValue($columnIndex++.$rowIndex, $jurnal['jumlah_siswa'] ?? $jurnal['jumlah_peserta']);
            $sheet->setCellValue($columnIndex++.$rowIndex, ($jurnal['siswa_tuntas'] ?? 0) . '/' . ($jurnal['siswa_total'] ?? $jurnal['jumlah_peserta']));
            $rowIndex++;
        }

        // Auto-size columns
        foreach(range('A', $sheet->getHighestDataColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="jurnal_mengajar.xlsx"');
        header('Cache-Control: max-age=0');

        // Write the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    /**
     * Parse dates from filters
     *
     * @param array $filters
     * @return array
     */
    private function parseDates(array $filters): array
    {
        $bulanAwal = $filters['bulan_awal'] ?? null;
        $bulanAkhir = $filters['bulan_akhir'] ?? null;
        $tahun = $filters['tahun'] ?? null;
        $tanggalAwal = $filters['tanggal_awal'] ?? null;
        $tanggalAkhir = $filters['tanggal_akhir'] ?? null;

        if ($bulanAwal && $bulanAkhir && $tahun) {
            // Input berupa bulan - konversi ke tanggal
            // Validasi input bulan dan tahun
            if (!is_numeric($bulanAwal) || !is_numeric($bulanAkhir) || !is_numeric($tahun)) {
                return ['error' => 'Format bulan atau tahun tidak valid.'];
            }

            if ($bulanAwal < 1 || $bulanAwal > 12 || $bulanAkhir < 1 || $bulanAkhir > 12) {
                return ['error' => 'Bulan harus antara 1 dan 12.'];
            }

            if ($tahun < 2000 || $tahun > 2100) {
                return ['error' => 'Tahun tidak valid.'];
            }

            // Pastikan bulan akhir tidak lebih kecil dari bulan awal
            if ($bulanAkhir < $bulanAwal) {
                return ['error' => 'Bulan akhir tidak boleh lebih kecil dari bulan awal.'];
            }

            // Membuat tanggal awal dan akhir berdasarkan bulan dan tahun
            $formattedTanggalAwal = date('Y-m-d', mktime(0, 0, 0, $bulanAwal, 1, $tahun));
            $formattedTanggalAkhir = date('Y-m-d', mktime(23, 59, 59, $bulanAkhir + 1, 0, $tahun)); // Akhir bulan terakhir
        } else {
            // Input berupa tanggal seperti sebelumnya
            // Validasi input tanggal
            if (!$tanggalAwal || !$tanggalAkhir) {
                return ['error' => 'Tanggal awal dan tanggal akhir harus diisi.'];
            }

            // Validasi format tanggal
            if (!strtotime($tanggalAwal) || !strtotime($tanggalAkhir)) {
                return ['error' => 'Format tanggal tidak valid.'];
            }

            // Pastikan tanggal akhir tidak lebih kecil dari tanggal awal
            if (strtotime($tanggalAkhir) < strtotime($tanggalAwal)) {
                return ['error' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.'];
            }

            // Format tanggal ke format database (Y-m-d)
            $formattedTanggalAwal = date('Y-m-d', strtotime($tanggalAwal));
            $formattedTanggalAkhir = date('Y-m-d', strtotime($tanggalAkhir));
        }

        return [
            'start' => $formattedTanggalAwal,
            'end' => $formattedTanggalAkhir
        ];
    }

    /**
     * Get filtered jurnals based on role
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $userId
     * @param string $role
     * @return array
     */
    private function getFilteredJurnals(string $startDate, string $endDate, int $userId, string $role): array
    {
        // Mengecek apakah user adalah wali kelas
        $isWaliKelas = false;
        $kelasWali = null;

        if ($role !== 'admin') {
            // Cek apakah user adalah wali kelas
            $kelasWali = $this->rombelModel
                ->where('wali_kelas', $userId)
                ->first();

            if ($kelasWali) {
                $isWaliKelas = true;
            }
        }

        // Mengambil data jurnal berdasarkan peran user
        if ($role === 'admin') {
            // Admin: Mengambil semua jurnal
            return $this->jurnalModel
                ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru, users.nip')
                ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
                ->join('users', 'users.id = jurnal_new.user_id')
                ->where('jurnal_new.mapel_id !=', 18)
                ->notLike('jurnal_new.materi', 'Absensi Kelas')
                ->where('jurnal_new.tanggal >=', $startDate)
                ->where('jurnal_new.tanggal <=', $endDate)
                ->orderBy('jurnal_new.tanggal', 'ASC')
                ->orderBy('jurnal_new.jam_ke', 'ASC')
                ->findAll();
        } else if ($isWaliKelas) {
            // Wali Kelas: Mengambil jurnal dari rombel yang diwali
            return $this->jurnalModel
                ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru, users.nip')
                ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
                ->join('users', 'users.id = jurnal_new.user_id')
                ->where('jurnal_new.rombel_id', $kelasWali['id'])
                ->where('jurnal_new.mapel_id !=', 18)
                ->notLike('jurnal_new.materi', 'Absensi Kelas')
                ->where('jurnal_new.tanggal >=', $startDate)
                ->where('jurnal_new.tanggal <=', $endDate)
                ->orderBy('jurnal_new.tanggal', 'ASC')
                ->orderBy('jurnal_new.jam_ke', 'ASC')
                ->findAll();
        } else {
            // Guru Biasa: Mengambil jurnal milik sendiri
            return $this->jurnalModel
                ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru, users.nip')
                ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
                ->join('users', 'users.id = jurnal_new.user_id')
                ->where('jurnal_new.user_id', $userId)
                ->where('jurnal_new.mapel_id !=', 18)
                ->notLike('jurnal_new.materi', 'Absensi Kelas')
                ->where('jurnal_new.tanggal >=', $startDate)
                ->where('jurnal_new.tanggal <=', $endDate)
                ->orderBy('jurnal_new.tanggal', 'ASC')
                ->orderBy('jurnal_new.jam_ke', 'ASC')
                ->findAll();
        }
    }

    /**
     * Parse date from various formats to DateTime object
     *
     * @param string $dateString
     * @return \DateTime|false
     */
    private function parseDate($dateString)
    {
        $formats = [
            'Y-m-d',      // 2025-01-11
            'm/d/Y',      // 01/11/2025
            'd/m/Y',      // 11/01/2025
            'd-m-Y',      // 11-01-2025
            'm-d-Y',      // 01-11-2025
        ];

        foreach ($formats as $format) {
            $date = date_create_from_format($format, $dateString);
            if ($date) {
                return $date;
            }
        }

        // Try with strtotime as fallback
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return new \DateTime('@' . $timestamp);
        }

        return false;
    }

    /**
     * Format tanggal menjadi nama hari dan tanggal
     *
     * @param string $dateString
     * @return string
     */
    private function formatHariTanggal($dateString)
    {
        $date = $this->parseDate($dateString);
        if (!$date) {
            return $dateString; // Return original if parsing fails
        }

        $hari = [
            'Minggu', 'Senin', 'Selasa', 'Rabu',
            'Kamis', 'Jumat', 'Sabtu'
        ];

        $hariIndonesia = $hari[$date->format('w')];
        return $hariIndonesia . ', ' . $date->format('d-m-Y');
    }
}