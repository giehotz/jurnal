<?php

namespace App\Repositories;

use App\Models\MataPelajaranModel;

class MataPelajaranRepository
{
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new MataPelajaranModel();
    }

    public function find($id)
    {
        return $this->mapelModel->find($id);
    }

    public function getAll()
    {
        return $this->mapelModel->orderBy('nama_mapel', 'ASC')->findAll();
    }
}
