<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatKenaikanKelasModel extends Model
{
    protected $table            = 'riwayat_kenaikan_kelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'rombel_id_asal',
        'rombel_id_tujuan',
        'tahun_ajaran_asal',
        'tahun_ajaran_tujuan',
        'tanggal_proses',
        'user_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
