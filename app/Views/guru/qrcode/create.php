<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">Generate QR Code Baru</h1>
        <a href="<?= base_url('guru/qrcode') ?>" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form QR Code</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('guru/qrcode/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="original_url">URL Tujuan <span class="text-danger">*</span></label>
                            <input type="url" class="form-control <?= session('errors.original_url') ? 'is-invalid' : '' ?>" id="original_url" name="original_url" value="<?= old('original_url') ?>" placeholder="https://example.com" required>
                            <div class="invalid-feedback">
                                <?= session('errors.original_url') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="custom_name">Nama Label (Opsional)</label>
                            <input type="text" class="form-control <?= session('errors.custom_name') ? 'is-invalid' : '' ?>" id="custom_name" name="custom_name" value="<?= old('custom_name') ?>" placeholder="Contoh: Absensi Kelas X">
                            <div class="invalid-feedback">
                                <?= session('errors.custom_name') ?>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold text-secondary mb-3">Kustomisasi (Opsional)</h6>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size">Ukuran (px)</label>
                                    <?php if ($settings['allow_custom_size']): ?>
                                        <input type="number" class="form-control <?= session('errors.size') ? 'is-invalid' : '' ?>" id="size" name="size" value="<?= old('size', $settings['default_size']) ?>" min="100" max="1000">
                                        <small class="form-text text-muted">Min: 100, Max: 1000</small>
                                    <?php else: ?>
                                        <input type="text" class="form-control" value="<?= $settings['default_size'] ?> (Default)" disabled>
                                        <input type="hidden" name="size" value="<?= $settings['default_size'] ?>">
                                        <small class="form-text text-muted">Diatur oleh Admin</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="qr_color">Warna QR</label>
                                    <?php if ($settings['allow_custom_colors']): ?>
                                        <input type="color" class="form-control" id="qr_color" name="qr_color" value="<?= old('qr_color', $settings['default_color']) ?>">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background-color: <?= $settings['default_color'] ?>; border: 1px solid #ccc; margin-right: 10px;"></div>
                                            <span class="text-muted">Default</span>
                                        </div>
                                        <input type="hidden" name="qr_color" value="<?= $settings['default_color'] ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bg_color">Warna Background</label>
                                    <?php if ($settings['allow_custom_colors']): ?>
                                        <input type="color" class="form-control" id="bg_color" name="bg_color" value="<?= old('bg_color', $settings['default_bg_color']) ?>">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background-color: <?= $settings['default_bg_color'] ?>; border: 1px solid #ccc; margin-right: 10px;"></div>
                                            <span class="text-muted">Default</span>
                                        </div>
                                        <input type="hidden" name="bg_color" value="<?= $settings['default_bg_color'] ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3">
                                <label>Opsi Logo</label>
                                <div class="form-group">
                                    <?php if ($settings['allow_custom_logo']): ?>
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="logo_option_none" name="logo_option" class="custom-control-input" value="none" checked>
                                            <label class="custom-control-label" for="logo_option_none">Tanpa Logo</label>
                                        </div>
                                        
                                        <?php if (!empty($settings['default_logo_path'])): ?>
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="logo_option_default" name="logo_option" class="custom-control-input" value="default">
                                            <label class="custom-control-label d-flex align-items-center" for="logo_option_default">
                                                Gunakan Logo Default Sekolah
                                                <img src="<?= base_url($settings['default_logo_path']) ?>" alt="Default Logo" class="ml-2 img-thumbnail" style="height: 30px;">
                                            </label>
                                        </div>
                                        <?php endif; ?>

                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="logo_option_custom" name="logo_option" class="custom-control-input" value="custom">
                                            <label class="custom-control-label" for="logo_option_custom">Upload Logo Kustom</label>
                                        </div>

                                        <div class="form-group pl-4" id="custom_logo_input" style="display: none;">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input <?= session('errors.logo') ? 'is-invalid' : '' ?>" id="logo" name="logo" accept="<?= $settings['allowed_mime_types'] ?? 'image/png, image/jpeg' ?>">
                                                <label class="custom-file-label" for="logo">Pilih file logo...</label>
                                            </div>
                                            <small class="form-text text-muted">Maks: <?= $settings['max_file_size_kb'] ?>KB. Logo akan ditempatkan di tengah QR Code.</small>
                                            <div class="invalid-feedback">
                                                <?= session('errors.logo') ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Custom Logo Disabled -->
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="logo_option_none" name="logo_option" class="custom-control-input" value="none" checked>
                                            <label class="custom-control-label" for="logo_option_none">Tanpa Logo</label>
                                        </div>

                                        <?php if (!empty($settings['default_logo_path'])): ?>
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="logo_option_default" name="logo_option" class="custom-control-input" value="default">
                                            <label class="custom-control-label d-flex align-items-center" for="logo_option_default">
                                                Gunakan Logo Default Sekolah
                                                <img src="<?= base_url($settings['default_logo_path']) ?>" alt="Default Logo" class="ml-2 img-thumbnail" style="height: 30px;">
                                            </label>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="alert alert-secondary mt-2 py-1 px-2 small">
                                            <i class="fas fa-info-circle mr-1"></i> Upload logo kustom dinonaktifkan oleh Admin.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const logoOptions = document.getElementsByName('logo_option');
                                    const customLogoInput = document.getElementById('custom_logo_input');
                                    
                                    function toggleCustomLogo() {
                                        let selected = 'none';
                                        for (const option of logoOptions) {
                                            if (option.checked) {
                                                selected = option.value;
                                                break;
                                            }
                                        }
                                        
                                        if (customLogoInput) {
                                            customLogoInput.style.display = (selected === 'custom') ? 'block' : 'none';
                                        }
                                    }
                                    
                                    logoOptions.forEach(option => {
                                        option.addEventListener('change', toggleCustomLogo);
                                    });
                                    
                                    // Initial check
                                    toggleCustomLogo();
                                });
                            </script>
                            
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="frame_style">Gaya Frame</label>
                                    <select class="form-control" id="frame_style" name="frame_style">
                                        <option value="none">Kotak (Default)</option>
                                        <option value="rounded">Rounded</option>
                                        <option value="circle">Lingkaran (Experimental)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="show_label" name="show_label" value="1">
                                    <label class="custom-control-label" for="show_label">Tampilkan Label (Nama) pada QR</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4">
                            <i class="fas fa-qrcode mr-2"></i> Generate QR Code
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
                        <p class="text-muted small">Masukkan URL untuk melihat preview</p>
                    </div>
                    <div id="loading-preview" class="d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Informasi</h6>
                </div>
                <div class="card-body">
                    <p>Fitur ini memungkinkan Anda membuat QR Code untuk berbagai keperluan, seperti:</p>
                    <ul>
                        <li>Link Absensi Siswa</li>
                        <li>Materi Pembelajaran</li>
                        <li>Pengumuman Kelas</li>
                        <li>Formulir Online</li>
                    </ul>
                    <p class="mb-0">QR Code yang dibuat akan tersimpan dan dapat diunduh kapan saja.</p>
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
            if (!originalUrl) {
                previewContainer.innerHTML = '<p class="text-muted small">Masukkan URL untuk melihat preview</p>';
                return;
            }

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

        // Add event listeners with debounce
        inputs.forEach(input => {
            input.addEventListener('change', updatePreview);
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(updatePreview, 800); // Delay 800ms
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
