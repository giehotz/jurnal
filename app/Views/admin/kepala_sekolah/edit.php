<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Data Kepala Sekolah</h3>
            </div>
            <form action="<?= base_url('admin/kepala_sekolah/update') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="nama_kepsek">Nama Kepala Sekolah</label>
                        <input type="text" class="form-control" id="nama_kepsek" name="nama_kepsek" value="<?= old('nama_kepsek', $kepsek['nama'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nip_kepsek">NIP Kepala Sekolah</label>
                        <input type="text" class="form-control" id="nip_kepsek" name="nip_kepsek" value="<?= old('nip_kepsek', $kepsek['nip'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="email_kepsek">Email Kepala Sekolah</label>
                        <input type="email" class="form-control" id="email_kepsek" name="email_kepsek" value="<?= old('email_kepsek', $kepsek['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
