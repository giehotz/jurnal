<?= $this->include('templates/header') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Daftar Jurnal Mengajar</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="<?= base_url('guru/jurnal/export/pdf') ?>" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="<?= base_url('guru/jurnal/export/excel') ?>" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
            <a href="<?= base_url('guru/jurnal/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jurnal
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Rombel</th>
                            <th>Mata Pelajaran</th>
                            <th>Topik</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jurnals)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data jurnal</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($jurnals as $jurnal): ?>
                        <tr>
                                <td><?= date('d M Y', strtotime($jurnal['tanggal'])) ?></td>
                                <td><?= esc($jurnal['nama_kelas']) ?> (<?= esc($jurnal['kode_kelas']) ?>)</td>
                                <td><?= $jurnal['nama_mapel'] ?></td>
                                <td><?= substr($jurnal['materi'], 0, 50) ?><?= strlen($jurnal['materi']) > 50 ? '...' : '' ?></td>
                                <td>
                                    <?php if ($jurnal['status'] == 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y H:i', strtotime($jurnal['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('guru/jurnal/view/' . (isset($jurnal['id']) ? $jurnal['id'] : '')) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="<?= base_url('guru/jurnal/edit/' . (isset($jurnal['id']) ? $jurnal['id'] : '')) ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('guru/jurnal/delete/' . (isset($jurnal['id']) ? $jurnal['id'] : '')) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>