<?php

namespace App\Models;

use CodeIgniter\Model;

class RombelModel extends Model
{
    protected $table = 'rombel';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_rombel',
        'nama_rombel',
        'tingkat',
        'jurusan',
        'wali_kelas',
        'ruangan_id',
        'nama_ruangan',
        'kurikulum',
        'jenis_rombel',
        'waktu_mengajar',
        'tahun_ajaran',
        'semester',
        'kapasitas',
        'is_active'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mengambil semua data rombel
     *
     * @return array
     */
    public function getRombel()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data rombel berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getRombelById($id)
    {
        return $this->find($id);
    }

    /**
     * Mengambil rombel berdasarkan tingkat
     *
     * @param string $tingkat
     * @return array
     */
    public function getRombelByTingkat($tingkat)
    {
        return $this->where('tingkat', $tingkat)->findAll();
    }

    /**
     * Mengambil rombel berdasarkan tahun ajaran
     *
     * @param string $tahunAjaran
     * @return array
     */
    public function getRombelByTahunAjaran($tahunAjaran)
    {
        return $this->where('tahun_ajaran', $tahunAjaran)->findAll();
    }

    /**
     * Menghitung jumlah siswa dalam rombel
     *
     * @param int $rombelId
     * @return int
     */
    public function countSiswaInRombel($rombelId)
    {
        $builder = $this->db->table('siswa');
        return $builder->where('rombel_id', $rombelId)
                      ->where('is_active', 1)
                      ->countAllResults();
    }

    /**
     * Mengecek apakah rombel masih memiliki kapasitas
     *
     * @param int $rombelId
     * @return bool
     */
    public function checkKapasitas($rombelId)
    {
        $rombel = $this->find($rombelId);
        if (!$rombel) {
            return false;
        }

        $totalSiswa = $this->countSiswaInRombel($rombelId);
        return $totalSiswa < $rombel['kapasitas'];
    }
    
    /**
     * Mengambil semua rombel aktif
     *
     * @return array
     */
    public function findAllActive()
    {
        return $this->where('is_active', 1)->findAll();
    }
}