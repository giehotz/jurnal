<?= $this->extend('admin/layouts/adminlte'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Guru</h1>
        <a href="<?= base_url('admin/guru/create') ?>" class="d-none d-sm-inline-block btn btn-sm custom-btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Guru
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <?= session()->getFlashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4 custom-card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Guru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Mata Pelajaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($teachers as $teacher) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $teacher['nip']; ?></td>
                                <td><?= $teacher['nama']; ?></td>
                                <td><?= $teacher['email']; ?></td>
                                <td><?= $teacher['mata_pelajaran']; ?></td>
                                <td>
                                    <?php if ($teacher['is_active']) : ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/guru/edit/' . $teacher['id']) ?>" class="btn btn-warning btn-sm custom-btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/guru/delete/' . $teacher['id']) ?>" class="btn btn-danger btn-sm custom-btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
