<?php

namespace App\Services;

use App\Models\JurnalModel;
use App\Models\RombelModel;
use App\Models\MapelModel;

class JurnalQueryService
{
    protected $jurnalModel;
    protected $rombelModel;
    protected $mapelModel;

    public function __construct(
        JurnalModel $jurnalModel,
        RombelModel $rombelModel,
        MapelModel $mapelModel
    ) {
        $this->jurnalModel = $jurnalModel;
        $this->rombelModel = $rombelModel;
        $this->mapelModel = $mapelModel;
    }

    /**
     * Get jurnal list for a specific user
     *
     * @param int $userId
     * @return array
     */
    public function getUserJurnals(int $userId): array
    {
        // Periksa apakah guru adalah wali kelas
        $waliKelasRombel = $this->rombelModel->where('wali_kelas', $userId)->first();

        if ($waliKelasRombel) {
            // Jika guru adalah wali kelas, tampilkan:
            // 1. Semua jurnal yang dibuat oleh guru ini (tidak peduli rombel)
            // 2. Semua jurnal untuk rombel yang diwali oleh guru ini (dibuat oleh siapa pun)

            // Mengambil data jurnal dengan kondisi khusus untuk wali kelas
            $builder = $this->jurnalModel->builder();
            $builder->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru');
            $builder->join('rombel', 'rombel.id = jurnal_new.rombel_id');
            $builder->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id');
            $builder->join('users', 'users.id = jurnal_new.user_id');
            $builder->orderBy('jurnal_new.created_at', 'DESC');

            // Kondisi khusus untuk wali kelas
            $builder->groupStart(); // Mulai grup kondisi
            $builder->where('jurnal_new.user_id', $userId); // Jurnal pribadi
            $builder->orWhere('rombel.wali_kelas', $userId); // Jurnal rombel yang diwali
            // Kondisi khusus untuk wali kelas
            $builder->groupStart(); // Mulai grup kondisi
            $builder->where('jurnal_new.user_id', $userId); // Jurnal pribadi
            $builder->orWhere('rombel.wali_kelas', $userId); // Jurnal rombel yang diwali
            $builder->groupEnd(); // Akhiri grup kondisi

            // Exclude Absensi
            $builder->where('jurnal_new.mapel_id !=', 18);
            $builder->notLike('jurnal_new.materi', 'Absensi Kelas');
            $builder->notLike('jurnal_new.keterangan', 'Generated from Absensi');

            $query = $builder->get();
            $jurnals = $query->getResultArray();

            $is_wali_kelas = true;
            $kelas_perwalian = $waliKelasRombel;
        } 
        else {
            // Jika bukan wali kelas, hanya tampilkan jurnal pribadi
            $jurnals = $this->jurnalModel
                ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru')
                ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
                ->where('jurnal_new.user_id', $userId)
                ->orderBy('jurnal_new.created_at', 'DESC')
                ->where('jurnal_new.user_id', $userId)
                ->where('jurnal_new.mapel_id !=', 18) // Exclude Absensi
                ->notLike('jurnal_new.materi', 'Absensi Kelas')
                ->notLike('jurnal_new.keterangan', 'Generated from Absensi')
                ->orderBy('jurnal_new.created_at', 'DESC')
                ->findAll();

            $is_wali_kelas = false;
            $kelas_perwalian = null;
        }

        return [
            'jurnals' => $jurnals,
            'is_wali_kelas' => $is_wali_kelas,
            'kelas_perwalian' => $kelas_perwalian
        ];
    }

    /**
     * Get jurnal by ID with related data
     *
     * @param int $id
     * @return array|null
     */
    public function getJurnalById(int $id): ?array
    {
        return $this->jurnalModel
            ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->join('users', 'users.id = jurnal_new.user_id')
            ->where('jurnal_new.id', $id)
            ->first();
    }

    /**
     * Get jurnal by ID with related data for PDF generation
     *
     * @param int $id
     * @return array|null
     */
    public function getJurnalByIdForPdf(int $id): ?array
    {
        return $this->jurnalModel
            ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel, users.nama as nama_guru, users.nip as nip')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->join('users', 'users.id = jurnal_new.user_id')
            ->where('jurnal_new.id', $id)
            ->first();
    }

