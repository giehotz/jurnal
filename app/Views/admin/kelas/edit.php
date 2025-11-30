<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Kelas</h3>
            </div>
            <form action="<?= base_url('admin/kelas/update/' . $class['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_kelas">Kode Kelas</label>
                                <input type="text" class="form-control" id="kode_kelas" name="kode_kelas" value="<?= old('kode_kelas', $class['kode_kelas']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tingkat">Tingkat</label>
                                <select class="form-control" id="tingkat" name="tingkat" required>
                                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                                        <option value="<?= $i ?>" <?= (isset($class['tingkat']) && $class['tingkat'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_kelas">Nama Kelas</label>
                                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="<?= old('nama_kelas', $class['nama_kelas']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jurusan">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?= old('jurusan', $class['jurusan'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun_ajaran">Tahun Ajaran</label>
                                <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="<?= old('tahun_ajaran', $class['tahun_ajaran'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select class="form-control" id="semester" name="semester">
                                    <option value="1" <?= (isset($class['semester']) && $class['semester'] == '1') ? 'selected' : '' ?>>1 (Ganjil)</option>
                                    <option value="2" <?= (isset($class['semester']) && $class['semester'] == '2') ? 'selected' : '' ?>>2 (Genap)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wali_kelas">Wali Kelas</label>
                                <select class="form-control select2" id="wali_kelas" name="wali_kelas" style="width: 100%;">
                                    <option value="">Pilih Wali Kelas</option>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <option value="<?= $teacher['id'] ?>" <?= (isset($class['wali_kelas']) && $class['wali_kelas'] == $teacher['id']) ? 'selected' : '' ?>><?= $teacher['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kapasitas">Kapasitas (Jumlah Maksimal Siswa)</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" min="1" max="100" value="<?= old('kapasitas', $class['kapasitas'] ?? 30) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('admin/kelas') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>