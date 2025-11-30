<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
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
</script>

<?= $this->endSection() ?>