<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Export Data Master</h3>
            </div>
            <div class="card-body">
                <p>Export data master (Guru, Kelas, Mapel) ke format Excel.</p>
                <a href="<?= base_url('admin/export/users') ?>" class="btn btn-app bg-info">
                    <i class="fas fa-users"></i> Export Guru
                </a>
                <a href="<?= base_url('admin/export/kelas') ?>" class="btn btn-app bg-warning">
                    <i class="fas fa-school"></i> Export Kelas
                </a>
                <a href="<?= base_url('admin/export/mapel') ?>" class="btn btn-app bg-danger">
                    <i class="fas fa-book"></i> Export Mapel
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Import Data Master</h3>
            </div>
            <div class="card-body">
                <p>Import data master dari file Excel.</p>
                <a href="<?= base_url('admin/import/users') ?>" class="btn btn-app bg-primary">
                    <i class="fas fa-file-import"></i> Import Guru
                </a>
                 <a href="<?= base_url('admin/import/kelas') ?>" class="btn btn-app bg-secondary" disabled>
                    <i class="fas fa-file-import"></i> Import Kelas (Soon)
                </a>
                 <a href="<?= base_url('admin/import/mapel') ?>" class="btn btn-app bg-secondary" disabled>
                    <i class="fas fa-file-import"></i> Import Mapel (Soon)
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
