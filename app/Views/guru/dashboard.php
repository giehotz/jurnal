<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 pb-5">
    <!-- Header Section -->
    <div class="row align-items-center mb-4 mt-4">
        <div class="col-12 col-md-8">
            <h1 class="h3 text-gray-800 font-weight-bold mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Selamat datang kembali, <span class="text-primary font-weight-bold"><?= esc($userName) ?></span>!</p>
        </div>
        <div class="col-12 col-md-4 text-md-right mt-3 mt-md-0">
            <div class="d-inline-flex align-items-center bg-white px-3 py-2 rounded-lg shadow-sm border border-light">
                <i class="far fa-calendar-alt text-primary mr-2"></i>
                <span class="font-weight-bold text-dark small"><?= date('d F Y') ?></span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <!-- Card 1: Jurnal Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card bg-primary text-white border-0 shadow-sm h-100 py-2 rounded-lg card-hover overflow-hidden position-relative stats-card">
                <div class="card-body position-relative z-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1 tracking-wide">Jurnal Bulan Ini</div>
                            <div class="h2 mb-0 font-weight-bold"><?= $stats['jurnal_bulan_ini'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-white text-primary rounded-circle shadow-sm">
                                <i class="fas fa-calendar-check text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-bg-icon text-white opacity-20">
                    <i class="fas fa-calendar-check fa-5x"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Jurnal Minggu Ini -->
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card bg-success text-white border-0 shadow-sm h-100 py-2 rounded-lg card-hover overflow-hidden position-relative stats-card">
                <div class="card-body position-relative z-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1 tracking-wide">Jurnal Minggu Ini</div>
                            <div class="h2 mb-0 font-weight-bold"><?= $stats['jurnal_minggu_ini'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-white text-success rounded-circle shadow-sm">
                                <i class="fas fa-calendar-week text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-bg-icon text-white opacity-20">
                    <i class="fas fa-calendar-week fa-5x"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Kelas -->
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card bg-info text-white border-0 shadow-sm h-100 py-2 rounded-lg card-hover overflow-hidden position-relative stats-card">
                <div class="card-body position-relative z-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1 tracking-wide">Total Kelas</div>
                            <div class="h2 mb-0 font-weight-bold"><?= $total_kelas ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-white text-info rounded-circle shadow-sm">
                                <i class="fas fa-users text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-bg-icon text-white opacity-20">
                    <i class="fas fa-users fa-5x"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Mapel -->
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card bg-warning text-white border-0 shadow-sm h-100 py-2 rounded-lg card-hover overflow-hidden position-relative stats-card">
                <div class="card-body position-relative z-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1 tracking-wide">Total Mapel</div>
                            <div class="h2 mb-0 font-weight-bold"><?= $total_mapel ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-shape bg-white text-warning rounded-circle shadow-sm">
                                <i class="fas fa-book text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-bg-icon text-white opacity-20">
                    <i class="fas fa-book fa-5x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">

            <!-- Quick Actions (Mobile/Tablet Friendly) -->
            <div class="d-block d-lg-none mb-4">
                <div class="card border-0 shadow-sm rounded-lg bg-white">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-6">
                                <a href="<?= base_url('guru/jurnal/create') ?>" class="btn btn-primary btn-block py-3 rounded-lg shadow-sm">
                                    <i class="fas fa-plus-circle mb-2 d-block fa-lg"></i> Buat Jurnal
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-light btn-block py-3 rounded-lg border">
                                    <i class="fas fa-list-alt mb-2 d-block fa-lg text-gray-600"></i> Lihat Jurnal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teaching Activity Chart -->
            <div class="card shadow-sm mb-4 border-0 rounded-lg">
                <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between rounded-top-lg border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark">Aktivitas Mengajar</h6>
                    <div class="dropdown no-arrow">
                        <!-- Optional: Add filters or options here if needed -->
                    </div>
                </div>
                <div class="card-body">
                    <?= $this->include('guru/components/teaching_activity_chart') ?>
                </div>
            </div>

            <!-- Classes & Subjects -->
            <div class="card shadow-sm border-0 rounded-lg mb-4 mb-lg-0">
                <div class="card-header py-2 bg-transparent border-bottom-0">
                    <ul class="nav nav-tabs border-0" id="teachingTabs" role="tablist">
                        <li class="nav-item mr-2">
                            <a class="nav-link active border-0 py-3 font-weight-bold" id="kelas-tab" data-toggle="tab" href="#kelas-tab-pane" role="tab">
                                <i class="fas fa-chalkboard mr-2 opacity-50"></i>Daftar Kelas <span class="badge badge-primary-soft ml-2"><?= count($kelas_diajar) ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border-0 py-3 font-weight-bold" id="mapel-tab" data-toggle="tab" href="#mapel-tab-pane" role="tab">
                                <i class="fas fa-book-open mr-2 opacity-50"></i>Mata Pelajaran <span class="badge badge-warning-soft ml-2"><?= count($mapel_diajar) ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body pt-2">
                    <div class="tab-content" id="teachingTabsContent">
                        <!-- Kelas Tab -->
                        <div class="tab-pane fade show active" id="kelas-tab-pane" role="tabpanel">
                            <?php if (!empty($kelas_diajar)): ?>
                                <div class="row">
                                    <?php foreach ($kelas_diajar as $kelas): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="p-3 rounded-lg border bg-light d-flex align-items-center transition-hover position-relative">
                                                <div class="icon-circle-sm bg-white text-primary shadow-sm mr-3 flex-shrink-0">
                                                    <i class="fas fa-chalkboard"></i>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h6 class="font-weight-bold text-dark mb-1 text-truncate"><?= esc($kelas['nama_rombel']) ?></h6>
                                                    <div class="text-xs text-uppercase tracking-wide text-muted font-weight-bold"><?= esc($kelas['kode_rombel']) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-3 text-gray-300">
                                        <i class="fas fa-chalkboard fa-3x"></i>
                                    </div>
                                    <h6 class="text-gray-500 font-weight-bold">Belum ada kelas</h6>
                                    <p class="text-muted small">Anda belum ditugaskan di kelas manapun.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Mapel Tab -->
                        <div class="tab-pane fade" id="mapel-tab-pane" role="tabpanel">
                            <?php if (!empty($mapel_diajar)): ?>
                                <div class="row">
                                    <?php foreach ($mapel_diajar as $mapel): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="p-3 rounded-lg border bg-light d-flex align-items-center transition-hover">
                                                <div class="icon-circle-sm bg-white text-warning shadow-sm mr-3 flex-shrink-0">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h6 class="font-weight-bold text-dark mb-1 text-truncate"><?= esc($mapel['nama_mapel']) ?></h6>
                                                    <div class="text-xs text-uppercase tracking-wide text-muted font-weight-bold"><?= esc($mapel['kode_mapel']) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-3 text-gray-300">
                                        <i class="fas fa-book-open fa-3x"></i>
                                    </div>
                                    <h6 class="text-gray-500 font-weight-bold">Belum ada mapel</h6>
                                    <p class="text-muted small">Belum ada mata pelajaran yang diampu.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Quick Actions (Desktop) -->
            <div class="d-none d-lg-block mb-4">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <a href="<?= base_url('guru/jurnal/create') ?>" class="col-6 p-4 text-center bg-primary text-white text-decoration-none hover-darken transition-fast">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <div class="font-weight-bold small">Buat Jurnal</div>
                            </a>
                            <a href="<?= base_url('guru/jurnal') ?>" class="col-6 p-4 text-center bg-light text-primary text-decoration-none hover-bg-gray transition-fast">
                                <i class="fas fa-list-alt fa-2x mb-2"></i>
                                <div class="font-weight-bold small">Lihat Data</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Widget -->
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center rounded-top-lg">
                    <a href="<?= base_url('guru/dashboard?month=' . $prev_month . '&year=' . $prev_year) ?>" class="btn btn-sm btn-link text-gray-500 hover-primary">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <h6 class="m-0 font-weight-bold text-dark">
                        <?= date('F Y', mktime(0, 0, 0, $current_month, 1, $current_year)) ?>
                    </h6>
                    <a href="<?= base_url('guru/dashboard?month=' . $next_month . '&year=' . $next_year) ?>" class="btn btn-sm btn-link text-gray-500 hover-primary">
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
                        <div class="calendar-header mb-3">
                            <div class="row text-center text-secondary small font-weight-bold">
                                <div class="col">Sen</div>
                                <div class="col">Sel</div>
                                <div class="col">Rab</div>
                                <div class="col">Kam</div>
                                <div class="col">Jum</div>
                                <div class="col">Sab</div>
                                <div class="col text-danger">Min</div>
                            </div>
                        </div>
                        <div class="calendar-body">
                            <?php foreach ($weeks as $weekNumber => $daysInWeek): ?>
                                <div class="row no-gutters mb-2">
                                    <?php
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

                                        $dayClass = 'calendar-date rounded-circle';
                                        if ($isToday) $dayClass .= ' today shadow-sm';
                                        if ($hasJournal) $dayClass .= ' has-event';
                                        if ($isHoliday) $dayClass .= ' is-holiday';

                                        $title = "";
                                        if ($isHoliday) $title .= $holidayName;
                                        if ($hasJournal) $title .= ($title ? " | " : "") . $journalCount . " Jurnal";
                                        ?>
                                        <div class="col">
                                            <div class="<?= $dayClass ?>" title="<?= esc($title) ?>" data-toggle="tooltip">
                                                <span class="date-number small"><?= $day['day_num'] ?></span>
                                                <?php if ($hasJournal): ?>
                                                    <span class="event-dot"></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php
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

                    <div class="mt-4 border-top pt-3 d-flex justify-content-center small text-muted">
                        <div class="d-flex align-items-center mr-3">
                            <span class="d-inline-block rounded-circle bg-primary mr-1" style="width: 8px; height: 8px;"></span>
                            <span>Ada Jurnal</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="d-inline-block rounded-circle bg-success mr-1" style="width: 8px; height: 8px;"></span>
                            <span>Hari Ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Global Cleanups */
    .rounded-lg {
        border-radius: 0.75rem !important;
    }

    .rounded-top-lg {
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }

    .tracking-wide {
        letter-spacing: 0.05em;
    }

    .opacity-10 {
        opacity: 0.1;
    }

    .opacity-20 {
        opacity: 0.2;
    }

    .opacity-50 {
        opacity: 0.5;
    }

    /* Card Styles */
    .card-hover {
        transition: transform 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .card-hover:hover {
        transform: translateY(-4px);
    }

    .card-bg-icon {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
        padding-right: 1.5rem;
    }

    .icon-shape {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-circle-sm {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Nav Tabs Custom */
    .nav-tabs .nav-link {
        color: #858796;
        border: none;
        position: relative;
    }

    .nav-tabs .nav-link:hover {
        color: #4e73df;
    }

    .nav-tabs .nav-link.active {
        color: #4e73df;
        background: transparent;
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #4e73df;
        border-radius: 3px 3px 0 0;
    }

    /* Badges */
    .badge-primary-soft {
        background-color: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }

    .badge-warning-soft {
        background-color: rgba(246, 194, 62, 0.1);
        color: #f6c23e;
    }

    /* Calendar Refinements */
    .calendar-date {
        height: 36px;
        width: 36px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
    }

    .calendar-date:hover {
        background-color: #f1f3f9;
    }

    .calendar-date.today {
        background-color: rgba(28, 200, 138, 0.15);
        color: #1cc88a;
        font-weight: 700;
    }

    .calendar-date.has-event {
        color: #4e73df;
        font-weight: 700;
    }

    .calendar-date.has-event:after {
        content: '';
        width: 4px;
        height: 4px;
        background-color: #4e73df;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
    }

    .calendar-date.today:after {
        background-color: #1cc88a;
    }

    .calendar-date.is-holiday {
        color: #e74a3b;
    }

    .calendar-date.is-holiday .date-number {
        border-bottom: 2px dotted #e74a3b;
    }

    /* Utility Hover Effects */
    .hover-darken:hover {
        filter: brightness(90%);
    }

    .hover-bg-gray:hover {
        background-color: #f8f9fc !important;
    }

    .transition-fast {
        transition: all 0.2s ease;
    }

    .transition-hover:hover {
        background-color: #fff !important;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        border-color: #e3e6f0 !important;
    }
</style>
<?= $this->endSection() ?>