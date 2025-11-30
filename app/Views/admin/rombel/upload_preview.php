<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-excel mr-1"></i> Preview Upload Siswa - Kelas <?= esc($rombel['nama_rombel']) ?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Silakan periksa data di bawah ini sebelum menyimpan. Pastikan data sudah benar.
                    </div>

                    <form action="<?= base_url('admin/rombel/store-upload/' . $rombel['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama Lengkap</th>
                                        <th>L/P</th>
                                        <th>Tanggal Lahir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($students)): ?>
                                        <tr>
                                        <td colspan="6" class="text-center">Tidak ada data yang ditemukan dalam file.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($students as $index => $student): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <?= esc($student['nis']) ?>
                                                    <input type="hidden" name="students[<?= $index ?>][nis]" value="<?= esc($student['nis']) ?>">
                                                </td>
                                                <td>
                                                    <?= esc($student['nisn']) ?>
                                                    <input type="hidden" name="students[<?= $index ?>][nisn]" value="<?= esc($student['nisn']) ?>">
                                                </td>
                                                <td>
                                                    <?= esc($student['nama']) ?>
                                                    <input type="hidden" name="students[<?= $index ?>][nama]" value="<?= esc($student['nama']) ?>">
                                                </td>
                                                <td>
                                                    <?= esc($student['jenis_kelamin']) ?>
                                                    <input type="hidden" name="students[<?= $index ?>][jenis_kelamin]" value="<?= esc($student['jenis_kelamin']) ?>">
                                                </td>
                                                <td>
                                                    <?= esc($student['tanggal_lahir']) ?>
                                                    <input type="hidden" name="students[<?= $index ?>][tanggal_lahir]" value="<?= esc($student['tanggal_lahir']) ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" <?= empty($students) ? 'disabled' : '' ?>>
                                <i class="fas fa-save"></i> Simpan Data
                            </button>
                            <a href="<?= base_url('admin/rombel/view/' . $rombel['id']) ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
