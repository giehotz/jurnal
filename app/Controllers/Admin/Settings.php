<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\MataPelajaranModel;
use App\Models\SettingModel;
use App\Models\KepalaSekolahModel;

class Settings extends BaseController
{
    protected $userModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $settingModel;
    protected $kepalaSekolahModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MataPelajaranModel();
        $this->settingModel = new SettingModel();
        $this->kepalaSekolahModel = new KepalaSekolahModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Get current user data
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Get settings from database
        $settings = $this->settingModel->getSettings();
        
        // If no settings exist in database, use default values
        if (empty($settings)) {
            $settings = [
                'school_name' => 'SMAN 1 Contoh',
                'school_year' => date('Y') . '/' . (date('Y') + 1),
                'semester' => 'ganjil',
                'headmaster_name' => 'Drs. Kepala Sekolah, M.Pd',
                'headmaster_nip' => '',
                'logo' => '',
                'school_address' => '',
                'school_level' => 'SMA/MA'
            ];
        }

        // Ambil data untuk pengaturan
        $data = [
            'title' => 'Pengaturan Sistem',
            'active' => 'settings',
            'user' => $user,
            'settings' => $settings,
            'total_guru' => $this->userModel->where('role', 'guru')->countAllResults(),
            'total_kelas' => $this->kelasModel->countAllResults(),
            'total_mapel' => $this->mapelModel->countAllResults(),
            'guru_aktif' => $this->userModel->where('role', 'guru')->where('is_active', 1)->countAllResults(),
            'admin_users' => $this->userModel->where('role', 'admin')->orwhere('role', 'super_admin')->findAll(),
        ];

