<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">Edit QR Code</h1>
        <a href="<?= base_url('guru/qrcode/show/' . $url['id']) ?>" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit QR Code</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('guru/qrcode/update/' . $url['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="original_url">URL Tujuan</label>
                            <input type="text" class="form-control" value="<?= esc($url['original_url']) ?>" readonly>
                            <input type="hidden" id="original_url" name="original_url" value="<?= esc($url['original_url']) ?>">
                            <small class="form-text text-muted">URL tidak dapat diubah.</small>
                        </div>

                        <div class="form-group">
                            <label for="custom_name">Nama Label</label>
                            <input type="text" class="form-control <?= session('errors.custom_name') ? 'is-invalid' : '' ?>" id="custom_name" name="custom_name" value="<?= old('custom_name', $url['custom_name']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.custom_name') ?>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold text-secondary mb-3">Kustomisasi</h6>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size">Ukuran (px)</label>
                                    <?php if ($globalSettings['allow_custom_size']): ?>
                                        <input type="number" class="form-control <?= session('errors.size') ? 'is-invalid' : '' ?>" id="size" name="size" value="<?= old('size', $settings['size']) ?>" min="100" max="1000">
                                    <?php else: ?>
                                        <input type="text" class="form-control" value="<?= $settings['size'] ?> (Default)" disabled>
                                        <input type="hidden" name="size" value="<?= $settings['size'] ?>">
                                        <small class="form-text text-muted">Diatur oleh Admin</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="qr_color">Warna QR</label>
                                    <?php if ($globalSettings['allow_custom_colors']): ?>
                                        <input type="color" class="form-control" id="qr_color" name="qr_color" value="<?= old('qr_color', $settings['qr_color']) ?>">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background-color: <?= $settings['qr_color'] ?>; border: 1px solid #ccc; margin-right: 10px;"></div>
                                            <span class="text-muted">Default</span>
                                        </div>
                                        <input type="hidden" name="qr_color" value="<?= $settings['qr_color'] ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bg_color">Warna Background</label>
                                    <?php if ($globalSettings['allow_custom_colors']): ?>
                                        <input type="color" class="form-control" id="bg_color" name="bg_color" value="<?= old('bg_color', $settings['bg_color']) ?>">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background-color: <?= $settings['bg_color'] ?>; border: 1px solid #ccc; margin-right: 10px;"></div>
                                            <span class="text-muted">Default</span>
                                        </div>
                                        <input type="hidden" name="bg_color" value="<?= $settings['bg_color'] ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if ($globalSettings['allow_custom_logo']): ?>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="logo">Upload Logo Baru (Opsional)</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input <?= session('errors.logo') ? 'is-invalid' : '' ?>" id="logo" name="logo" accept="<?= $globalSettings['allowed_mime_types'] ?? 'image/png, image/jpeg' ?>">
                                        <label class="custom-file-label" for="logo">Pilih file logo...</label>
                                    </div>
                                    <input type="hidden" name="current_logo" value="<?= esc($settings['logo_path']) ?>">
                                    <small class="form-text text-muted">Maks: <?= $globalSettings['max_file_size_kb'] ?>KB. Biarkan kosong jika tidak ingin mengubah logo.</small>
                                    <?php if (!empty($settings['logo_path'])): ?>
                                        <div class="mt-2">
                                            <span class="badge badge-info">Logo saat ini terpasang</span>
                                            <div class="custom-control custom-checkbox mt-1">
                                                <input type="checkbox" class="custom-control-input" id="remove_logo" name="remove_logo" value="1">
                                                <label class="custom-control-label" for="remove_logo">Hapus Logo Saat Ini</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.logo') ?>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                                <?php if (!empty($globalSettings['default_logo_path'])): ?>
                                <div class="col-md-12 mt-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i> QR Code menggunakan logo default sekolah.
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="frame_style">Gaya Frame</label>
                                    <select class="form-control" id="frame_style" name="frame_style">
                                        <option value="none" <?= $settings['frame_style'] == 'none' ? 'selected' : '' ?>>Kotak (Default)</option>
                                        <option value="rounded" <?= $settings['frame_style'] == 'rounded' ? 'selected' : '' ?>>Rounded</option>
                                        <option value="circle" <?= $settings['frame_style'] == 'circle' ? 'selected' : '' ?>>Lingkaran (Experimental)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="show_label" name="show_label" value="1" <?= $settings['show_label'] ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="show_label">Tampilkan Label (Nama) pada QR</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Preview Card -->
            <div class="card shadow mb-4 sticky-top" style="top: 100px; z-index: 100;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Live Preview</h6>
                </div>
                <div class="card-body text-center bg-light">
                    <div id="qr-preview-container" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                        <p class="text-muted small">Loading preview...</p>
                    </div>
                    <div id="loading-preview" class="d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select');
        const previewContainer = document.getElementById('qr-preview-container');
        const loading = document.getElementById('loading-preview');
        let timeout = null;

        function updatePreview() {
            const originalUrl = document.getElementById('original_url').value;
            if (!originalUrl) return;

            // Show loading
            previewContainer.style.display = 'none';
            loading.classList.remove('d-none');

            const formData = new FormData(form);

            fetch('<?= base_url('guru/qrcode/preview') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.blob();
            })
            .then(blob => {
                const imageUrl = URL.createObjectURL(blob);
                previewContainer.innerHTML = `<img src="${imageUrl}" class="img-fluid" style="max-height: 250px;">`;
                previewContainer.style.display = 'flex';
                loading.classList.add('d-none');
            })
            .catch(error => {
                console.error('Error:', error);
                previewContainer.innerHTML = '<p class="text-danger small">Gagal memuat preview</p>';
                previewContainer.style.display = 'flex';
                loading.classList.add('d-none');
            });
        }

        // Initial preview
        updatePreview();

        // Add event listeners with debounce
        inputs.forEach(input => {
            input.addEventListener('change', updatePreview);
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(updatePreview, 800);
            });
        });
        
        // Custom file input label
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("logo").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
            updatePreview();
        });
    });
</script>
<?= $this->endSection() ?>
