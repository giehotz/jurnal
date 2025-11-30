<?php

namespace App\Controllers;

class TestLayout extends BaseController
{
    public function admin()
    {
        $data = [
            'title' => 'Test Admin Layout',
            'active' => 'dashboard'
        ];
        return view('admin/layouts/template', $data);
    }
    
    public function guru()
    {
        $data = [
            'title' => 'Test Guru Layout',
            'active' => 'dashboard'
        ];
        return view('guru/layouts/template', $data);
    }
    
    public function kepalaSekolah()
    {
        $data = [
            'title' => 'Test Kepala Sekolah Layout',
            'active' => 'dashboard'
        ];
        return view('kepala_sekolah/layouts/template', $data);
    }
}