<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Absensi</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/absensi/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Input Absensi Harian
                    </a>
                    <a href="<?= base_url('admin/absensi/export') ?>" class="btn btn-success btn-sm">
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
                        <form action="<?= base_url('admin/absensi') ?>" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $startDate ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">Tanggal Akhir</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $endDate ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="rombel_id">Rombel</label>
                                    <select name="rombel_id" id="rombel_id" class="form-control">
                                        <option value="">Semua Rombel</option>
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
                                    <a href="<?= base_url('admin/absensi') ?>" class="btn btn-secondary">Reset</a>
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
                                            <th>Kelas</th>
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
                                            <?php $no = 1; foreach ($rekapKelas as $kelas): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= esc($kelas['nama_rombel']) ?></td>
                                                    <td><?= esc($kelas['jumlah_siswa']) ?></td>
                                                    <td><?= esc($kelas['hadir']) ?></td>
                                                    <td><?= esc($kelas['izin']) ?></td>
                                                    <td><?= esc($kelas['sakit']) ?></td>
                                                    <td><?= esc($kelas['alfa']) ?></td>
                                                    <td>
                                                        <a href="<?= base_url('admin/absensi/detail/' . $kelas['rombel_id'] . '?start_date=' . $startDate . '&end_date=' . $endDate) ?>" 
                                                           class="btn btn-info btn-sm" 
                                                           title="Detail">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data rekapitulasi</td>
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

<!-- ChartJS -->
<script src="<?= base_url('AdminLTE') ?>/plugins/chart.js/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik absensi per hari
    var ctx = document.getElementById('absensiChart').getContext('2d');
    
    // Data sebenarnya dari controller
    var hariLabels = <?= json_encode($rekapHarian['labels']) ?>;
    var hadirData = <?= json_encode($rekapHarian['hadir']) ?>;
    var izinData = <?= json_encode($rekapHarian['izin']) ?>;
    var sakitData = <?= json_encode($rekapHarian['sakit']) ?>;
    var alfaData = <?= json_encode($rekapHarian['alfa']) ?>;
    
    var absensiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hariLabels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadirData,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)', // hijau
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Izin',
                    data: izinData,
                    backgroundColor: 'rgba(23, 162, 184, 0.8)', // biru
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Sakit',
                    data: sakitData,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)', // kuning
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Alpha',
                    data: alfaData,
                    backgroundColor: 'rgba(220, 53, 69, 0.8)', // merah
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>