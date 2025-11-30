<?php

namespace App\Services;

use App\Models\JurnalModel;
use App\Models\RombelModel;
use App\Models\SettingModel;

class JurnalPdfService
{
    protected $jurnalModel;
    protected $rombelModel;
    protected $settingModel;

    public function __construct(
        JurnalModel $jurnalModel,
        RombelModel $rombelModel,
        SettingModel $settingModel
    ) {
        $this->jurnalModel = $jurnalModel;
        $this->rombelModel = $rombelModel;
        $this->settingModel = $settingModel;
    }

    /**
     * Generate PDF for a single jurnal entry
     *
     * @param array $jurnal
     * @return string
     */
    public function generateSinglePdf(array $jurnal)
    {
        // Create DOMPDF instance
        $dompdf = $this->createPdfInstance();

        // Data untuk ditampilkan di PDF
        $data = [
            'jurnal' => $jurnal
        ];

        // Render view ke HTML
        $html = view('admin/monitoring/pdf', $data);

        // Load HTML ke DOMPDF
        $dompdf->loadHtml($html);

        // Setup ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Return PDF content
        return $dompdf->output();
    }

    /**
     * Generate report PDF for multiple jurnal entries
     *
     * @param array $filters
     * @param int $userId
     * @param string $role
     * @param array $userData
     * @return string
     */
    public function generateReportPdf(array $filters, int $userId, string $role, array $userData)
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

        // If we're exporting data for a specific teacher (not admin/wali kelas exporting their own data)
        // and there's only one teacher in the results, use that teacher's name
        if (!empty($jurnals) && ($role === 'admin' || $userData['is_wali_kelas'])) {
            $uniqueTeachers = $this->getUniqueTeachers($jurnals);
            
            // If there's only one unique teacher in the results, use their info
            if (count($uniqueTeachers) == 1) {
                $teacherInfo = reset($uniqueTeachers);
                $userData['nama'] = $teacherInfo['nama'];
                $userData['nip'] = $teacherInfo['nip'];
            }
        }

        // Data untuk ditampilkan di PDF
        // Ambil pengaturan sekolah
        $schoolSettings = $this->settingModel->getSettings();

        $data = [
            'jurnals' => $jurnals,
            'tanggal_awal' => $formattedTanggalAwal,
            'tanggal_akhir' => $formattedTanggalAkhir,
            'user' => $userData,
            'school_settings' => $schoolSettings
        ];

        // Load the helpers for use in the PDF view
        helper(['tanggal', 'laporan']);

        // Create DOMPDF instance
        $dompdf = $this->createPdfInstance();

        // Render view ke HTML
        $html = view('guru/jurnal/pdf_report', $data);

        // Load HTML ke DOMPDF
        $dompdf->loadHtml($html);

        // Setup ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Return PDF content
        return $dompdf->output();
    }

    /**
     * Create DOMPDF instance with common options
     *
     * @return \Dompdf\Dompdf
     */
    private function createPdfInstance()
    {
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        return new \Dompdf\Dompdf($options);
    }

    /**
     * Get unique teachers from jurnal data
     *
     * @param array $jurnals
     * @return array
     */
    private function getUniqueTeachers(array $jurnals): array
    {
        $uniqueTeachers = [];
        foreach ($jurnals as $jurnal) {
            $teacherKey = $jurnal['user_id'] . '_' . $jurnal['nama_guru'];
            $uniqueTeachers[$teacherKey] = [
                'nama' => $jurnal['nama_guru'],
                'nip' => $jurnal['nip']
            ];
        }
        return $uniqueTeachers;
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

            // Parsing tanggal dengan berbagai format yang mungkin
            $parsedTanggalAwal = $this->parseDate($tanggalAwal);
            $parsedTanggalAkhir = $this->parseDate($tanggalAkhir);

            if (!$parsedTanggalAwal || !$parsedTanggalAkhir) {
                return ['error' => 'Format tanggal tidak valid.'];
            }

            // Format tanggal ke format database (Y-m-d)
            $formattedTanggalAwal = $parsedTanggalAwal->format('Y-m-d');
            $formattedTanggalAkhir = $parsedTanggalAkhir->format('Y-m-d');

            // Pastikan tanggal akhir tidak lebih kecil dari tanggal awal
            if (strtotime($formattedTanggalAkhir) < strtotime($formattedTanggalAwal)) {
                return ['error' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.'];
            }
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

        // Create base query
        $query = $this->baseJurnalQuery()
            ->where('jurnal_new.tanggal >=', $startDate)
            ->where('jurnal_new.tanggal <=', $endDate);

        // Mengambil data jurnal berdasarkan peran user
        if ($role === 'admin') {
            // Admin: Mengambil semua jurnal
            return $query->get()->getResultArray();
        } else if ($isWaliKelas) {
            // Wali Kelas: Mengambil jurnal dari kelas yang diwali
            return $query->where('jurnal_new.rombel_id', $kelasWali['id'])->get()->getResultArray();
        } else {
            // Guru Biasa: Mengambil jurnal milik sendiri
            return $query->where('jurnal_new.user_id', $userId)->get()->getResultArray();
        }
    }

    /**
     * Create base jurnal query with common joins and ordering
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    private function baseJurnalQuery()
    {
        return $this->jurnalModel
            ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru, users.nip')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->join('users', 'users.id = jurnal_new.user_id')
            ->where('jurnal_new.mapel_id !=', 18)
            ->notLike('jurnal_new.materi', 'Absensi Kelas')
            ->orderBy('jurnal_new.tanggal', 'ASC')
            ->orderBy('jurnal_new.jam_ke', 'ASC');
    }

    /**
     * Parse date from various formats to DateTime object
     *
     * @param string $dateString
     * @return \DateTime|false
     */
    private function parseDate($dateString)
    {
        // Trim whitespace to avoid accidental spaces
        $dateString = trim($dateString);
        
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
}