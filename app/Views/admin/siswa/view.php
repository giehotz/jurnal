<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detail Siswa</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">NIS</th>
                        <td><?= esc($student['nis']) ?></td>
                    </tr>
                    <tr>
                        <th>NISN</th>
                        <td><?= esc($student['nisn']) ?></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?= esc($student['nama']) ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?= esc($student['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td><?= esc($student['tanggal_lahir']) ?></td>
                    </tr>
                    <tr>
                        <th>Rombel</th>
                        <td><?= esc($student['rombel_nama']) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($student['is_active']): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat pada</th>
                        <td><?= esc($student['created_at']) ?></td>
                    </tr>
                    <tr>
                        <th>Terakhir diupdate</th>
                        <td><?= esc($student['updated_at']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('admin/siswa/edit/' . $student['id']) ?>" class="btn btn-primary">Edit</a>
                <a href="<?= base_url('admin/siswa') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>