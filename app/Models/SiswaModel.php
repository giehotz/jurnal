<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'rombel_id',
        'password',
        'is_active',
        'foto'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mengambil semua data siswa
     *
     * @return array
     */
    public function getSiswa()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data siswa berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getSiswaById($id)
    {
        return $this->find($id);
    }

    /**
     * Mengambil siswa berdasarkan rombel
     *
     * @param int $rombelId
     * @return array
     */
    public function getSiswaByRombel($rombelId)
    {
        return $this->where('rombel_id', $rombelId)
                   ->where('is_active', 1)
                   ->findAll();
    }

    /**
     * Mengambil siswa aktif berdasarkan tahun ajaran
     *
     * @param string $tahunAjaran
     * @return array
     */
    public function getSiswaAktif($tahunAjaran)
    {
        $builder = $this->db->table('siswa s');
        $builder->select('s.*, r.nama_rombel, r.kode_rombel');
        $builder->join('rombel_siswa rs', 's.id = rs.siswa_id');
        $builder->join('rombel r', 'rs.rombel_id = r.id');
        $builder->where('s.is_active', 1);
        $builder->where('r.tahun_ajaran', $tahunAjaran);
        $builder->orderBy('r.nama_rombel, s.nama');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Mengambil siswa dengan informasi rombel
     *
     * @return array
     */
    public function getSiswaWithRombel()
    {
        $builder = $this->db->table('siswa s');
        $builder->select('s.*, r.nama_rombel, r.kode_rombel, r.tingkat');
        $builder->join('rombel_siswa rs', 's.id = rs.siswa_id');
        $builder->join('rombel r', 'rs.rombel_id = r.id');
        $builder->where('s.is_active', 1);
        $builder->orderBy('r.tingkat, r.nama_rombel, s.nama');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Mencari siswa berdasarkan nama, NIS, atau NISN
     *
     * @param string $keyword
     * @return array
     */
    public function searchSiswa($keyword)
    {
        $builder = $this->db->table('siswa s');
        $builder->select('s.*, r.nama_rombel, r.kode_rombel, r.tingkat');
        $builder->join('rombel_siswa rs', 's.id = rs.siswa_id');
        $builder->join('rombel r', 'rs.rombel_id = r.id');
        $builder->where('s.is_active', 1);
        $builder->groupStart()
                ->like('s.nama', $keyword)
                ->orLike('s.nis', $keyword)
                ->orLike('s.nisn', $keyword)
                ->groupEnd();
        $builder->orderBy('r.tingkat, r.nama_rombel, s.nama');
        
        return $builder->get()->getResultArray();
    }
}