<?php

namespace App\Models;

use CodeIgniter\Model;

class UrlModel extends Model
{
    protected $table            = 'url_entries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'original_url', 'short_slug', 'custom_name', 'click_count', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getUrlBySlug($slug)
    {
        return $this->where('short_slug', $slug)->first();
    }
}
