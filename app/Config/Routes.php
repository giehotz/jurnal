<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Import route functions
use function Config\Routes\adminRoutes;
use function Config\Routes\guruRoutes;
use function Config\Routes\kepalaSekolahRoutes;
use function Config\Routes\apiRoutes;
use function Config\Routes\authRoutes;
use function Config\autoRoutes;

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
// Temporarily disabled to debug
$routes->set404Override('App\\Controllers\\Fallback::index');
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', function() { return redirect()->to('auth/login'); });

// Include Admin Routes
if (file_exists(APPPATH . 'Config/Routes/AdminRoutes.php')) {
    require_once APPPATH . 'Config/Routes/AdminRoutes.php';
    adminRoutes($routes);
}

// Include Guru Routes
if (file_exists(APPPATH . 'Config/Routes/GuruRoutes.php')) {
    require_once APPPATH . 'Config/Routes/GuruRoutes.php';
    guruRoutes($routes);
}

// Include Kepala Sekolah Routes
if (file_exists(APPPATH . 'Config/Routes/KepalaSekolahRoutes.php')) {
    require_once APPPATH . 'Config/Routes/KepalaSekolahRoutes.php';
    kepalaSekolahRoutes($routes);
}

// Include API Routes
if (file_exists(APPPATH . 'Config/Routes/ApiRoutes.php')) {
    require_once APPPATH . 'Config/Routes/ApiRoutes.php';
    apiRoutes($routes);
}

// Include Auth Routes
if (file_exists(APPPATH . 'Config/Routes/AuthRoutes.php')) {
    require_once APPPATH . 'Config/Routes/AuthRoutes.php';
    authRoutes($routes);
}

// Include Auto-generated Routes
if (file_exists(APPPATH . 'Config/AutoRoutes.php')) {
    require_once APPPATH . 'Config/AutoRoutes.php';
    autoRoutes($routes);
}

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}