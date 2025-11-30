<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4 pt-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="m-0 text-dark font-weight-bold h3">Dashboard Overview</h1>
                    <p class="text-muted mb-0">Selamat datang kembali, <strong><?= session()->get('nama') ?></strong>!</p>
                </div>
                <div class="text-right">
                    <span class="badge badge-light p-2 font-weight-normal shadow-sm">
                        <i class="far fa-calendar-alt mr-1 text-primary"></i> <?= date('d F Y') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-white shadow-sm border-0 rounded-lg overflow-hidden position-relative h-100">
                <div class="inner p-4">
                    <h3 class="font-weight-bold text-primary"><?= $total_guru ?? 0 ?></h3>
                    <p class="text-muted font-weight-bold text-uppercase small mb-0">Total Guru</p>
                </div>
                <div class="icon text-primary opacity-10" style="top: 15px; right: 20px; font-size: 3.5rem; opacity: 0.1;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="<?= base_url('admin/guru') ?>" class="small-box-footer bg-primary text-white py-2 px-3 d-block text-decoration-none" style="opacity: 0.9; transition: all 0.3s;">
                    Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-white shadow-sm border-0 rounded-lg overflow-hidden position-relative h-100">
                <div class="inner p-4">
                    <h3 class="font-weight-bold text-success"><?= $total_kelas ?? 0 ?></h3>
                    <p class="text-muted font-weight-bold text-uppercase small mb-0">Total Kelas</p>
                </div>
                <div class="icon text-success opacity-10" style="top: 15px; right: 20px; font-size: 3.5rem; opacity: 0.1;">
                    <i class="fas fa-school"></i>
                </div>
                <a href="<?= base_url('admin/rombel') ?>" class="small-box-footer bg-success text-white py-2 px-3 d-block text-decoration-none" style="opacity: 0.9; transition: all 0.3s;">
                    Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-white shadow-sm border-0 rounded-lg overflow-hidden position-relative h-100">
                <div class="inner p-4">
                    <h3 class="font-weight-bold text-info"><?= $total_mapel ?? 0 ?></h3>
                    <p class="text-muted font-weight-bold text-uppercase small mb-0">Total Mapel</p>
                </div>
                <div class="icon text-info opacity-10" style="top: 15px; right: 20px; font-size: 3.5rem; opacity: 0.1;">
                    <i class="fas fa-book"></i>
                </div>
                <a href="<?= base_url('admin/mapel') ?>" class="small-box-footer bg-info text-white py-2 px-3 d-block text-decoration-none" style="opacity: 0.9; transition: all 0.3s;">
                    Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-white shadow-sm border-0 rounded-lg overflow-hidden position-relative h-100">
                <div class="inner p-4">
                    <h3 class="font-weight-bold text-warning"><?= $jurnal_hari_ini ?? 0 ?></h3>
                    <p class="text-muted font-weight-bold text-uppercase small mb-0">Jurnal Hari Ini</p>
                </div>
                <div class="icon text-warning opacity-10" style="top: 15px; right: 20px; font-size: 3.5rem; opacity: 0.1;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <a href="<?= base_url('admin/monitoring') ?>" class="small-box-footer bg-warning text-white py-2 px-3 d-block text-decoration-none" style="opacity: 0.9; transition: all 0.3s;">
                    Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-chart-line mr-2 text-primary"></i> Statistik Jurnal Bulanan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="position-relative" style="height: 350px;">
                        <canvas id="jurnalChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-lg mb-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-chart-pie mr-2 text-info"></i> Jurnal per Mapel</h3>
                </div>
                <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                    <div class="position-relative w-100" style="height: 300px;">
                        <canvas id="mapelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-crown mr-2 text-warning"></i> Top 5 Guru Paling Aktif</h3>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="position-relative" style="height: 300px;">
                        <canvas id="guruChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-history mr-2 text-secondary"></i> Aktivitas Terbaru</h3>
                        <a href="<?= base_url('admin/monitoring') ?>" class="btn btn-sm btn-light shadow-sm rounded-pill px-3">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="pl-4">Guru</th>
                                    <th>Mapel</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th class="text-right pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_jurnals)): ?>
                                    <?php foreach ($recent_jurnals as $jurnal): ?>
                                        <tr>
                                            <td class="pl-4 align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-user text-secondary"></i>
                                                    </div>
                                                    <div>
                                                        <span class="font-weight-bold d-block text-dark"><?= $jurnal['nama_guru'] ?></span>
                                                        <small class="text-muted"><?= date('d M Y', strtotime($jurnal['created_at'])) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle"><span class="badge badge-light border px-2 py-1"><?= $jurnal['nama_mapel'] ?></span></td>
                                            <td class="align-middle text-muted"><?= $jurnal['kode_rombel'] ?></td>
                                            <td class="align-middle">
                                                <?php if ($jurnal['status'] == 'published'): ?>
                                                    <span class="badge badge-soft-success px-2 py-1 rounded-pill"><i class="fas fa-check-circle mr-1"></i> Published</span>
                                                <?php else: ?>
                                                    <span class="badge badge-soft-warning px-2 py-1 rounded-pill"><i class="fas fa-clock mr-1"></i> Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right pr-4 align-middle">
                                                <a href="<?= base_url('admin/monitoring/detail/' . $jurnal['id']) ?>" class="btn btn-sm btn-outline-primary rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada aktivitas jurnal terbaru.</td>
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

