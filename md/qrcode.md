## **Arsitektur Sistem & Alur Logika**

### **1. Struktur Data yang Dibutuhkan**
```
- URL Data:
  • ID (primary key)
  • Original URL (input user)
  • Custom Slug (opsional)
  • QR Code Data (path file/generated data)
  • Customization Settings:
    - QR Color
    - Background Color  
    - Size
    - Logo (opsional)
    - Frame Style (opsional)
  • Created Date
  • User ID (jika ada sistem user)
```

### **2. Alur Proses Utama**

#### **A. Input & Validasi URL**
```
1. User memasukkan URL di form
2. Sistem validasi:
   - Format URL valid (http/https)
   - URL accessible (opsional)
   - Safe URL (security check)
3. Generate unique identifier untuk URL
```

#### **B. Generate Tombol/Short Link**
```
1. Buat short link berdasarkan:
   - Auto-generate random string, ATAU
   - Custom slug dari user
2. Simpan mapping: short-link → original URL
3. Generate HTML button code untuk embed
```

#### **C. Generate & Customize QR Code**
```
1. Ambil URL target (original atau short link)
2. Terima parameter kustomisasi dari user:
   - Warna QR
   - Warna background  
   - Size (200x200, 300x300, etc)
   - Tambah logo di center
   - Style frame/border
3. Generate QR code image dengan parameter tersebut
4. Simpan sebagai file/image data
```

### **3. Flow Processing Logic**

#### **Scenario: User Input URL Baru**
```
1. POST Request → Controller
   ↓
2. Validation Layer
   - URL format check
   - Security sanitization
   ↓
3. Processing Engine
   - Generate unique ID/slug
   - Create short URL
   - Apply QR customization params
   - Generate QR code image
   ↓
4. Output Generation
   - Return: Short URL, Button HTML, QR Image
   - Save to database
   ↓  
5. Response ke User
   - Display generated assets
   - Download options
```

#### **Scenario: Customize QR Code**
```
1. User pilih existing URL
   ↓
2. Load current QR settings
   ↓  
3. User ubah parameters:
   - Color picker untuk QR color
   - Color picker untuk background
   - Size slider/select
   - Upload logo (validate: size, format)
   - Pilih frame style
   ↓
4. Regenerate QR dengan new params
   ↓
5. Preview real-time (AJAX) atau submit
   ↓
6. Save new version/history
```

### **4. Komponen yang Diperlukan**

#### **A. Backend Processing**
```
- URL Validator & Sanitizer
- QR Code Generator (library: endroid/qr-code)
- Image Processor (GD/ImageMagick)
- File Manager (simpan QR images)
- Security Layer (anti-malware URL)
```

#### **B. Frontend Interface**  
```
- Form input URL
- QR Customization Panel:
  • Color pickers
  • Size controls  
  • Logo upload
  • Style selector
- Preview area (real-time)
- Download buttons
- Embed code generator
```

#### **C. Output Components**
```
- Short URL (redirect system)
- QR Code Image (multiple formats)
- HTML Button Code
- Shareable links
- Analytics (opsional)
```

### **5. Security Considerations**

```
1. URL Validation:
   - Block malicious URLs
   - Check for phishing patterns
   - Validate URL reachability

2. File Upload Safety:
   - Validate logo images
   - Scan for malicious files
   - Limit file size/types

3. Rate Limiting:
   - Prevent spam generation
   - Limit requests per user
```

### **6. Enhancement Opportunities**

```
- Batch URL processing
- QR code analytics (scan tracking)
- Template system untuk QR designs
- API endpoint untuk external apps
- Bulk export functionality
- History/archive management
```


# **Struktur Project CodeIgniter 4 untuk Fitur URL to QR Code**

## **1. Struktur Folder & File**

