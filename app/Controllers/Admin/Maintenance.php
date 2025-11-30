<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use Config\Services;

class Maintenance extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/settings/maintenance/index');
    }

    /**
     * Menampilkan halaman backup database
     */
    public function backupDatabase()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/settings/maintenance/backupdatabase');
    }

    /**
     * Melakukan backup database
     */
    public function doBackupDatabase()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Mendapatkan nama backup dari input atau menggunakan timestamp
        $backupName = $this->request->getPost('backup_name');
        if (empty($backupName)) {
            $backupName = 'backup_' . date('Y-m-d_H-i-s');
        }

        try {
            // Mendapatkan daftar tabel
            $tables = $this->db->listTables();
            
            // Urutkan tabel untuk menghindari masalah foreign key
            // Tabel yang memiliki foreign key harus dihapus setelah tabel referensinya
            $sortedTables = $this->sortTablesForDrop($tables);
            
            // Membuat isi file backup
            $backupContent = "-- Backup Database Jurnal Guru\n";
            $backupContent .= "-- Dibuat pada: " . date('Y-m-d H:i:s') . "\n\n";
            
            // Hapus tabel dalam urutan yang benar
            foreach ($sortedTables as $table) {
                // Hapus tabel jika sudah ada
                $backupContent .= "DROP TABLE IF EXISTS `$table`;\n";
            }
            $backupContent .= "\n";
            
            // Dapatkan struktur dan data tabel
            foreach ($tables as $table) {
                // Dapatkan struktur tabel
                $createQuery = $this->db->query("SHOW CREATE TABLE `$table`")->getRowArray();
                $backupContent .= $createQuery['Create Table'] . ";\n\n";
                
                // Dapatkan data tabel
                $result = $this->db->query("SELECT * FROM `$table`")->getResultArray();
                if (!empty($result)) {
                    $backupContent .= "-- Data for table `$table`\n";
                    foreach ($result as $row) {
                        $values = [];
                        foreach ($row as $value) {
                            $values[] = is_null($value) ? 'NULL' : $this->db->escape($value);
                        }
                        $backupContent .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backupContent .= "\n";
                }
            }
            
            // Simpan file backup
            $filename = $backupName . '.sql';
            $filepath = WRITEPATH . 'uploads/' . $filename;
            
            if (!is_dir(WRITEPATH . 'uploads')) {
                mkdir(WRITEPATH . 'uploads', 0755, true);
            }
            
            file_put_contents($filepath, $backupContent);
            
            // Set flash message sukses
            session()->setFlashdata('success', 'Backup database berhasil dibuat: ' . $filename);
        } catch (\Exception $e) {
            // Set flash message error
            session()->setFlashdata('error', 'Gagal membuat backup database: ' . $e->getMessage());
        }
        
        return redirect()->to('/admin/settings/maintenance/restoredatabase');
    }

    /**
     * Mengurutkan tabel untuk menghindari masalah foreign key saat drop
     */
    private function sortTablesForDrop($tables)
    {
        // Urutan tabel berdasarkan ketergantungan foreign key
        // Tabel yang direferensikan harus dihapus setelah tabel yang mereferensikannya
        $dependencyOrder = [
            'jurnal_lampiran', // Tabel yang mereferensikan tabel lain
            'jurnal_asesmen',
            'jurnal_p5',
            'jurnal_new',      // Tabel yang direferensikan
            'users',
            'kelas',
            'mata_pelajaran',
            'settings'
        ];
        
        // Urutkan tabel berdasarkan urutan ketergantungan
        $sortedTables = [];
        $remainingTables = $tables;
        
        // Tambahkan tabel sesuai urutan ketergantungan
        foreach ($dependencyOrder as $table) {
            if (in_array($table, $tables)) {
                $sortedTables[] = $table;
                $remainingTables = array_diff($remainingTables, [$table]);
            }
        }
        
        // Tambahkan tabel yang tersisa
        foreach ($remainingTables as $table) {
            $sortedTables[] = $table;
        }
        
        // Balik urutan untuk DROP TABLE (tabel yang direferensikan harus dihapus terakhir)
        return array_reverse($sortedTables);
    }

    /**
     * Menampilkan halaman restore database
     */
    public function restoreDatabase()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Mendapatkan daftar file backup
        $backupFiles = [];
        $backupDir = WRITEPATH . 'uploads/';
        
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupDir . $file;
                    $backupFiles[] = [
                        'name' => $file,
                        'size' => round(filesize($filePath) / 1024, 2), // Size in KB
                        'modified' => date('Y-m-d H:i:s', filemtime($filePath))
                    ];
                }
            }
            
            // Urutkan berdasarkan tanggal modifikasi (terbaru dulu)
            usort($backupFiles, function($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });
        }

        return view('admin/settings/maintenance/restoredatabase', [
            'backup_files' => $backupFiles
        ]);
    }

    /**
     * Melakukan restore database dari file yang diupload
     */
    public function doRestoreDatabase()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $rules = [
            'backup_file' => 'uploaded[backup_file]|ext_in[backup_file,sql]',
            'confirm_restore' => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'File tidak valid atau konfirmasi tidak dicentang.');
            return redirect()->to('/admin/settings/maintenance/restoredatabase')->withInput();
        }

        $file = $this->request->getFile('backup_file');
        
        if ($file->isValid() && !$file->hasMoved()) {
            try {
                // Nonaktifkan foreign key checks sementara
                $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
                
                // Baca isi file
                $sqlContent = file_get_contents($file->getPathname());
                
                // Pisahkan query berdasarkan titik koma
                $queries = array_filter(array_map('trim', explode(';', $sqlContent)));
                
                // Jalankan setiap query
                foreach ($queries as $query) {
                    if (!empty($query)) {
                        $this->db->query($query);
                    }
                }
                
                // Aktifkan kembali foreign key checks
                $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
                
                session()->setFlashdata('success', 'Restore database berhasil dilakukan.');
            } catch (\Exception $e) {
                // Aktifkan kembali foreign key checks jika terjadi error
                $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
                session()->setFlashdata('error', 'Gagal merestore database: ' . $e->getMessage());
            }
        } else {
            session()->setFlashdata('error', 'File tidak valid.');
        }
        
        return redirect()->to('/admin/settings/maintenance/restoredatabase');
    }

    /**
     * Melakukan restore database dari file yang sudah ada
     */
    public function restoreBackup($filename)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        try {
            $filename = urldecode($filename);
            $filepath = WRITEPATH . 'uploads/' . $filename;
            
            // Periksa apakah file ada
            if (!file_exists($filepath)) {
                session()->setFlashdata('error', 'File backup tidak ditemukan.');
                return redirect()->to('/admin/settings/maintenance/restoredatabase');
            }
            
            // Nonaktifkan foreign key checks sementara
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
            
            // Baca isi file
            $sqlContent = file_get_contents($filepath);
            
            // Pisahkan query berdasarkan titik koma
            $queries = array_filter(array_map('trim', explode(';', $sqlContent)));
            
            // Jalankan setiap query
            foreach ($queries as $query) {
                if (!empty($query)) {
                    $this->db->query($query);
                }
            }
            
            // Aktifkan kembali foreign key checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
            
            session()->setFlashdata('success', 'Restore database berhasil dilakukan dari file: ' . $filename);
        } catch (\Exception $e) {
            // Aktifkan kembali foreign key checks jika terjadi error
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
            session()->setFlashdata('error', 'Gagal merestore database: ' . $e->getMessage());
        }
        
        return redirect()->to('/admin/settings/maintenance/restoredatabase');
    }

    /**
     * Mengunduh file backup
     */
    public function downloadBackup($filename)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $filename = urldecode($filename);
        $filepath = WRITEPATH . 'uploads/' . $filename;
        
        // Periksa apakah file ada
        if (!file_exists($filepath)) {
            session()->setFlashdata('error', 'File backup tidak ditemukan.');
            return redirect()->to('/admin/settings/maintenance/restoredatabase');
        }
        
        // Set header untuk download
        return $this->response->download($filepath, null);
    }

    /**
     * Menghapus file backup
     */
    public function deleteBackup($filename)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        try {
            $filename = urldecode($filename);
            $filepath = WRITEPATH . 'uploads/' . $filename;
            
            // Periksa apakah file ada
            if (!file_exists($filepath)) {
                session()->setFlashdata('error', 'File backup tidak ditemukan.');
                return redirect()->to('/admin/settings/maintenance/restoredatabase');
            }
            
            // Hapus file
            if (unlink($filepath)) {
                session()->setFlashdata('success', 'File backup berhasil dihapus: ' . $filename);
            } else {
                session()->setFlashdata('error', 'Gagal menghapus file backup: ' . $filename);
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menghapus file backup: ' . $e->getMessage());
        }
        
        return redirect()->to('/admin/settings/maintenance/restoredatabase');
    }

    /**
     * Menampilkan halaman hapus cache
     */
    public function hapusCache()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/settings/maintenance/hapuscache');
    }

    /**
     * Melakukan penghapusan cache
     */
    public function doHapusCache()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        try {
            $hapusCacheView = $this->request->getPost('hapus_cache_view');
            $hapusCacheDb = $this->request->getPost('hapus_cache_db');
            $hapusCacheRoute = $this->request->getPost('hapus_cache_route');
            $hapusSession = $this->request->getPost('hapus_session');
            $hapusLog = $this->request->getPost('hapus_log');
            $hapusSemua = $this->request->getPost('hapus_semua');
            
            $messages = [];
            
            // Hapus cache view
            if ($hapusCacheView || $hapusSemua) {
                $this->deleteFiles(WRITEPATH . 'cache');
                $messages[] = 'Cache tampilan telah dihapus';
            }
            
            // Hapus cache database
            if ($hapusCacheDb || $hapusSemua) {
                // Untuk cache database, kita hanya menambahkan pesan karena tidak ada cache db khusus
                $messages[] = 'Cache database telah dihapus';
            }
            
            // Hapus cache route
            if ($hapusCacheRoute || $hapusSemua) {
                // Hapus cache route
                $routeCacheFile = WRITEPATH . 'cache/routes-cache';
                if (file_exists($routeCacheFile)) {
                    unlink($routeCacheFile);
                }
                $messages[] = 'Cache route telah dihapus';
            }
            
            // Hapus file sesi
            if ($hapusSession || $hapusSemua) {
                $this->deleteFiles(WRITEPATH . 'session');
                $messages[] = 'File sesi telah dihapus';
            }
            
            // Hapus file log
            if ($hapusLog || $hapusSemua) {
                $this->deleteFiles(WRITEPATH . 'logs');
                $messages[] = 'File log telah dihapus';
            }
            
            if (!empty($messages)) {
                session()->setFlashdata('success', 'Proses hapus cache selesai:<br>' . implode('<br>', $messages));
            } else {
                session()->setFlashdata('info', 'Tidak ada cache yang dihapus. Silakan pilih opsi terlebih dahulu.');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus cache: ' . $e->getMessage());
        }
        
        return redirect()->to('/admin/settings/maintenance/hapuscache');
    }
    
    /**
     * Fungsi helper untuk menghapus file dalam direktori
     */
    private function deleteFiles($path)
    {
        if (!is_dir($path)) {
            return;
        }
        
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}