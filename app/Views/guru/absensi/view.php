<?= $this->extend('guru/layouts/template')?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-eye mr-2"></i>Detail Absensi</h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informasi Jurnal -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Tanggal</th>
                                <td>: <?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td>: <?= esc($rombel['kode_rombel']) ?> - <?= esc($rombel['nama_rombel']) ?></td>
                            </tr>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <td>: <?= esc($mapel['nama_mapel']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Jam Ke</th>
                                <td>: <?= $jurnal['jam_ke'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Materi</th>
                                <td>: <?= esc($jurnal['materi']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Statistik Kehadiran -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Hadir</span>
                                <span class="info-box-number"><?= $stats['hadir'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Izin</span>
                                <span class="info-box-number"><?= $stats['izin'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-user-injured"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sakit</span>
                                <span class="info-box-number"><?= $stats['sakit'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Alfa</span>
                                <span class="info-box-number"><?= $stats['alfa'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Siswa -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Daftar Kehadiran Siswa</h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">NIS</th>
                                    <th width="30%">Nama Siswa</th>
                                    <th width="15%">Status</th>
                                    <th width="40%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($absensi)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($absensi as $item): ?>
                                        <?php
                                        // Ambil data siswa
                                        $siswaModel = new \App\Models\SiswaModel();
                                        $siswa = $siswaModel->find($item['siswa_id']);
                                        
                                        // Tentukan badge class berdasarkan status
                                        $badgeClass = '';
                                        $statusText = '';
                                        switch ($item['status']) {
                                            case 'hadir':
                                                $badgeClass = 'badge-success';
                                                $statusText = 'Hadir';
                                                break;
                                            case 'izin':
                                                $badgeClass = 'badge-info';
                                                $statusText = 'Izin';
                                                break;
                                            case 'sakit':
                                                $badgeClass = 'badge-warning';
                                                $statusText = 'Sakit';
                                                break;
                                            case 'alfa':
                                                $badgeClass = 'badge-danger';
                                                $statusText = 'Alfa';
                                                break;
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($siswa['nis']) ?></td>
                                            <td><?= esc($siswa['nama']) ?></td>
                                            <td>
                                                <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td><?= esc($item['keterangan']) ?: '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data absensi</td>
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
<?= $this->endSection() ?>
