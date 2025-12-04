<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/custom-admin.css') ?>">
<style>
    /* Specific styles for monitoring dashboard */
    .monitoring-stat-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        border: 1px solid #E2E8F0;
        box-shadow: var(--shadow-sm);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s;
    }
    .monitoring-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: var(--spacing-md);
    }
    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1;
        margin-bottom: var(--spacing-xs);
    }
    .stat-label {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 500;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="m-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main);">Monitoring</h1>
            <p class="text-muted m-0">Pantau aktivitas jurnal dan absensi secara real-time</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="custom-card mb-4">
        <div class="custom-card-body">
            <form action="<?= base_url('admin/monitoring') ?>" method="GET" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small font-weight-bold">RENTANG WAKTU</label>
                        <select name="range" class="custom-form-control w-100" id="rangeSelect">
                            <option value="today" <?= $filter['range'] == 'today' ? 'selected' : '' ?>>Hari Ini</option>
                            <option value="7_days" <?= $filter['range'] == '7_days' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                            <option value="this_month" <?= $filter['range'] == 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                            <option value="custom" <?= $filter['range'] == 'custom' ? 'selected' : '' ?>>Custom Tanggal</option>
                        </select>
                    </div>
                    <div class="col-md-3 custom-date" style="<?= $filter['range'] == 'custom' ? '' : 'display:none;' ?>">
                        <label class="form-label text-muted small font-weight-bold">DARI TANGGAL</label>
                        <input type="date" name="start_date" class="custom-form-control w-100" value="<?= $filter['start_date'] ?>">
                    </div>
                    <div class="col-md-3 custom-date" style="<?= $filter['range'] == 'custom' ? '' : 'display:none;' ?>">
                        <label class="form-label text-muted small font-weight-bold">SAMPAI TANGGAL</label>
                        <input type="date" name="end_date" class="custom-form-control w-100" value="<?= $filter['end_date'] ?>">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="custom-btn custom-btn-primary w-100 mr-2">
                                <i class="fas fa-filter"></i> Terapkan
                            </button>
                            <a href="<?= base_url('admin/monitoring') ?>" class="custom-btn custom-btn-secondary w-100">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 text-right ml-auto">
                        <div class="btn-group">
                            <button type="button" class="custom-btn custom-btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" style="border: 1px solid #E2E8F0; border-radius: var(--radius-md); box-shadow: var(--shadow-lg);">
                                <a class="dropdown-item py-2" href="<?= base_url('admin/monitoring/export/pdf') . '?' . $_SERVER['QUERY_STRING'] ?>">
                                    <i class="fas fa-file-pdf text-danger mr-2"></i> Export PDF
                                </a>
                                <a class="dropdown-item py-2" href="<?= base_url('admin/monitoring/export/excel') . '?' . $_SERVER['QUERY_STRING'] ?>">
                                    <i class="fas fa-file-excel text-success mr-2"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
            <div class="monitoring-stat-card">
                <div class="stat-icon-wrapper bg-emerald-soft">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $cards['total_jurnal'] ?></div>
                    <div class="stat-label">Total Jurnal</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
            <div class="monitoring-stat-card">
                <div class="stat-icon-wrapper" style="background-color: #E0F2FE; color: #0284C7;">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $cards['total_absensi'] ?></div>
                    <div class="stat-label">Total Absensi (Kelas)</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3 mb-lg-0">
            <div class="monitoring-stat-card">
                <div class="stat-icon-wrapper" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $cards['avg_attendance'] ?><span style="font-size: 1rem; color: var(--text-muted);">%</span></div>
                    <div class="stat-label">Rata-rata Kehadiran</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="monitoring-stat-card">
                <div class="stat-icon-wrapper" style="background-color: #FEE2E2; color: #DC2626;">
                    <i class="fas fa-school"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $cards['active_classes'] ?></div>
                    <div class="stat-label">Kelas Aktif</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart: Daily Activity -->
        <div class="col-md-8">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Aktivitas Jurnal & Absensi Harian</h3>
                </div>
                <div class="custom-card-body">
                    <canvas id="dailyActivityChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <!-- Chart: Student Attendance (Pie) -->
        <div class="col-md-4">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Statistik Kehadiran Siswa</h3>
                </div>
                <div class="custom-card-body">
                    <canvas id="studentAttendanceChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart: Class Attendance Bar -->
        <div class="col-md-6">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Grafik Kehadiran per Kelas</h3>
                </div>
                <div class="custom-card-body">
                    <canvas id="classAttendanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart: Monthly Trend -->
        <div class="col-md-6">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Trend Bulanan (6 Bulan Terakhir)</h3>
                </div>
                <div class="custom-card-body">
                    <canvas id="monthlyTrendChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Table: Rekap Kehadiran -->
        <div class="col-md-6">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Rekap Kehadiran per Kelas</h3>
                </div>
                <div class="custom-card-body p-0">
                    <div class="table-responsive" style="height: 300px;">
                        <table class="custom-table table-head-fixed">
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
                                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                                <?php else: ?>
                                    <?php foreach($rekap_kehadiran as $row): ?>
                                        <tr>
                                            <td><span class="font-weight-bold"><?= $row['nama_rombel'] ?></span></td>
                                            <td><span class="custom-badge badge-success-soft"><?= $row['total_hadir'] ?></span></td>
                                            <td><span class="custom-badge badge-warning-soft"><?= $row['total_sakit'] ?></span></td>
                                            <td><span class="custom-badge badge-info-soft"><?= $row['total_izin'] ?></span></td>
                                            <td><span class="custom-badge badge-danger-soft"><?= $row['total_alfa'] ?></span></td>
                                            <td>
                                                <?php 
                                                    $pct = round($row['avg_persentase'], 1);
                                                    $badgeClass = 'badge-success-soft';
                                                    if($pct < 60) $badgeClass = 'badge-danger-soft';
                                                    elseif($pct < 80) $badgeClass = 'badge-warning-soft';
                                                ?>
                                                <span class="custom-badge <?= $badgeClass ?>"><?= $pct ?>%</span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/absensi/detail/' . $row['rombel_id'] . '?start_date=' . $filter['start_date'] . '&end_date=' . $filter['end_date']) ?>" class="custom-btn custom-btn-info custom-btn-sm" target="_blank" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">
                                                    <i class="fas fa-eye"></i>
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
        </div>

        <!-- Table: Rekap Jurnal Harian -->
        <div class="col-md-6">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Rekap Jurnal Harian</h3>
                </div>
                <div class="custom-card-body p-0">
                    <div class="table-responsive" style="height: 300px;">
                        <table class="custom-table table-head-fixed">
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
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                                <?php else: ?>
                                    <?php foreach($rekap_jurnal_harian as $row): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                            <td><span class="font-weight-bold"><?= $row['total_jurnal'] ?></span></td>
                                            <td><?= $row['total_guru'] ?></td>
                                            <td><span class="custom-badge badge-success-soft">Active</span></td>
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

    <div class="row">
        <!-- Table: Monitoring Guru Aktif -->
        <div class="col-md-12">
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Top 10 Guru Paling Aktif</h3>
                </div>
                <div class="custom-card-body p-0">
                    <div class="table-responsive">
                        <table class="custom-table">
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
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                                <?php else: ?>
                                    <?php foreach($guru_aktif as $row): ?>
                                        <tr>
                                            <td><span class="font-weight-bold text-primary"><?= $row['nama'] ?></span></td>
                                            <td><?= $row['mata_pelajaran'] ?></td>
                                            <td><span class="custom-badge badge-info-soft"><?= $row['total_jurnal'] ?></span></td>
                                            <td><span class="custom-badge badge-success-soft"><?= $row['total_absensi'] ?></span></td>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('AdminLTE') ?>/plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url('assets/js/monitoring-charts.js') ?>"></script>
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

        // Initialize Charts
        const chartData = {
            dailyActivity: <?= json_encode($daily_activity) ?>,
            studentAttendance: <?= json_encode($student_attendance) ?>,
            classAttendance: <?= json_encode($rekap_kehadiran) ?>,
            monthlyTrend: <?= json_encode($monthly_trend) ?>
        };

        MonitoringCharts.init(chartData);
    });
</script>
<?= $this->endSection() ?>