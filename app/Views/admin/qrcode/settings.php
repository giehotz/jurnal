<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-admin.css') ?>">
    <style>
        /* Local overrides for tabs if needed */
        /* Local overrides for tabs */
        .nav-tabs .nav-link {
            color: #64748B; /* Slate 500 */
            background-color: #F1F5F9; /* Slate 100 */
            border: none;
            margin-right: 4px;
            border-radius: 8px 8px 0 0;
            padding: 1rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .nav-tabs .nav-link:hover {
            background-color: #E2E8F0; /* Slate 200 */
            color: #1E293B; /* Slate 800 */
        }
        .nav-tabs .nav-link.active {
            color: #ffffff;
            background-color: #10B981; /* Emerald 500 */
            border: none;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.05);
        }
        .nav-tabs {
            border-bottom: 2px solid #10B981; /* Emerald 500 */
        }
        /* Custom Switch Colors: Red (Inactive) / Green (Active) */
        .custom-switch .custom-control-label::before {
            background-color: #EF4444;
            border-color: #EF4444;
        }
        .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #10B981;
            border-color: #10B981;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="custom-card shadow-sm">
            <div class="custom-card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-emerald">Pengaturan QR Code</h6>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                        <ul class="mb-0 pl-3">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/qrcode/settings/update') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <ul class="nav nav-tabs mb-4" id="settingTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                <i class="fas fa-cog mr-2"></i>Umum
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="permissions-tab" data-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">
                                <i class="fas fa-user-shield mr-2"></i>Izin Pengguna
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="settingTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="form-group row">
                                <label for="default_size" class="col-sm-3 col-form-label font-weight-bold text-secondary">Ukuran Default (px)</label>
                                <div class="col-sm-9">
                                    <input type="number" class="custom-form-control" id="default_size" name="default_size" value="<?= esc($settings['default_size']) ?>" min="50" max="1000">
                                    <small class="form-text text-muted mt-2">Ukuran default QR Code yang dihasilkan.</small>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="default_color" class="col-sm-3 col-form-label font-weight-bold text-secondary">Warna Default</label>
                                <div class="col-sm-9">
                                    <div class="d-flex align-items-center">
                                        <input type="color" class="form-control form-control-color mr-3" id="default_color" name="default_color" value="<?= esc($settings['default_color']) ?>" title="Choose your color" style="width: 50px; height: 38px; padding: 0; border: none; background: none;">
                                        <span class="text-muted"><?= esc($settings['default_color']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="default_bg_color" class="col-sm-3 col-form-label font-weight-bold text-secondary">Warna Background</label>
                                <div class="col-sm-9">
                                    <div class="d-flex align-items-center">
                                        <input type="color" class="form-control form-control-color mr-3" id="default_bg_color" name="default_bg_color" value="<?= esc($settings['default_bg_color']) ?>" title="Choose your color" style="width: 50px; height: 38px; padding: 0; border: none; background: none;">
                                        <span class="text-muted"><?= esc($settings['default_bg_color']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="default_logo" class="col-sm-3 col-form-label font-weight-bold text-secondary">Logo Default</label>
                                <div class="col-sm-9">
                                    <?php if (!empty($settings['default_logo_path'])): ?>
                                        <div class="mb-3 p-2 border rounded d-inline-block bg-light position-relative">
                                            <img src="<?= base_url($settings['default_logo_path']) ?>" alt="Default Logo" class="img-fluid" style="max-height: 100px;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: -10px; right: -10px; border-radius: 50%; width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;" data-toggle="modal" data-target="#deleteLogoModal" title="Hapus Logo">
                                                <i class="fas fa-times" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="default_logo" name="default_logo" accept="image/*">
                                        <label class="custom-file-label" for="default_logo">Pilih file logo...</label>
                                    </div>
                                    <small class="form-text text-muted mt-2">Upload logo default yang akan muncul di tengah QR Code (Max 2MB).</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Permissions Settings -->
                        <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                            <div class="form-group row align-items-center">
                                <div class="col-sm-3 font-weight-bold text-secondary">Kustomisasi Logo</div>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_custom_logo" name="allow_custom_logo" <?= $settings['allow_custom_logo'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="allow_custom_logo">Izinkan guru mengupload logo kustom</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row align-items-center">
                                <div class="col-sm-3 font-weight-bold text-secondary">Kustomisasi Warna</div>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_custom_colors" name="allow_custom_colors" <?= $settings['allow_custom_colors'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="allow_custom_colors">Izinkan guru mengubah warna QR Code</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row align-items-center">
                                <div class="col-sm-3 font-weight-bold text-secondary">Kustomisasi Ukuran</div>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_custom_size" name="allow_custom_size" <?= $settings['allow_custom_size'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="allow_custom_size">Izinkan guru mengubah ukuran QR Code</label>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <div class="form-group row">
                                <label for="max_file_size_kb" class="col-sm-3 col-form-label font-weight-bold text-secondary">Max Upload Size (KB)</label>
                                <div class="col-sm-9">
                                    <input type="number" class="custom-form-control" id="max_file_size_kb" name="max_file_size_kb" value="<?= esc($settings['max_file_size_kb']) ?>" min="100">
                                    <small class="form-text text-muted mt-2">Batas maksimal ukuran file logo yang diupload oleh guru.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row mt-5">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" class="custom-btn-danger mr-3" data-toggle="modal" data-target="#resetModal">
                                <i class="fas fa-undo mr-2"></i>Reset Default
                            </button>
                            <button type="submit" class="custom-btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Logo Confirmation Modal -->
<div class="modal fade" id="deleteLogoModal" tabindex="-1" role="dialog" aria-labelledby="deleteLogoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-danger" id="deleteLogoModalLabel">Hapus Logo Default</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-secondary">
                Apakah Anda yakin ingin menghapus logo default? Logo akan dihapus permanen.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <form action="<?= base_url('admin/qrcode/settings/delete-logo') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="custom-btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-danger" id="resetModalLabel">Konfirmasi Reset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-secondary">
                Apakah Anda yakin ingin mereset semua pengaturan QR Code ke nilai default? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <form action="<?= base_url('admin/qrcode/settings/reset') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="custom-btn-danger">Ya, Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
