<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard Guru</h1>
            <p class="text-muted mb-0">Selamat Datang, <span class="text-primary font-weight-bold"><?= esc($userName) ?></span></p>
        </div>
        <div class="d-none d-md-block">
            <div class="bg-white p-2 rounded shadow-sm border">
                <i class="far fa-calendar-alt text-primary mr-2"></i>
                <span class="font-weight-bold text-dark"><?= date('d F Y') ?></span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jurnal Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['jurnal_bulan_ini'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-light text-primary">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jurnal Minggu Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['jurnal_minggu_ini'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success-light text-success">
                                <i class="fas fa-calendar-week fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Kelas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_kelas ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info-light text-info">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2 card-hover">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Mapel</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_mapel ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning-light text-warning">
                                <i class="fas fa-book fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8 mb-4">
            <!-- Teaching Activity Chart -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Mengajar</h6>
                </div>
                <div class="card-body">
                    <?= $this->include('guru/components/teaching_activity_chart') ?>
                </div>
            </div>

            <!-- Classes & Subjects -->
            <div class="card shadow-sm border-0">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <ul class="nav nav-pills custom-pills" id="teachingTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="kelas-tab" data-toggle="tab" href="#kelas-tab-pane" role="tab">
                                <i class="fas fa-chalkboard mr-2"></i>Kelas (<?= count($kelas_diajar) ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mapel-tab" data-toggle="tab" href="#mapel-tab-pane" role="tab">
                                <i class="fas fa-book-open mr-2"></i>Mapel (<?= count($mapel_diajar) ?>)
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="teachingTabsContent">
                        <div class="tab-pane fade show active" id="kelas-tab-pane" role="tabpanel">
                            <?php if (!empty($kelas_diajar)): ?>
                                <div class="row">
                                    <?php foreach ($kelas_diajar as $kelas): ?>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="p-3 rounded border bg-light d-flex align-items-center">
                                                <div class="bg-white p-2 rounded shadow-sm mr-3 text-primary">
                                                    <i class="fas fa-chalkboard"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-dark"><?= esc($kelas['nama_rombel']) ?></div>
                                                    <small class="text-muted"><?= esc($kelas['kode_rombel']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mb-2"></i>
                                    <p>Belum ada data kelas.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="mapel-tab-pane" role="tabpanel">
                            <?php if (!empty($mapel_diajar)): ?>
                                <div class="row">
                                    <?php foreach ($mapel_diajar as $mapel): ?>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="p-3 rounded border bg-light d-flex align-items-center">
                                                <div class="bg-white p-2 rounded shadow-sm mr-3 text-warning">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-dark"><?= esc($mapel['nama_mapel']) ?></div>
                                                    <small class="text-muted"><?= esc($mapel['kode_mapel']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mb-2"></i>
                                    <p>Belum ada data mata pelajaran.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4 border-0 bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold mb-3">Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('guru/jurnal/create') ?>" class="btn btn-light btn-block text-primary font-weight-bold mb-2 shadow-sm">
                            <i class="fas fa-plus-circle mr-2"></i> Buat Jurnal Baru
                        </a>
                        <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-outline-light btn-block font-weight-bold">
                            <i class="fas fa-list-alt mr-2"></i> Daftar Jurnal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Calendar Widget -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('guru/dashboard?month=' . $prev_month . '&year=' . $prev_year) ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <h6 class="m-0 font-weight-bold text-gray-800">
                        <i class="far fa-calendar-alt mr-2 text-primary"></i>
                        <?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?>
                    </h6>
                    <a href="<?= base_url('guru/dashboard?month=' . $next_month . '&year=' . $next_year) ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    <?php
                    helper('tanggal');
                    $weeks = get_dates_by_week($current_month, $current_year);
                    $today = date('Y-m-d');
                    ?>
                    
                    <div class="calendar-widget">
                        <div class="calendar-header mb-2">
                            <div class="row text-center text-muted small font-weight-bold">
                                <div class="col">Sen</div>
                                <div class="col">Sel</div>
                                <div class="col">Rab</div>
                                <div class="col">Kam</div>
                                <div class="col">Jum</div>
                                <div class="col">Sab</div>
                                <div class="col">Min</div>
                            </div>
                        </div>
                        <div class="calendar-body">
                            <?php foreach ($weeks as $weekNumber => $daysInWeek): ?>
                                <div class="row no-gutters mb-1">
                                    <?php 
                                    // Fill empty days at start of first week
                                    if ($weekNumber == 1) {
                                        $firstDay = reset($daysInWeek);
                                        $dayIndex = date('N', strtotime($firstDay['date_sql']));
                                        for ($i = 1; $i < $dayIndex; $i++) {
                                            echo '<div class="col"></div>';
                                        }
                                    }
                                    ?>
                                    
                                    <?php foreach ($daysInWeek as $day): ?>
                                        <?php 
                                        $dateSql = $day['date_sql'];
                                        $hasJournal = isset($jurnal_by_date[$dateSql]);
                                        $isToday = ($dateSql === $today);
                                        $isHoliday = isset($holidays[$dateSql]);
                                        $holidayName = $isHoliday ? $holidays[$dateSql] : '';
                                        $journalCount = $hasJournal ? $jurnal_by_date[$dateSql] : 0;
                                        
                                        $dayClass = 'calendar-date';
                                        if ($isToday) $dayClass .= ' today';
                                        if ($hasJournal) $dayClass .= ' has-event';
                                        if ($isHoliday) $dayClass .= ' is-holiday';
                                        
                                        $title = "";
                                        if ($isHoliday) $title .= $holidayName;
                                        if ($hasJournal) $title .= ($title ? " | " : "") . $journalCount . " Jurnal";
                                        ?>
                                        <div class="col">
                                            <div class="<?= $dayClass ?>" title="<?= esc($title) ?>" data-toggle="tooltip">
                                                <span class="date-number"><?= $day['day_num'] ?></span>
                                                <?php if ($hasJournal): ?>
                                                    <span class="event-dot"></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <?php 
                                    // Fill empty days at end of last week
                                    $lastDay = end($daysInWeek);
                                    $lastDayIndex = date('N', strtotime($lastDay['date_sql']));
                                    for ($i = $lastDayIndex; $i < 7; $i++) {
                                        echo '<div class="col"></div>';
                                    }
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top small">
                        <div class="d-flex align-items-center mb-1">
                            <span class="d-inline-block rounded-circle bg-primary mr-2" style="width: 10px; height: 10px;"></span>
                            <span class="text-muted">Ada Jurnal</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="d-inline-block rounded-circle bg-success mr-2" style="width: 10px; height: 10px;"></span>
                            <span class="text-muted">Hari Ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles */
    .icon-circle {
        height: 3rem;
        width: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-primary-light { background-color: rgba(78, 115, 223, 0.1); }
    .bg-success-light { background-color: rgba(28, 200, 138, 0.1); }
    .bg-info-light { background-color: rgba(54, 185, 204, 0.1); }
    .bg-warning-light { background-color: rgba(246, 194, 62, 0.1); }
    
    .card-hover {
        transition: transform 0.2s ease-in-out;
    }
    .card-hover:hover {
        transform: translateY(-3px);
    }
    
    .custom-pills .nav-link {
        border-radius: 20px;
        padding: 0.5rem 1.5rem;
        color: #858796;
        font-weight: 600;
    }
    .custom-pills .nav-link.active {
        background-color: #4e73df;
        color: white;
        box-shadow: 0 2px 4px rgba(78, 115, 223, 0.25);
    }
    
    /* Calendar Widget Styles */
    .calendar-date {
        height: 40px;
        width: 40px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: default;
        position: relative;
        transition: all 0.2s;
    }
    .calendar-date:hover {
        background-color: #f8f9fc;
    }
    .calendar-date.today {
        background-color: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
        font-weight: bold;
    }
    .calendar-date.has-event {
        font-weight: bold;
        color: #4e73df;
    }
    .calendar-date.has-event.today {
        color: #1cc88a;
    }
    .event-dot {
        width: 5px;
        height: 5px;
        background-color: #4e73df;
        border-radius: 50%;
        position: absolute;
        bottom: 5px;
    }
    .calendar-date.today .event-dot {
        background-color: #1cc88a;
    }
    .calendar-date.is-holiday {
        color: #e74a3b; /* Red color for holidays */
        font-weight: bold;
    }
    .calendar-date.is-holiday .date-number {
        border-bottom: 2px dotted #e74a3b;
    }
    .date-number {
        line-height: 1;
    }
</style>
<?= $this->endSection() ?>
