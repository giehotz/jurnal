<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Import Users from Excel</h3>
            </div>
            <form action="<?= base_url('admin/import/users') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
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

                    <div class="form-group">
                        <label for="file_excel">File Excel</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_excel" name="file_excel" required>
                                <label class="custom-file-label" for="file_excel">Pilih file</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Upload file dengan format .xls atau .xlsx</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="<?= base_url('admin/export') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Petunjuk</h3>
            </div>
            <div class="card-body">
                <p>
                    Untuk mengimpor user, silahkan unduh template excel yang sudah disediakan.
                </p>
                <a href="<?= base_url('admin/import/download_template/users') ?>" class="btn btn-info">
                    <i class="fas fa-download"></i> Download Template
                </a>
                <hr>
                <strong>Format Kolom:</strong>
                <ul>
                    <li><strong>NIP</strong>: Nomor Induk Pegawai (wajib diisi)</li>
                    <li><strong>Nama</strong>: Nama lengkap user (wajib diisi)</li>
                    <li><strong>Email</strong>: Alamat email yang valid (wajib diisi)</li>
                    <li><strong>Role</strong>: guru, admin, atau super_admin (default: guru)</li>
                    <li><strong>Password</strong>: Kosongkan untuk menggunakan password default (12345678)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
