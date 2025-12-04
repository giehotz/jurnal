<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * Admin Routes
 * 
 * All routes related to admin functionality
 */
function adminRoutes(RouteCollection $routes)
{
    // Admin Routes
    $routes->group('admin', ['filter' => 'auth'], function($routes) {
        // Dashboard
        $routes->get('dashboard', 'Admin\Dashboard::index');
        
        // Profile
        $routes->get('profile', 'Admin\Profile::index');
        $routes->get('profile/edit', 'Admin\Profile::edit');
        $routes->post('profile/update', 'Admin\Profile::update');
        
        // Absensi
        $routes->get('absensi', 'Admin\Absensi::index');
        $routes->get('absensi/create', 'Admin\Absensi::create');
        $routes->post('absensi/store', 'Admin\Absensi::store');
        $routes->get('absensi/edit/(:num)', 'Admin\Absensi::edit/$1');
        $routes->post('absensi/update/(:num)', 'Admin\Absensi::update/$1');
        $routes->get('absensi/delete/(:num)', 'Admin\Absensi::delete/$1');
        $routes->get('absensi/detail/(:num)', 'Admin\Absensi::detail/$1');
        $routes->post('absensi/getSiswaByRombel', 'Admin\Absensi::getSiswaByRombel');
        $routes->get('absensi/debug', 'Admin\Absensi::debugRombelSiswa');
        $routes->get('absensi/export', 'Admin\Absensi::export');
        $routes->post('absensi/process_export', 'Admin\Absensi::process_export');
        
        // Pindah Kelas
        $routes->get('pindah-kelas', 'Admin\PindahKelas::index');
        $routes->post('pindah-kelas/get-rombel-by-tingkat', 'Admin\PindahKelas::getRombelByTingkat');
        $routes->post('pindah-kelas/get-siswa-by-rombel', 'Admin\PindahKelas::getSiswaByRombel');
        $routes->post('pindah-kelas/move-students', 'Admin\PindahKelas::moveStudents');
        
        // Class Management
        $routes->get('kelas', 'Admin\Kelas::index');
        $routes->get('kelas/create', 'Admin\Kelas::create');
        $routes->post('kelas/store', 'Admin\Kelas::store');
        $routes->get('kelas/view/(:num)', 'Admin\Kelas::view/$1');
        $routes->get('kelas/edit/(:num)', 'Admin\Kelas::edit/$1');
        $routes->post('kelas/update/(:num)', 'Admin\Kelas::update/$1');
        $routes->get('kelas/delete/(:num)', 'Admin\Kelas::delete/$1');
        
        // Rombel Management
        $routes->get('rombel', 'Admin\Rombel::index');
        $routes->get('rombel/create', 'Admin\Rombel::create');
        $routes->post('rombel/store', 'Admin\Rombel::store');
        $routes->get('rombel/view/(:num)', 'Admin\Rombel::view/$1');
        $routes->get('rombel/edit/(:num)', 'Admin\Rombel::edit/$1');
        $routes->post('rombel/update/(:num)', 'Admin\Rombel::update/$1');
        $routes->get('rombel/delete/(:num)', 'Admin\Rombel::delete/$1');
        $routes->get('rombel/assign-students/(:num)', 'Admin\Rombel::assignStudents/$1');
        $routes->post('rombel/save-students/(:num)', 'Admin\Rombel::saveStudents/$1');
        $routes->post('rombel/save-student-assignments/(:num)', 'Admin\Rombel::saveStudentAssignments/$1');
        $routes->get('rombel/download-template', 'Admin\Rombel::downloadTemplate');
        $routes->post('rombel/preview-upload/(:num)', 'Admin\Rombel::previewUpload/$1');
        $routes->post('rombel/store-upload/(:num)', 'Admin\Rombel::storeUpload/$1');
        
        // Ruangan Management
        $routes->get('ruangan', 'Admin\Ruangan::index');
        $routes->get('ruangan/create', 'Admin\Ruangan::create');
        $routes->post('ruangan/store', 'Admin\Ruangan::store');
        $routes->get('ruangan/edit/(:num)', 'Admin\Ruangan::edit/$1');
        $routes->post('ruangan/update/(:num)', 'Admin\Ruangan::update/$1');
        $routes->get('ruangan/delete/(:num)', 'Admin\Ruangan::delete/$1');
        
        // Auto-Route Manager
        $routes->get('autoroute', 'Admin\AutoRouteManager::index');
        $routes->post('autoroute/update-toggle', 'Admin\AutoRouteManager::updateToggle');
        $routes->get('autoroute/allowed', 'Admin\AutoRouteManager::allowed');
        $routes->post('autoroute/add-allowed', 'Admin\AutoRouteManager::addAllowed');
        $routes->get('autoroute/delete-allowed/(:num)', 'Admin\AutoRouteManager::deleteAllowed/$1');
        $routes->get('autoroute/toggle-allowed/(:num)', 'Admin\AutoRouteManager::toggleAllowed/$1');
        $routes->get('autoroute/logs', 'Admin\AutoRouteManager::logs');
        $routes->get('autoroute/clear-logs', 'Admin\AutoRouteManager::clearLogs');
        
        // Monitoring Jurnal
        $routes->get('monitoring', 'Admin\Monitoring::index');
        $routes->get('monitoring/detail/(:num)', 'Admin\Monitoring::detail/$1');
        $routes->get('monitoring/detail/(:num)/export/pdf', 'Admin\Monitoring::exportDetailToPdf/$1');
        $routes->get('monitoring/export/pdf', 'Admin\Monitoring::exportToPdf');
        $routes->get('monitoring/exportToPdf', 'Admin\Monitoring::exportToPdf');
        $routes->get('monitoring/detail/(:num)/export/excel', 'Admin\Monitoring::exportDetailToExcel/$1');
        $routes->get('monitoring/export/excel', 'Admin\Monitoring::exportToExcel');
        $routes->get('monitoring/exportToExcel', 'Admin\Monitoring::exportToExcel');
        
        // User Management
        $routes->get('users', 'Admin\UserManagement::index');
        $routes->get('user-management', 'Admin\UserManagement::index');
        $routes->get('users/create', 'Admin\UserManagement::create');
        $routes->post('users/store', 'Admin\UserManagement::store');
        $routes->get('users/edit/(:num)', 'Admin\UserManagement::edit/$1');
        $routes->post('users/update/(:num)', 'Admin\UserManagement::update/$1');
        $routes->get('users/delete/(:num)', 'Admin\UserManagement::delete/$1');
        $routes->get('users/resetPassword/(:num)', 'Admin\UserManagement::resetPassword/$1');
        $routes->get('users/reset_password/(:num)', 'Admin\UserManagement::resetPassword/$1');
        $routes->get('users/import', 'Admin\UserManagement::import');
        $routes->post('users/import', 'Admin\UserManagement::importUsersFromExcel');
        $routes->post('users/importUsersFromExcel', 'Admin\UserManagement::importUsersFromExcel');
        $routes->get('users/downloadTemplate', 'Admin\UserManagement::downloadTemplate');
        $routes->get('users/download_template', 'Admin\UserManagement::downloadTemplate');
        
        // Mapel Management
        $routes->get('mapel', 'Admin\Mapel::index');
        $routes->get('mapel/create', 'Admin\Mapel::create');
        $routes->post('mapel/store', 'Admin\Mapel::store');
        $routes->get('mapel/edit/(:num)', 'Admin\Mapel::edit/$1');
        $routes->post('mapel/update/(:num)', 'Admin\Mapel::update/$1');
        $routes->put('mapel/update/(:num)', 'Admin\Mapel::update/$1');
        $routes->get('mapel/delete/(:num)', 'Admin\Mapel::delete/$1');
        $routes->get('mapel/upload', 'Admin\Mapel::upload');
        $routes->post('mapel/import', 'Admin\Mapel::import');
        $routes->get('mapel/download-template', 'Admin\Mapel::downloadTemplate');
        
        // Export/Import
        $routes->get('export', 'Admin\Export::index');
        $routes->get('export/generate-pdf', 'Admin\Export::generatePDF');
        $routes->get('export/generate-excel', 'Admin\Export::generateExcel');
        
        // Settings
        $routes->get('settings', 'Admin\Settings::index');
        $routes->get('settings/settingapps', 'Admin\Settings::settingApps');
        $routes->post('settings/save', 'Admin\Settings::save');
        $routes->get('settings/maintenance', 'Admin\Settings::maintenance');
        $routes->post('settings/run-maintenance', 'Admin\Settings::runMaintenance');

        // QR Code Settings
        $routes->get('qrcode/settings', 'Admin\QRCodeSettings::index');
        $routes->post('qrcode/settings/update', 'Admin\QRCodeSettings::update');
        $routes->post('qrcode/settings/reset', 'Admin\QRCodeSettings::reset');
        $routes->post('qrcode/settings/delete-logo', 'Admin\QRCodeSettings::deleteLogo');
        
        // Guru Management
        $routes->get('guru', 'Admin\Guru::index');
        $routes->get('guru/create', 'Admin\Guru::create');
        $routes->post('guru/store', 'Admin\Guru::store');
        $routes->get('guru/edit/(:num)', 'Admin\Guru::edit/$1');
        $routes->post('guru/update/(:num)', 'Admin\Guru::update/$1');
        $routes->get('guru/delete/(:num)', 'Admin\Guru::delete/$1');
        $routes->get('guru/import', 'Admin\Guru::import');
        $routes->post('guru/process-import', 'Admin\Guru::processImport');
        $routes->get('guru/download-template', 'Admin\Guru::downloadTemplate');

        // Siswa Management
        $routes->get('siswa', 'Admin\Siswa::index');
        $routes->get('siswa/create', 'Admin\Siswa::create');
        $routes->post('siswa/store', 'Admin\Siswa::store');
        $routes->get('siswa/edit/(:num)', 'Admin\Siswa::edit/$1');
        $routes->post('siswa/update/(:num)', 'Admin\Siswa::update/$1');
        $routes->get('siswa/delete/(:num)', 'Admin\Siswa::delete/$1');
        $routes->get('siswa/upload', 'Admin\Siswa::upload');
        $routes->post('siswa/import', 'Admin\Siswa::processUpload');
        $routes->get('siswa/download-template', 'Admin\Siswa::downloadTemplate');
        
        // Laporan
        $routes->get('laporan', 'Admin\Laporan::index');
        $routes->get('laporan/index', 'Admin\Laporan::index');
        $routes->get('laporan/guru', 'Admin\Laporan::guru');
        $routes->get('laporan/jurnal', 'Admin\Laporan::jurnal');
        $routes->get('laporan/statistik', 'Admin\Laporan::statistik');
        $routes->get('laporan/export', 'Admin\Laporan::export');
        $routes->post('laporan/generate', 'Admin\Laporan::generate');
        $routes->get('laporan/export/guru/pdf', 'Admin\Laporan::exportGuruToPdf');
        $routes->get('laporan/export/guru/excel', 'Admin\Laporan::exportGuruToExcel');
    });
}