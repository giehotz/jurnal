<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Admin',
            'active' => 'profile',
            'user' => $user
        ];

        return view('admin/profile/edit', $data);
    }

    public function edit()
    {
        // Cek jika user sudah login dan memiliki role admin
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Profil',
            'active' => 'profile',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/profile/edit', $data);
    }

    public function update()
    {
        // Cek jika user sudah login dan memiliki role admin
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        // Aturan validasi
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'nip' => 'permit_empty|min_length[5]|max_length[20]',
            'email' => 'required|valid_email|max_length[100]',
            'no_telepon' => 'permit_empty|max_length[20]',
            'alamat' => 'permit_empty|max_length[255]',
            'profile_picture' => 'permit_empty|mime_in[image/jpg,image/jpeg,image/png]|max_size[profile_picture,2048]'
        ];

        // Cek apakah email berubah
        if ($this->request->getPost('email') !== $user['email']) {
            $rules['email'] .= '|is_unique[users.email,id,'.$userId.']';
        }

        // Cek apakah NIP berubah (jika diisi)
        if (!empty($this->request->getPost('nip')) && $this->request->getPost('nip') !== $user['nip']) {
            $rules['nip'] .= '|is_unique[users.nip,id,'.$userId.']';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Siapkan data untuk update
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nip' => $this->request->getPost('nip'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'alamat' => $this->request->getPost('alamat'),
        ];

        // Proses upload foto profil jika ada
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            // Hapus foto profil lama jika ada
            if (!empty($user['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/profile_pictures/' . $user['profile_picture'])) {
                unlink(ROOTPATH . 'public/uploads/profile_pictures/' . $user['profile_picture']);
            }

            // Generate nama file unik
            $newName = $profilePicture->getRandomName();
            $profilePicture->move(ROOTPATH . 'public/uploads/profile_pictures/', $newName);
            $data['profile_picture'] = $newName;
        }

        // Update data user
        if ($this->userModel->update($userId, $data)) {
            // Update session data
            $sessionData = [
                'nama' => $data['nama'],
                'email' => $data['email'],
                'nip' => $data['nip']
            ];
            
            // Update profile picture in session if it was changed
            if (isset($data['profile_picture'])) {
                $sessionData['profile_picture'] = $data['profile_picture'];
            }
            
            session()->set($sessionData);

            return redirect()->to('/admin/profile')->with('success', 'Profil berhasil diperbarui');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui profil');
        }
    }
}