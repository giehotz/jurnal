<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_kelas',
        'nama_kelas',
        'tingkat'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mengambil semua data kelas
     *
     * @return array
     */
    public function getKelas()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data kelas berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getKelasById($id)
    {
        return $this->find($id);
    }

    /**
     * Mengambil kelas berdasarkan tingkat
     *
     * @param string $tingkat
     * @return array
     */
    public function getKelasByTingkat($tingkat)
    {
        return $this->where('tingkat', $tingkat)->findAll();
    }
}