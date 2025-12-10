<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
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

class QRCode extends BaseController
{
    protected $urlModel;
    protected $qrModel;
    protected $qrSettingsModel;

    public function __construct()
    {
        $this->urlModel = new UrlModel();
        $this->qrModel = new QRModel();
        $this->qrSettingsModel = new \App\Models\QRGlobalSettingsModel();
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');

        $urls = $this->urlModel->where('user_id', $userId)->orderBy('created_at', 'ASC')->findAll();

        $data = [
            'title' => 'QR Code Manager',
            'active' => 'qrcode',
            'urls' => $urls
        ];

        return view('guru/qrcode/index', $data);
    }

    public function create()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $settings = $this->qrSettingsModel->getActiveSettings();

        $data = [
            'title' => 'Generate QR Code',
            'active' => 'qrcode',
            'settings' => $settings
        ];

        return view('guru/qrcode/create', $data);
    }

    public function store()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $settings = $this->qrSettingsModel->getActiveSettings();

        $rules = [
            'original_url' => 'required|valid_url',
            'custom_name' => 'required|min_length[3]|max_length[100]',
            'custom_slug' => 'permit_empty|alpha_dash|is_unique[url_entries.custom_slug]',
        ];

        if ($settings['allow_custom_size']) {
            $rules['size'] = 'required|integer|greater_than[50]|less_than[1000]';
        }

        if ($settings['allow_custom_colors']) {
            $rules['qr_color'] = 'required|regex_match[/^#[a-fA-F0-9]{6}$/]';
            $rules['bg_color'] = 'required|regex_match[/^#[a-fA-F0-9]{6}$/]';
        }

        if ($settings['allow_custom_logo']) {
            $rules['logo'] = 'permit_empty|max_size[logo,' . $settings['max_file_size_kb'] . ']|is_image[logo]|mime_in[logo,' . $settings['allowed_mime_types'] . ']';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        $originalUrl = $this->request->getPost('original_url');
        $customName = $this->request->getPost('custom_name');
        $customSlug = $this->request->getPost('custom_slug');

        // Generate unique slug if not provided
        if (empty($customSlug)) {
            $customSlug = bin2hex(random_bytes(4));
            while ($this->urlModel->where('short_slug', $customSlug)->first()) {
                $customSlug = bin2hex(random_bytes(4));
            }
        }

        $urlData = [
            'user_id' => $userId,
            'original_url' => $originalUrl,
            'short_slug' => $customSlug,
            'custom_name' => $customName ?: $originalUrl,
        ];

        $this->urlModel->insert($urlData);
        $urlId = $this->urlModel->getInsertID();

        // Use settings or user input based on permissions
        $size = $settings['allow_custom_size'] ? ($this->request->getPost('size') ?: $settings['default_size']) : $settings['default_size'];
        $qrColor = $settings['allow_custom_colors'] ? ($this->request->getPost('qr_color') ?: $settings['default_color']) : $settings['default_color'];
        $bgColor = $settings['allow_custom_colors'] ? ($this->request->getPost('bg_color') ?: $settings['default_bg_color']) : $settings['default_bg_color'];

        $qrData = [
            'url_id' => $urlId,
            'size' => $size,
            'qr_color' => $qrColor,
            'bg_color' => $bgColor,
            'frame_style' => $this->request->getPost('frame_style') ?: 'none',
            'show_label' => $this->request->getPost('show_label') ? 1 : 0,
        ];

        // Handle Logo
        $logoPath = null;
        $logoOption = $this->request->getPost('logo_option');

        if ($logoOption === 'custom' && $settings['allow_custom_logo']) {
            $logo = $this->request->getFile('logo');
            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                $newName = $logo->getRandomName();
                $logo->move(FCPATH . 'uploads/qr_logos', $newName);
                $logoPath = $newName;
            }
        } elseif ($logoOption === 'default' && !empty($settings['default_logo_path'])) {
            // Use default logo
            $defaultLogoSource = FCPATH . $settings['default_logo_path'];
            if (file_exists($defaultLogoSource)) {
                $extension = pathinfo($defaultLogoSource, PATHINFO_EXTENSION);
                $newDefaultName = 'default_' . bin2hex(random_bytes(8)) . '.' . $extension;
                copy($defaultLogoSource, FCPATH . 'uploads/qr_logos/' . $newDefaultName);
                $logoPath = $newDefaultName;
            }
        }
        // If 'none', $logoPath remains null

        $qrData['logo_path'] = $logoPath;

        $this->qrModel->insert($qrData);

        return redirect()->to('/guru/qrcode')->with('success', 'QR Code generated successfully');
    }

    public function show($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return redirect()->to('/guru/qrcode')->with('error', 'QR Code not found');
        }

        $settings = $this->qrModel->where('url_id', $id)->first();
        $settings['label_text'] = $url['custom_name'] ?: $url['original_url'];

        // Generate QR Code for display
        try {
            $qrData = $this->generateFinalQRString($url['original_url'], $settings);
            $qrImage = 'data:image/png;base64,' . base64_encode($qrData);
        } catch (\Exception $e) {
            $qrImage = '';
            session()->setFlashdata('error', 'Error generating QR: ' . $e->getMessage());
        }

        $data = [
            'title' => 'QR Code Details',
            'active' => 'qrcode',
            'url' => $url,
            'settings' => $settings,
            'qrImage' => $qrImage
        ];

        return view('guru/qrcode/show', $data);
    }

    public function edit($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return redirect()->to('/guru/qrcode')->with('error', 'QR Code not found');
        }

        $settings = $this->qrModel->where('url_id', $id)->first();
        $globalSettings = $this->qrSettingsModel->getActiveSettings();

        $data = [
            'title' => 'Edit QR Code',
            'active' => 'qrcode',
            'url' => $url,
            'settings' => $settings,
            'globalSettings' => $globalSettings
        ];

        return view('guru/qrcode/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return redirect()->to('/guru/qrcode')->with('error', 'QR Code not found');
        }

        $rules = [
            'original_url' => 'required|valid_url',
            'custom_name'  => 'permit_empty|max_length[255]',
            'size'         => 'permit_empty|integer|greater_than[100]|less_than[1000]',
            'logo'         => 'permit_empty|uploaded[logo]|max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update URL info
        $this->urlModel->update($id, [
            'original_url' => $this->request->getPost('original_url'),
            'custom_name' => $this->request->getPost('custom_name')
        ]);

        // Update Settings
        $settings = $this->qrModel->where('url_id', $id)->first();
        $globalSettings = $this->qrSettingsModel->getActiveSettings();

        // Use settings or user input based on permissions
        $size = $globalSettings['allow_custom_size'] ? ($this->request->getPost('size') ?: $globalSettings['default_size']) : $globalSettings['default_size'];
        $qrColor = $globalSettings['allow_custom_colors'] ? ($this->request->getPost('qr_color') ?: $globalSettings['default_color']) : $globalSettings['default_color'];
        $bgColor = $globalSettings['allow_custom_colors'] ? ($this->request->getPost('bg_color') ?: $globalSettings['default_bg_color']) : $globalSettings['default_bg_color'];

        $qrData = [
            'size' => $size,
            'qr_color' => $qrColor,
            'bg_color' => $bgColor,
            'frame_style' => $this->request->getPost('frame_style') ?: 'none',
            'show_label' => $this->request->getPost('show_label') ? 1 : 0,
        ];

        // Handle Logo Upload
        $logoPath = $settings['logo_path']; // Keep existing logo by default

        if ($globalSettings['allow_custom_logo']) {
            $logo = $this->request->getFile('logo');
            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                $newName = $logo->getRandomName();
                $logo->move(FCPATH . 'uploads/qr_logos', $newName);
                $logoPath = $newName;

                // Delete old logo if exists
                if (!empty($settings['logo_path']) && file_exists(FCPATH . 'uploads/qr_logos/' . $settings['logo_path'])) {
                    unlink(FCPATH . 'uploads/qr_logos/' . $settings['logo_path']);
                }
            } elseif ($this->request->getPost('remove_logo') == '1') {
                // Remove logo if requested and no new logo uploaded
                if (!empty($settings['logo_path']) && file_exists(FCPATH . 'uploads/qr_logos/' . $settings['logo_path'])) {
                    unlink(FCPATH . 'uploads/qr_logos/' . $settings['logo_path']);
                }
                $logoPath = null;
            }
        }

        $qrData['logo_path'] = $logoPath;

        $this->qrModel->update($settings['id'], $qrData);

        return redirect()->to('/guru/qrcode/show/' . $id)->with('success', 'QR Code updated successfully');
    }

    public function render($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return $this->response->setStatusCode(404);
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return $this->response->setStatusCode(404);
        }

        $settings = $this->qrModel->where('url_id', $id)->first();
        $settings['label_text'] = $url['custom_name'] ?: $url['original_url'];

        try {
            $resultString = $this->generateFinalQRString($url['original_url'], $settings);

            return response()->setHeader('Content-Type', 'image/png')
                ->setHeader('Cache-Control', 'public, max-age=31536000') // Cache for 1 year
                ->setHeader('Pragma', 'public')
                ->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT')
                ->setBody($resultString);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500);
        }
    }

    public function download($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return redirect()->to('/guru/qrcode')->with('error', 'QR Code not found');
        }

        $settings = $this->qrModel->where('url_id', $id)->first();
        $settings['label_text'] = $url['custom_name'] ?: $url['original_url'];

        try {
            $resultString = $this->generateFinalQRString($url['original_url'], $settings);

            $filename = preg_replace('/[^a-zA-Z0-9\s_\-]/', '', $url['custom_name']);
            $filename = str_replace(' ', '_', $filename);
            if (empty($filename)) {
                $filename = 'qrcode-' . $url['short_slug'];
            }

            return response()->setHeader('Content-Type', 'image/png')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.png"')
                ->setBody($resultString);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating QR: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return redirect()->to('/guru/qrcode')->with('error', 'QR Code not found');
        }

        $this->urlModel->delete($id);

        return redirect()->to('/guru/qrcode')->with('success', 'QR Code deleted successfully');
    }

    private function generateFinalQRString($content, $settings)
    {
        // 1. Generate base QR (ResultInterface)
        $result = $this->buildBaseQR($content, $settings);
        $qrString = $result->getString();

        // 2. Apply Frame Style via GD
        if (!empty($settings['frame_style']) && $settings['frame_style'] !== 'none') {
            $qrString = $this->applyFrameStyle($qrString, $settings['frame_style']);
        }

        return $qrString;
    }

    private function buildBaseQR($content, $settings)
    {
        $size = $settings['size'] ?? 300;
        $qrColor = $this->hexToRgb($settings['qr_color'] ?? '#000000');
        $bgColor = $this->hexToRgb($settings['bg_color'] ?? '#FFFFFF');

        // We use Margin default, styling is done post-process for shapes
        $roundBlockSizeMode = RoundBlockSizeMode::Margin;

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

            // If not found in qr_logos, check if it's a direct path (e.g. default logo)
            if (!file_exists($logoPath)) {
                $logoPath = FCPATH . $settings['logo_path'];
            }

            if (file_exists($logoPath)) {
                $builder->logoPath($logoPath);
                $builder->logoResizeToWidth((int)($size * 0.2)); // Logo size 20% of QR
                $builder->logoPunchoutBackground(true);
            }
        }

        if (!empty($settings['show_label'])) {
            $labelText = $settings['label_text'] ?? $content;
            $builder->labelText($labelText);
            $builder->labelFont(new NotoSans(14));
            $builder->labelAlignment(LabelAlignment::Center);
        }

        return $builder->build();
    }

    private function applyFrameStyle($imageString, $style)
    {
        $src = imagecreatefromstring($imageString);
        if (!$src) return $imageString;

        $width = imagesx($src);
        $height = imagesy($src);

        // Create compatible image
        $dst = imagecreatetruecolor($width, $height);

        // Handle transparency
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);

        if ($style === 'circle') {
            $centerX = $width / 2;
            $centerY = $height / 2;
            $radius = min($width, $height) / 2;

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $dx = $x - $centerX;
                    $dy = $y - $centerY;
                    if ($dx * $dx + $dy * $dy <= $radius * $radius) {
                        $rgb = imagecolorat($src, $x, $y);
                        imagesetpixel($dst, $x, $y, $rgb);
                    }
                }
            }
        } elseif ($style === 'rounded') {
            $radius = min($width, $height) * 0.10; // 10% radius

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $shouldDraw = true;
                    // Check corners
                    if ($x < $radius && $y < $radius) { // TL
                        if (($x - $radius) * ($x - $radius) + ($y - $radius) * ($y - $radius) > $radius * $radius) $shouldDraw = false;
                    } elseif ($x > $width - $radius && $y < $radius) { // TR
                        if (($x - ($width - $radius)) * ($x - ($width - $radius)) + ($y - $radius) * ($y - $radius) > $radius * $radius) $shouldDraw = false;
                    } elseif ($x < $radius && $y > $height - $radius) { // BL
                        if (($x - $radius) * ($x - $radius) + ($y - ($height - $radius)) * ($y - ($height - $radius)) > $radius * $radius) $shouldDraw = false;
                    } elseif ($x > $width - $radius && $y > $height - $radius) { // BR
                        if (($x - ($width - $radius)) * ($x - ($width - $radius)) + ($y - ($height - $radius)) * ($y - ($height - $radius)) > $radius * $radius) $shouldDraw = false;
                    }

                    if ($shouldDraw) {
                        $rgb = imagecolorat($src, $x, $y);
                        imagesetpixel($dst, $x, $y, $rgb);
                    }
                }
            }
        } else {
            // Default copy
            imagecopy($dst, $src, 0, 0, 0, 0, $width, $height);
        }

        ob_start();
        imagepng($dst);
        $finalImage = ob_get_clean();



        return $finalImage;
    }

    public function preview()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return $this->response->setStatusCode(403);
        }

        $originalUrl = $this->request->getPost('original_url');
        if (!$originalUrl) {
            return $this->response->setStatusCode(400);
        }

        $settings = [
            'size' => $this->request->getPost('size') ?: 300,
            'qr_color' => $this->request->getPost('qr_color') ?: '#000000',
            'bg_color' => $this->request->getPost('bg_color') ?: '#FFFFFF',
            'frame_style' => $this->request->getPost('frame_style') ?: 'none',
            'show_label' => $this->request->getPost('show_label') ? 1 : 0,
            'label_text' => $this->request->getPost('custom_name') ?: $originalUrl,
        ];

        // Handle Logo Logic for Preview
        $settings['logo_path'] = null; // Default to no logo

        $logo = $this->request->getFile('logo');

        // 1. Check if new logo is being uploaded
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(FCPATH . 'uploads/qr_logos', $newName);
            $settings['logo_path'] = $newName;
        }
        // 2. Check if user wants to remove the logo
        elseif ($this->request->getPost('remove_logo') == '1') {
            $settings['logo_path'] = null;
        }
        // 3. Fallback to current existing logo
        else {
            $currentLogo = $this->request->getPost('current_logo');
            if ($currentLogo) {
                $settings['logo_path'] = basename($currentLogo);
            }
        }

        try {
            $resultString = $this->generateFinalQRString($originalUrl, $settings);
            return $this->response->setContentType('image/png')->setBody($resultString);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody($e->getMessage());
        }
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
