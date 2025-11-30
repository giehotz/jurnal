<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * Kepala Sekolah Routes
 * 
 * All routes related to headmaster functionality
 */
function kepalaSekolahRoutes(RouteCollection $routes)
{
    // Kepala Sekolah Routes
    $routes->group('kepala_sekolah', ['filter' => 'auth'], function($routes) {
        // Dashboard
        $routes->get('dashboard', 'KepalaSekolah\Dashboard::index');
        
        // Monitoring Jurnal
        $routes->get('monitoring', 'KepalaSekolah\Monitoring::index');
        $routes->get('monitoring/detail/(:num)', 'KepalaSekolah\Monitoring::detail/$1');
        
        // Laporan
        $routes->get('laporan', 'KepalaSekolah\Laporan::index');
        $routes->get('laporan/guru', 'KepalaSekolah\Laporan::guru');
        $routes->get('laporan/jurnal', 'KepalaSekolah\Laporan::jurnal');
        $routes->get('laporan/statistik', 'KepalaSekolah\Laporan::statistik');
        
        // Profile
        $routes->get('profile', 'KepalaSekolah\Profile::index');
        $routes->get('profile/edit', 'KepalaSekolah\Profile::edit');
        $routes->post('profile/update', 'KepalaSekolah\Profile::update');
    });
}