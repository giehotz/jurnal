<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form action="<?= base_url('admin/monitoring') ?>" method="GET" id="filterForm">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label>Rentang Waktu</label>
                                <select name="range" class="form-control" id="rangeSelect">
                                    <option value="today" <?= $filter['range'] == 'today' ? 'selected' : '' ?>>Hari Ini</option>
                                    <option value="7_days" <?= $filter['range'] == '7_days' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                                    <option value="this_month" <?= $filter['range'] == 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                                    <option value="custom" <?= $filter['range'] == 'custom' ? 'selected' : '' ?>>Custom Tanggal</option>
                                </select>
                            </div>
                            <div class="col-md-3 custom-date" style="<?= $filter['range'] == 'custom' ? '' : 'display:none;' ?>">
                                <label>Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $filter['start_date'] ?>">
                            </div>
                            <div class="col-md-3 custom-date" style="<?= $filter['range'] == 'custom' ? '' : 'display:none;' ?>">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="<?= $filter['end_date'] ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Terapkan
                                </button>
                                <a href="<?= base_url('admin/monitoring') ?>" class="btn btn-secondary">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                            <div class="col-md-3 text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="<?= base_url('admin/monitoring/export/pdf') . '?' . $_SERVER['QUERY_STRING'] ?>">
                                            <i class="fas fa-file-pdf text-danger mr-2"></i> Export PDF
                                        </a>
                                        <a class="dropdown-item" href="<?= base_url('admin/monitoring/export/excel') . '?' . $_SERVER['QUERY_STRING'] ?>">
                                            <i class="fas fa-file-excel text-success mr-2"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $cards['total_jurnal'] ?></h3>
                    <p>Total Jurnal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $cards['total_absensi'] ?></h3>
                    <p>Total Absensi (Kelas)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $cards['avg_attendance'] ?><sup style="font-size: 20px">%</sup></h3>
                    <p>Rata-rata Kehadiran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $cards['active_classes'] ?></h3>
                    <p>Kelas Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-school"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart: Daily Activity -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Jurnal & Absensi Harian</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyActivityChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <!-- Chart: Student Attendance (Pie) -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Kehadiran Siswa</h3>
                </div>
                <div class="card-body">
                    <canvas id="studentAttendanceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart: Class Attendance Bar -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Kehadiran per Kelas</h3>
                </div>
                <div class="card-body">
                    <canvas id="classAttendanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart: Monthly Trend -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trend Bulanan (6 Bulan Terakhir)</h3>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Table: Rekap Kehadiran -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekap Kehadiran per Kelas</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Hadir</th>
                                <th>Sakit</th>
                                <th>Izin</th>
                                <th>Alfa</th>
                                <th>%</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($rekap_kehadiran)): ?>
                                <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                            <?php else: ?>
                                <?php foreach($rekap_kehadiran as $row): ?>
                                    <tr>
                                        <td><?= $row['nama_rombel'] ?></td>
                                        <td><?= $row['total_hadir'] ?></td>
                                        <td><?= $row['total_sakit'] ?></td>
                                        <td><?= $row['total_izin'] ?></td>
                                        <td><?= $row['total_alfa'] ?></td>
                                        <td>
                                            <?php 
                                                $pct = round($row['avg_persentase'], 1);
                                                $badge = 'success';
                                                if($pct < 60) $badge = 'danger';
                                                elseif($pct < 80) $badge = 'warning';
                                            ?>
                                            <span class="badge bg-<?= $badge ?>"><?= $pct ?>%</span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/absensi/detail/' . $row['rombel_id'] . '?start_date=' . $filter['start_date'] . '&end_date=' . $filter['end_date']) ?>" class="btn btn-xs btn-info" target="_blank">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Table: Rekap Jurnal Harian -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekap Jurnal Harian</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Total Jurnal</th>
                                <th>Guru Aktif</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($rekap_jurnal_harian)): ?>
                                <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                            <?php else: ?>
                                <?php foreach($rekap_jurnal_harian as $row): ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                        <td><?= $row['total_jurnal'] ?></td>
                                        <td><?= $row['total_guru'] ?></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Table: Monitoring Guru Aktif -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 10 Guru Paling Aktif</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Jurnal Dibuat</th>
                                <th>Absensi Diisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($guru_aktif)): ?>
                                <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                            <?php else: ?>
                                <?php foreach($guru_aktif as $row): ?>
                                    <tr>
                                        <td><?= $row['nama'] ?></td>
                                        <td><?= $row['mata_pelajaran'] ?></td>
                                        <td><?= $row['total_jurnal'] ?></td>
                                        <td><?= $row['total_absensi'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        // Toggle Custom Date Inputs
        $('#rangeSelect').change(function() {
            if($(this).val() == 'custom') {
                $('.custom-date').show();
            } else {
                $('.custom-date').hide();
            }
        });

        // --- Chart 1: Daily Activity (Line) ---
        var ctxDaily = document.getElementById('dailyActivityChart').getContext('2d');
        var dailyData = <?= json_encode($daily_activity) ?>;
        
        new Chart(ctxDaily, {
            type: 'line',
            data: {
                labels: dailyData.map(item => {
                    var date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                datasets: [
                    {
                        label: 'Jurnal',
                        data: dailyData.map(item => item.jurnal),
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        lineTension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Absensi',
                        data: dailyData.map(item => item.absensi),
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        lineTension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    yAxes: [{ 
                        ticks: { 
                            beginAtZero: true, 
                            stepSize: 1 
                        } 
                    }] 
                },
                legend: { position: 'top' }
            }
        });

        // --- Chart 2: Student Attendance (Pie) ---
        var ctxPie = document.getElementById('studentAttendanceChart').getContext('2d');
        var attendanceData = <?= json_encode($student_attendance) ?>;
        
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                datasets: [{
                    data: [
                        attendanceData.total_hadir || 0,
                        attendanceData.total_sakit || 0,
                        attendanceData.total_izin || 0,
                        attendanceData.total_alfa || 0
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom' }
            }
        });

        // --- Chart 3: Monthly Trend (Area) ---
        var ctxTrend = document.getElementById('monthlyTrendChart').getContext('2d');
        var trendData = <?= json_encode($monthly_trend) ?>;
        
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: trendData.map(item => item.month),
                datasets: [
                    {
                        label: 'Jurnal',
                        data: trendData.map(item => item.jurnal),
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                        lineTension: 0.3
                    },
                    {
                        label: 'Absensi',
                        data: trendData.map(item => item.absensi),
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        fill: true,
                        lineTension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    yAxes: [{ 
                        ticks: { 
                            beginAtZero: true 
                        } 
                    }] 
                },
                legend: { position: 'top' }
            }
        });

        // --- Chart 4: Class Attendance (Bar) ---
        var ctxClass = document.getElementById('classAttendanceChart').getContext('2d');
        var classData = <?= json_encode($rekap_kehadiran) ?>;
        
        // Prepare data
        var classLabels = [];
        var classPercentages = [];
        
        if (classData && classData.length > 0) {
            classLabels = classData.map(item => item.nama_rombel);
            classPercentages = classData.map(item => parseFloat(item.avg_persentase).toFixed(1));
        }

        var classColors = classPercentages.map(pct => {
            if(pct < 60) return '#dc3545'; // Danger
            if(pct < 80) return '#ffc107'; // Warning
            return '#28a745'; // Success
        });

        new Chart(ctxClass, {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [{
                    label: 'Persentase Kehadiran (%)',
                    data: classPercentages,
                    backgroundColor: classColors,
                    borderColor: classColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 100,
                            callback: function(value) { return value + "%" }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return tooltipItem.yLabel + '%';
                        }
                    }
                },
                legend: { display: false }
            }
        });
    });
</script>
<?= $this->endSection() ?>