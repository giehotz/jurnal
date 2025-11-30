<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        // Menampilkan form login
        return view('auth/login');
    }
    
    public function attemptLogin()
    {
        // Memproses login pengguna
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Validasi input
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];
        
        if (!$this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }
        
        // Cek kredensial pengguna
        $user = $this->checkCredentials($email, $password);
        
        if ($user) {
            // Buat session
            $session_data = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'nama' => $user['nama'],
                'nip' => $user['nip'] ?? null,
                'profile_picture' => !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.png',
                'logged_in' => TRUE
            ];
            
            session()->set($session_data);
            session()->setFlashdata('success', 'CAKEP 😍 Login berhasil! ' . esc($user['nama']) . '.');

            // Redirect berdasarkan role
            switch ($user['role']) {
                case 'guru':
                    return redirect()->to('/guru/dashboard');
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                case 'super_admin':
                    return redirect()->to('/admin/dashboard');
                case 'kepala_sekolah':
                    return redirect()->to('/kepala_sekolah/dashboard');
                default:
                    return redirect()->to('/');
            }
        } else {
            // Cek apakah email ditemukan tapi password salah
            $existingUser = $this->userModel->where('email', $email)->first();
            
            if ($existingUser) {
                // Email ditemukan tapi password salah
                session()->setFlashdata('error', 'Oops! Lupa password? Jangan panik! 😅 Hubungi @Sugianto TAMVAN 🗿 dan dia akan bantu kamu dengan senyum lebar! 😊');
            } else {
                // Email tidak ditemukan
                session()->setFlashdata('error', 'Email atau password salah!');
            }
            
            return redirect()->back()->withInput();
        }
    }
    
    public function logout()
    {
        // Menghapus session
        session()->destroy();
        session()->setFlashdata('success', 'Anda telah logout.');
        return redirect()->to('/auth/login');
    }
    
    /**
     * Fungsi untuk cek kredensial pengguna menggunakan model
     */
    private function checkCredentials($email, $password)
    {
        // Cari user berdasarkan email
        $user = $this->userModel->where('email', $email)->first();
        
        // Jika user ditemukan dan password cocok
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }
}