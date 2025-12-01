<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan QR Code</h6>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
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
                    
                    <ul class="nav nav-tabs" id="settingTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Umum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="permissions-tab" data-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">Izin Pengguna</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content pt-4" id="settingTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="form-group row">
                                <label for="default_size" class="col-sm-3 col-form-label">Ukuran Default (px)</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="default_size" name="default_size" value="<?= esc($settings['default_size']) ?>" min="50" max="1000">
                                    <small class="form-text text-muted">Ukuran default QR Code yang dihasilkan.</small>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="default_color" class="col-sm-3 col-form-label">Warna Default</label>
                                <div class="col-sm-9">
                                    <input type="color" class="form-control form-control-color" id="default_color" name="default_color" value="<?= esc($settings['default_color']) ?>" title="Choose your color">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="default_bg_color" class="col-sm-3 col-form-label">Warna Background Default</label>
                                <div class="col-sm-9">
                                    <input type="color" class="form-control form-control-color" id="default_bg_color" name="default_bg_color" value="<?= esc($settings['default_bg_color']) ?>" title="Choose your color">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="default_logo" class="col-sm-3 col-form-label">Logo Default</label>
                                <div class="col-sm-9">
                                    <?php if (!empty($settings['default_logo_path'])): ?>
                                        <div class="mb-2">
                                            <img src="<?= base_url($settings['default_logo_path']) ?>" alt="Default Logo" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control-file" id="default_logo" name="default_logo" accept="image/*">
                                    <small class="form-text text-muted">Upload logo default yang akan muncul di tengah QR Code (Max 2MB).</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Permissions Settings -->
                        <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                            <div class="form-group row">
                                <div class="col-sm-3">Kustomisasi Logo</div>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_custom_logo" name="allow_custom_logo" <?= $settings['allow_custom_logo'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="allow_custom_logo">Izinkan guru mengupload logo kustom</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-sm-3">Kustomisasi Warna</div>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_custom_colors" name="allow_custom_colors" <?= $settings['allow_custom_colors'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="allow_custom_colors">Izinkan guru mengubah warna QR Code</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-sm-3">Kustomisasi Ukuran</div>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_custom_size" name="allow_custom_size" <?= $settings['allow_custom_size'] ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="allow_custom_size">Izinkan guru mengubah ukuran QR Code</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="max_file_size_kb" class="col-sm-3 col-form-label">Maksimal Ukuran File Upload (KB)</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="max_file_size_kb" name="max_file_size_kb" value="<?= esc($settings['max_file_size_kb']) ?>" min="100">
                                    <small class="form-text text-muted">Batas maksimal ukuran file logo yang diupload oleh guru.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row mt-4">
                        <div class="col-sm-12 text-right">
                            <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#resetModal">Reset ke Default</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel">Konfirmasi Reset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mereset semua pengaturan QR Code ke nilai default? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="<?= base_url('admin/qrcode/settings/reset') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Ya, Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
