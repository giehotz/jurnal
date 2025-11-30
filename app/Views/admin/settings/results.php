<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Pengaturan Aplikasi</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/settings/settingapps') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Pengaturan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($settings) && !empty($settings)): ?>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td width="30%"><strong>Nama Sekolah</strong></td>
                                    <td><?= esc($settings['school_name'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tahun Ajaran</strong></td>
                                    <td><?= esc($settings['school_year'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Semester</strong></td>
                                    <td>
                                        <?php 
                                        if (isset($settings['semester'])) {
                                            echo $settings['semester'] == 'ganjil' ? 'Ganjil' : 'Genap';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Kepala Sekolah</strong></td>
                                    <td><?= esc($settings['headmaster_name'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>NIP Kepala Sekolah</strong></td>
                                    <td><?= esc($settings['headmaster_nip'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat Sekolah</strong></td>
                                    <td><?= esc($settings['school_address'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Logo Sekolah</strong></td>
                                    <td>
                                        <?php if (!empty($settings['logo'])): ?>
                                            <img src="<?= base_url('uploads/logos/' . esc($settings['logo'])) ?>" alt="Logo Sekolah" class="img-thumbnail" style="max-height: 100px;">
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada logo yang diunggah</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Informasi</h5>
                            Belum ada pengaturan aplikasi yang tersimpan di database.
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <a href="<?= base_url('admin/settings') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>