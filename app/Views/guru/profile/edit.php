<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .edit-header-icon {
        font-size: 40px;
        opacity: 0.9;
    }

    .edit-header-content h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .edit-header-content p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .form-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #667eea;
        font-size: 18px;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .picture-preview-box {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .picture-preview-box.has-image {
        border: none;
        padding: 0;
    }

    .picture-preview-box:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.02);
    }

    .picture-preview-box img {
        max-width: 100%;
        max-height: 250px;
        border-radius: 8px;
        display: block;
        margin: 0 auto;
    }

    .custom-file-input {
        display: none;
    }

    .file-input-label {
        display: inline-block;
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .file-input-label:hover {
        background: #764ba2;
        transform: translateY(-2px);
    }

    .button-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-save {
        background: #667eea;
        color: white;
    }

    .btn-save:hover {
        background: #764ba2;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #333;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .alert {
        border-radius: 6px;
        border: 1px solid;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    /* Crop Modal Styles */
    .crop-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .crop-modal-overlay.show {
        display: flex;
    }

    .crop-modal {
        background: white;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .crop-modal-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .crop-modal-header h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .crop-modal-header .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #999;
        transition: color 0.3s;
    }

    .crop-modal-header .close-btn:hover {
        color: #333;
    }

    .crop-modal-body {
        flex: 1;
        padding: 20px;
        overflow: auto;
    }

    .crop-container {
        max-width: 100%;
        max-height: 400px;
    }

    .crop-container img {
        max-width: 100%;
    }

    .crop-modal-footer {
        padding: 20px;
        border-top: 1px solid #eee;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-crop-confirm {
        background: #28a745;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-crop-confirm:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .btn-crop-cancel {
        background: #f0f0f0;
        color: #333;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-crop-cancel:hover {
        background: #e0e0e0;
    }

    .crop-tools {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .crop-tool-btn {
        padding: 8px 12px;
        background: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
    }

    .crop-tool-btn:hover {
        background: #e0e0e0;
    }

    @media (max-width: 768px) {
        .edit-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .button-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .crop-modal {
            width: 95%;
            max-height: 90vh;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="edit-header">
        <div class="edit-header-icon">
            <i class="fas fa-user-edit"></i>
        </div>
        <div class="edit-header-content">
            <h1>Edit Profil</h1>
            <p>Perbarui informasi pribadi dan data akun Anda</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div style="margin-bottom: 20px;">
        <div style="display: flex; gap: 15px; border-bottom: 2px solid #f0f0f0; flex-wrap: wrap;">
            <a href="<?= base_url('guru/profile') ?>" style="padding: 12px 0; color: #999; text-decoration: none; font-weight: 500; border-bottom: 3px solid transparent; transition: all 0.3s;">
                <i class="fas fa-user-circle"></i> Profil
            </a>
            <a href="<?= base_url('guru/profile/edit') ?>" style="padding: 12px 0; color: #667eea; text-decoration: none; font-weight: 500; border-bottom: 3px solid #667eea;">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            <a href="<?= base_url('guru/profile/change-password') ?>" style="padding: 12px 0; color: #999; text-decoration: none; font-weight: 500; border-bottom: 3px solid transparent; transition: all 0.3s;">
                <i class="fas fa-key"></i> Ubah Password
            </a>
        </div>
    </div>

    <form action="<?= base_url('guru/profile/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" id="remove_picture" name="remove_picture" value="0">
            <input type="hidden" id="croppedImageData" name="cropped_image_data" value="">
            <input type="hidden" id="remove_banner" name="remove_banner" value="0">
            <input type="hidden" id="croppedBannerData" name="cropped_banner_data" value="">

        <!-- Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Data Pribadi Section -->
        <div class="form-card">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-id-card"></i> Data Pribadi
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               value="<?= old('nama', $user['nama']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nip"><i class="fas fa-barcode"></i> NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" 
                               value="<?= old('nip', $user['nip'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= old('email', $user['email']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="no_telepon"><i class="fas fa-phone"></i> No. Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                               value="<?= old('no_telepon', $user['no_telepon'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="4" 
                              placeholder="Masukkan alamat lengkap Anda"><?= old('alamat', $user['alamat'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Foto Profil Section -->
        <!-- Banner Section -->
        <div class="form-card">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-image"></i> Banner Profil
                </h3>

                <div class="picture-preview-box banner-preview <?= (!empty($user['banner'])) ? 'has-image' : '' ?>" style="padding:0;">
                    <?php if (!empty($user['banner'])): ?>
                        <img id="bannerPreviewImage" src="<?= base_url('uploads/profile_banners/' . $user['banner']) ?>" alt="Banner Preview" style="width:100%;height:auto;border-radius:8px;max-height:240px;object-fit:cover;">
                    <?php else: ?>
                        <div id="bannerPreviewPlaceholder" style="background: linear-gradient(135deg, #f5e105ff 0%, #14d209ff 100%);height:140px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">Banner Default</div>
                    <?php endif; ?>
                </div>

                <input type="file" class="custom-file-input" id="banner_image" name="banner_image" accept="image/*">
                <label for="banner_image" class="file-input-label">
                    <i class="fas fa-cloud-upload-alt"></i> Pilih Banner
                </label>

                <?php if (!empty($user['banner'])): ?>
                    <button type="button" class="btn btn-delete" id="remove_banner_btn" style="margin-left: 10px;">
                        <i class="fas fa-trash"></i> Hapus Banner
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-card">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-image"></i> Foto Profil
                </h3>
                
                <div class="picture-preview-box <?= (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png') ? 'has-image' : '' ?>">
                    <img id="preview" 
                         src="<?= !empty($user['profile_picture']) ? base_url('uploads/profile_pictures/' . $user['profile_picture']) : base_url('uploads/profile_pictures/default.png') ?>" 
                         alt="Preview Foto Profil">
                </div>
                
                <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture" accept="image/*">
                <label for="profile_picture" class="file-input-label">
                    <i class="fas fa-cloud-upload-alt"></i> Pilih Foto
                </label>
                
                <?php if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png'): ?>
                    <button type="button" class="btn btn-delete" id="remove_picture_btn" style="margin-left: 10px;">
                        <i class="fas fa-trash"></i> Hapus Foto
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Crop Modal -->
        <div class="crop-modal-overlay" id="cropModalOverlay">
            <div class="crop-modal">
                <div class="crop-modal-header">
                    <h2><i class="fas fa-crop"></i> Crop Foto Profil</h2>
                    <button type="button" class="close-btn" id="closeCropModal">&times;</button>
                </div>
                <div class="crop-modal-body">
                    <div class="crop-tools">
                        <button type="button" class="crop-tool-btn" id="rotateCW">
                            <i class="fas fa-redo"></i> Putar Kanan
                        </button>
                        <button type="button" class="crop-tool-btn" id="rotateCCW">
                            <i class="fas fa-undo"></i> Putar Kiri
                        </button>
                        <button type="button" class="crop-tool-btn" id="resetCrop">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                    <div class="crop-container">
                        <img id="cropImage" src="" alt="Crop image">
                    </div>
                </div>
                <div class="crop-modal-footer">
                    <button type="button" class="btn-crop-cancel" id="cancelCrop">Batal</button>
                    <button type="button" class="btn-crop-confirm" id="confirmCrop">Gunakan Foto Ini</button>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="form-card">
            <div class="button-group">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="<?= base_url('guru/profile') ?>" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Crop Modal for Banner -->
<div class="crop-modal-overlay" id="cropBannerModalOverlay">
    <div class="crop-modal">
        <div class="crop-modal-header">
            <h2><i class="fas fa-crop"></i> Crop Banner</h2>
            <button type="button" class="close-btn" id="closeBannerCropModal">&times;</button>
        </div>
        <div class="crop-modal-body">
            <div class="crop-tools">
                <button type="button" class="crop-tool-btn" id="bannerRotateCW">
                    <i class="fas fa-redo"></i> Putar Kanan
                </button>
                <button type="button" class="crop-tool-btn" id="bannerRotateCCW">
                    <i class="fas fa-undo"></i> Putar Kiri
                </button>
                <button type="button" class="crop-tool-btn" id="bannerResetCrop">
                    <i class="fas fa-sync"></i> Reset
                </button>
            </div>
            <div class="crop-container">
                <img id="cropImageBanner" src="" alt="Crop banner image">
            </div>
        </div>
        <div class="crop-modal-footer">
            <button type="button" class="btn-crop-cancel" id="cancelBannerCrop">Batal</button>
            <button type="button" class="btn-crop-confirm" id="confirmBannerCrop">Gunakan Banner Ini</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
// Crop Image Variables
let cropper;
let selectedFile;

// Open crop modal when file is selected
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    selectedFile = file;
    const reader = new FileReader();
    
    reader.onload = function(event) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = event.target.result;
        
        // Show modal
        document.getElementById('cropModalOverlay').classList.add('show');
        
        // Initialize cropper
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            restore: true,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: true,
            background: true,
            modal: true,
            movable: true
        });
    };
    
    reader.readAsDataURL(file);
});

// Close crop modal
document.getElementById('closeCropModal').addEventListener('click', closeCropModal);
document.getElementById('cancelCrop').addEventListener('click', closeCropModal);

function closeCropModal() {
    document.getElementById('cropModalOverlay').classList.remove('show');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('profile_picture').value = '';
}

// Rotate buttons
document.getElementById('rotateCW').addEventListener('click', function(e) {
    e.preventDefault();
    if (cropper) cropper.rotate(45);
});

document.getElementById('rotateCCW').addEventListener('click', function(e) {
    e.preventDefault();
    if (cropper) cropper.rotate(-45);
});

document.getElementById('resetCrop').addEventListener('click', function(e) {
    e.preventDefault();
    if (cropper) cropper.reset();
});

// Confirm crop
document.getElementById('confirmCrop').addEventListener('click', function(e) {
    e.preventDefault();
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas();
    const croppedImageData = canvas.toDataURL();
    
    // Set preview
    const preview = document.getElementById('preview');
    preview.src = croppedImageData;
    document.querySelector('.picture-preview-box').classList.add('has-image');
    
    // Store cropped image data
    document.getElementById('croppedImageData').value = croppedImageData;
    
    // Show remove button if it's hidden
    const removeBtn = document.getElementById('remove_picture_btn');
    if (removeBtn) {
        removeBtn.style.display = 'inline-flex';
    }
    
    closeCropModal();
});

// Close modal when clicking outside
document.getElementById('cropModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCropModal();
    }
});

// Handle remove picture
function handleRemovePicture(e) {
    e.preventDefault();
    const preview = document.getElementById('preview');
    const previewBox = preview.parentElement;
    
    preview.src = '<?= base_url('uploads/profile_pictures/default.png') ?>';
    previewBox.classList.remove('has-image');
    document.getElementById('croppedImageData').value = '';
    document.getElementById('remove_picture').value = '1';
    this.style.display = 'none';
}

// Hapus foto
if (document.getElementById('remove_picture_btn')) {
    document.getElementById('remove_picture_btn').addEventListener('click', handleRemovePicture);
}

// Banner crop variables
let bannerCropper;
let selectedBannerFile;

// Banner file selected -> open banner crop modal
document.getElementById('banner_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    selectedBannerFile = file;
    const reader = new FileReader();
    reader.onload = function(event) {
        const cropImage = document.getElementById('cropImageBanner');
        cropImage.src = event.target.result;
        document.getElementById('cropBannerModalOverlay').classList.add('show');

        if (bannerCropper) bannerCropper.destroy();
        bannerCropper = new Cropper(cropImage, {
            aspectRatio: 3.2,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            restore: true,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: true
        });
    };
    reader.readAsDataURL(file);
});

// Banner modal controls
document.getElementById('closeBannerCropModal').addEventListener('click', function() {
    document.getElementById('cropBannerModalOverlay').classList.remove('show');
    if (bannerCropper) { bannerCropper.destroy(); bannerCropper = null; }
    document.getElementById('banner_image').value = '';
});
document.getElementById('cancelBannerCrop').addEventListener('click', function() {
    document.getElementById('cropBannerModalOverlay').classList.remove('show');
    if (bannerCropper) { bannerCropper.destroy(); bannerCropper = null; }
    document.getElementById('banner_image').value = '';
});

document.getElementById('bannerRotateCW').addEventListener('click', function(e){ e.preventDefault(); if (bannerCropper) bannerCropper.rotate(45); });
document.getElementById('bannerRotateCCW').addEventListener('click', function(e){ e.preventDefault(); if (bannerCropper) bannerCropper.rotate(-45); });
document.getElementById('bannerResetCrop').addEventListener('click', function(e){ e.preventDefault(); if (bannerCropper) bannerCropper.reset(); });

document.getElementById('confirmBannerCrop').addEventListener('click', function(e){
    e.preventDefault();
    if (!bannerCropper) return;
    const canvas = bannerCropper.getCroppedCanvas({ width: 1200 });
    const croppedData = canvas.toDataURL();

    // Set preview
    const bannerPrevImg = document.getElementById('bannerPreviewImage');
    if (bannerPrevImg) {
        bannerPrevImg.src = croppedData;
    } else {
        const placeholder = document.getElementById('bannerPreviewPlaceholder');
        if (placeholder) {
            const parent = placeholder.parentElement;
            parent.innerHTML = '<img id="bannerPreviewImage" src="' + croppedData + '" alt="Banner Preview" style="width:100%;height:auto;border-radius:8px;max-height:240px;object-fit:cover;">';
        }
    }

    document.getElementById('croppedBannerData').value = croppedData;
    const removeBtn = document.getElementById('remove_banner_btn');
    if (removeBtn) removeBtn.style.display = 'inline-flex';

    document.getElementById('cropBannerModalOverlay').classList.remove('show');
    if (bannerCropper) { bannerCropper.destroy(); bannerCropper = null; }
});

// Close banner modal when clicking outside
document.getElementById('cropBannerModalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.remove('show');
        if (bannerCropper) { bannerCropper.destroy(); bannerCropper = null; }
        document.getElementById('banner_image').value = '';
    }
});

// Remove banner
function handleRemoveBanner(e) {
    e.preventDefault();
    const previewImg = document.getElementById('bannerPreviewImage');
    const placeholder = document.getElementById('bannerPreviewPlaceholder');
    if (previewImg) {
        previewImg.parentElement.innerHTML = '<div id="bannerPreviewPlaceholder" style="background: linear-gradient(135deg, #f5e105ff 0%, #14d209ff 100%);height:140px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">Banner Default</div>';
    }
    document.getElementById('croppedBannerData').value = '';
    document.getElementById('remove_banner').value = '1';
    this.style.display = 'none';
}

if (document.getElementById('remove_banner_btn')) {
    document.getElementById('remove_banner_btn').addEventListener('click', handleRemoveBanner);
}

// Intercept form submit to convert cropped banner base64 into a binary File
const profileForm = document.querySelector('form[action="<?= base_url('guru/profile/update') ?>"]');
if (profileForm) {
    profileForm.addEventListener('submit', function(e) {
        // If there is cropped banner base64 data, convert to File and send via FormData
        const croppedBannerVal = document.getElementById('croppedBannerData').value;
        if (croppedBannerVal && croppedBannerVal.length > 100) {
            e.preventDefault();

            // helper to convert dataURL to File
            function dataURLtoFile(dataurl, filename) {
                const arr = dataurl.split(',');
                const mime = arr[0].match(/:(.*?);/)[1];
                const bstr = atob(arr[1]);
                let n = bstr.length;
                const u8arr = new Uint8Array(n);
                while(n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new File([u8arr], filename, { type: mime });
            }

            // create FormData and append all form inputs
            const fd = new FormData(profileForm);

            // Remove the cropped banner base64 so server doesn't pick base64 route
            fd.set('cropped_banner_data', '');

            // create File from base64 and append as banner_image
            try {
                const extMatch = croppedBannerVal.match(/^data:image\/(\w+);base64,/);
                const ext = extMatch ? (extMatch[1] === 'jpeg' ? 'jpg' : extMatch[1]) : 'png';
                const fileName = 'banner_' + Date.now() + '.' + ext;
                const bannerFile = dataURLtoFile(croppedBannerVal, fileName);
                fd.set('banner_image', bannerFile);
            } catch (err) {
                console.error('Failed to convert cropped banner to file', err);
            }

            // send via fetch
            fetch(profileForm.action, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            }).then(function(response) {
                if (response.redirected) {
                    window.location.href = response.url;
                } else if (response.ok) {
                    // fallback: reload
                    window.location.reload();
                } else {
                    alert('Gagal mengunggah banner. Silakan coba lagi.');
                }
            }).catch(function(err) {
                console.error(err);
                alert('Terjadi kesalahan saat mengunggah banner.');
            });
        }
    });
}
</script>

<?= $this->endSection() ?>
