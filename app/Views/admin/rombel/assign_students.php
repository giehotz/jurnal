<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-success mb-3">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-excel"></i> Import Siswa via Excel</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Langkah 1:</strong> Download template Excel.</p>
                        <a href="<?= base_url('admin/rombel/download-student-template') ?>" class="btn btn-outline-success">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Langkah 2:</strong> Upload file Excel yang sudah diisi.</p>
                        <form action="<?= base_url('admin/rombel/upload-students/' . $rombel['id']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
                                    <label class="custom-file-label" for="file_excel">Pilih file Excel...</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">Upload & Import</button>
                                </div>
                            </div>
                            <small class="text-muted">Format: .xlsx atau .xls</small>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Kelola Siswa di Rombel: <?= esc($rombel['nama_rombel']) ?></h3>
            </div>

            <form action="<?= base_url('admin/rombel/save-student-assignments/' . $rombel['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Siswa untuk Rombel <?= esc($rombel['nama_rombel']) ?>:</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" id="check-all"></th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($students)): ?>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox"
                                                        name="students[]"
                                                        value="<?= esc($student['id']) ?>"
                                                        <?= $student['rombel_id'] == $rombel['id'] ? 'checked' : '' ?>>
                                                </td>
                                                <td><?= esc($student['nis']) ?></td>
                                                <td><?= esc($student['nama']) ?></td>
                                                <td><?= esc($student['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></td>
                                                <td>
                                                    <?php if ($student['rombel_id'] == $rombel['id']): ?>
                                                        <span class="badge bg-success">Sudah di rombel ini</span>
                                                    <?php elseif (!empty($student['rombel_id'])): ?>
                                                        <span class="badge bg-warning">Di rombel lain</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Belum ada rombel</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data siswa</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= base_url('admin/rombel/view/' . $rombel['id']) ?>" class="btn btn-secondary">Kembali ke Detail Rombel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('check-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="students[]"]');
        for (const checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    // Display selected file name
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>

<?= $this->endSection() ?>