    /**
     * Get recent jurnals for a user
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getRecentJurnals(int $userId, int $limit = 5): array
    {
        return $this->jurnalModel
            ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->where('jurnal_new.user_id', $userId)
            ->orderBy('jurnal_new.created_at', 'DESC')
            ->limit($limit)
            ->where('jurnal_new.user_id', $userId)
            ->where('jurnal_new.mapel_id !=', 18) // Exclude Absensi
            ->notLike('jurnal_new.materi', 'Absensi Kelas')
            ->notLike('jurnal_new.keterangan', 'Generated from Absensi')
            ->orderBy('jurnal_new.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get available hours for a rombel on a specific date
     *
     * @param int $rombelId ID of the rombel (class) to check available hours for
     * @param string $tanggal Date to check in YYYY-MM-DD format
     * @param int|null $editJurnalId Optional ID of jurnal being edited (to exclude from availability check)
     * @return array An array containing:
     *               - used_hours: array of hours already used
     *               - available_hours: array of hours still available
     *               - next_hour: the first available hour (null if all hours are used)
     */
    public function getAvailableHours(int $rombelId, string $tanggal, ?int $editJurnalId = null): array
    {
        // 1. Ambil semua jam yang sudah terpakai untuk rombel & tanggal tertentu
        $builder = $this->jurnalModel->builder();
        $builder->select('jam_ke, jumlah_jam');
        $builder->where('rombel_id', $rombelId);
        $builder->where('tanggal', $tanggal);
        
        // Jika sedang edit, abaikan jurnal yang sedang diedit
        if ($editJurnalId) {
            $builder->where('id !=', $editJurnalId);
        }
        
        $query = $builder->get();
        $usedJurnals = $query->getResultArray();

        // 2. Proses semua jam yang terpakai untuk mendapatkan daftar jam individual
        $allUsedHours = [];
        foreach ($usedJurnals as $jurnal) {
            // Misal jam_ke = "1-3", kita pecah menjadi array [1,2,3]
            $jamRange = explode('-', $jurnal['jam_ke']);
            if (count($jamRange) == 2) {
                // Format range seperti "1-3"
                $start = (int)$jamRange[0];
                $end = (int)$jamRange[1];
                for ($i = $start; $i <= $end; $i++) {
                    $allUsedHours[] = $i;
                }
            } else {
                // Format tunggal seperti "2"
                $allUsedHours[] = (int)$jurnal['jam_ke'];
            }
        }

        // 3. Tentukan jam kosong pertama setelah semua jam terpakai
        $maxHours = 12; // Maksimal jam pelajaran dalam sehari
        $nextHour = null;
        for ($i = 1; $i <= $maxHours; $i++) {
            if (!in_array($i, $allUsedHours)) {
                $nextHour = $i;
                break;
            }
        }

        // 4. Buat daftar semua jam yang tersedia (untuk dropdown)
        $availableHours = [];
        for ($i = 1; $i <= $maxHours; $i++) {
            if (!in_array($i, $allUsedHours)) {
                $availableHours[] = $i;
            }
        }

        // Pastikan nextHour tidak melebihi batas
        if ($nextHour > $maxHours) {
            $nextHour = null; // Tidak ada jam tersedia berikutnya
        }

        return [
            'used_hours' => $allUsedHours,      // Kirim semua jam yg terpakai
            'available_hours' => $availableHours, // Kirim hanya jam yg benar-benar kosong
            'next_hour' => $nextHour            // Kirim jam kosong pertama
        ];
    }

    /**
     * Get all rombels and subjects for form dropdowns
     *
     * @return array
     */
    public function getFormOptions(): array
    {
        return [
            'kelas' => $this->rombelModel->findAll(),
            'mapel' => $this->mapelModel->findAll()
        ];
    }

    /**
     * Get jurnal with class and subject for editing
     *
     * @param int $id
     * @return array|null
     */
    public function getJurnalForEdit(int $id): ?array
    {
        return $this->jurnalModel
            ->select('jurnal_new.*, rombel.nama_rombel, rombel.kode_rombel, mata_pelajaran.nama_mapel')
            ->join('rombel', 'rombel.id = jurnal_new.rombel_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jurnal_new.mapel_id')
            ->where('jurnal_new.id', $id)
            ->first();
    }
}