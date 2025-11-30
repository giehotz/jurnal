<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rekap Jurnal per Guru</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="rekap-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Guru</th>
                            <th>Nama Guru</th>
                            <th>Total Jurnal</th>
                            <th>Jurnal Published</th>
                            <th>Jurnal Draft</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rekap_guru)): ?>
                            <?php foreach ($rekap_guru as $rekap): ?>
                            <tr>
                                <td><?= $rekap['id_guru'] ?></td>
                                <td><?= $rekap['nama_guru'] ?></td>
                                <td><?= $rekap['total_jurnal'] ?></td>
                                <td><?= $rekap['jurnal_published'] ?></td>
                                <td><?= $rekap['jurnal_draft'] ?></td>
                                <td>
                                    <a href="<?= base_url('admin/monitoring/jurnal?guru_id=' . $rekap['id_guru']) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Jurnal
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<script src="<?= base_url('AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

<script>
  $(function () {
    $("#rekap-table").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
    });
  });
</script>
<?= $this->endSection() ?>
