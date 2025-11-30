<?php

namespace App\Models;

use CodeIgniter\Model;

class JurnalAsesmenModel extends Model
{
    protected $table        = 'jurnal_asesmen';
    protected $primaryKey   = 'id';
    protected $returnType   = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['jurnal_id', 'jenis_asesmen', 'hasil', 'siswa_tuntas', 'siswa_total'];

    /**
     * Mengambil data asesmen berdasarkan jurnal_id
     *
     * @param int $jurnalId
     * @return array
     */
    public function getAsesmenByJournalId($jurnalId)
    {
        return $this->where('jurnal_id', $jurnalId)->findAll();
    }
}