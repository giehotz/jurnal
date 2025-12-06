<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QRGlobalSettingsModel;
use App\Models\UrlModel;
use App\Models\QRModel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

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
    public function listQRCodes()
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $urlModel = new UrlModel();

        // Fetch URLs with User info
        $entries = $urlModel->select('url_entries.*, users.nama as user_name, users.role as user_role, users.profile_picture')
            ->join('users', 'users.id = url_entries.user_id', 'left')
            ->orderBy('url_entries.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Daftar QR Code',
            'active' => 'qrcode_list',
            'entries' => $entries
        ];

        return view('admin/qrcode/list', $data);
    }

    public function renderQR($id)
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $urlModel = new UrlModel();
        $qrModel = new QRModel();

        $url = $urlModel->find($id);
        if (!$url) {
            return $this->response->setStatusCode(404);
        }

        $settings = $qrModel->where('url_id', $id)->first();
        if (!$settings) {
            // Use defaults if not found? Or create default on fly?
            // Let's just create a basic settings array
            $settings = [
                'size' => 300,
                'qr_color' => '#000000',
                'bg_color' => '#FFFFFF',
                'logo_path' => null,
            ];
        }

        $settings['label_text'] = $url['custom_name'] ?: $url['original_url'];

        try {
            $result = $this->buildQR($url['original_url'], $settings);

            return response()->setHeader('Content-Type', $result->getMimeType())
                ->setHeader('Cache-Control', 'public, max-age=31536000')
                ->setHeader('Pragma', 'public')
                ->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT')
                ->setBody($result->getString());
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody($e->getMessage());
        }
    }

    public function downloadQR($id)
    {
        if (!session()->get('logged_in') || (session()->get('role') !== 'admin' && session()->get('role') !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $urlModel = new UrlModel();
        $qrModel = new QRModel();

        $url = $urlModel->find($id);
        if (!$url) {
            return redirect()->back()->with('error', 'QR Code not found');
        }

        $settings = $qrModel->where('url_id', $id)->first();
        if (!$settings) {
            $settings = [
                'size' => 300,
                'qr_color' => '#000000',
                'bg_color' => '#FFFFFF',
                'logo_path' => null,
            ];
        }
        $settings['label_text'] = $url['custom_name'] ?: $url['original_url'];

        try {
            $result = $this->buildQR($url['original_url'], $settings);

            $filename = preg_replace('/[^a-zA-Z0-9\s_\-]/', '', $url['custom_name']);
            $filename = str_replace(' ', '_', $filename);
            if (empty($filename)) {
                $filename = 'qrcode-' . $url['short_slug'];
            }

            return response()->setHeader('Content-Type', $result->getMimeType())
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.png"')
                ->setBody($result->getString());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating QR: ' . $e->getMessage());
        }
    }

    private function buildQR($content, $settings)
    {
        $size = $settings['size'] ?? 300;
        $qrColor = $this->hexToRgb($settings['qr_color'] ?? '#000000');
        $bgColor = $this->hexToRgb($settings['bg_color'] ?? '#FFFFFF');

        $roundBlockSizeMode = RoundBlockSizeMode::Margin;
        if (isset($settings['frame_style'])) {
            switch ($settings['frame_style']) {
                case 'rounded':
                    $roundBlockSizeMode = RoundBlockSizeMode::Enlarge;
                    break;
                case 'circle':
                    $roundBlockSizeMode = RoundBlockSizeMode::Shrink;
                    break;
            }
        }

        $builder = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($content)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size($size)
            ->margin(10)
            ->roundBlockSizeMode($roundBlockSizeMode)
            ->foregroundColor(new Color($qrColor[0], $qrColor[1], $qrColor[2]))
            ->backgroundColor(new Color($bgColor[0], $bgColor[1], $bgColor[2]));

        if (!empty($settings['logo_path'])) {
            $logoPath = FCPATH . 'uploads/qr_logos/' . $settings['logo_path'];
            if (!file_exists($logoPath)) {
                $logoPath = FCPATH . $settings['logo_path'];
            }

            if (file_exists($logoPath)) {
                $builder->logoPath($logoPath);
                $builder->logoResizeToWidth((int)($size * 0.2));
                $builder->logoPunchoutBackground(true);
            }
        }

        if (!empty($settings['show_label'])) {
            $labelText = $settings['label_text'] ?? $content;
            $builder->labelText($labelText);
            $builder->labelFont(new NotoSans(20));
            $builder->labelAlignment(LabelAlignment::Center);
        }

        return $builder->build();
    }

    private function hexToRgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [$r, $g, $b];
    }
}
