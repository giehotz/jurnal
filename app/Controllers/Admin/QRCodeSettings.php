<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QRGlobalSettingsModel;

class QRCodeSettings extends BaseController
{
    protected $qrSettingsModel;

    public function __construct()
    {
        $this->qrSettingsModel = new QRGlobalSettingsModel();
    }

    public function index()
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $settings = $this->qrSettingsModel->getActiveSettings();

        $data = [
            'title' => 'Pengaturan QR Code',
            'active' => 'qrcode_settings',
            'settings' => $settings
        ];

        return view('admin/qrcode/settings', $data);
    }

    public function update()
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $settings = $this->qrSettingsModel->getActiveSettings();
        $id = $settings['id'];

        $rules = [
            'default_size' => 'required|integer|greater_than[50]|less_than[1000]',
            'default_color' => 'required|regex_match[/^#[a-fA-F0-9]{6}$/]',
            'default_bg_color' => 'required|regex_match[/^#[a-fA-F0-9]{6}$/]',
            'max_file_size_kb' => 'required|integer',
            'default_logo' => 'max_size[default_logo,2048]|is_image[default_logo]|mime_in[default_logo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'default_size' => $this->request->getPost('default_size'),
            'default_color' => $this->request->getPost('default_color'),
            'default_bg_color' => $this->request->getPost('default_bg_color'),
            'allow_custom_logo' => $this->request->getPost('allow_custom_logo') ? 1 : 0,
            'allow_custom_colors' => $this->request->getPost('allow_custom_colors') ? 1 : 0,
            'allow_custom_size' => $this->request->getPost('allow_custom_size') ? 1 : 0,
            'max_file_size_kb' => $this->request->getPost('max_file_size_kb'),
        ];

        // Handle Logo Upload
        $file = $this->request->getFile('default_logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/logos', $newName);
            $data['default_logo_path'] = 'uploads/logos/' . $newName;
        }

        if ($this->qrSettingsModel->update($id, $data)) {
            return redirect()->to('/admin/qrcode/settings')->with('success', 'Pengaturan berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan.');
        }
    }

    public function reset()
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $settings = $this->qrSettingsModel->getActiveSettings();
        $id = $settings['id'];

        $defaults = [
            'default_size'        => 300,
            'default_color'       => '#000000',
            'default_bg_color'    => '#FFFFFF',
            'default_logo_path'   => null,
            'allow_custom_logo'   => 1,
            'allow_custom_colors' => 1,
            'allow_custom_size'   => 1,
            'max_file_size_kb'    => 2048,
            'allowed_mime_types'  => 'image/png,image/jpeg,image/gif',
        ];

        if ($this->qrSettingsModel->update($id, $defaults)) {
            return redirect()->to('/admin/qrcode/settings')->with('success', 'Pengaturan berhasil direset ke default.');
        } else {
            return redirect()->to('/admin/qrcode/settings')->with('error', 'Gagal mereset pengaturan.');
        }
    }

    public function deleteLogo()
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $settings = $this->qrSettingsModel->getActiveSettings();
        $id = $settings['id'];

        if (!empty($settings['default_logo_path'])) {
            $filePath = ROOTPATH . 'public/' . $settings['default_logo_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if ($this->qrSettingsModel->update($id, ['default_logo_path' => null])) {
                return redirect()->to('/admin/qrcode/settings')->with('success', 'Logo default berhasil dihapus.');
            } else {
                return redirect()->to('/admin/qrcode/settings')->with('error', 'Gagal menghapus logo dari database.');
            }
        }

        return redirect()->to('/admin/qrcode/settings')->with('error', 'Tidak ada logo untuk dihapus.');
    }
}
