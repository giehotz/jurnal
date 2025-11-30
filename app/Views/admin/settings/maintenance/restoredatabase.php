<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Restore Database</h3>
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
                    
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                        <p>Proses restore database akan menggantikan seluruh data saat ini dengan data dari file backup. 
                        Pastikan Anda memiliki backup terbaru sebelum melanjutkan. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    
                    <?php if (!empty($backup_files)): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">File Backup Tersedia</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th>Tanggal Modifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backup_files as $file): ?>
                                    <tr>
                                        <td><?= esc($file['name']) ?></td>
                                        <td><?= esc($file['size']) ?> KB</td>
                                        <td><?= esc($file['modified']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/settings/maintenance/downloadbackup/' . urlencode($file['name'])) ?>" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                            <a href="<?= base_url('admin/settings/maintenance/restorebackup/' . urlencode($file['name'])) ?>" 
                                               class="btn btn-sm btn-warning"
                                               onclick="return confirm('PERINGATAN: Ini akan menggantikan seluruh data database saat ini dengan data dari file <?= esc($file['name']) ?>. Apakah Anda yakin ingin melanjutkan?')">
                                                <i class="fas fa-upload"></i> Restore
                                            </a>
                                            <a href="<?= base_url('admin/settings/maintenance/deletebackup/' . urlencode($file['name'])) ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus file <?= esc($file['name']) ?>?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <p>Tidak ada file backup yang tersedia. Silakan buat backup terlebih dahulu.</p>
                    </div>
                    <?php endif; ?>
                    
                    <p>Pilih file backup untuk merestore database:</p>
                    
                    <form action="<?= base_url('admin/settings/maintenance/restoredatabase') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="backup_file">File Backup:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="backup_file" name="backup_file" accept=".sql">
                                    <label class="custom-file-label" for="backup_file">Pilih file SQL</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Hanya file dengan ekstensi .sql yang diperbolehkan.</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="confirm_restore" name="confirm_restore" required>
                            <label class="form-check-label" for="confirm_restore">Saya memahami risiko dan ingin melanjutkan proses restore</label>
                        </div>
                        
                        <button type="submit" class="btn btn-danger" onclick="return confirm('PERINGATAN: Ini akan menggantikan seluruh data database saat ini. Apakah Anda yakin ingin melanjutkan?')">
                            <i class="fas fa-upload"></i> Restore Database
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
    bsCustomFileInput.init();
});
</script>
<?= $this->endSection() ?>