<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guru-responsive.css') ?>">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Absensi Siswa</h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/absensi/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Input Absensi Harian
                    </a>
                    <a href="<?= base_url('guru/absensi/export') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export"></i> Export Data Absensi
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Filter Form -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form action="<?= base_url('guru/absensi') ?>" method="GET" id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tanggal Mulai</label>
                                    <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                        <input type="text" name="start_date_display" class="form-control datetimepicker-input" data-target="#start_date_picker" value="<?= date('d/m/Y', strtotime($startDate)) ?>" />
                                        <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="start_date" id="start_date" value="<?= $startDate ?>">
                                </div>
                                <div class="col-md-3">
                                    <label>Tanggal Akhir</label>
                                    <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                        <input type="text" name="end_date_display" class="form-control datetimepicker-input" data-target="#end_date_picker" value="<?= date('d/m/Y', strtotime($endDate)) ?>" />
                                        <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="end_date" id="end_date" value="<?= $endDate ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="rombel_id">Kelas</label>
                                    <select name="rombel_id" id="rombel_id" class="form-control">
                                        <option value="">Semua Kelas</option>
                                        <?php foreach ($rombel as $r): ?>
                                            <option value="<?= $r['id'] ?>" <?= ($rombelId == $r['id']) ? 'selected' : '' ?>>
                                                <?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Grafik Absensi -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Grafik Rekapitulasi Absensi per Hari</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="absensiChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Ringkasan Absensi -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tabel Rekapitulasi Absensi per Kelas</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Kelas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Diisi Oleh</th>
                                            <th>Jumlah Siswa</th>
                                            <th>Hadir</th>
                                            <th>Izin</th>
                                            <th>Sakit</th>
                                            <th>Alpha</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($rekapKelas)): ?>
                                            <?php $no = 1;
                                            foreach ($rekapKelas as $kelas): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= date('d M Y', strtotime($kelas['tanggal'])) ?></td>
                                                    <td><?= esc($kelas['nama_rombel']) ?></td>
                                                    <td><?= esc($kelas['nama_mapel'] ?? '-') ?></td>
                                                    <td><?= esc($kelas['nama_guru'] ?? '-') ?></td>
                                                    <td><?= esc($kelas['jumlah_siswa']) ?></td>
                                                    <td><?= esc($kelas['hadir']) ?></td>
                                                    <td><?= esc($kelas['izin']) ?></td>
                                                    <td><?= esc($kelas['sakit']) ?></td>
                                                    <td><?= esc($kelas['alfa']) ?></td>
                                                    <td>
                                                        <?php if (!empty($kelas['rombel_id'])): ?>
                                                            <div class="btn-group">
                                                                <a href="<?= base_url('guru/absensi/detail/' . $kelas['rombel_id'] . '?start_date=' . date('Y-m-d', strtotime($kelas['tanggal'])) . '&end_date=' . date('Y-m-d', strtotime($kelas['tanggal']))) ?>"
                                                                    class="btn btn-info btn-sm"
                                                                    title="Detail">
                                                                    <i class="fas fa-eye"></i> Detail
                                                                </a>
                                                                <?php if (!empty($kelas['jurnal_id'])): ?>
                                                                    <a href="<?= base_url('guru/absensi/delete/' . $kelas['jurnal_id']) ?>"
                                                                        class="btn btn-danger btn-sm"
                                                                        title="Hapus"
                                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data absensi ini? Data jurnal terkait juga akan dihapus.')">
                                                                        <i class="fas fa-trash"></i> Hapus
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary btn-sm" disabled title="Tidak ada data">
                                                                <i class="fas fa-eye-slash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" class="text-center">Tidak ada data rekapitulasi</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/guru-responsive.css') ?>">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Absensi Siswa</h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/absensi/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Input Absensi Harian
                    </a>
                    <a href="<?= base_url('guru/absensi/export') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export"></i> Export Data Absensi
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Filter Form -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form action="<?= base_url('guru/absensi') ?>" method="GET" id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tanggal Mulai</label>
                                    <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                        <input type="text" name="start_date_display" class="form-control datetimepicker-input" data-target="#start_date_picker" value="<?= date('d/m/Y', strtotime($startDate)) ?>" />
                                        <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="start_date" id="start_date" value="<?= $startDate ?>">
                                </div>
                                <div class="col-md-3">
                                    <label>Tanggal Akhir</label>
                                    <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                        <input type="text" name="end_date_display" class="form-control datetimepicker-input" data-target="#end_date_picker" value="<?= date('d/m/Y', strtotime($endDate)) ?>" />
                                        <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="end_date" id="end_date" value="<?= $endDate ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="rombel_id">Kelas</label>
                                    <select name="rombel_id" id="rombel_id" class="form-control">
                                        <option value="">Semua Kelas</option>
                                        <?php foreach ($rombel as $r): ?>
                                            <option value="<?= $r['id'] ?>" <?= ($rombelId == $r['id']) ? 'selected' : '' ?>>
                                                <?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Grafik Absensi -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Grafik Rekapitulasi Absensi per Hari</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="absensiChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Ringkasan Absensi -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tabel Rekapitulasi Absensi per Kelas</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Kelas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Diisi Oleh</th>
                                            <th>Jumlah Siswa</th>
                                            <th>Hadir</th>
                                            <th>Izin</th>
                                            <th>Sakit</th>
                                            <th>Alpha</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($rekapKelas)): ?>
                                            <?php $no = 1;
                                            foreach ($rekapKelas as $kelas): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= date('d M Y', strtotime($kelas['tanggal'])) ?></td>
                                                    <td><?= esc($kelas['nama_rombel']) ?></td>
                                                    <td><?= esc($kelas['nama_mapel'] ?? '-') ?></td>
                                                    <td><?= esc($kelas['nama_guru'] ?? '-') ?></td>
                                                    <td><?= esc($kelas['jumlah_siswa']) ?></td>
                                                    <td><?= esc($kelas['hadir']) ?></td>
                                                    <td><?= esc($kelas['izin']) ?></td>
                                                    <td><?= esc($kelas['sakit']) ?></td>
                                                    <td><?= esc($kelas['alfa']) ?></td>
                                                    <td>
                                                        <?php if (!empty($kelas['rombel_id'])): ?>
                                                            <div class="btn-group">
                                                                <a href="<?= base_url('guru/absensi/detail/' . $kelas['rombel_id'] . '?start_date=' . date('Y-m-d', strtotime($kelas['tanggal'])) . '&end_date=' . date('Y-m-d', strtotime($kelas['tanggal']))) ?>"
                                                                    class="btn btn-info btn-sm"
                                                                    title="Detail">
                                                                    <i class="fas fa-eye"></i> Detail
                                                                </a>
                                                                <?php if (!empty($kelas['jurnal_id'])): ?>
                                                                    <a href="<?= base_url('guru/absensi/delete/' . $kelas['jurnal_id']) ?>"
                                                                        class="btn btn-danger btn-sm"
                                                                        title="Hapus"
                                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data absensi ini? Data jurnal terkait juga akan dihapus.')">
                                                                        <i class="fas fa-trash"></i> Hapus
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <button class="btn btn-secondary btn-sm" disabled title="Tidak ada data">
                                                                <i class="fas fa-eye-slash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" class="text-center">Tidak ada data rekapitulasi</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    if (typeof GuruAbsensi !== 'undefined') {
    var chartData = {
    labels: <?= json_encode($rekapHarian['labels'] ?? []) ?>,
    hadir: <?= json_encode($rekapHarian['hadir'] ?? []) ?>,
    izin: <?= json_encode($rekapHarian['izin'] ?? []) ?>,
    sakit: <?= json_encode($rekapHarian['sakit'] ?? []) ?>,
    alfa: <?= json_encode($rekapHarian['alfa'] ?? []) ?>
    };
    GuruAbsensi.init(chartData);
    } else {
    console.error("GuruAbsensi object not found!");
    }

    // Init Date Pickers
    if ($.fn.datetimepicker) {
    console.log("TempusDominus loaded");
    $('#start_date_picker').datetimepicker({
    format: 'DD/MM/YYYY',
    icons: {
    time: 'far fa-clock',
    date: 'far fa-calendar',
    up: 'fas fa-arrow-up',
    down: 'fas fa-arrow-down',
    previous: 'fas fa-chevron-left',
    next: 'fas fa-chevron-right',
    today: 'fas fa-calendar-check',
    clear: 'far fa-trash-alt',
    close: 'far fa-times-circle'
    }
    });
    $('#end_date_picker').datetimepicker({
    format: 'DD/MM/YYYY',
    icons: {
    time: 'far fa-clock',
    date: 'far fa-calendar',
    up: 'fas fa-arrow-up',
    down: 'fas fa-arrow-down',
    previous: 'fas fa-chevron-left',
    next: 'fas fa-chevron-right',
    today: 'fas fa-calendar-check',
    clear: 'far fa-trash-alt',
    close: 'far fa-times-circle'
    }
    });
    } else {
    console.error("TempusDominus not loaded!");
    }

    // Sync with hidden inputs
    $('#start_date_picker').on('change.datetimepicker', function(e) {
    if (e.date) {
    $('#start_date').val(e.date.format('YYYY-MM-DD'));
    }
    });
    $('#end_date_picker').on('change.datetimepicker', function(e) {
    if (e.date) {
    $('#end_date').val(e.date.format('YYYY-MM-DD'));
    }
    });
    });
    </script>
    <?= $this->endSection() ?>