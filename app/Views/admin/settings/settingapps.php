<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan Aplikasi</h3>
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
                    
                    <form action="<?= base_url('admin/settings/save') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="school_name">Nama Sekolah</label>
                            <input type="text" class="form-control" id="school_name" name="school_name" value="<?= isset($settings['school_name']) ? esc($settings['school_name']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="school_level">Jenjang Sekolah</label>
                            <select class="form-control" id="school_level" name="school_level" required>
                                <option value="SD/MI" <?= (isset($settings['school_level']) && $settings['school_level'] == 'SD/MI') ? 'selected' : '' ?>>SD/MI</option>
                                <option value="SMP/MTs" <?= (isset($settings['school_level']) && $settings['school_level'] == 'SMP/MTs') ? 'selected' : '' ?>>SMP/MTs</option>
                                <option value="SMA/MA" <?= (isset($settings['school_level']) && $settings['school_level'] == 'SMA/MA') ? 'selected' : '' ?>>SMA/MA</option>
                            </select>
                            <small class="form-text text-muted">Pilih jenjang sekolah untuk menentukan tingkat kelas yang tersedia</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="school_year">Tahun Ajaran</label>
                            <select class="form-control" id="school_year" name="school_year" required>
                                <?php if (isset($school_years) && is_array($school_years)): ?>
                                    <?php foreach ($school_years as $year): ?>
                                        <option value="<?= esc($year) ?>" <?= (isset($settings['school_year']) && $settings['school_year'] == $year) ? 'selected' : '' ?>>
                                            <?= esc($year) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Fallback to text input if school_years is not provided -->
                                    <option value="<?= isset($settings['school_year']) ? esc($settings['school_year']) : '' ?>" selected>
                                        <?= isset($settings['school_year']) ? esc($settings['school_year']) : '' ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                            <small class="form-text text-muted">Pilih tahun ajaran dari daftar</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select class="form-control" id="semester" name="semester" required>
                                <option value="ganjil" <?= (isset($settings['semester']) && $settings['semester'] == 'ganjil') ? 'selected' : '' ?>>Ganjil</option>
                                <option value="genap" <?= (isset($settings['semester']) && $settings['semester'] == 'genap') ? 'selected' : '' ?>>Genap</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="headmaster_name">Nama Kepala Sekolah</label>
                            <input type="text" class="form-control" id="headmaster_name" name="headmaster_name" value="<?= isset($settings['headmaster_name']) ? esc($settings['headmaster_name']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="headmaster_nip">NIP Kepala Sekolah</label>
                            <input type="text" class="form-control" id="headmaster_nip" name="headmaster_nip" value="<?= isset($settings['headmaster_nip']) ? esc($settings['headmaster_nip']) : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="school_address">Alamat Sekolah</label>
                            <textarea class="form-control" id="school_address" name="school_address" rows="3"><?= isset($settings['school_address']) ? esc($settings['school_address']) : '' ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="logo">Logo Sekolah</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                                    <label class="custom-file-label" for="logo">Pilih file</label>
                                </div>
                            </div>
                            <?php if (!empty($settings['logo'])): ?>
                                <div class="mt-2">
                                    <img src="<?= base_url('uploads/logos/' . esc($settings['logo'])) ?>" alt="Logo Sekolah" class="img-thumbnail" style="max-height: 100px;">
                                    <p class="text-muted">Logo saat ini</p>
                                </div>
                            <?php else: ?>
                                <div class="mt-2">
                                    <p class="text-muted">Belum ada logo yang diunggah</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        <a href="<?= base_url('admin/settings') ?>" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tidak perlu JavaScript khusus karena tingkat kelas akan ditangani di sisi server
    // Saat pengguna mengganti jenjang sekolah dan menyimpan, sistem akan menyesuaikan tingkat kelas secara otomatis
});
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
<?= $this->endSection() ?>