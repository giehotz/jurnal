<?php

namespace App\Models;

use CodeIgniter\Model;

class KepalaSekolahModel extends Model
{
    protected $table = 'kepala_sekolah';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'nip',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
