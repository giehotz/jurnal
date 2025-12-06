<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Tambah Agenda Kalender</h3>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/kalender-pendidikan/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="YYYY/YYYY" required value="<?= isset($settings['school_year']) ? esc($settings['school_year']) : old('tahun_ajaran') ?>" readonly>
                            <small class="text-muted">Data diambil dari Pengaturan Aplikasi</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Semester</label>
                            <select name="semester" class="form-control">
                                <option value="1" <?= old('semester') == 1 ? 'selected' : '' ?>>1 (Ganjil)</option>
                                <option value="2" <?= old('semester') == 2 ? 'selected' : '' ?>>2 (Genap)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required value="<?= old('tanggal') ?>">
                    </div>

                    <div class="form-group">
                        <label>Jenis Hari</label>
                        <select name="jenis_hari" class="form-control" required>
                            <option value="libur_nasional" <?= old('jenis_hari') == 'libur_nasional' ? 'selected' : '' ?>>Libur Nasional</option>
                            <option value="libur_sekolah" <?= old('jenis_hari') == 'libur_sekolah' ? 'selected' : '' ?>>Libur Sekolah</option>
                            <option value="ujian" <?= old('jenis_hari') == 'ujian' ? 'selected' : '' ?>>Ujian / PTS / PAS</option>
                            <option value="event" <?= old('jenis_hari') == 'event' ? 'selected' : '' ?>>Event / Kegiatan</option>
                            <option value="rapat" <?= old('jenis_hari') == 'rapat' ? 'selected' : '' ?>>Rapat Guru</option>
                            <option value="lainnya" <?= old('jenis_hari') == 'lainnya' ? 'selected' : '' ?>>Lainnya (Input Manual)</option>
                        </select>
                    </div>

                    <div class="form-group" id="manual_jenis_hari_group" style="display: none;">
                        <label>Jenis Hari (Manual)</label>
                        <input type="text" name="jenis_hari_manual" class="form-control" placeholder="Tulis jenis hari..." value="<?= old('jenis_hari_manual') ?>">
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const select = document.querySelector('select[name="jenis_hari"]');
                            const manualGroup = document.getElementById('manual_jenis_hari_group');
                            const manualInput = document.querySelector('input[name="jenis_hari_manual"]');

                            function toggleManual() {
                                if (select.value === 'lainnya') {
                                    manualGroup.style.display = 'block';
                                    manualInput.setAttribute('required', 'required');
                                } else {
                                    manualGroup.style.display = 'none';
                                    manualInput.removeAttribute('required');
                                    manualInput.value = ''; // Clear if hidden? Maybe better to keep if accidental toggle.
                                }
                            }

                            select.addEventListener('change', toggleManual);
                            // Run on init in case of validation error redirect
                            toggleManual();
                        });
                    </script>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan') ?></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <a href="<?= base_url('admin/kalender-pendidikan') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>