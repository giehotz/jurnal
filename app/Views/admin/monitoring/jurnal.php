<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Monitoring Jurnal</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter Guru:</label>
                            <select class="form-control select2" id="guru-filter" style="width: 100%;">
                                <option value="">Semua Guru</option>
                                <?php foreach ($guru_list as $guru): ?>
                                    <option value="<?= $guru['nama'] ?>"><?= $guru['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter Kelas:</label>
                            <select class="form-control select2" id="kelas-filter" style="width: 100%;">
                                <option value="">Semua Kelas</option>
                                <?php foreach ($kelas_list as $k): ?>
                                    <option value="<?= $k['nama_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter Tanggal:</label>
                            <input type="date" class="form-control" id="tanggal-filter" value="<?= isset($filter['tanggal']) ? $filter['tanggal'] : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary" id="btn-filter">Filter</button>
                        <button class="btn btn-secondary" id="btn-reset">Reset</button>
                        <a href="<?= base_url('admin/monitoring/exportToPdf') ?>" class="btn btn-danger">Export PDF</a>
                        <a href="<?= base_url('admin/monitoring/exportToExcel') ?>" class="btn btn-success">Export Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Jurnal</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Guru</th>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Ke-</th>
                            <th>Materi</th>
                            <th>Jumlah Jam</th>
                            <th>Jumlah Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($jurnals as $jurnal): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $jurnal['guru_nama'] ?></td>
                            <td><?= $jurnal['tanggal'] ?></td>
                            <td><?= $jurnal['kelas_nama'] ?></td>
                            <td><?= $jurnal['mapel_nama'] ?></td>
                            <td><?= $jurnal['jam_ke'] ?></td>
                            <td><?= $jurnal['materi'] ?></td>
                            <td><?= $jurnal['jumlah_jam'] ?></td>
                            <td><?= $jurnal['jumlah_peserta'] ?></td>
                            <td>
                                <?php if ($jurnal['status'] == 'published'): ?>
                                    <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/monitoring/detail/' . $jurnal['id']) ?>" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2();
        
        // Event handler untuk tombol filter
        $('#btn-filter').click(function() {
            var guru = $('#guru-filter').val();
            var kelas = $('#kelas-filter').val();
            var tanggal = $('#tanggal-filter').val();
            
            var url = '<?= base_url("admin/monitoring") ?>?';
            var params = [];
            
            if (guru) params.push('guru_id=' + encodeURIComponent(guru));
            if (kelas) params.push('kelas_id=' + encodeURIComponent(kelas));
            if (tanggal) params.push('tanggal=' + encodeURIComponent(tanggal));
            
            window.location.href = url + params.join('&');
        });
        
        // Event handler untuk tombol reset
        $('#btn-reset').click(function() {
            $('#guru-filter').val('').trigger('change');
            $('#kelas-filter').val('').trigger('change');
            $('#tanggal-filter').val('');
        });
    });
</script>
<?= $this->endSection() ?>
