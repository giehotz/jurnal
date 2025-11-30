<?php

namespace App\Controllers;

class TestBaseUrl extends BaseController
{
    public function index()
    {
        $data = [
            'base_url' => base_url(),
            'adminlte_css' => base_url('vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css'),
            'fontawesome_css' => base_url('vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css')
        ];
        
        return view('test_baseurl', $data);
    }
}