        return view('admin/settings/index', $data);
    }
    
    public function settingApps()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Get settings from database
        $settings = $this->settingModel->getSettings();
        
        // Generate list of school years for the next 5 years
        $currentYear = date('Y');
        $schoolYears = [];
        for ($i = -2; $i <= 3; $i++) {
            $startYear = $currentYear + $i;
            $endYear = $startYear + 1;
            $schoolYears[] = $startYear . '/' . $endYear;
        }
        
        // If no settings exist in database, use default values
        if (empty($settings)) {
            $settings = [
                'school_name' => 'SMAN 1 Contoh',
                'school_year' => date('Y') . '/' . (date('Y') + 1),
                'semester' => 'ganjil',
                'headmaster_name' => 'Drs. Kepala Sekolah, M.Pd',
                'headmaster_nip' => '',
                'logo' => '',
                'school_address' => '',
                'school_level' => 'SMA/MA'
            ];
        }
        
        // Tentukan tingkat kelas berdasarkan jenis sekolah
        $schoolLevels = [
            'SD/MI' => [1, 2, 3, 4, 5, 6],
            'SMP/MTs' => [7, 8, 9],
            'SMA/MA' => [10, 11, 12]
        ];
        
        $currentLevel = $settings['school_level'] ?? 'SMA/MA';
        $classLevels = $schoolLevels[$currentLevel] ?? [10, 11, 12];

        // Data untuk view
        $data = [
            'title' => 'Pengaturan Aplikasi',
            'active' => 'settings',
            'settings' => $settings,
            'school_years' => $schoolYears,
            'school_levels' => $schoolLevels,
            'class_levels' => $classLevels
        ];

        return view('admin/settings/settingapps', $data);
    }
    
    public function results()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Get settings from database
        $settings = $this->settingModel->getSettings();
        
        // If no settings exist in database, use default values
        if (empty($settings)) {
            $settings = [
                'school_name' => 'SMAN 1 Contoh',
                'school_year' => date('Y') . '/' . (date('Y') + 1),
                'semester' => 'ganjil',
                'headmaster_name' => 'Drs. Kepala Sekolah, M.Pd',
                'headmaster_nip' => '',
                'logo' => '',
                'school_address' => '',
                'school_level' => 'SMA/MA'
            ];
        }

        // Data untuk view
        $data = [
            'title' => 'Hasil Pengaturan Aplikasi',
            'active' => 'settings',
            'settings' => $settings
        ];

        return view('admin/settings/results', $data);
    }
    
    public function save()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }
        
        // Get the input data
        $data = [
            'school_name' => $this->request->getPost('school_name'),
            'school_year' => $this->request->getPost('school_year'),
            'semester' => $this->request->getPost('semester'),
            'headmaster_name' => $this->request->getPost('headmaster_name'),
            'headmaster_nip' => $this->request->getPost('headmaster_nip'),
            'school_address' => $this->request->getPost('school_address'),
            'school_level' => $this->request->getPost('school_level')
        ];
        
        // Handle logo upload if provided
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Generate a random name for the file
            $newName = $logo->getRandomName();
            // Move the file to the public/uploads/logos directory
            $logo->move(ROOTPATH . 'public/uploads/logos', $newName);
            // Add the logo filename to the data
            $data['logo'] = $newName;
        }
        
        // Save settings to database
        if ($this->settingModel->updateSettings($data)) {
            
            // Sync headmaster data to kepala_sekolah table
            $headmasterName = $data['headmaster_name'];
            $headmasterNip = $data['headmaster_nip'];
            
            if (!empty($headmasterName)) {
                // Check if there's an existing record (assuming single active headmaster or just updating the latest)
                // For simplicity and based on requirements, we'll try to find by NIP if exists, or just take the first one
                
                $existingHeadmaster = null;
                if (!empty($headmasterNip)) {
                    $existingHeadmaster = $this->kepalaSekolahModel->where('nip', $headmasterNip)->first();
                }
                
                if (!$existingHeadmaster) {
                    // If not found by NIP, maybe just get the first record? 
                    // Or should we always create new if NIP is different?
                    // Let's assume we want to keep a history or list. 
                    // BUT, the user request implies "update data", so maybe 1-to-1 sync.
                    // Let's try to update the first record if it exists, otherwise insert.
                    $existingHeadmaster = $this->kepalaSekolahModel->first();
                }
                
                $headmasterData = [
                    'nama' => $headmasterName,
                    'nip' => $headmasterNip
                ];
                
                if ($existingHeadmaster) {
                    $this->kepalaSekolahModel->update($existingHeadmaster['id'], $headmasterData);
                } else {
                    $this->kepalaSekolahModel->insert($headmasterData);
                }
            }
            
            return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil disimpan');
        } else {
            return redirect()->to('/admin/settings')->with('error', 'Gagal menyimpan pengaturan');
        }
    }
    
    public function populateDefaultSettings()
    {
        // Only allow this in development environment
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/admin/settings');
        }
        
        $defaultSettings = [
            'school_name' => 'SMAN 1 Contoh',
            'school_year' => date('Y') . '/' . (date('Y') + 1),
            'semester' => 'ganjil',
            'headmaster_name' => 'Drs. Kepala Sekolah, M.Pd',
            'headmaster_nip' => '',
            'logo' => '',
            'school_address' => 'Jl. Contoh Alamat Sekolah No. 123',
            'school_level' => 'SMA/MA'
        ];
        
        // Save default settings to database
        if ($this->settingModel->updateSettings($defaultSettings)) {
            return redirect()->to('/admin/settings/results')->with('success', 'Pengaturan default berhasil disimpan');
        } else {
            return redirect()->to('/admin/settings/results')->with('error', 'Gagal menyimpan pengaturan default');
        }
    }
    
    public function updateSettingsDirectly()
    {
        // Only allow this in development environment
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/admin/settings');
        }
        
        // Directly update the settings in the database
        $db = \Config\Database::connect();
        $builder = $db->table('settings');
        
        $defaultSettings = [
            'school_name' => 'SMAN 1 Contoh',
            'school_year' => date('Y') . '/' . (date('Y') + 1),
            'semester' => 'ganjil',
            'headmaster_name' => 'Drs. Kepala Sekolah, M.Pd',
            'headmaster_nip' => '',
            'school_address' => 'Jl. Contoh Alamat Sekolah No. 123',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $result = $builder->where('id', 1)->update($defaultSettings);
        
        if ($result) {
            return redirect()->to('/admin/settings/results')->with('success', 'Pengaturan berhasil diperbarui langsung');
        } else {
            return redirect()->to('/admin/settings/results')->with('error', 'Gagal memperbarui pengaturan');
        }
    }
}