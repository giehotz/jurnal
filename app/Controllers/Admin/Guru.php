<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Guru extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Manajemen Guru',
            'active' => 'guru',
            'teachers' => $this->userModel->getUsersByRole('guru')
        ];

        return view('admin/guru/index', $data);
    }
}
