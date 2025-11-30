<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Backup Database</h3>
                </div>
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
                    
                    <p>Fitur ini memungkinkan Anda untuk membuat backup dari seluruh database sistem.</p>
                    
                    <form action="<?= base_url('admin/settings/maintenance/backupdatabase') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="backup_name">Nama File Backup (Opsional):</label>
                            <input type="text" class="form-control" id="backup_name" name="backup_name" placeholder="Masukkan nama file backup (tanpa ekstensi)">
                            <small class="form-text text-muted">Jika tidak diisi, sistem akan menggunakan timestamp sebagai nama file.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin melakukan backup database?')">
                            <i class="fas fa-download"></i> Backup Database
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>