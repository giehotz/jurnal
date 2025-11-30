<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Profil</h3>
            </div>
            <form action="<?= base_url('guru/profile/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" id="remove_picture" name="remove_picture" value="0">
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama', $user['nama']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="<?= old('nip', $user['nip'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telepon">No. Telepon</label>
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= old('no_telepon', $user['no_telepon'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= old('alamat', $user['alamat'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="profile_picture">Foto Profil</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture" accept="image/*">
                                        <label class="custom-file-label" for="profile_picture">Pilih file</label>
                                    </div>
                                </div>
                                <!-- Preview foto -->
                                <div class="mt-2">
                                    <img id="preview" src="<?= !empty($user['profile_picture']) ? base_url('uploads/profile_pictures/' . $user['profile_picture']) : base_url('uploads/profile_pictures/default.png') ?>" 
                                         alt="Preview Foto Profil" class="img-thumbnail" style="max-height: 200px; max-width: 100%;">
                                </div>
                                <?php if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png'): ?>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-danger btn-sm" id="remove_picture_btn">
                                        <i class="fas fa-trash"></i> Hapus Foto
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= base_url('guru/profile') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi untuk preview foto sebelum upload
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    const removeBtn = document.getElementById('remove_picture_btn');
    const removeInput = document.getElementById('remove_picture');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            // Sembunyikan tombol hapus saat ada file baru dipilih
            if (removeBtn) {
                removeBtn.style.display = 'none';
            }
            // Reset flag hapus foto
            if (removeInput) {
                removeInput.value = '0';
            }
        }
        
        reader.readAsDataURL(file);
    }
});

// Update label file input saat file dipilih
document.getElementById('profile_picture').addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || "Pilih file";
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});

// Fungsi untuk hapus foto
document.getElementById('remove_picture_btn').addEventListener('click', function() {
    const preview = document.getElementById('preview');
    const removeInput = document.getElementById('remove_picture');
    const fileInput = document.getElementById('profile_picture');
    
    // Ganti preview dengan foto default
    preview.src = '<?= base_url('uploads/profile_pictures/default.png') ?>';
    
    // Set flag untuk menghapus foto
    if (removeInput) {
        removeInput.value = '1';
    }
    
    // Reset input file
    if (fileInput) {
        fileInput.value = '';
        // Update label file input
        var nextSibling = fileInput.nextElementSibling;
        nextSibling.innerText = "Pilih file";
    }
    
    // Sembunyikan tombol hapus
    this.style.display = 'none';
});
</script>

<?= $this->endSection() ?>