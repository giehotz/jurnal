<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Absensi Kelas <?= esc($rombel['nama_rombel']) ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kelas</span>
                                <span class="info-box-number"><?= esc($rombel['kode_rombel']) ?> - <?= esc($rombel['nama_rombel']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filter Tanggal -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="filter_date" class="mr-2">Pilih Tanggal:</label>
                                <input type="date" name="filter_date" id="filter_date" class="form-control" value="<?= esc($filterDate) ?>" onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <h5><i class="fas fa-calendar-alt"></i> Tanggal: <?= date('d F Y', strtotime($filterDate)) ?></h5>
                        </div>
                    </div>
                </div>

                <!-- Alert Hari Libur -->
                <?php if ($isHoliday): ?>
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Hari Libur!</h5>
                        Tanggal <?= date('d F Y', strtotime($filterDate)) ?> adalah hari libur: <strong><?= esc($holidayName) ?></strong>. 
                        Tidak dapat mengisi absensi pada hari libur.
                    </div>
                <?php endif; ?>

                <!-- Tabel Absensi -->
                <?php if (!$isHoliday): ?>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($detailAbsensi)): ?>
                                <?php $no = 1; foreach ($detailAbsensi as $d): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                                        <td><?= esc($d['nis']) ?></td>
                                        <td><?= esc($d['nama_siswa']) ?></td>
                                        <td>
                                            <?php 
                                                switch($d['status']) {
                                                    case 'hadir':
                                                        echo '<span class="badge bg-success">Hadir</span>';
                                                        break;
                                                    case 'sakit':
                                                        echo '<span class="badge bg-warning">Sakit</span>';
                                                        break;
                                                    case 'izin':
                                                        echo '<span class="badge bg-info">Izin</span>';
                                                        break;
                                                    case 'alfa':
                                                        echo '<span class="badge bg-danger">Alfa</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">' . esc($d['status']) . '</span>';
                                                }
                                            ?>
                                        </td>
                                        <td><?= esc($d['keterangan']) ?></td>
                                        <td>
                                            <a href="<?= base_url('guru/absensi/edit/' . $d['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <p class="text-muted">Tidak ada data absensi pada tanggal ini.</p>
                                        <a href="<?= base_url('guru/absensi/create?rombel_id=' . $rombel['id'] . '&tanggal=' . $filterDate) ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Isi Absensi Sekarang
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
