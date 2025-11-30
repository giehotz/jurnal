<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Guru</span>
                    <span class="info-box-number"><?= $total_guru ?? 0 ?></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-school"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Kelas</span>
                    <span class="info-box-number"><?= $total_kelas ?? 0 ?></span>
                </div>
            </div>
        </div>
        <div class="clearfix hidden-md-up"></div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Mapel</span>
                    <span class="info-box-number"><?= $total_mapel ?? 0 ?></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-calendar-day"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Jurnal Hari Ini</span>
                    <span class="info-box-number"><?= $jurnal_hari_ini ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Grafik Jurnal Per Bulan</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="jurnalChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jurnal per Mata Pelajaran</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="mapelChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Guru Paling Aktif</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="guruChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Absensi Siswa Hari Ini</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="absensiChart" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="far fa-circle text-success"></i> Hadir (<?= $absensi_stats['hadir'] ?>)</li>
                                <li><i class="far fa-circle text-warning"></i> Sakit (<?= $absensi_stats['sakit'] ?>)</li>
                                <li><i class="far fa-circle text-primary"></i> Izin (<?= $absensi_stats['izin'] ?>)</li>
                                <li><i class="far fa-circle text-danger"></i> Alpa (<?= $absensi_stats['alfa'] ?>)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">10 Jurnal Terakhir Dibuat</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Guru</th>
                                    <th>Mapel</th>
                                    <th>Kelas</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_jurnals)): ?>
                                    <?php foreach ($recent_jurnals as $jurnal): ?>
                                        <tr>
                                            <td><?= $jurnal['id'] ?></td>
                                            <td><?= $jurnal['nama_guru'] ?></td>
                                            <td><?= $jurnal['nama_mapel'] ?></td>
                                            <td><?= $jurnal['kode_kelas'] ?></td>
                                            <td><?= date('d M Y', strtotime($jurnal['created_at'])) ?></td>
                                            <td>
                                                <?php if ($jurnal['status'] == 'published'): ?>
                                                    <span class="badge badge-success">Published</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/monitoring/detail/' . $jurnal['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada jurnal terbaru.</td>
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

<!-- Scripts -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('AdminLTE/plugins/chart.js/Chart.min.js') ?>"></script>
<script>
$(function () {
    'use strict'

    // Variabel untuk menyimpan instance chart
    var jurnalChartInstance = null;
    var mapelChartInstance = null;
    var guruChartInstance = null;

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart jurnal per bulan
    function initJurnalChart() {
        // Hancurkan chart jika sudah ada
        if (jurnalChartInstance) {
            jurnalChartInstance.destroy();
        }
        
        // Jurnal per bulan chart
        var jurnalChartCanvas = $('#jurnalChart').get(0).getContext('2d')
        
        // Data from PHP
        var bulanLabels = [
            '<?= date("M Y", strtotime("-11 months")) ?>',
            '<?= date("M Y", strtotime("-10 months")) ?>',
            '<?= date("M Y", strtotime("-9 months")) ?>',
            '<?= date("M Y", strtotime("-8 months")) ?>',
            '<?= date("M Y", strtotime("-7 months")) ?>',
            '<?= date("M Y", strtotime("-6 months")) ?>',
            '<?= date("M Y", strtotime("-5 months")) ?>',
            '<?= date("M Y", strtotime("-4 months")) ?>',
            '<?= date("M Y", strtotime("-3 months")) ?>',
            '<?= date("M Y", strtotime("-2 months")) ?>',
            '<?= date("M Y", strtotime("-1 months")) ?>',
            '<?= date("M Y") ?>'
        ];
        
        var jurnalChartData = {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Jumlah Jurnal',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: <?= json_encode($jurnal_per_bulan ?? [0,0,0,0,0,0,0,0,0,0,0,0]) ?>
                }
            ]
        }
        var jurnalChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
        jurnalChartInstance = new Chart(jurnalChartCanvas, {
            type: 'line',
            data: jurnalChartData,
            options: jurnalChartOptions
        });
    }

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart jurnal per mapel
    function initMapelChart() {
        // Hancurkan chart jika sudah ada
        if (mapelChartInstance) {
            mapelChartInstance.destroy();
        }
        
        // Jurnal per mapel chart
        var mapelChartCanvas = $('#mapelChart').get(0).getContext('2d')
        
        // Prepare data for the chart
        var mapelLabels = [];
        var mapelData = [];
        var mapelColors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc'];
        
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
            datasets: [
                {
                    label: 'Jumlah Jurnal',
                    backgroundColor: mapelColors,
                    data: mapelData
                }
            ]
        }
        var mapelChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        mapelChartInstance = new Chart(mapelChartCanvas, {
            type: 'bar',
            data: mapelChartData,
            options: mapelChartOptions
        });
    }

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart top 5 guru
    function initGuruChart() {
        // Hancurkan chart jika sudah ada
        if (guruChartInstance) {
            guruChartInstance.destroy();
        }
        
        // Top 5 guru chart
        var guruChartCanvas = $('#guruChart').get(0).getContext('2d')
        
        // Prepare data for the chart
        var guruLabels = [];
        var guruData = [];
        var guruColors = ['#d2d6de', '#a2d6de', '#d2a6de', '#d2d6ae', '#d2d6de'];
        
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
            datasets: [
                {
                    label: 'Jumlah Jurnal',
                    backgroundColor: guruColors,
                    data: guruData
                }
            ]
        }
        var guruChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            indexAxis: 'y', // Change to horizontal bar chart
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
        guruChartInstance = new Chart(guruChartCanvas, {
            type: 'bar',
            data: guruChartData,
            options: guruChartOptions
        });
    }

    // Inisialisasi semua chart saat halaman dimuat
    initJurnalChart();
    initMapelChart();
    initGuruChart();

    // Event listener untuk card jurnal per bulan
    $('#jurnalChart').closest('.card').on('expanded.lte.cardwidget', function() {
        initJurnalChart();
    });

    // Event listener untuk card jurnal per mapel
    $('#mapelChart').closest('.card').on('expanded.lte.cardwidget', function() {
        initMapelChart();
    });

    // Event listener untuk card top 5 guru
    $('#guruChart').closest('.card').on('expanded.lte.cardwidget', function() {
        initGuruChart();
    });

    // Variabel untuk chart absensi
    var absensiChartInstance = null;

    function initAbsensiChart() {
        if (absensiChartInstance) {
            absensiChartInstance.destroy();
        }

        var absensiChartCanvas = $('#absensiChart').get(0).getContext('2d');
        var absensiData = {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
            datasets: [{
                data: [
                    <?= $absensi_stats['hadir'] ?>, 
                    <?= $absensi_stats['sakit'] ?>, 
                    <?= $absensi_stats['izin'] ?>, 
                    <?= $absensi_stats['alfa'] ?>
                ],
                backgroundColor: ['#00a65a', '#f39c12', '#00c0ef', '#f56954']
            }]
        };

        var absensiOptions = {
            maintainAspectRatio: false,
            responsive: true,
        };

        absensiChartInstance = new Chart(absensiChartCanvas, {
            type: 'doughnut',
            data: absensiData,
            options: absensiOptions
        });
    }

    initAbsensiChart();

    $('#absensiChart').closest('.card').on('expanded.lte.cardwidget', function() {
        initAbsensiChart();
    });
})
</script>
<?= $this->endSection() ?>