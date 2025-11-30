<?php

namespace App\Models;

use CodeIgniter\Model;

class JurnalP5Model extends Model
{
    protected $table = 'jurnal_p5';
    protected $primaryKey = 'id';
    protected $allowedFields = ['jurnal_id', 'dimensi', 'aktivitas'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    /**
     * Mengambil data P5 berdasarkan jurnal_id
     *
     * @param int $jurnalId
     * @return array
     */
    public function getP5ByJournalId(int $jurnalId): array
    {
        return $this->where('jurnal_id', $jurnalId)->findAll();
    }
}