<style>
    .small-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .small-box .icon {
        transition: all 0.3s linear;
    }
    .small-box:hover .icon {
        transform: scale(1.1);
    }
    .badge-soft-success {
        color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }
    .badge-soft-warning {
        color: #ffc107;
        background-color: rgba(255, 193, 7, 0.1);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
</style>

<!-- Scripts -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('AdminLTE/plugins/chart.js/Chart.min.js') ?>"></script>
<script>
$(function () {
    'use strict'

    // Global Chart Defaults
    Chart.defaults.global.defaultFontFamily = "'Inter', 'Segoe UI', 'Helvetica Neue', 'Arial', sans-serif";
    Chart.defaults.global.defaultFontColor = '#6c757d';

    // Variabel untuk menyimpan instance chart
    var jurnalChartInstance = null;
    var mapelChartInstance = null;
    var guruChartInstance = null;

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart jurnal per bulan
    function initJurnalChart() {
        if (jurnalChartInstance) {
            jurnalChartInstance.destroy();
        }
        
        var ctx = $('#jurnalChart').get(0).getContext('2d');
        
        // Gradient Fill
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(60, 141, 188, 0.5)');
        gradient.addColorStop(1, 'rgba(60, 141, 188, 0.05)');

        var bulanLabels = [
            '<?= date("M", strtotime("-11 months")) ?>', '<?= date("M", strtotime("-10 months")) ?>',
            '<?= date("M", strtotime("-9 months")) ?>', '<?= date("M", strtotime("-8 months")) ?>',
            '<?= date("M", strtotime("-7 months")) ?>', '<?= date("M", strtotime("-6 months")) ?>',
            '<?= date("M", strtotime("-5 months")) ?>', '<?= date("M", strtotime("-4 months")) ?>',
            '<?= date("M", strtotime("-3 months")) ?>', '<?= date("M", strtotime("-2 months")) ?>',
            '<?= date("M", strtotime("-1 months")) ?>', '<?= date("M") ?>'
        ];
        
        var jurnalChartData = {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Jurnal',
                backgroundColor: gradient,
                borderColor: '#3c8dbc',
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3c8dbc',
                pointHoverBackgroundColor: '#3c8dbc',
                pointHoverBorderColor: '#fff',
                borderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                lineTension: 0.4,
                data: <?= json_encode($jurnal_per_bulan ?? [0,0,0,0,0,0,0,0,0,0,0,0]) ?>
            }]
        }
        
        var jurnalChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: { display: false },
            tooltips: {
                backgroundColor: '#fff',
                titleFontColor: '#333',
                bodyFontColor: '#666',
                borderColor: '#ddd',
                borderWidth: 1,
                xPadding: 10,
                yPadding: 10,
                displayColors: false,
                intersect: false,
                mode: 'index'
            },
            scales: {
                xAxes: [{ 
                    gridLines: { display: false } 
                }],
                yAxes: [{ 
                    ticks: { beginAtZero: true, stepSize: 1, precision: 0 },
                    gridLines: { borderDash: [5, 5], color: '#f0f0f0' }
                }]
            }
        }
        
        jurnalChartInstance = new Chart(ctx, {
            type: 'line',
            data: jurnalChartData,
            options: jurnalChartOptions
        });
    }

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart jurnal per mapel
    function initMapelChart() {
        if (mapelChartInstance) {
            mapelChartInstance.destroy();
        }
        
        var ctx = $('#mapelChart').get(0).getContext('2d');
        
        var mapelLabels = [];
        var mapelData = [];
        var mapelColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
        
        <?php if (!empty($jurnal_per_mapel)): ?>
            <?php foreach ($jurnal_per_mapel as $index => $mapel): ?>
                mapelLabels.push('<?= $mapel['nama_mapel'] ?>');
                mapelData.push(<?= $mapel['jumlah'] ?>);
            <?php endforeach; ?>
        <?php else: ?>
            mapelLabels = ['Tidak ada data'];
            mapelData = [0];
        <?php endif; ?>
        
        var mapelChartData = {
            labels: mapelLabels,
            datasets: [{
                data: mapelData,
                backgroundColor: mapelColors,
                hoverBackgroundColor: mapelColors,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
                borderWidth: 4
            }]
        }
        
        var mapelChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: { 
                position: 'bottom', 
                labels: { usePointStyle: true, padding: 20 } 
            },
            cutoutPercentage: 70,
        }
        
        mapelChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: mapelChartData,
            options: mapelChartOptions
        });
    }

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart top 5 guru
    function initGuruChart() {
        if (guruChartInstance) {
            guruChartInstance.destroy();
        }
        
        var ctx = $('#guruChart').get(0).getContext('2d');
        
        var guruLabels = [];
        var guruData = [];
        
        <?php if (!empty($guru_aktif_data)): ?>
            <?php foreach ($guru_aktif_data as $index => $guru): ?>
                guruLabels.push('<?= $guru['nama'] ?>');
                guruData.push(<?= $guru['jumlah_jurnal'] ?>);
            <?php endforeach; ?>
        <?php else: ?>
            guruLabels = ['Tidak ada data'];
            guruData = [0];
        <?php endif; ?>
        
        var guruChartData = {
            labels: guruLabels,
            datasets: [{
                label: 'Jumlah Jurnal',
                backgroundColor: '#4e73df',
                hoverBackgroundColor: '#2e59d9',
                borderColor: '#4e73df',
                data: guruData,
                barPercentage: 0.6
            }]
        }
        
        var guruChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: { display: false },
            scales: {
                xAxes: [{ 
                    ticks: { beginAtZero: true, stepSize: 1, precision: 0 },
                    gridLines: { borderDash: [5, 5], color: '#f0f0f0' }
                }],
                yAxes: [{ 
                    gridLines: { display: false } 
                }]
            }
        }
        
        guruChartInstance = new Chart(ctx, {
            type: 'horizontalBar',
            data: guruChartData,
            options: guruChartOptions
        });
    }

    // Inisialisasi semua chart saat halaman dimuat
    initJurnalChart();
    initMapelChart();
    initGuruChart();
})
</script>
<?= $this->endSection() ?>