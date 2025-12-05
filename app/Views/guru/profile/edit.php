<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
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

<script>
// Preview foto sebelum upload
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    const removeBtn = document.getElementById('remove_picture_btn');
    const removeInput = document.getElementById('remove_picture');
    const previewBox = preview.parentElement;
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewBox.classList.add('has-image');
            if (removeBtn) removeBtn.style.display = 'inline-flex';
            if (removeInput) removeInput.value = '0';
        }
        reader.readAsDataURL(file);
    }
});

// Hapus foto
if (document.getElementById('remove_picture_btn')) {
    document.getElementById('remove_picture_btn').addEventListener('click', function(e) {
        e.preventDefault();
        const preview = document.getElementById('preview');
        const removeInput = document.getElementById('remove_picture');
        const fileInput = document.getElementById('profile_picture');
        const previewBox = preview.parentElement;
        
        preview.src = '<?= base_url('uploads/profile_pictures/default.png') ?>';
        previewBox.classList.remove('has-image');
        if (removeInput) removeInput.value = '1';
        if (fileInput) fileInput.value = '';
        this.style.display = 'none';
    });
}
</script>

<?= $this->endSection() ?>