<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * Main and Test Routes
 * 
 * All main routes and test routes
 */
function mainRoutes(RouteCollection $routes)
{
    // Main Route - redirect to login page
    $routes->get('/', 'Auth::login');
    
    // Test Routes
    $routes->get('/test-adminlte', 'TestAdminLTE::index');
    $routes->get('/test-baseurl', 'TestBaseUrl::index');
    $routes->get('/test-layout/admin', 'TestLayout::admin');
    $routes->get('/test-layout/guru', 'TestLayout::guru');
    $routes->get('/test-layout/kepala-sekolah', 'TestLayout::kepalaSekolah');
    
    // Jurnal New Routes
    $routes->group('jurnal-new', ['filter' => 'auth'], function($routes) {
        $routes->get('/', 'JurnalNew::index');
        $routes->get('create', 'JurnalNew::create');
        $routes->post('store', 'JurnalNew::store');
    });
    
    // Error Routes
    $routes->get('errors/404', 'Errors::show404');
    $routes->get('errors/500', 'Errors::show500');
}