<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterKalenderPendidikanModel extends Model
{
    protected $table = 'master_kalender_pendidikan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'tahun_ajaran',
        'semester',
        'tanggal',
        'jenis_hari',
        'keterangan',
        'warna_kode',
        'created_by',
        'lembaga_id'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get kalender by tahun ajaran & semester
    public function getByTahunSemester($tahunAjaran, $semester, $lembayaId)
    {
        return $this->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembayaId)
            ->orderBy('tanggal', 'ASC')
            ->findAll();
    }

    // Check duplicate tanggal
    public function checkDuplicate($tanggal, $tahunAjaran, $semester, $lembayaId, $excludeId = null)
    {
        $query = $this->where('tanggal', $tanggal)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembayaId);

        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }

        return $query->first();
    }

    // Bulk insert dari excel
    public function bulkInsert($dataArray, $createdBy, $lembayaId)
    {
        $data = array_map(function ($row) use ($createdBy, $lembayaId) {
            return [
                'tahun_ajaran' => $row['tahun_ajaran'],
                'semester'     => $row['semester'],
                'tanggal'      => $row['tanggal'],
                'jenis_hari'   => $row['jenis_hari'] ?? 'hari_efektif',
                'keterangan'   => $row['keterangan'] ?? null,
                'warna_kode'   => $this->getWarnaBerdasarkanJenis($row['jenis_hari']),
                'created_by'   => $createdBy,
                'lembaga_id'   => $lembayaId,
            ];
        }, $dataArray);

        return $this->insertBatch($data);
    }

    // Get warna default berdasarkan jenis hari
    public function getWarnaBerdasarkanJenis($jenisHari)
    {
        // Simple caching within request
        static $warnaMap = null;

        if ($warnaMap === null) {
            $db = \Config\Database::connect();
            // Check if table exists to avoid error during initial migration if not run yet
            if ($db->tableExists('master_jenis_hari')) {
                $rows = $db->table('master_jenis_hari')->get()->getResultArray();
                foreach ($rows as $r) {
                    $warnaMap[$r['nama']] = $r['warna'];
                }
            } else {
                $warnaMap = []; // Fallback if table missing
            }
        }

        return $warnaMap[$jenisHari] ?? '#6c757d'; // Default Grey
    }

    // Get statistik kalender
    public function getStatistik($tahunAjaran, $semester, $bulan, $lembayaId)
    {
        $result = $this->selectCount('id', 'total')
            ->select('jenis_hari')
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('lembaga_id', $lembayaId)
            ->where('MONTH(tanggal)', $bulan)
            ->groupBy('jenis_hari')
            ->findAll();

        return $result;
    }
}
