<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/custom-admin.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="m-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main);">Data Absensi</h1>
                <p class="text-muted m-0">Kelola data kehadiran siswa dengan mudah</p>
            </div>
            <div class="d-flex">
                <?php if (!empty($rombel)): ?>
                    <a href="<?= base_url('admin/absensi/detail/' . $rombel[0]['id']) ?>" class="custom-btn custom-btn-info custom-btn-sm mr-2">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('admin/absensi/create') ?>" class="custom-btn custom-btn-primary custom-btn-sm mr-2">
                    <i class="fas fa-plus"></i> Input Absensi
                </a>
                <a href="<?= base_url('admin/absensi/export') ?>" class="custom-btn custom-btn-secondary custom-btn-sm">
                    <i class="fas fa-file-export"></i> Export
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); background-color: #DCFCE7; color: #166534; border: 1px solid #86EFAC;">
                <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); background-color: #FEE2E2; color: #991B1B; border: 1px solid #FECACA;">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Filter Card -->
        <div class="custom-card">
            <div class="custom-card-body">
                <form action="<?= base_url('admin/absensi') ?>" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label text-muted small font-weight-bold">TANGGAL MULAI</label>
                            <input type="date" name="start_date" id="start_date" class="custom-form-control w-100" value="<?= $startDate ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label text-muted small font-weight-bold">TANGGAL AKHIR</label>
                            <input type="date" name="end_date" id="end_date" class="custom-form-control w-100" value="<?= $endDate ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="rombel_id" class="form-label text-muted small font-weight-bold">ROMBEL</label>
                            <select name="rombel_id" id="rombel_id" class="custom-form-control w-100">
                                <option value="">Semua Rombel</option>
                                <?php foreach ($rombel as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= ($rombelId == $r['id']) ? 'selected' : '' ?>>
                                        <?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="custom-btn custom-btn-primary w-100 mr-2">Filter</button>
                            <a href="<?= base_url('admin/absensi') ?>" class="custom-btn custom-btn-secondary w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Grafik Absensi -->
        <div class="custom-card">
            <div class="custom-card-header">
                <h3 class="custom-card-title">Grafik Rekapitulasi</h3>
            </div>
            <div class="custom-card-body">
                <canvas id="absensiChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Tabel Ringkasan Absensi -->
        <div class="custom-card">
            <div class="custom-card-header">
                <h3 class="custom-card-title">Rekapitulasi per Kelas</h3>
            </div>
            <div class="custom-card-body p-0">
                <div class="table-responsive">
                    <table class="custom-table">
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
                                        <td>
                                            <span style="font-weight: 600; color: var(--text-main);"><?= esc($kelas['nama_rombel']) ?></span>
                                        </td>
                                        <td><?= esc($kelas['jumlah_siswa']) ?></td>
                                        <td><span class="custom-badge badge-success-soft"><?= esc($kelas['hadir']) ?></span></td>
                                        <td><span class="custom-badge badge-info-soft"><?= esc($kelas['izin']) ?></span></td>
                                        <td><span class="custom-badge badge-warning-soft"><?= esc($kelas['sakit']) ?></span></td>
                                        <td><span class="custom-badge badge-danger-soft"><?= esc($kelas['alfa']) ?></span></td>
                                        <td>
                                            <a href="<?= base_url('admin/absensi/detail/' . $kelas['rombel_id'] . '?start_date=' . $startDate . '&end_date=' . $endDate) ?>" 
                                               class="custom-btn custom-btn-secondary custom-btn-sm" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/gray/surveillance-camera.svg" alt="No Data" style="width: 150px; opacity: 0.5;">
                                        <p class="text-muted mt-3">Tidak ada data rekapitulasi untuk periode ini.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
    
    // Custom Chart Config
    Chart.defaults.global.defaultFontFamily = "'Inter', sans-serif";
    Chart.defaults.global.defaultFontColor = '#64748B';
    
    var absensiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hariLabels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadirData,
                    backgroundColor: '#10B981',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Izin',
                    data: izinData,
                    backgroundColor: '#0EA5E9',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Sakit',
                    data: sakitData,
                    backgroundColor: '#F59E0B',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Alpha',
                    data: alfaData,
                    backgroundColor: '#EF4444',
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        padding: 10
                    },
                    gridLines: {
                        borderDash: [2, 4],
                        color: '#F1F5F9',
                        drawBorder: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            },
            tooltips: {
                backgroundColor: '#1E293B',
                titleFontFamily: "'Outfit', sans-serif",
                bodyFontFamily: "'Inter', sans-serif",
                cornerRadius: 8,
                xPadding: 12,
                yPadding: 12
            }
        }
    });
});
</script>
<?= $this->endSection() ?>