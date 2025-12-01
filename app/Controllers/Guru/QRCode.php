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
            $qrImage = $this->generateQRImage($url['original_url'], $settings);
        } catch (\Exception $e) {
            $qrImage = ''; // Handle error gracefully
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
            'custom_name'  => 'permit_empty|max_length[255]',
            'size'         => 'permit_empty|integer|greater_than[100]|less_than[1000]',
            'logo'         => 'permit_empty|uploaded[logo]|max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update URL info
        $this->urlModel->update($id, [
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
                
                // If removed, should we revert to default? 
                // Maybe user wants NO logo. But if default exists, maybe we should force it?
                // Let's say if they remove custom logo, and default exists, we use default.
                if (!empty($globalSettings['default_logo_path'])) {
                     $defaultLogoSource = FCPATH . $globalSettings['default_logo_path'];
                     if (file_exists($defaultLogoSource)) {
                         $extension = pathinfo($defaultLogoSource, PATHINFO_EXTENSION);
                         $newDefaultName = 'default_' . bin2hex(random_bytes(8)) . '.' . $extension;
                         copy($defaultLogoSource, FCPATH . 'uploads/qr_logos/' . $newDefaultName);
                         $logoPath = $newDefaultName;
                     }
                }
            }
        } else {
            // If custom logo not allowed, force default
            // If current logo is different from default (and not one of the auto-generated defaults), replace it?
            // This is tricky. For now, let's just ensure if they try to change it, we ignore it.
            // And if we want to enforce default on update:
            if (!empty($globalSettings['default_logo_path'])) {
                 // Check if current logo is valid? 
                 // Let's just re-apply default if it's missing or we want to be strict.
                 // Being strict:
                 if (empty($logoPath)) {
                     $defaultLogoSource = FCPATH . $globalSettings['default_logo_path'];
                     if (file_exists($defaultLogoSource)) {
                         $extension = pathinfo($defaultLogoSource, PATHINFO_EXTENSION);
                         $newDefaultName = 'default_' . bin2hex(random_bytes(8)) . '.' . $extension;
                         copy($defaultLogoSource, FCPATH . 'uploads/qr_logos/' . $newDefaultName);
                         $logoPath = $newDefaultName;
                     }
                 }
            }
        }
        
        $qrData['logo_path'] = $logoPath;

        $this->qrModel->update($settings['id'], $qrData);

        return redirect()->to('/guru/qrcode/show/' . $id)->with('success', 'QR Code updated successfully');
    }

    public function render($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'guru') {
            return redirect()->to('/auth/login');
        }

        $url = $this->urlModel->find($id);
        if (!$url || $url['user_id'] != session()->get('user_id')) {
            return $this->response->setStatusCode(404);
        }

        $settings = $this->qrModel->where('url_id', $id)->first();
        $settings['label_text'] = $url['custom_name'] ?: $url['original_url'];
        
        try {
            $result = $this->buildQR($url['original_url'], $settings);
            
            return response()->setHeader('Content-Type', $result->getMimeType())
                             ->setHeader('Cache-Control', 'public, max-age=31536000') // Cache for 1 year
                             ->setHeader('Pragma', 'public')
                             ->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT')
                             ->setBody($result->getString());
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
            $result = $this->buildQR($url['original_url'], $settings);
            
            $filename = preg_replace('/[^a-zA-Z0-9\s_\-]/', '', $url['custom_name']);
            $filename = str_replace(' ', '_', $filename);
            if (empty($filename)) {
                $filename = 'qrcode-' . $url['short_slug'];
            }

            return response()->setHeader('Content-Type', $result->getMimeType())
                             ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'.png"')
                             ->setBody($result->getString());
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
        // Also delete settings via foreign key cascade, but good to be explicit if needed.
        // Schema has CASCADE, so it should be fine.
        
        return redirect()->to('/guru/qrcode')->with('success', 'QR Code deleted successfully');
    }

    private function generateQRImage($content, $settings)
    {
        $result = $this->buildQR($content, $settings);
        return $result->getDataUri();
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
            // We need the custom name or original URL to show as label. 
            // Since buildQR only gets content and settings, we might need to pass the label text in settings or infer it.
            // For preview, we might not have the custom name easily if it's just input.
            // Let's assume 'label_text' is passed in settings or we use content.
            // But wait, 'content' is the URL. We probably want the 'custom_name'.
            // In store/update/preview, we should pass 'label_text'.
            
            $labelText = $settings['label_text'] ?? $content;
            $builder->labelText($labelText);
            $builder->labelFont(new NotoSans(20));
            $builder->labelAlignment(LabelAlignment::Center);
        }
            
        return $builder->build();
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

        // Handle Logo (Temporary upload or just ignore for preview if too complex, 
        // but for "wow" we should try. JS FormData can send files).
        // Handle Logo
        $logoOption = $this->request->getPost('logo_option');
        $globalSettings = $this->qrSettingsModel->getActiveSettings();

        if ($logoOption === 'custom') {
            $logo = $this->request->getFile('logo');
            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                $newName = $logo->getRandomName();
                $logo->move(FCPATH . 'uploads/qr_logos', $newName);
                $settings['logo_path'] = $newName;
            } else {
                // If custom selected but no new file, check if we have a current logo from edit
                $currentLogo = $this->request->getPost('current_logo');
                if ($currentLogo) {
                    $settings['logo_path'] = basename($currentLogo);
                }
            }
        } elseif ($logoOption === 'default') {
            if (!empty($globalSettings['default_logo_path'])) {
                $settings['logo_path'] = $globalSettings['default_logo_path'];
                // Note: buildQR expects just the filename if it's in uploads/qr_logos OR a full path?
                // Let's check buildQR. 
                // buildQR: $logoPath = FCPATH . 'uploads/qr_logos/' . $settings['logo_path'];
                // But default logo is in public/uploads/logos/ (usually).
                // Wait, default logo path in DB is relative to public, e.g. 'uploads/logos/default.png'.
                // buildQR logic at line 431: $logoPath = FCPATH . 'uploads/qr_logos/' . $settings['logo_path'];
                // This assumes all logos are in 'uploads/qr_logos'.
                // If we use default logo, it might be elsewhere.
                // We need to handle this in buildQR or copy it here.
                // Copying for preview seems wasteful.
                // Let's modify buildQR to handle absolute paths or check multiple locations?
                // Or better, let's pass the full path to buildQR if it's the default logo.
                
                // Actually, let's fix buildQR to be more flexible.
                // But for now, let's just hack the settings['logo_path'] to be a flag or handle it in buildQR.
                // If I change buildQR, it affects everything.
                
                // Let's look at buildQR again.
                // Line 430: if (!empty($settings['logo_path'])) {
                // Line 431:    $logoPath = FCPATH . 'uploads/qr_logos/' . $settings['logo_path'];
                
                // If I set $settings['logo_path'] to '../../uploads/logos/default.png', it might work?
                // FCPATH is root. uploads/qr_logos is inside public (or root/public).
                // If FCPATH points to public folder (CI4 default), then:
                // FCPATH . 'uploads/qr_logos/' . '../../uploads/logos/default.png' -> public/uploads/logos/default.png
                // This seems risky.
                
                // Better approach: Update buildQR to check if file exists at path relative to FCPATH first.
            }
        } else {
            // None
            $settings['logo_path'] = null;
        }

        try {
            $result = $this->buildQR($originalUrl, $settings);
            return $this->response->setContentType($result->getMimeType())->setBody($result->getString());
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setBody($e->getMessage());
        }
    }

    private function hexToRgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        return [$r, $g, $b];
    }
}