```
/app
├── Config/
│   └── QRCode.php (konfigurasi QR code)
├── Controllers/
│   ├── UrlQRController.php
│   └── Admin/ (jika butuh admin panel)
├── Models/
│   ├── UrlModel.php
│   └── QRModel.php
├── Entities/
│   ├── UrlEntity.php
│   └── QREntity.php
├── Views/
│   ├── url_qr/
│   │   ├── create.php (form input)
│   │   ├── result.php (hasil generate)
│   │   ├── customize.php (kustomisasi QR)
│   │   └── list.php (daftar URL)
│   └── templates/ (header, footer)
├── Libraries/
│   ├── QRGenerator.php
│   └── URLValidator.php
├── Helpers/
│   └── qr_helper.php
└── Filters/
    └── AuthFilter.php (jika butuh auth)

/public/
├── assets/
│   ├── qr_codes/ (folder simpan QR images)
│   ├── js/
│   │   ├── qr-customizer.js
│   │   └── url-validator.js
│   ├── css/
│   │   └── qr-styles.css
│   └── uploads/ (logo user)
└── index.php

/writable/
├── qr_tmp/ (temporary files)
└── logs/
```

## **2. Database Structure**

```sql
-- Tabel untuk menyimpan data URL
CREATE TABLE url_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL, -- jika ada sistem user
    original_url TEXT NOT NULL,
    short_slug VARCHAR(50) UNIQUE,
    custom_name VARCHAR(255) NULL,
    click_count INT DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME
);

-- Tabel untuk menyimpan setting QR code
CREATE TABLE qr_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    url_id INT,
    qr_color VARCHAR(7) DEFAULT '#000000',
    bg_color VARCHAR(7) DEFAULT '#FFFFFF',
    size INT DEFAULT 300,
    logo_path VARCHAR(255) NULL,
    frame_style ENUM('none', 'square', 'circle', 'rounded') DEFAULT 'none',
    error_correction ENUM('L', 'M', 'Q', 'H') DEFAULT 'L',
    version INT DEFAULT 5,
    created_at DATETIME,
    FOREIGN KEY (url_id) REFERENCES url_entries(id)
);

-- Tabel untuk tracking scan QR (opsional)
CREATE TABLE qr_analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    qr_id INT,
    scanned_at DATETIME,
    ip_address VARCHAR(45),
    user_agent TEXT,
    country VARCHAR(100),
    FOREIGN KEY (qr_id) REFERENCES url_entries(id)
);
```

## **3. Routing Structure**

```php
// app/Config/Routes.php

$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('qr', 'UrlQRController::index');
    $routes->post('qr/generate', 'UrlQRController::generate');
    $routes->get('qr/customize/(:num)', 'UrlQRController::customize/$1');
    $routes->post('qr/update/(:num)', 'UrlQRController::update/$1');
    $routes->get('qr/history', 'UrlQRController::history');
    $routes->get('qr/delete/(:num)', 'UrlQRController::delete/$1');
    $routes->get('qr/download/(:num)', 'UrlQRController::download/$1');
});

// Public routes (tanpa auth)
$routes->get('s/(:segment)', 'UrlQRController::redirect/$1');
$routes->get('qr/public', 'UrlQRController::publicGenerate');
$routes->post('qr/public-generate', 'UrlQRController::publicGeneratePost');
```

## **4. Controller Structure**

### **UrlQRController.php**
```php
<?php
namespace App\Controllers;

class UrlQRController extends BaseController
{
    public function index()
    {
        // Show form input URL
    }
    
    public function generate()
    {
        // Process URL input & generate initial QR
    }
    
    public function customize($id)
    {
        // Show customization form
    }
    
    public function update($id)
    {
        // Process QR customization
    }
    
    public function history()
    {
        // Show user's URL & QR history
    }
    
    public function download($id)
    {
        // Download QR in various formats
    }
    
    public function redirect($slug)
    {
        // Handle short URL redirection + tracking
    }
    
    public function publicGenerate()
    {
        // Public QR generator (no auth required)
    }
}
```

## **5. Model Structure**

### **UrlModel.php**
```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class UrlModel extends Model
{
    protected $table = 'url_entries';
    protected $primaryKey = 'id';
    
    public function createUrl($data)
    {
        // Create new URL entry
    }
    
    public function getUrlBySlug($slug)
    {
        // Get URL by short slug
    }
    
    public function getUserUrls($user_id)
    {
        // Get user's URL history
    }
}
```

### **QRModel.php**
```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class QRModel extends Model
{
    protected $table = 'qr_settings';
    
    public function saveQRSettings($data)
    {
        // Save QR customization settings
    }
    
    public function getQRSettings($url_id)
    {
        // Get QR settings for URL
    }
}
```

