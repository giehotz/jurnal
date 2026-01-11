<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-end">
            <div class="col-sm-6">
                <h1 class="m-0">Data Kelas</h1>
                <p class="m-0 text-muted">Master Data</p>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                <a href="#" class="btn btn-success ml-1 disabled" style="pointer-events: none; opacity: 0.6;" title="Fitur ini akan segera hadir">
                    <i class="fas fa-plus"></i> Tambah Sekaligus (Segera Hadir)
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Data Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Data Ruangan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <!-- Filter Section -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filterTingkat">Tingkat</label>
                                <select id="filterTingkat" class="form-control" style="width: 100%;">
                                    <option value="">--Pilih--</option>
                                    <?php
                                    $start = 1;
                                    $end = 12;
                                    if (isset($school_level)) {
                                        if ($school_level == 'SD/MI') {
                                            $start = 1;
                                            $end = 6;
                                        } elseif ($school_level == 'SMP/MTs') {
                                            $start = 7;
                                            $end = 9;
                                        } elseif ($school_level == 'SMA/MA') {
                                            $start = 10;
                                            $end = 12;
                                        }
                                    }
                                    for ($i = $start; $i <= $end; $i++) : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Wali Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Jenis</th>
                                    <th>Kurikulum</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($rombels)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($rombels as $rombel): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($rombel['nama_rombel']) ?></td>
                                            <td><?= esc($rombel['jumlah_siswa'] ?? '0') ?></td>
                                            <td><?= esc($rombel['wali_kelas_nama']) ?></td>
                                            <td><?= esc($rombel['tingkat']) ?></td>
                                            <td><?= esc($rombel['jurusan'] ?? '-') ?></td>
                                            <td><?= esc($rombel['jenis_rombel'] ?? 'Reguler') ?></td>
                                            <td><?= esc($rombel['kurikulum'] ?? '-') ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/rombel/view/' . $rombel['id']) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('admin/rombel/assign-students/' . $rombel['id']) ?>" class="btn btn-success btn-sm" title="Kelola Siswa">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>
                                                <a href="<?= base_url('admin/rombel/edit/' . $rombel['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('admin/rombel/delete/' . $rombel['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                        <div class="row mb-3">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahRuangan">
                                    <i class="fas fa-plus"></i> Tambah Ruangan
                                </button>
                            </div>
                        </div>
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Digunakan Oleh</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($ruangan as $r) : ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= esc($r['nama_ruangan']) ?></td>
                                        <td><?= esc($r['kapasitas']) ?></td>
                                        <td><?= esc($r['jenis']) ?></td>
                                        <td class="text-center">
                                            <?php if ($r['status'] == 'Kosong'): ?>
                                                <span class="badge badge-success">Kosong</span>
                                            <?php else: ?>
                                                <span class="badge badge-primary">Terpakai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($r['status'] == 'Terpakai'): ?>
                                                <a href="<?= base_url('admin/rombel/view/' . $r['rombel_id']) ?>"><i class="fas fa-chalkboard"></i> <?= esc($r['rombel_nama']) ?></a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($r['keterangan']) ?></td>
                                        <td>
                                            <?php if ($r['status'] == 'Kosong'): ?>
                                                <a href="<?= base_url('admin/rombel/create?ruangan_id=' . $r['id']) ?>" class="btn btn-info btn-sm" title="Buat Kelas di Ruangan ini">
                                                    <i class="fas fa-plus-circle"></i> Buat Kelas
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('admin/ruangan/edit/' . $r['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/ruangan/delete/' . $r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
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
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/rombel/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <!-- Informasi Utama -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="tingkat" class="col-sm-3 col-form-label">Tingkat/Kelas <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="tingkat" name="tingkat" required>
                                        <option value="">-Pilih-</option>
                                        <?php for ($i = $start; $i <= $end; $i++) : ?>
                                            <option value="<?= $i ?>">Kelas <?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jurusan" class="col-sm-3 col-form-label">Jurusan</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="jurusan" name="jurusan">
                                        <option value="">-Pilih-</option>
                                        <option value="IPA">IPA</option>
                                        <option value="IPS">IPS</option>
                                        <option value="Bahasa">Bahasa</option>
                                        <option value="Umum">Umum</option>
                                    </select>
                                    <small class="text-muted">Kosongkan jika tidak ada penjurusan</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_rombel" class="col-sm-3 col-form-label">Kategori Kelas <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="jenis_rombel" name="jenis_rombel" required>
                                        <option value="">-Pilih-</option>
                                        <option value="Reguler" selected>Reguler</option>
                                        <option value="Eskul">Eskul</option>
                                        <option value="Matrikulasi">Matrikulasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kurikulum" class="col-sm-3 col-form-label">Kurikulum</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="kurikulum" name="kurikulum">
                                        <option value="">-Pilih-</option>
                                        <option value="Kurikulum Merdeka">Kurikulum Merdeka</option>
                                        <option value="K13">K13</option>
                                        <option value="KTSP">KTSP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_rombel" class="col-sm-3 col-form-label">Ruangan <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="nama_rombel" name="nama_rombel" style="width: 100%;" required>
                                        <option value="">-Pilih Ruangan-</option>

                                        <?php if (isset($rooms) && !empty($rooms)) : ?>
                                            <?php foreach ($rooms as $room) : ?>
                                                <option value="<?= $room['nama_ruangan'] ?>" data-id="<?= $room['id'] ?>" data-jenis="<?= $room['jenis_ruangan'] ?? '' ?>">
                                                    <?= $room['nama_ruangan'] ?>
                                                    <?php if (!empty($room['jenis_ruangan'])): ?>
                                                        - <?= $room['jenis_ruangan'] ?>
                                                    <?php endif; ?>
                                                    (Kapasitas: <?= $room['kapasitas'] ?? 'N/A' ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>Tidak ada data ruangan</option>
                                        <?php endif; ?>
                                    </select>
                                    <input type="hidden" name="ruangan_id" id="ruangan_id">
                                    <small class="text-muted">Pilih ruangan untuk kelas ini</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kode_rombel" class="col-sm-3 col-form-label">Kode Kelas</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="kode_rombel" name="kode_rombel" placeholder="Otomatis dari ruangan">
                                    <small class="text-muted">Kode kelas dapat disesuaikan jika terjadi duplikasi</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="wali_kelas" class="col-sm-3 col-form-label">Nama Walikelas</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="wali_kelas" name="wali_kelas" style="width: 100%;">
                                        <option value="">-Pilih-</option>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>"><?= $teacher['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Hidden Required Fields default values -->
                            <input type="hidden" name="tahun_ajaran" value="<?= $school_year ?? '2025/2026' ?>">
                            <input type="hidden" name="semester" value="1">
                            <input type="hidden" name="kapasitas" value="30">
                            <!-- Note: The Controller validation requires tahun_ajaran. Ideally we add a field or hidden it.
                                 The Image doesn't show it. I will hide it for now using the passed variable.
                            -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables & Plugins -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('AdminLTE/plugins/select2/js/select2.full.min.js') ?>"></script>

<script>
    $(function() {
        // 1. Initialize DataTable
        var table = $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "print", "excel"],
            "dom": 'Bfrtip'
        });

        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        var table2 = $("#example2").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "print", "excel"],
            "dom": 'Bfrtip'
        });
        table2.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

        // 2. Initialize Select2 ONLY for Modal (NOT for plain filter)


        // Modal Select2 elements
        $('#modalTambah .select2').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#modalTambah')
        });

        // 3. Filter Event Listener (Plain select - NO Select2)
        // Filter logic for Tingkat
        $('#filterTingkat').on('change', function() {
            var val = $(this).val();
            console.log("Filter changed! Value:", val);

            if (val) {
                // Search for exact match in column 4 (Tingkat)
                var regex = '^' + $.fn.dataTable.util.escapeRegex(val) + '$';
                table.column(4).search(regex, true, false).draw();
            } else {
                // Clear filter
                table.column(4).search('').draw();
            }
        });

        // Auto-generate kode rombel from Select
        $('#nama_rombel').on('change', function() {
            var nama = $(this).val();
            var selectedOption = $(this).find('option:selected');
            var ruanganId = selectedOption.data('id');

            if (nama) {
                var kode = nama.replace(/\s+/g, '-').toUpperCase();
                $('#kode_rombel').val(kode);
                $('#ruangan_id').val(ruanganId);
            } else {
                $('#kode_rombel').val('');
                $('#ruangan_id').val('');
            }
        });
    });
</script>
<!-- Modal Tambah Ruangan -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-labelledby="modalTambahRuanganLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahRuanganLabel">Tambah Ruangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/ruangan/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_ruangan">Nama Ruangan</label>
                        <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" placeholder="Contoh: Ruang 101" required>
                    </div>
                    <div class="form-group">
                        <label for="kapasitas">Kapasitas</label>
                        <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="30" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis Ruangan</label>
                        <select class="form-control" id="jenis" name="jenis">
                            <option value="">-Pilih-</option>
                            <option value="Kelas">Kelas</option>
                            <option value="Lab Komputer">Lab Komputer</option>
                            <option value="Lab IPA">Lab IPA</option>
                            <option value="Perpustakaan">Perpustakaan</option>
                            <option value="Aula">Aula</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>