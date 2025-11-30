<?php

namespace App\Repositories;

use App\Models\RombelModel;

class RombelRepository
{
    protected $rombelModel;
    protected $db;

    public function __construct()
    {
        $this->rombelModel = new RombelModel();
        $this->db = \Config\Database::connect();
    }

    public function find($id)
    {
        return $this->rombelModel->find($id);
    }

    public function getKelasGuru($userId)
    {
        // Menampilkan semua kelas yang tersedia
        // Guru bisa mengisi absensi untuk kelas manapun
        $builder = $this->db->table('rombel r');
        $builder->select('r.id, r.kode_rombel, r.nama_rombel, r.tingkat');
        $builder->orderBy('r.kode_rombel', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    public function getKelasWali($userId)
    {
        return $this->rombelModel->where('wali_kelas', $userId)->first();
    }
}
