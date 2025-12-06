<?php

namespace App\Models;

use CodeIgniter\Model;

class KalenderGuruViewModel extends Model
{
    protected $table = 'kalender_guru_view';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'master_kalender_id',
        'guru_id',
        'tanggal',
        'jenis_hari',
        'keterangan',
        'warna_kode',
        'mata_pelajaran_terjadwal',
        'tahun_ajaran',
        'semester',
        'lembaga_id',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    // Get kalender untuk guru spesifik
    public function getKalenderGuru($guruId, $tahunAjaran, $semester, $lembayaId, $bulan = null)
    {
        $query = $this->where('guru_id', $guruId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembayaId)
            ->orderBy('tanggal', 'ASC');

        if ($bulan) {
            $query->where('MONTH(tanggal)', $bulan);
        }

        return $query->findAll();
    }

    // Get kalender by tanggal
    public function getByTanggal($tanggal, $guruId)
    {
        return $this->where('tanggal', $tanggal)
            ->where('guru_id', $guruId)
            ->first();
    }

    // Update view ketika admin publish kalender
    public function syncFromMasterKalender($tahunAjaran, $semester, $lembayaId)
    {
        $db = \Config\Database::connect();
        $kalenderModel = new MasterKalenderPendidikanModel();

        // Get semua guru di lembaga
        $guruList = $db->table('users')
            ->where('role', 'guru')
            ->get()
            ->getResultArray();

        // Get master kalender
        $masterKalender = $kalenderModel->getByTahunSemester($tahunAjaran, $semester, $lembayaId);

        // OPTIMIZATION: Delete existing view data for this semester first to avoid duplicates or stale data
        $this->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembayaId)
            ->delete();

        if (empty($guruList) || empty($masterKalender)) {
            return false;
        }

        $batchData = [];
        foreach ($guruList as $guru) {
            foreach ($masterKalender as $kalender) {
                $batchData[] = [
                    'master_kalender_id' => $kalender['id'],
                    'guru_id'            => $guru['id'],
                    'tanggal'            => $kalender['tanggal'],
                    'jenis_hari'         => $kalender['jenis_hari'],
                    'keterangan'         => $kalender['keterangan'],
                    'warna_kode'         => $kalender['warna_kode'], // Sync warna_kode
                    'tahun_ajaran'       => $tahunAjaran,
                    'semester'           => $semester,
                    'lembaga_id'         => $lembayaId,
                    'created_at'         => date('Y-m-d H:i:s'),
                ];
            }
        }

        // Chunk insert to avoid memory limit
        $chunks = array_chunk($batchData, 1000);
        foreach ($chunks as $chunk) {
            $this->insertBatch($chunk);
        }

        return true;
    }
}
