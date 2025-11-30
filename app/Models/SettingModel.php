<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'school_name',
        'school_year',
        'semester',
        'headmaster_name',
        'headmaster_nip',
        'logo',
        'school_address',
        'school_level',
        'auto_route_enabled'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * Get all settings as key-value pairs
     *
     * @return array
     */
    public function getSettings()
    {
        $setting = $this->first();
        
        if ($setting) {
            return [
                'school_name' => $setting['school_name'],
                'school_year' => $setting['school_year'],
                'semester' => $setting['semester'],
                'headmaster_name' => $setting['headmaster_name'],
                'headmaster_nip' => $setting['headmaster_nip'],
                'logo' => $setting['logo'],
                'school_address' => $setting['school_address'],
                'school_level' => $setting['school_level'] ?? 'SMA/MA',
                'auto_route_enabled' => $setting['auto_route_enabled'] ?? 1
            ];
        }
        
        // If no settings exist, return empty array
        return [];
    }
    
    /**
     * Get a specific setting by key
     *
     * @param string $key
     * @return mixed
     */
    public function getSetting($key)
    {
        $setting = $this->first();
        return $setting[$key] ?? null;
    }
    
    /**
     * Update or create settings
     *
     * @param array $data
     * @return bool
     */
    public function updateSettings($data)
    {
        $setting = $this->first();
        
        if ($setting) {
            // Update existing settings
            return $this->update($setting['id'], $data);
        } else {
            // Create new settings
            return $this->insert($data);
        }
    }
}