## **6. Library Structure**

### **QRGenerator.php**
```php
<?php
namespace App\Libraries;

class QRGenerator
{
    public function generate($url, $settings = [])
    {
        // Generate QR code dengan custom settings
    }
    
    public function customize($qrData, $settings)
    {
        // Apply customization to existing QR
    }
    
    public function saveAsFile($qrData, $filename, $format = 'png')
    {
        // Save QR as image file
    }
    
    public function download($qrData, $filename, $format = 'png')
    {
        // Force download QR
    }
}
```

### **URLValidator.php**
```php
<?php
namespace App\Libraries;

class URLValidator
{
    public function validate($url)
    {
        // Validate URL format & safety
    }
    
    public function isSafe($url)
    {
        // Check if URL is safe (malware, phishing)
    }
    
    public function generateSlug()
    {
        // Generate unique short slug
    }
}
```

## **7. Configuration Files**

### **app/Config/QRCode.php**
```php
<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class QRCode extends BaseConfig
{
    public $defaultSize = 300;
    public $defaultColor = '#000000';
    public $defaultBgColor = '#FFFFFF';
    public $allowedFormats = ['png', 'svg', 'eps'];
    public $maxFileSize = 2048; // KB untuk logo
    public $allowedMimes = ['image/png', 'image/jpeg', 'image/gif'];
}
```

## **8. Helper Functions**

### **app/Helpers/qr_helper.php**
```php
<?php
if (!function_exists('qr_download_link')) {
    function qr_download_link($qr_id, $format = 'png')
    {
        // Generate download link
    }
}

if (!function_exists('qr_embed_code')) {
    function qr_embed_code($qr_url, $size = 'medium')
    {
        // Generate embed code untuk website
    }
}
```

## **9. Migration Files**

```bash
php spark make:migration CreateUrlEntries
php spark make:migration CreateQRSettings  
php spark make:migration CreateQRAnalytics
```

## **10. Filter (Optional)**

### **app/Filters/AuthFilter.php**
```php
<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in untuk fitur premium
    }
}
```

## **11. View Components**

### **Layout Views:**
- `header.php` - Header template
- `footer.php` - Footer template  
- `sidebar.php` - Navigation sidebar

### **Feature Views:**
- `create.php` - Form input URL
- `customize.php` - QR customization panel
- `result.php` - Hasil generate + download options
- `list.php` - History & management

## **12. Asset Structure**

### **CSS (qr-styles.css):**
```css
.qr-preview { styles }
.color-picker { styles }
.size-slider { styles }
.qr-button { styles }
```

### **JavaScript (qr-customizer.js):**
```javascript
// Real-time QR preview
// Color picker functionality
// AJAX save settings
// Download handlers
```
# **Struktur Logika CodeIgniter 4 - URL to QR Code**

## **1. Arsitektur Logika MVC**

### **A. Controller - UrlQRController**
```
index()
├── Tampilkan form input URL
├── Load view dengan default settings

generate() [POST]
├── Validasi input URL
├── Cek keamanan URL
├── Generate short slug
├── Buat QR code dasar
├── Simpan ke database
├── Redirect ke halaman customize

customize($id)
├── Ambil data URL berdasarkan ID
├── Load QR settings saat ini
├── Tampilkan form kustomisasi
├── Show real-time preview

update($id) [POST]
├── Terima parameter kustomisasi
├── Validasi input (colors, size, logo)
├── Generate QR dengan setting baru
├── Simpan file QR image
├── Update database settings
├── Berikan response JSON/Success

download($id)
├── Ambil data QR berdasarkan ID
├── Generate file download
├── Set headers untuk download
├── Output file dalam format png/svg

redirect($slug)
├── Cari original URL berdasarkan slug
├── Log analytics (jika ada)
├── Redirect ke original URL
```

### **B. Model - UrlModel & QRModel**

#### **UrlModel**
```
createUrl($data)
├── Generate unique slug
├── Validasi duplikasi URL
├── Insert data ke tabel url_entries
├── Return ID created

getUrlBySlug($slug)
├── Query database by slug
├── Increment click_count
├── Return URL data

getUserUrls($user_id)
├── Get semua URL oleh user
├── Join dengan QR settings
├── Return paginated results
```

