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
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Rombel</th>
                            <th>Nama Rombel</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Wali Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rombels)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($rombels as $rombel): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($rombel['kode_rombel']) ?></td>
                                <td><?= esc($rombel['nama_rombel']) ?></td>
                                <td><?= esc($rombel['tingkat']) ?></td>
                                <td><?= esc($rombel['jurusan'] ?? '-') ?></td>
                                <td><?= esc($rombel['wali_kelas_nama']) ?></td>
                                <td><?= esc($rombel['tahun_ajaran']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/rombel/view/' . $rombel['id']) ?>" class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('admin/rombel/edit/' . $rombel['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/rombel/delete/' . $rombel['id']) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus rombel ini? Semua data terkait akan ikut terhapus.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data rombel</td>
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

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<script src="<?= base_url('AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script>
$(function () {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>
<?= $this->endSection() ?>