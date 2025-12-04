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
                      m.nama_mapel, u.nama as nama_guru, a.tanggal, a.jurnal_id,
                      COUNT(DISTINCT s.id) as jumlah_siswa,
                      COUNT(DISTINCT CASE WHEN a.status = "hadir" THEN s.id END) as hadir,
                      COUNT(DISTINCT CASE WHEN a.status = "izin" THEN s.id END) as izin,
                      COUNT(DISTINCT CASE WHEN a.status = "sakit" THEN s.id END) as sakit,
                      COUNT(DISTINCT CASE WHEN a.status = "alfa" THEN s.id END) as alfa')
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

        $builder->groupBy('r.id, r.kode_rombel, r.nama_rombel, m.nama_mapel, u.nama, a.tanggal, a.jurnal_id')
            ->orderBy('a.tanggal', 'DESC')
            ->orderBy('r.kode_rombel', 'ASC')
            ->orderBy('m.nama_mapel', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getRekapHarianGuru($startDate, $endDate, $rombelId, $userId)
    {
        $builder = $this->db->table('absensi a')
            ->select('DATE(a.tanggal) as tanggal,
                      COUNT(DISTINCT CASE WHEN a.status = "hadir" THEN a.siswa_id END) as hadir,
                      COUNT(DISTINCT CASE WHEN a.status = "izin" THEN a.siswa_id END) as izin,
                      COUNT(DISTINCT CASE WHEN a.status = "sakit" THEN a.siswa_id END) as sakit,
                      COUNT(DISTINCT CASE WHEN a.status = "alfa" THEN a.siswa_id END) as alfa')
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
