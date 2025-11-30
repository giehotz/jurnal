<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * API Routes
 * 
 * All routes related to API functionality
 */
function apiRoutes(RouteCollection $routes)
{
    // API Routes
    $routes->group('api', function($routes) {
        // API Authentication
        $routes->post('auth/login', 'Api\Auth::login');
        $routes->post('auth/logout', 'Api\Auth::logout');
        
        // API Guru Routes
        $routes->group('guru', function($routes) {
            $routes->get('dashboard', 'Api\Guru::dashboard');
            $routes->get('jurnal', 'Api\Guru::jurnal');
            $routes->get('jurnal/(:num)', 'Api\Guru::jurnalDetail/$1');
            $routes->post('jurnal', 'Api\Guru::createJurnal');
            $routes->put('jurnal/(:num)', 'Api\Guru::updateJurnal/$1');
            $routes->delete('jurnal/(:num)', 'Api\Guru::deleteJurnal/$1');
        });
        
        // API Admin Routes
        $routes->group('admin', function($routes) {
            $routes->get('dashboard', 'Api\Admin::dashboard');
            $routes->get('users', 'Api\Admin::users');
            $routes->get('users/(:num)', 'Api\Admin::userDetail/$1');
            $routes->post('users', 'Api\Admin::createUser');
            $routes->put('users/(:num)', 'Api\Admin::updateUser/$1');
            $routes->delete('users/(:num)', 'Api\Admin::deleteUser/$1');
        });
    });
}