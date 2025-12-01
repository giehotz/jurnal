<?php

namespace App\Models;

use CodeIgniter\Model;

class QRGlobalSettingsModel extends Model
{
    protected $table            = 'qr_global_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'default_size',
        'default_color',
        'default_bg_color',
        'default_logo_path',
        'allow_custom_logo',
        'allow_custom_colors',
        'allow_custom_size',
        'max_file_size_kb',
        'allowed_mime_types',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active settings (returns the first row as there should only be one)
     * 
     * @return array
     */
    public function getActiveSettings()
    {
        $settings = $this->first();
        
        if (!$settings) {
            // Return defaults if no record exists (should not happen if migration ran)
            return [
                'default_size'        => 300,
                'default_color'       => '#000000',
                'default_bg_color'    => '#FFFFFF',
                'allow_custom_logo'   => 1,
                'allow_custom_colors' => 1,
                'allow_custom_size'   => 1,
                'max_file_size_kb'    => 2048,
                'allowed_mime_types'  => 'image/png,image/jpeg,image/gif',
            ];
        }
        
        return $settings;
    }
}
