<?php

namespace Config\Routes;

use CodeIgniter\Router\RouteCollection;

/**
 * Asset Routes
 * 
 * All routes related to serving assets
 */
function assetRoutes(RouteCollection $routes)
{
    // Serve AdminLTE assets from public directory
    $routes->get('AdminLTE/(:any)', function ($path) {
        $filePath = ROOTPATH . 'public/AdminLTE/' . $path;
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf' => 'font/ttf',
                'eot' => 'application/vnd.ms-fontobject',
                'otf' => 'font/otf'
            ];
            
            $mimeType = $mimeTypes[$extension] ?? mime_content_type($filePath);
            return response()
                ->setContentType($mimeType)
                ->setBody(file_get_contents($filePath));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }, ['priority' => 10]);

    // Serve vendor assets
    $routes->get('vendor/almasaeed2010/adminlte/(:any)', function ($path) {
        $filePath = ROOTPATH . 'vendor/almasaeed2010/adminlte/' . $path;
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml'
            ];
            
            $mimeType = $mimeTypes[$extension] ?? mime_content_type($filePath);
            return response()
                ->setContentType($mimeType)
                ->setBody(file_get_contents($filePath));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }, ['priority' => 10]);
    
    // Serve uploads assets
    $routes->get('uploads/(:any)', function ($path) {
        $filePath = ROOTPATH . 'public/uploads/' . $path;
        if (file_exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            
            $mimeType = $mimeTypes[$extension] ?? mime_content_type($filePath);
            return response()
                ->setContentType($mimeType)
                ->setBody(file_get_contents($filePath));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }, ['priority' => 10]);
}