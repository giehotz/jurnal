<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RombelModel;
use App\Models\SiswaModel;
use App\Models\RiwayatKenaikanKelasModel;

class KenaikanKelas extends BaseController
{
    protected $rombelModel;
    protected $siswaModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->siswaModel = new SiswaModel();
        $this->riwayatModel = new RiwayatKenaikanKelasModel();
    }

    public function index()
    {
        // Cek login & role (mirip PindahKelas)
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil list tingkat unik
        $tingkatList = $this->rombelModel->select('tingkat')
            ->distinct()
            ->where('is_active', 1)
            ->orderBy('tingkat', 'ASC')
            ->findAll();

        // Ambil tahun ajaran aktif dari settings (atau hardcode sementara jika belum ada model settings yang global accessible)
        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $currentYear = $setting['school_year'] ?? date('Y') . '/' . (date('Y') + 1);

        $data = [
            'title' => 'Kenaikan Kelas',
            'active' => 'kenaikan-kelas', // Nanti perlu highlight menu sidebar
            'tingkatList' => $tingkatList,
            'currentYear' => $currentYear
        ];

        return view('admin/kenaikan_kelas/index', $data);
    }

    // Reuse logic getRombel tapi bisa difilter
    public function getRombel()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $tingkat = $this->request->getPost('tingkat');
        // Bisa tambahkan filter tahun ajaran jika tabel rombel punya kolom tahun_ajaran yang reliable

        $rombel = $this->rombelModel->where('tingkat', $tingkat)
            ->where('is_active', 1)
            ->orderBy('nama_rombel', 'ASC')
            ->findAll();

        return $this->response->setJSON($rombel);
    }

    public function getSiswa()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $rombelId = $this->request->getPost('rombel_id');
        $siswa = $this->siswaModel->where('rombel_id', $rombelId)
            ->where('is_active', 1)
            ->orderBy('nama', 'ASC')
            ->findAll();

        return $this->response->setJSON($siswa);
    }

    public function process()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $sourceRombelId = $this->request->getPost('source_rombel_id');
        $targetRombelId = $this->request->getPost('target_rombel_id');
        $studentIds = $this->request->getPost('student_ids');
        $userId = session()->get('id'); // Asumsi user id tersimpan di session

        if (!$sourceRombelId || !$targetRombelId || empty($studentIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        // Ambil info tahun ajaran (opsional, bisa ambil dari rombel jika rombel terikat tahun)
        // Disini kita ambil dari settings current year untuk target, dan source diasumsikan tahun sebelumnya
        // Atau ambil dr rombel masing-masing jika struktur DB mendukung

        $db = \Config\Database::connect();
        $setting = $db->table('settings')->get()->getRowArray();
        $currentYear = $setting['school_year'] ?? date('Y') . '/' . (date('Y') + 1);

        $db->transStart();

        try {
            $updatedCount = 0;
            foreach ($studentIds as $siswaId) {
                // 1. Update siswa ke rombel baru
                $this->siswaModel->update($siswaId, ['rombel_id' => $targetRombelId]);

                // 2. Catat History
                $this->riwayatModel->insert([
                    'siswa_id' => $siswaId,
                    'rombel_id_asal' => $sourceRombelId,
                    'rombel_id_tujuan' => $targetRombelId,
                    'tahun_ajaran_asal' => '?', // Idealnya ambil dari rombel asal atau input
                    'tahun_ajaran_tujuan' => $currentYear,
                    'tanggal_proses' => date('Y-m-d H:i:s'),
                    'user_id' => $userId
                ]);

                $updatedCount++;
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memproses database transaction']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Berhasil menaikkan $updatedCount siswa ke kelas tujuan."
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
