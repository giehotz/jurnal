<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guru-responsive.css') ?>">
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Jurnal Mengajar</h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/jurnal/generate-pdf') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">Cetak PDF</span>
                    </a>
                    <a href="<?= base_url('guru/jurnal/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Tambah Jurnal</span>
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Wali Kelas</h5>
                        Anda adalah wali kelas <?= esc($kelas_perwalian['nama_rombel']) ?>.
                        Menampilkan semua jurnal Anda dan jurnal guru lain untuk kelas ini.
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Mobile Card View -->
                <div class="mobile-card-view">
                    <?php if (!empty($jurnals)): ?>
                        <?php foreach ($jurnals as $j): ?>
                            <div class="jurnal-card">
                                <div class="jurnal-card-header">
                                    <div>
                                        <strong>#<?= $j['id'] ?></strong>
                                        <div class="text-muted" style="font-size: 0.875rem;"><?= $j['tanggal'] ?></div>
                                    </div>
                                    <div>
                                        <?php if ($j['status'] == 'published'): ?>
                                            <span class="badge bg-success">Published</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Draft</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="jurnal-card-body">
                                    <div class="jurnal-info-row">
                                        <div class="jurnal-info-label">Kelas:</div>
                                        <div class="jurnal-info-value"><?= esc($j['nama_kelas']) ?> (<?= esc($j['kode_kelas']) ?>)</div>
                                    </div>
                                    <div class="jurnal-info-row">
                                        <div class="jurnal-info-label">Mapel:</div>
                                        <div class="jurnal-info-value"><?= $j['nama_mapel'] ?></div>
                                    </div>
                                    <div class="jurnal-info-row">
                                        <div class="jurnal-info-label">Jam Ke:</div>
                                        <div class="jurnal-info-value"><?= $j['jam_ke'] ?></div>
                                    </div>
                                    <div class="jurnal-info-row">
                                        <div class="jurnal-info-label">Materi:</div>
                                        <div class="jurnal-info-value"><?= substr($j['materi'], 0, 50) . '...' ?></div>
                                    </div>
                                    <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
                                        <div class="jurnal-info-row">
                                            <div class="jurnal-info-label">Guru:</div>
                                            <div class="jurnal-info-value"><?= $j['nama_guru'] ?? 'Anda' ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="jurnal-card-footer">
                                    <a href="<?= base_url('guru/jurnal/view/' . $j['id']) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    <?php if (!isset($is_wali_kelas) || !$is_wali_kelas || (isset($j['user_id']) && $j['user_id'] == session()->get('user_id'))): ?>
                                        <a href="<?= base_url('guru/jurnal/edit/' . $j['id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?= base_url('guru/jurnal/delete/' . $j['id']) ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Belum ada jurnal</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Desktop Table View -->
                <div class="desktop-table-view">
                    <div class="table-responsive">
                        <table id="jurnal-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Jam Ke</th>
                                    <th>Materi</th>
                                    <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
                                        <th>Guru</th>
                                    <?php endif; ?>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($jurnals)): ?>
                                    <?php foreach ($jurnals as $j): ?>
                                        <tr>
                                            <td><?= $j['id'] ?></td>
                                            <td><?= $j['tanggal'] ?></td>
                                            <td><?= esc($j['nama_kelas']) ?> (<?= esc($j['kode_kelas']) ?>)</td>
                                            <td><?= $j['nama_mapel'] ?></td>
                                            <td><?= $j['jam_ke'] ?></td>
                                            <td><?= substr($j['materi'], 0, 50) . '...' ?></td>
                                            <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
                                                <td><?= $j['nama_guru'] ?? 'Anda' ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <?php if ($j['status'] == 'published'): ?>
                                                    <span class="badge bg-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('guru/jurnal/view/' . $j['id']) ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (!isset($is_wali_kelas) || !$is_wali_kelas || (isset($j['user_id']) && $j['user_id'] == session()->get('user_id'))): ?>
                                                    <a href="<?= base_url('guru/jurnal/edit/' . $j['id']) ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('guru/jurnal/delete/' . $j['id']) ?>" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/js/guru-jurnal.js') ?>"></script>
<?= $this->endSection() ?>