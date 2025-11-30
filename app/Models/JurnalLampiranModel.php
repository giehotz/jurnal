<?php

namespace App\Models;

use CodeIgniter\Model;

class JurnalLampiranModel extends Model
{
    protected $table            = 'jurnal_lampiran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'jurnal_id',
        'nama_file',
        'file_path',
        'tipe_file',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'jurnal_id'  => 'required|integer',
        'nama_file'  => 'required|max_length[255]',
        'file_path'  => 'required|max_length[500]',
        'tipe_file'  => 'required|max_length[50]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get attachments by jurnal ID
     */
    public function getAttachmentsByJurnalId($jurnalId)
    {
        return $this->where('jurnal_id', $jurnalId)->findAll();
    }

    /**
     * Get attachment by ID
     */
    public function getAttachmentById($id)
    {
        return $this->where('id', $id)->first();
    }
}