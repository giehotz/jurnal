<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RombelModel;
use App\Models\SiswaModel;

class PindahKelas extends BaseController
{
    protected $rombelModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->siswaModel = new SiswaModel();
    }

    /**
     * Menampilkan halaman utama pindah kelas
     */
    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil semua tingkat yang tersedia
        $tingkatList = $this->rombelModel->select('tingkat')
                                         ->distinct()
                                         ->where('is_active', 1)
                                         ->orderBy('tingkat', 'ASC')
                                         ->findAll();

        $data = [
            'title' => 'Pindah Kelas',
            'active' => 'pindah-kelas',
            'tingkatList' => $tingkatList
        ];

        return view('admin/pindah_kelas/index', $data);
    }

    /**
     * API untuk mengambil data rombel berdasarkan tingkat
     */
    public function getRombelByTingkat()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $tingkat = $this->request->getPost('tingkat');
        
        if (!$tingkat) {
            return $this->response->setJSON(['error' => 'Tingkat is required'])->setStatusCode(400);
        }

        try {
            // Ambil data rombel berdasarkan tingkat
            $rombel = $this->rombelModel->where('tingkat', $tingkat)
                                        ->where('is_active', 1)
                                        ->orderBy('nama_rombel', 'ASC')
                                        ->findAll();

            return $this->response->setJSON($rombel);
        } catch (\Exception $e) {
            log_message('error', 'Error saat mengambil data rombel: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Terjadi kesalahan saat mengambil data rombel'])->setStatusCode(500);
        }
    }

    /**
     * API untuk mengambil data siswa berdasarkan rombel
     */
    public function getSiswaByRombel()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $rombelId = $this->request->getPost('rombel_id');
        
        if (!$rombelId) {
            return $this->response->setJSON(['error' => 'Rombel ID is required'])->setStatusCode(400);
        }

        try {
            // Ambil data siswa berdasarkan rombel
            $siswa = $this->siswaModel->where('rombel_id', $rombelId)
                                      ->where('is_active', 1)
                                      ->orderBy('nama', 'ASC')
                                      ->findAll();

            return $this->response->setJSON($siswa);
        } catch (\Exception $e) {
            log_message('error', 'Error saat mengambil data siswa: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Terjadi kesalahan saat mengambil data siswa'])->setStatusCode(500);
        }
    }

    /**
     * Memindahkan siswa ke rombel lain
     */
    public function moveStudents()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $sourceRombelId = $this->request->getPost('source_rombel_id');
        $targetRombelId = $this->request->getPost('target_rombel_id');
        $studentIds = $this->request->getPost('student_ids');

        // Validasi input
        if (!$sourceRombelId || !$targetRombelId || empty($studentIds) || !is_array($studentIds)) {
            return $this->response->setJSON(['error' => 'Data tidak lengkap'])->setStatusCode(400);
        }

        try {
            // Pastikan rombel asal dan tujuan ada
            $sourceRombel = $this->rombelModel->find($sourceRombelId);
            $targetRombel = $this->rombelModel->find($targetRombelId);

            if (!$sourceRombel || !$targetRombel) {
                return $this->response->setJSON(['error' => 'Rombel tidak ditemukan'])->setStatusCode(404);
            }

            // Update rombel_id untuk setiap siswa yang dipilih
            $updatedCount = 0;
            foreach ($studentIds as $studentId) {
                // Pastikan siswa ada dan memang berada di rombel asal
                $siswa = $this->siswaModel->where('id', $studentId)
                                          ->where('rombel_id', $sourceRombelId)
                                          ->first();
                
                if ($siswa) {
                    // Update rombel_id
                    $this->siswaModel->update($studentId, ['rombel_id' => $targetRombelId]);
                    $updatedCount++;
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $updatedCount . ' siswa berhasil dipindahkan dari ' . $sourceRombel['nama_rombel'] . ' ke ' . $targetRombel['nama_rombel']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error saat memindahkan siswa: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Terjadi kesalahan saat memindahkan siswa'])->setStatusCode(500);
        }
    }
}