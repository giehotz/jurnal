<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Absensi Kelas <?= esc($rombel['nama_rombel']) ?></h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/absensi?start_date=' . $startDate . '&end_date=' . $endDate . '&rombel_id=' . $rombel['id']) ?>" class="btn btn-secondary btn-sm">
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
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <h5><i class="fas fa-calendar-alt"></i> Periode</h5>
                            <p>Dari tanggal <?= date('d-m-Y', strtotime($startDate)) ?> sampai <?= date('d-m-Y', strtotime($endDate)) ?></p>
                        </div>
                    </div>
                </div>
                
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
                                <td colspan="7" class="text-center">Tidak ada data absensi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
