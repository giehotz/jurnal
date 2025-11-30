<?php

namespace App\Repositories;

use App\Models\JurnalModel;

class JurnalRepository
{
    protected $jurnalModel;

    public function __construct()
    {
        $this->jurnalModel = new JurnalModel();
    }

    public function find($id)
    {
        return $this->jurnalModel->find($id);
    }

    public function isOwnedBy($jurnalId, $userId)
    {
        $jurnal = $this->find($jurnalId);
        return $jurnal && $jurnal['user_id'] == $userId;
    }

    public function insert($data)
    {
        return $this->jurnalModel->insert($data);
    }

    public function update($id, $data)
    {
        return $this->jurnalModel->update($id, $data);
    }
}
