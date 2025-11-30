<?php

namespace App\Repositories;

use App\Models\SiswaModel;

class SiswaRepository
{
    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    public function find($id)
    {
        return $this->siswaModel->find($id);
    }

    public function getSiswaByRombelId($rombelId)
    {
        $siswa = $this->siswaModel->where('rombel_id', $rombelId)
                                  ->where('is_active', 1)
                                  ->orderBy('nama', 'ASC')
                                  ->findAll();

        $result = [];
        foreach ($siswa as $s) {
            $result[] = [
                'siswa_id' => $s['id'],
                'siswa_nis' => $s['nis'],
                'siswa_nama' => $s['nama']
            ];
        }

        return $result;
    }
}
