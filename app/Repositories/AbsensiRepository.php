<?php

namespace App\Repositories;

use App\Models\AbsensiModel;

class AbsensiRepository
{
    protected $absensiModel;
    protected $db;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->db = \Config\Database::connect();
    }

    public function find($id)
    {
        return $this->absensiModel->find($id);
    }

    public function update($id, $data)
    {
        return $this->absensiModel->update($id, $data);
    }

    public function insertBatch($data)
    {
        return $this->absensiModel->insertBatch($data);
    }

    public function insert($data)
    {
        return $this->absensiModel->insert($data);
    }

    public function getAbsensiByJurnal($jurnalId)
    {
        return $this->absensiModel->getAbsensiByJurnal($jurnalId);
    }

    public function getRekapKelasGuru($startDate, $endDate, $rombelId, $userId)
    {
        $builder = $this->db->table('absensi a')
            ->select('r.id as rombel_id, r.kode_rombel, r.nama_rombel, 
                      m.nama_mapel, u.nama as nama_guru,
                      COUNT(DISTINCT s.id) as jumlah_siswa,
                      SUM(CASE WHEN a.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                      SUM(CASE WHEN a.status = "izin" THEN 1 ELSE 0 END) as izin,
                      SUM(CASE WHEN a.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                      SUM(CASE WHEN a.status = "alfa" THEN 1 ELSE 0 END) as alfa')
            ->join('siswa s', 'a.siswa_id = s.id')
            ->join('rombel r', 'a.rombel_id = r.id')
            ->join('mata_pelajaran m', 'a.mapel_id = m.id', 'left')
            ->join('users u', 'a.guru_id = u.id', 'left')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate);

        // Check if user is Wali Kelas
        $rombelModel = new \App\Models\RombelModel();
        $isWaliKelas = false;
        
        if ($rombelId) {
            $rombel = $rombelModel->find($rombelId);
            if ($rombel && $rombel['wali_kelas'] == $userId) {
                $isWaliKelas = true;
            }
        } else {
            // If no specific rombel selected, we need to check if user is wali kelas of ANY rombel
            // But the query groups by rombel, so we might need a more complex condition or just filter by guru_id if not wali kelas
            // For simplicity, if no rombel selected, we only show what they taught OR if they are wali kelas of that specific row's rombel
            // This is hard to do in a single simple query without subqueries or complex ORs.
            // Let's stick to: If specific rombel selected AND user is wali kelas => show all.
            // If no rombel selected => show only where guru_id = userId (standard teacher view)
            // OR we can try to be smarter:
            // WHERE a.guru_id = $userId OR r.wali_kelas = $userId
        }

        if ($rombelId) {
            $builder->where('r.id', $rombelId);
            if (!$isWaliKelas) {
                $builder->where('a.guru_id', $userId);
            }
        } else {
             $builder->groupStart()
                    ->where('a.guru_id', $userId)
                    ->orWhere('r.wali_kelas', $userId)
                    ->groupEnd();
        }

        $builder->groupBy('r.id, r.kode_rombel, r.nama_rombel, m.nama_mapel, u.nama')
            ->orderBy('r.kode_rombel', 'ASC')
            ->orderBy('m.nama_mapel', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getRekapHarianGuru($startDate, $endDate, $rombelId, $userId)
    {
        $builder = $this->db->table('absensi a')
            ->select('DATE(a.tanggal) as tanggal,
                      SUM(CASE WHEN a.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                      SUM(CASE WHEN a.status = "izin" THEN 1 ELSE 0 END) as izin,
                      SUM(CASE WHEN a.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                      SUM(CASE WHEN a.status = "alfa" THEN 1 ELSE 0 END) as alfa')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate);

        // Check if user is Wali Kelas
        $isWaliKelas = false;
        if ($rombelId) {
            $rombelModel = new \App\Models\RombelModel();
            $rombel = $rombelModel->find($rombelId);
            if ($rombel && $rombel['wali_kelas'] == $userId) {
                $isWaliKelas = true;
            }
        }

        if ($rombelId) {
            $builder->where('a.rombel_id', $rombelId);
            if (!$isWaliKelas) {
                $builder->where('a.guru_id', $userId);
            }
        } else {
            $builder->where('a.guru_id', $userId);
        }

        $builder->groupBy('DATE(a.tanggal)')
            ->orderBy('DATE(a.tanggal)', 'ASC');

        return $builder->get()->getResultArray();
    }

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
        
        $builder->where('a.rombel_id', $rombelId);
        
        if ($startDate && $endDate) {
            $builder->where('a.tanggal >=', $startDate);
            $builder->where('a.tanggal <=', $endDate);
        }

        if ($userId) {
            $builder->where('a.guru_id', $userId);
        }
        
        $builder->orderBy('a.tanggal DESC, s.nama ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getDataExport($startDate, $endDate, $rombelId, $userId)
    {
        $builder = $this->db->table('absensi a')
            ->select('a.*, s.nama as nama_siswa, s.nis, s.nisn, r.nama_rombel, r.kode_rombel')
            ->join('siswa s', 'a.siswa_id = s.id')
            ->join('rombel r', 'a.rombel_id = r.id')
            ->where('a.tanggal >=', $startDate)
            ->where('a.tanggal <=', $endDate);

        // Check if user is Wali Kelas
        $isWaliKelas = false;
        if ($rombelId) {
            $rombelModel = new \App\Models\RombelModel();
            $rombel = $rombelModel->find($rombelId);
            if ($rombel && $rombel['wali_kelas'] == $userId) {
                $isWaliKelas = true;
            }
        }

        if ($rombelId) {
            $builder->where('a.rombel_id', $rombelId);
            if (!$isWaliKelas) {
                $builder->where('a.guru_id', $userId);
            }
        } else {
            $builder->where('a.guru_id', $userId);
        }

        $builder->orderBy('r.kode_rombel', 'ASC')
                ->orderBy('s.nama', 'ASC')
                ->orderBy('a.tanggal', 'ASC');

        return $builder->get()->getResultArray();
    }
}
