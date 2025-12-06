<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Maintenance System</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>Backup</h3>
                                <p>Database</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <a href="<?= base_url('admin/settings/maintenance/backupdatabase') ?>" class="small-box-footer">Buka <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>Restore</h3>
                                <p>Database</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-upload"></i>
                            </div>
                            <a href="<?= base_url('admin/settings/maintenance/restoredatabase') ?>" class="small-box-footer">Buka <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>Cache</h3>
                                <p>Bersihkan Cache</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-broom"></i>
                            </div>
                            <a href="<?= base_url('admin/settings/maintenance/hapuscache') ?>" class="small-box-footer">Buka <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>