<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table            = 'mata_pelajaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_mapel',
        'nama_mapel',
        'fase',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'kode_mapel' => 'required|is_unique[mata_pelajaran.kode_mapel,id,{id}]',
        'nama_mapel' => 'required',
        'fase'       => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get all subjects
     */
    public function getSubjects()
    {
        return $this->findAll();
    }

    /**
     * Get subject by ID
     */
    public function getSubjectById($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Get subjects by phase
     */
    public function getSubjectsByPhase($phase)
    {
        return $this->where('fase', $phase)->findAll();
    }
}