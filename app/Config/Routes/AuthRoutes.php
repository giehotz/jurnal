<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * Auth Routes
 * 
 * All routes related to authentication
 */
function authRoutes(RouteCollection $routes)
{
    // Authentication Routes
    $routes->get('/auth/login', 'Auth::login');
    $routes->post('/auth/attemptLogin', 'Auth::attemptLogin');
    $routes->get('/auth/logout', 'Auth::logout');
}