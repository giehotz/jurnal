<?php

namespace App\Models;

use CodeIgniter\Model;

class QRModel extends Model
{
    protected $table            = 'qr_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['url_id', 'qr_color', 'bg_color', 'size', 'logo_path', 'frame_style', 'error_correction', 'version', 'created_at', 'show_label'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; 

    public function getSettings($url_id)
    {
        return $this->where('url_id', $url_id)->first();
    }
}
