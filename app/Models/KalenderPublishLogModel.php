<?php

namespace App\Models;

use CodeIgniter\Model;

class KalenderPublishLogModel extends Model
{
    protected $table = 'kalender_publish_log';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'tahun_ajaran',
        'semester',
        'published_by',
        'published_at',
        'status',
        'lembaga_id'
    ];
    // No timestamps handling automatically for published_at unless we map it 
    // but usually 'created_at' is used. Here 'published_at' is specific.
    protected $useTimestamps = false;

    // We can use events or just set it manually on insert.
}
