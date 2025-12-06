<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Edit Agenda Kalender</h3>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/kalender-pendidikan/update/' . $kalender['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <!-- Method spoofing if needed, but CI4 handles POST updates fine usually. CI4 ResourceController uses PUT/PATCH -->

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Tahun Ajaran</label>
                            <input type="text" class="form-control" value="<?= esc($kalender['tahun_ajaran']) ?>" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Semester</label>
                            <input type="text" class="form-control" value="<?= esc($kalender['semester']) ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal</label>
                        <!-- Allow edit date? Usually keys shouldn't change, but users make mistakes. -->
                        <input type="date" name="tanggal" class="form-control" required value="<?= esc($kalender['tanggal']) ?>">
                    </div>

                    <?php
                    $standardTypes = ['hari_efektif', 'libur_nasional', 'libur_sekolah', 'ujian', 'event', 'rapat'];
                    $isCustom = !in_array($kalender['jenis_hari'], $standardTypes);
                    ?>
                    <div class="form-group">
                        <label>Jenis Hari</label>
                        <select name="jenis_hari" class="form-control" required>

                            <option value="libur_nasional" <?= $kalender['jenis_hari'] == 'libur_nasional' ? 'selected' : '' ?>>Libur Nasional</option>
                            <option value="libur_sekolah" <?= $kalender['jenis_hari'] == 'libur_sekolah' ? 'selected' : '' ?>>Libur Sekolah</option>
                            <option value="ujian" <?= $kalender['jenis_hari'] == 'ujian' ? 'selected' : '' ?>>Ujian / PTS / PAS</option>
                            <option value="event" <?= $kalender['jenis_hari'] == 'event' ? 'selected' : '' ?>>Event / Kegiatan</option>
                            <option value="rapat" <?= $kalender['jenis_hari'] == 'rapat' ? 'selected' : '' ?>>Rapat Guru</option>
                            <option value="lainnya" <?= $isCustom ? 'selected' : '' ?>>Lainnya (Input Manual)</option>
                        </select>
                    </div>

                    <div class="form-group" id="manual_jenis_hari_group" style="<?= $isCustom ? 'display: block;' : 'display: none;' ?>">
                        <label>Jenis Hari (Manual)</label>
                        <input type="text" name="jenis_hari_manual" class="form-control" placeholder="Tulis jenis hari..." value="<?= $isCustom ? esc($kalender['jenis_hari']) : '' ?>" <?= $isCustom ? 'required' : '' ?>>
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
                                }
                            }

                            select.addEventListener('change', toggleManual);
                        });
                    </script>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"><?= esc($kalender['keterangan']) ?></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <a href="<?= base_url('admin/kalender-pendidikan') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>