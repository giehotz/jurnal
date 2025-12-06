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
    public function maintenance()
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/settings/maintenance/index', [
            'title' => 'Maintenance System',
            'active' => 'maintenance'
        ]);
    }

    public function backupDatabase()
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        if ($this->request->getMethod() === 'post') {
            try {
                $name = $this->request->getPost('backup_name');
                if (empty($name)) {
                    $name = 'backup_' . date('Y-m-d_H-i-s');
                }
                // Sanitize name
                $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);

                $filename = $name . '.sql';
                $path = WRITEPATH . 'database/backups/';

                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }

                $filepath = $path . $filename;

                // Generate Backup Content
                $sqlContent = $this->generateSqlBackup();

                if (file_put_contents($filepath, $sqlContent)) {
                    return redirect()->back()->with('success', 'Database berhasil dibackup: ' . $filename);
                } else {
                    return redirect()->back()->with('error', 'Gagal menulis file backup.');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        return view('admin/settings/maintenance/backupdatabase', [
            'title' => 'Backup Database',
            'active' => 'maintenance'
        ]);
    }

    public function restoreDatabase()
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // List Backup Files
        $backupPath = WRITEPATH . 'database/backups/';
        $backupFiles = [];
        if (is_dir($backupPath)) {
            $files = scandir($backupPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $backupFiles[] = [
                        'name' => $file,
                        'size' => round(filesize($backupPath . $file) / 1024, 2),
                        'modified' => date('Y-m-d H:i:s', filemtime($backupPath . $file))
                    ];
                }
            }
        }

        if ($this->request->getMethod() === 'post') {
            // Placeholder for Restore Logic
            return redirect()->back()->with('success', 'Restore functionality is not yet fully implemented in this demo.');
        }

        return view('admin/settings/maintenance/restoredatabase', [
            'title' => 'Restore Database',
            'active' => 'maintenance',
            'backup_files' => $backupFiles
        ]);
    }

    public function hapusCache()
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        if ($this->request->getMethod() === 'post') {
            $cache = \Config\Services::cache();

            if ($this->request->getPost('hapus_semua')) {
                $cache->clean();
            } else {
                if ($this->request->getPost('hapus_cache_db')) {
                    $cache->clean();
                }
            }

            $cache->clean();
            return redirect()->back()->with('success', 'Cache berhasil dibersihkan.');
        }

        return view('admin/settings/maintenance/hapuscache', [
            'title' => 'Hapus Cache',
            'active' => 'maintenance'
        ]);
    }
    private function generateSqlBackup()
    {
        $db = \Config\Database::connect();
        $tables = $db->listTables();

        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Get Create Table
            $query = $db->query("SHOW CREATE TABLE `$table`");
            $row = $query->getRowArray();
            $createTable = $row['Create Table'] ?? $row['Create View'] ?? '';

            $sql .= "-- Table structure for table `$table`\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $createTable . ";\n\n";

            // Get Data
            $query = $db->query("SELECT * FROM `$table`");
            $rows = $query->getResultArray();

            if (!empty($rows)) {
                $sql .= "-- Dumping data for table `$table`\n";
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = "NULL";
                        } else {
                            $values[] = "'" . $db->escapeString($value) . "'";
                        }
                    }
                    $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $sql;
    }

    public function downloadBackup($filename)
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $path = WRITEPATH . 'database/backups/' . urldecode($filename);
        if (file_exists($path)) {
            return $this->response->download($path, null);
        } else {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }
    }

    public function deleteBackup($filename)
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $path = WRITEPATH . 'database/backups/' . urldecode($filename);
        if (file_exists($path)) {
            unlink($path);
            return redirect()->back()->with('success', 'File backup berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }
    }

    public function confirmRestoreBackup($filename)
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $path = WRITEPATH . 'database/backups/' . urldecode($filename);
        if (file_exists($path)) {
            // Read file content
            $sql = file_get_contents($path);

            // Execute SQL
            $db = \Config\Database::connect();

            // Split SQL into individual queries (basic splitting by semicolon at end of line)
            // Note: This is a simplistic parsing and might fail on complex stored procedures or strings containing semicolons.
            // For robust restore, shell_exec('mysql ...') is better but requires env config.
            // We'll try transaction if possible or just run queries.

            try {
                // Disable foreign key checks for restore
                $db->query("SET FOREIGN_KEY_CHECKS=0");

                $lines = explode(";\n", $sql);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line) && strpos($line, '--') !== 0) {
                        $db->query($line);
                    }
                }

                $db->query("SET FOREIGN_KEY_CHECKS=1");
                return redirect()->to('admin/settings/maintenance/restoredatabase')->with('success', 'Database berhasil direstore dari file: ' . $filename);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal restore database: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }
    }
}