#### **QRModel**
```
saveQRSettings($data)
├── Cek apakah setting sudah ada (update/insert)
├── Handle logo upload
├── Simpan path file QR
├── Return success status

getQRSettings($url_id)
├── Ambil settings berdasarkan url_id
├── Include default values jika tidak ada
├── Return array settings
```

## **2. Flow Processing Logic**

### **Scenario: Generate QR Baru**
```
User Input URL → 
Validation Layer → 
Generate Short Slug → 
Create Basic QR → 
Save to Database → 
Show Customization Panel → 
Real-time Preview → 
Save Customization → 
Download/Share
```

### **Scenario: Customize Existing QR**
```
Select QR from History → 
Load Current Settings → 
Modify Parameters → 
Real-time Preview (AJAX) → 
Save New Settings → 
Regenerate QR Image → 
Update Database
```

## **3. Library Logic Flow**

### **QRGenerator Library**
```
generate($url, $settings)
├── Validate input parameters
├── Set default values untuk missing settings
├── Generate QR data dengan library external
├── Apply customization (colors, logo, frame)
├── Return QR image data

customize($qrData, $settings)
├── Parse existing QR data
├── Apply new customization settings
├── Maintain error correction levels
├── Return modified QR data

saveAsFile($qrData, $filename, $format)
├── Validate writable directory
├── Generate file path
├── Save image dalam berbagai format
├── Return file path untuk database
```

### **URLValidator Library**
```
validate($url)
├── Check URL format validity
├── Verify URL accessibility (optional)
├── Sanitize malicious characters
├── Return validation result

isSafe($url)
├── Check against malware databases
├── Verify SSL certificate (https)
├── Domain reputation check
├── Return safety status

generateSlug()
├── Generate random string
├── Check database for uniqueness
├── Return available slug
```

## **4. Database Interaction Logic**

### **Create New Entry**
```
1. Start transaction
2. Insert to url_entries → get ID
3. Insert default settings to qr_settings
4. Generate initial QR image
5. Commit transaction
6. Return success dengan URL ID
```

### **Update Customization**
```
1. Get existing settings
2. Handle logo upload (if any)
3. Generate new QR dengan updated settings
4. Update qr_settings table
5. Update QR image file
6. Return new file path
```

## **5. File Management Logic**

### **QR Image Storage**
```
/public/assets/qr_codes/
├── {user_id}/ (jika login)
│   ├── {url_id}.png
│   ├── {url_id}_original.png
│   └── thumbs/ (untuk preview)
└── public/ (untuk guest users)
    ├── {session_id}/
    └── temp/ (auto-cleanup)
```

### **Logo Upload Handling**
```
1. Validate file (type, size, dimensions)
2. Generate unique filename
3. Resize logo untuk QR compatibility
4. Save to /public/assets/uploads/logos/
5. Store path in database
6. Cleanup old logos on update
```

## **6. Security Logic Layers**

### **Input Validation Chain**
```
URL Input:
1. Format validation (filter_var)
2. Scheme check (http/https only)
3. Length limits
4. Malicious pattern detection

File Upload:
1. MIME type verification
2. File size limits
3. Image dimension checks
4. Virus scan (optional)

Customization Parameters:
1. Color format validation
2. Size range limits
3. CSS injection prevention
```

## **7. Error Handling Logic**

### **Validation Errors**
```
- Return to form dengan error messages
- Highlight invalid fields
- Preserve valid input data
```

### **Generation Errors**
```
- Log error untuk debugging
- User-friendly error message
- Suggest alternative parameters
```

### **Database Errors**
```
- Transaction rollback
- Consistent state maintenance
- Error reporting mechanism
```

## **8. Performance Optimization Logic**

### **Caching Strategy**
```
- Cache generated QR images
- Cache URL lookup results
- Session storage untuk temporary data
```

### **Cleanup Procedures**
```
- Scheduled cleanup of temporary files
- Orphaned file detection and removal
- Database optimization routines
```

## **9. User Experience Flow**

### **Guest User Flow**
```
Input URL → Generate → Customize → Download → Done
(Session-based temporary storage)
```

### **Registered User Flow**  
```
Login → Input URL → Generate → Save → Customize → 
History Management → Analytics → Share → Download
(Permanent storage dengan management)
```
