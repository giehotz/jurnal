<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * Guru Routes
 * 
 * All routes related to teacher functionality
 */
function guruRoutes(RouteCollection $routes)
{
    // Public Debug Route (Temporary)
    // $routes->get('guru/jurnal/debug-test', 'Guru\Jurnal::debugTest');
    // $routes->post('guru/jurnal/check-daily-attendance', 'Guru\Jurnal::checkDailyAttendance');

    // Guru Routes
    $routes->group('guru', ['filter' => 'auth'], function($routes) {
        // Dashboard
        $routes->get('dashboard', 'Guru\Dashboard::index');
        
        // Test Helper
        $routes->get('test-helper', 'Guru\TestHelper::index');
        
        // Jurnal Management
        $routes->get('jurnal', 'Guru\Jurnal::index');
        $routes->get('jurnal/create', 'Guru\Jurnal::create');
        $routes->post('jurnal/store', 'Guru\Jurnal::store');
        $routes->post('jurnal/get-available-hours', 'Guru\Jurnal::getAvailableHours');
        $routes->post('jurnal/check-daily-attendance', 'Guru\Jurnal::checkDailyAttendance');
        $routes->post('jurnal/get-siswa-by-kelas', 'Guru\Jurnal::getSiswaByKelas');
        $routes->get('jurnal/view', 'Guru\Jurnal::index');
        $routes->get('jurnal/edit/(:num)', 'Guru\Jurnal::edit/$1');
        $routes->post('jurnal/update/(:num)', 'Guru\Jurnal::update/$1');
        $routes->get('jurnal/view/(:num)', 'Guru\Jurnal::view/$1');
        $routes->get('jurnal/delete/(:num)', 'Guru\Jurnal::delete/$1');
        $routes->get('jurnal/generate-pdf', 'Guru\Jurnal::generatePdf');
        $routes->post('jurnal/export/pdf', 'Guru\Jurnal::exportPdf');
        $routes->get('jurnal/export/excel', 'Guru\Jurnal::exportExcel');
        $routes->post('jurnal/export/excel', 'Guru\Jurnal::exportExcel');
        $routes->get('jurnal/pdf/(:num)', 'Guru\Jurnal::pdf/$1');
        
        // Attendance Management
        $routes->get('absensi', 'Guru\AbsensiReportController::index');
        $routes->get('absensi/create', 'Guru\AbsensiInputController::create');
        $routes->post('absensi/store', 'Guru\AbsensiInputController::store');
        $routes->get('absensi/view/(:num)', 'Guru\AbsensiViewController::view/$1');
        $routes->get('absensi/detail/(:num)', 'Guru\AbsensiViewController::detail/$1');
        $routes->get('absensi/edit/(:num)', 'Guru\AbsensiInputController::edit/$1');
        $routes->post('absensi/update/(:num)', 'Guru\AbsensiInputController::update/$1');
        $routes->get('absensi/export', 'Guru\AbsensiReportController::export');
        $routes->post('absensi/process_export', 'Guru\AbsensiReportController::process_export');
        $routes->post('absensi/get-siswa-by-rombel', 'Guru\AbsensiViewController::getSiswaByRombel');
        
        // Profile
        $routes->get('profile', 'Guru\Profile::index');
        $routes->get('profile/edit', 'Guru\Profile::edit');
        $routes->post('profile/update', 'Guru\Profile::update');
        $routes->get('profile/change-password', 'Guru\Profile::changePassword');
        $routes->post('profile/update-password', 'Guru\Profile::updatePassword');
    });
}