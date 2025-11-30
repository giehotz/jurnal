<?php

namespace App\Models;

use CodeIgniter\Model;

class MataPelajaranModel extends Model
{
    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_mapel', 'nama_mapel'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mengambil semua data mata pelajaran
     *
     * @return array
     */
    public function getSubjects()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data mata pelajaran berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getSubjectById($id)
    {
        return $this->find($id);
    }

    /**
     * Mengambil data mata pelajaran berdasarkan kode_mapel
     *
     * @param string $kodeMapel
     * @return array|null
     */
    public function getSubjectByKode($kodeMapel)
    {
        return $this->where('kode_mapel', $kodeMapel)->first();
    }
}