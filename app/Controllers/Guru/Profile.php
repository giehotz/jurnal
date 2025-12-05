<?php

namespace App\Controllers\Guru;

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
        // Cek jika user sudah login dan memiliki role guru
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Pengguna',
            'user' => $user
        ];

        return view('guru/profile/index', $data);
    }

    public function edit()
    {
        // Cek jika user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Profil',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/profile/edit', $data);
    }

    public function update()
    {
        // Cek jika user sudah login
        if (!session()->get('logged_in')) {
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
            'tanggal_lahir' => 'permit_empty|valid_date',
            'alamat' => 'permit_empty|max_length[255]',
            'no_telepon' => 'permit_empty|max_length[20]',
            'profile_picture' => 'permit_empty|mime_in[image/jpg,image/jpeg,image/png]|max_size[profile_picture,2048]',
            'banner_image' => 'permit_empty|mime_in[image/jpg,image/jpeg,image/png]|max_size[banner_image,4096]'
        ];

        // Cek apakah email berubah
        if ($this->request->getPost('email') !== $user['email']) {
            $rules['email'] .= '|is_unique[users.email]';
        }

        // Cek apakah NIP berubah (jika diisi)
        if (!empty($this->request->getPost('nip')) && $this->request->getPost('nip') !== $user['nip']) {
            $rules['nip'] .= '|is_unique[users.nip]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Proses upload foto profil
        $profilePicture = $user['profile_picture'] ?? '';
        // Proses banner
        $banner = $user['banner'] ?? '';
        $bannerFile = $this->request->getFile('banner_image');
        $file = $this->request->getFile('profile_picture');

        // Cek apakah ada cropped image (base64) dari frontend
        $croppedData = $this->request->getPost('cropped_image_data');
        $croppedBannerData = $this->request->getPost('cropped_banner_data');

        // Cek apakah user ingin menghapus foto
        $removePicture = $this->request->getPost('remove_picture');
        $removeBanner = $this->request->getPost('remove_banner');

        if ($removePicture == '1') {
            // Hapus foto profil lama jika ada dan bukan foto default
            if ($profilePicture && $profilePicture !== 'default.png' && file_exists(ROOTPATH . 'public/uploads/profile_pictures/' . $profilePicture)) {
                unlink(ROOTPATH . 'public/uploads/profile_pictures/' . $profilePicture);
            }
            $profilePicture = 'default.png';
        } 
        // Jika ada data crop (base64) dari JS, simpan sebagai file
        else if (!empty($croppedData)) {
            // Format: data:image/png;base64,AAA...
            if (preg_match('/^data:image\/(\w+);base64,/', $croppedData, $type)) {
                $data = substr($croppedData, strpos($croppedData, ',') + 1);
                $data = base64_decode($data);
                $ext = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);

                // Hapus foto lama
                if ($profilePicture && $profilePicture !== 'default.png' && file_exists(ROOTPATH . 'public/uploads/profile_pictures/' . $profilePicture)) {
                    unlink(ROOTPATH . 'public/uploads/profile_pictures/' . $profilePicture);
                }

                // Save new file
                $newName = bin2hex(random_bytes(8)) . '.' . $ext;
                $savePath = ROOTPATH . 'public/uploads/profile_pictures/' . $newName;
                file_put_contents($savePath, $data);
                $profilePicture = $newName;
            }
        }
        // Handle banner removal
        if ($removeBanner == '1') {
            if ($banner && file_exists(ROOTPATH . 'public/uploads/profile_banners/' . $banner)) {
                unlink(ROOTPATH . 'public/uploads/profile_banners/' . $banner);
            }
            $banner = null;
        } else {
            // If there is cropped banner data (base64)
            if (!empty($croppedBannerData)) {
                if (preg_match('/^data:image\/(\w+);base64,/', $croppedBannerData, $type)) {
                    $dataBanner = substr($croppedBannerData, strpos($croppedBannerData, ',') + 1);
                    $dataBanner = base64_decode($dataBanner);
                    $extBanner = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);

                    // remove old banner
                    if ($banner && file_exists(ROOTPATH . 'public/uploads/profile_banners/' . $banner)) {
                        unlink(ROOTPATH . 'public/uploads/profile_banners/' . $banner);
                    }

                    // ensure directory exists
                    if (!is_dir(ROOTPATH . 'public/uploads/profile_banners/')) {
                        mkdir(ROOTPATH . 'public/uploads/profile_banners/', 0755, true);
                    }

                    $newBannerName = bin2hex(random_bytes(8)) . '.' . $extBanner;
                    $saveBannerPath = ROOTPATH . 'public/uploads/profile_banners/' . $newBannerName;
                    file_put_contents($saveBannerPath, $dataBanner);
                    $banner = $newBannerName;
                }
            }
            // fallback: normal file upload for banner
            else if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
                if ($banner && file_exists(ROOTPATH . 'public/uploads/profile_banners/' . $banner)) {
                    unlink(ROOTPATH . 'public/uploads/profile_banners/' . $banner);
                }
                if (!is_dir(ROOTPATH . 'public/uploads/profile_banners/')) {
                    mkdir(ROOTPATH . 'public/uploads/profile_banners/', 0755, true);
                }
                $newBannerName = $bannerFile->getRandomName();
                $bannerFile->move(ROOTPATH . 'public/uploads/profile_banners/', $newBannerName);
                $banner = $newBannerName;
            }
        }

        // Handle profile picture upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus foto profil lama jika ada dan bukan foto default
            if ($profilePicture && $profilePicture !== 'default.png' && file_exists(ROOTPATH . 'public/uploads/profile_pictures/' . $profilePicture)) {
                unlink(ROOTPATH . 'public/uploads/profile_pictures/' . $profilePicture);
            }
            
            // Generate nama file baru
            $newName = $file->getRandomName();
            // Pindahkan file ke folder uploads
            $file->move(ROOTPATH . 'public/uploads/profile_pictures/', $newName);
            $profilePicture = $newName;
        }

        // Data untuk diupdate
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nip' => $this->request->getPost('nip') ?: null,
            'email' => $this->request->getPost('email'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat' => $this->request->getPost('alamat') ?: null,
            'no_telepon' => $this->request->getPost('no_telepon') ?: null,
            'profile_picture' => $profilePicture,
            'banner' => $banner
        ];

        // Update data user
        if ($this->userModel->updateUser($userId, $data)) {
            // Update session data
            session()->set([
                'nama' => $data['nama'],
                'nip' => $data['nip'],
                'email' => $data['email'],
                'profile_picture' => $data['profile_picture'], // Tambahkan ini untuk memperbarui foto profil di session
                'banner' => $data['banner'] ?? null
            ]);
            
            session()->setFlashdata('success', 'Profil berhasil diperbarui.');
            return redirect()->to('/guru/profile');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui profil.');
            return redirect()->back()->withInput();
        }
    }

    public function changePassword()
    {
        // Cek jika user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Ubah Password',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('guru/profile/change_password', $data);
    }

    public function updatePassword()
    {
        // Cek jika user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            throw new PageNotFoundException('User tidak ditemukan');
        }

        // Aturan validasi
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Cek password saat ini
        $currentPassword = $this->request->getPost('current_password');
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password saat ini tidak sesuai.');
            return redirect()->back()->withInput();
        }

        // Update password
        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        
        if ($this->userModel->updateUser($userId, ['password' => $newPassword])) {
            session()->setFlashdata('success', 'Password berhasil diubah.');
            return redirect()->to('/guru/profile');
        } else {
            session()->setFlashdata('error', 'Gagal mengubah password.');
            return redirect()->back()->withInput();
        }
    }
}