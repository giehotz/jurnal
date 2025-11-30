<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Rombel</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/rombel/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Rombel
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="rombel-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode Rombel</th>
                            <th>Nama Rombel</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Wali Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Jumlah Siswa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rombel)): ?>
                            <?php foreach ($rombel as $r): ?>
                            <tr>
                                <td><?= $r['id'] ?></td>
                                <td><?= $r['kode_rombel'] ?></td>
                                <td><?= $r['nama_rombel'] ?></td>
                                <td><?= $r['tingkat'] ?></td>
                                <td><?= $r['jurusan'] ?? '-' ?></td>
                                <td>
                                    <?php if (!empty($r['wali_kelas_nama'])): ?>
                                        <?= $r['wali_kelas_nama'] ?>
                                    <?php else: ?>
                                        <span class="text-muted">Belum ditentukan</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $r['tahun_ajaran'] ?></td>
                                <td><?= $r['semester'] ?></td>
                                <td><?= $r['jumlah_siswa'] ?></td>
                                <td>
                                    <?php if ($r['is_active']): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/rombel/view/' . $r['id']) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admin/rombel/edit/' . $r['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/rombel/assign-students/' . $r['id']) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <?php if ($r['is_active']): ?>
                                        <a href="<?= base_url('admin/rombel/delete/' . $r['id']) ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin menonaktifkan rombel ini?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('admin/rombel/activate/' . $r['id']) ?>" class="btn btn-success btn-sm" 
                                           onclick="return confirm('Apakah Anda yakin ingin mengaktifkan rombel ini?')">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data rombel</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>

<?= $this->endSection() ?>