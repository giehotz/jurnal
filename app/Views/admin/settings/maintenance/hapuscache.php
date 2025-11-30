<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hapus Cache</h3>
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
                    
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle"></i> Informasi</h5>
                        <p>Fitur ini akan menghapus cache sistem yang mencakup:</p>
                        <ul>
                            <li>Cache tampilan (view cache)</li>
                            <li>Cache database</li>
                            <li>Cache route</li>
                            <li>File sesi</li>
                            <li>File log sistem</li>
                        </ul>
                    </div>
                    
                    <p>Proses penghapusan cache dapat membantu memperbaiki masalah kinerja atau tampilan yang tidak sesuai.</p>
                    
                    <form action="<?= base_url('admin/settings/maintenance/hapuscache') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hapus_cache_view" name="hapus_cache_view">
                                <label class="form-check-label" for="hapus_cache_view">
                                    Hapus cache tampilan
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hapus_cache_db" name="hapus_cache_db">
                                <label class="form-check-label" for="hapus_cache_db">
                                    Hapus cache database
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hapus_cache_route" name="hapus_cache_route">
                                <label class="form-check-label" for="hapus_cache_route">
                                    Hapus cache route
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hapus_session" name="hapus_session">
                                <label class="form-check-label" for="hapus_session">
                                    Hapus file sesi
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hapus_log" name="hapus_log">
                                <label class="form-check-label" for="hapus_log">
                                    Hapus file log
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hapus_semua" name="hapus_semua">
                                <label class="form-check-label" for="hapus_semua">
                                    Hapus semua cache (rekomendasi)
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin menghapus cache sistem?')">
                            <i class="fas fa-trash-alt"></i> Hapus Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    // Saat "Hapus semua cache" dicentang, centang semua opsi lainnya
    $('#hapus_semua').change(function() {
        if(this.checked) {
            $('#hapus_cache_view').prop('checked', true);
            $('#hapus_cache_db').prop('checked', true);
            $('#hapus_cache_route').prop('checked', true);
            $('#hapus_session').prop('checked', true);
            $('#hapus_log').prop('checked', true);
        } else {
            $('#hapus_cache_view').prop('checked', false);
            $('#hapus_cache_db').prop('checked', false);
            $('#hapus_cache_route').prop('checked', false);
            $('#hapus_session').prop('checked', false);
            $('#hapus_log').prop('checked', false);
        }
    });
});
</script>
<?= $this->endSection() ?>