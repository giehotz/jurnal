<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Repositories\AbsensiRepository;
use App\Repositories\RombelRepository;
use App\Repositories\MataPelajaranRepository;
use App\Repositories\JurnalRepository;
use App\Repositories\SiswaRepository;
use App\Helpers\MobileDetection;
use App\Helpers\AuthorizationHelper;
use App\Validation\AbsensiValidation;
use App\DTO\AbsensiDTO;

class AbsensiInputController extends BaseController
{
    protected $authService;
    protected $absensiRepo;
    protected $rombelRepo;
    protected $mapelRepo;
    protected $jurnalRepo;
    protected $siswaRepo;
    protected $absensiValidation;
    protected $db;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->absensiRepo = new AbsensiRepository();
        $this->rombelRepo = new RombelRepository();
        $this->mapelRepo = new MataPelajaranRepository();
        $this->jurnalRepo = new JurnalRepository();
        $this->siswaRepo = new SiswaRepository();
        $this->absensiValidation = new AbsensiValidation();
        $this->db = \Config\Database::connect();
    }

    public function create()
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        $userId = $this->authService->getUserId();
        $kelasGuru = $this->rombelRepo->getKelasGuru($userId);
        $mapel = $this->mapelRepo->getAll();

        $data = [
            'title' => 'Input Absensi Harian',
            'active' => 'absensi',
            'rombel' => $kelasGuru,
            'mapel' => $mapel,
            'selected_rombel' => $this->request->getGet('rombel_id'),
            'selected_tanggal' => $this->request->getGet('tanggal')
        ];

        $viewPath = MobileDetection::isMobile() ? 'mobile/guru/absensi/create' : 'guru/absensi/create';
        return view($viewPath, $data);
    }

    public function store()
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        if (!$this->validate($this->absensiValidation->getRules())) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid');
        }

        $dto = new AbsensiDTO($this->request->getPost());
        $userId = $this->authService->getUserId();

        if (!AuthorizationHelper::isGuruMengajarKelas($userId, $dto->rombelId)) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak mengajar kelas ini');
        }

        $this->db->transBegin();

        try {
            // 1. Create Jurnal
            $jurnalId = $this->jurnalRepo->insert([
                'user_id' => $userId,
                'rombel_id' => $dto->rombelId,
                'mapel_id' => $dto->mapelId,
                'tanggal' => $dto->tanggal,
                'jam_ke' => $dto->jamKe,
                'materi' => $dto->materi,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if (!$jurnalId) {
                throw new \Exception('Gagal membuat jurnal');
            }

            // 2. Insert Absensi linked to Jurnal
            $hadirCount = 0;
            $tidakHadirCount = 0;

            foreach ($dto->absensiData as $siswaId => $data) {
                $status = $data['status'];
                
                $this->absensiRepo->insert([
                    'jurnal_id' => $jurnalId, // Link to Jurnal
                    'tanggal' => $dto->tanggal,
                    'rombel_id' => $dto->rombelId,
                    'guru_id' => $userId,
                    'mapel_id' => $dto->mapelId,
                    'siswa_id' => $siswaId,
                    'status' => $status,
                    'keterangan' => $data['keterangan'] ?? ''
                ]);

                if ($status == 'hadir') {
                    $hadirCount++;
                } else {
                    $tidakHadirCount++;
                }
            }

            // 3. Update Jurnal counts
            $this->jurnalRepo->update($jurnalId, [
                'jumlah_siswa_hadir' => $hadirCount,
                'jumlah_siswa_tidak_hadir' => $tidakHadirCount
            ]);

            $this->db->transCommit();
            return redirect()->to('/guru/absensi')->with('success', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data absensi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        $userId = $this->authService->getUserId();
        $absensi = $this->absensiRepo->find($id);
        
        if (!$absensi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data absensi tidak ditemukan');
        }

        $jurnal = $this->jurnalRepo->find($absensi['jurnal_id']);
        if (!$jurnal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data jurnal tidak ditemukan');
        }

        if ($jurnal['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit absensi ini');
        }

        $siswa = $this->siswaRepo->find($absensi['siswa_id']);
        if (!$siswa) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Absensi',
            'active' => 'absensi',
            'absensi' => $absensi,
            'jurnal' => $jurnal,
            'siswa' => $siswa
        ];

        $viewPath = MobileDetection::isMobile() ? 'mobile/guru/absensi/edit' : 'guru/absensi/edit';
        return view($viewPath, $data);
    }

    public function update($id)
    {
        if (!$this->authService->checkGuruAccess()) {
            return redirect()->to('/auth/login');
        }

        $userId = $this->authService->getUserId();
        $absensi = $this->absensiRepo->find($id);
        
        if ($absensi) {
            $jurnal = $this->jurnalRepo->find($absensi['jurnal_id']);
            if ($jurnal && $jurnal['user_id'] != $userId) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengupdate absensi ini');
            }
        }

        $data = [
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan')
        ];

        if ($this->absensiRepo->update($id, $data)) {
            return redirect()->to('/guru/absensi')->with('success', 'Data absensi berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data absensi.');
        }
    }
}
