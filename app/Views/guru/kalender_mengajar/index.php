<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --accent-color: #667eea;
        --secondary-color: #764ba2;
        --soft-bg: #f8f9fc;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        --hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: var(--soft-bg);
    }

    .modern-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .modern-card:hover {
        box-shadow: var(--hover-shadow);
    }

    .page-header-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        border-left: 5px solid var(--accent-color);
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #718096;
        font-size: 0.95rem;
    }

    /* Stats Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0, 0, 0, 0.02);
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }

    .stat-icon.efektif {
        background: linear-gradient(135deg, #00b09b, #96c93d);
        box-shadow: 0 5px 15px rgba(0, 176, 155, 0.3);
    }

    .stat-icon.ujian {
        background: linear-gradient(135deg, #f6d365, #fda085);
        box-shadow: 0 5px 15px rgba(246, 211, 101, 0.3);
    }

    .stat-icon.libur {
        background: linear-gradient(135deg, #ff5f6d, #ffc371);
        box-shadow: 0 5px 15px rgba(255, 95, 109, 0.3);
    }

    .stat-icon.kegiatan {
        background: linear-gradient(135deg, #667eea, #764ba2);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .stat-info h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        color: #2d3748;
    }

    .stat-info p {
        margin: 0;
        font-size: 0.85rem;
        color: #a0aec0;
        font-weight: 500;
    }

    /* Filters */
    .custom-select-modern {
        background-color: #edf2f7;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 10px 15px;
        font-weight: 600;
        color: #4a5568;
        cursor: pointer;
        transition: all 0.3s;
    }

    .custom-select-modern:focus {
        background-color: white;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    /* Calendar Styling */
    .calendar-container {
        padding: 25px;
    }

    .calendar-nav-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #edf2f7;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5568;
        transition: all 0.2s;
    }

    .calendar-nav-btn:hover {
        background: var(--accent-color);
        color: white;
    }

    .calendar-month-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #2d3748;
        margin: 0 15px;
        text-transform: capitalize;
    }

    .weekday-header {
        font-weight: 700;
        color: #a0aec0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding-bottom: 15px;
    }

    .calendar-day-cell {
        min-height: 110px;
        padding: 10px;
        border-radius: 15px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .calendar-day-cell:hover {
        background: #f7fafc;
        border-color: #e2e8f0;
    }

    .day-number {
        font-weight: 700;
        color: #4a5568;
        display: inline-block;
        width: 28px;
        height: 28px;
        text-align: center;
        line-height: 28px;
        border-radius: 50%;
        margin-bottom: 5px;
    }

    .is-today .day-number {
        background: var(--accent-color);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
    }

    .text-danger {
        color: #fc8181 !important;
    }

    .event-chip {
        display: block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        color: white;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Agenda Timeline */
    .timeline {
        padding: 25px;
        position: relative;
        max-height: 500px;
        overflow-y: auto;
    }

    /* Custom Scrollbar */
    .timeline::-webkit-scrollbar {
        width: 6px;
    }

    .timeline::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .timeline::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }

    .timeline::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 44px;
        height: calc(100% - 60px);
        width: 2px;
        background: #edf2f7;
    }

    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 25px;
    }

    .timeline-date-badge {
        position: absolute;
        left: -10px;
        top: 0;
        width: 45px;
        height: 45px;
        background: white;
        border: 2px solid var(--accent-color);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .timeline-day {
        font-size: 0.9rem;
        font-weight: 800;
        color: #2d3748;
        line-height: 1;
    }

    .timeline-month {
        font-size: 0.6rem;
        color: #718096;
        text-transform: uppercase;
        font-weight: 700;
    }

    .timeline-content {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }

    .timeline-content:hover {
        background: white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transform: translateX(5px);
    }

    .timeline-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
        font-size: 0.95rem;
    }

    .timeline-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
        margin-top: 5px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .page-header-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-card {
            margin-bottom: 15px;
        }

        .calendar-day-cell {
            min-height: 80px;
        }

        .weekday-header {
            font-size: 0.7rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">

    <!-- Page Header -->
    <div class="page-header-card mb-4">
        <div>
            <h1 class="page-title">Kalender Mengajar</h1>
            <p class="page-subtitle">
                <i class="fas fa-school mr-2"></i>Tahun Ajaran <?= esc($tahun_ajaran) ?>
            </p>
            <a href="<?= base_url('guru/kalender-mengajar/pdf?tahun_ajaran=' . urlencode($tahun_ajaran)) ?>"
                class="btn btn-danger btn-sm mt-2"
                target="_blank"
                rel="noopener noreferrer">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>

        </div>
        <div>
            <form action="" method="get" class="d-flex align-items-center flex-wrap gap-2">
                <input type="hidden" name="tahun" value="<?= $tahun ?>">
                <input type="hidden" name="tahun_ajaran" value="<?= $tahun_ajaran ?>">

                <div class="d-flex gap-2">
                    <select name="semester" class="custom-select-modern" onchange="this.form.submit()">
                        <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Semester Ganjil</option>
                        <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Semester Genap</option>
                    </select>

                    <select name="bulan" class="custom-select-modern" onchange="this.form.submit()">
                        <?php
                        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                        foreach ($months as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $bulan == $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="stat-icon efektif"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3><?= $statistik['hari_efektif'] ?></h3>
                    <p>Hari Efektif</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="stat-icon ujian"><i class="fas fa-edit"></i></div>
                <div class="stat-info">
                    <h3><?= $statistik['ujian'] ?></h3>
                    <p>Hari Ujian</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="stat-icon libur"><i class="fas fa-umbrella-beach"></i></div>
                <div class="stat-info">
                    <h3><?= $statistik['libur_nasional'] ?></h3>
                    <p>Libur Nasional</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon kegiatan"><i class="fas fa-star"></i></div>
                <div class="stat-info">
                    <h3><?= $statistik['event'] ?></h3>
                    <p>Kegiatan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Calendar Grid -->
        <div class="col-lg-8 mb-4">
            <div class="modern-card h-100">
                <div class="calendar-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <?php
                        $prevMonth = $bulan - 1;
                        $prevYear = $tahun;
                        if ($prevMonth < 1) {
                            $prevMonth = 12;
                            $prevYear--;
                        }
                        $nextMonth = $bulan + 1;
                        $nextYear = $tahun;
                        if ($nextMonth > 12) {
                            $nextMonth = 1;
                            $nextYear++;
                        }
                        ?>
                        <a href="?bulan=<?= $prevMonth ?>&tahun=<?= $prevYear ?>&semester=<?= $semester ?>&tahun_ajaran=<?= urlencode($tahun_ajaran) ?>" class="calendar-nav-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <h2 class="calendar-month-title"><?= $months[(int)$bulan] ?> <?= $tahun ?></h2>
                        <a href="?bulan=<?= $nextMonth ?>&tahun=<?= $nextYear ?>&semester=<?= $semester ?>&tahun_ajaran=<?= urlencode($tahun_ajaran) ?>" class="calendar-nav-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <!-- Weekdays -->
                    <div class="row text-center no-gutters mb-2">
                        <div class="col weekday-header">Sen</div>
                        <div class="col weekday-header">Sel</div>
                        <div class="col weekday-header">Rab</div>
                        <div class="col weekday-header">Kam</div>
                        <div class="col weekday-header">Jum</div>
                        <div class="col weekday-header">Sab</div>
                        <div class="col weekday-header text-danger">Min</div>
                    </div>

                    <!-- Days -->
                    <?php foreach ($weeks as $weekNumber => $daysInWeek): ?>
                        <div class="row no-gutters">
                            <?php if ($weekNumber == 1) {
                                $firstDay = reset($daysInWeek);
                                $dayIndex = date('N', strtotime($firstDay['date_sql']));
                                for ($i = 1; $i < $dayIndex; $i++) echo '<div class="col"></div>';
                            } ?>

                            <?php foreach ($daysInWeek as $day): ?>
                                <?php
                                $dateSql = $day['date_sql'];
                                $events = $events_by_date[$dateSql] ?? [];
                                $isToday = ($dateSql === date('Y-m-d'));
                                $isHolidayEvent = false;
                                foreach ($events as $evt) {
                                    if (in_array($evt['jenis_hari'], ['libur_nasional', 'libur_sekolah'])) {
                                        $isHolidayEvent = true;
                                        break;
                                    }
                                }
                                $isSunday = (date('N', strtotime($dateSql)) == 7);
                                $isHoliday = $isSunday || $isHolidayEvent;
                                ?>
                                <div class="col">
                                    <div class="calendar-day-cell <?= $isToday ? 'is-today' : '' ?>" title="<?= $isToday ? 'Hari Ini' : '' ?>">
                                        <div class="text-center">
                                            <span class="day-number <?= $isHoliday && !$isToday ? 'text-danger' : '' ?>"><?= $day['day_num'] ?></span>
                                        </div>

                                        <div class="mt-1">
                                            <?php foreach ($events as $evt):
                                                $bgData = $evt['warna_kode'] ?? '#6c757d';
                                                // Adjust color logic if needed
                                            ?>
                                                <span class="event-chip" style="background-color: <?= $bgData ?>; opacity: 0.9;">
                                                    <?= esc($evt['keterangan']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php
                            $lastDay = end($daysInWeek);
                            $lastDayIndex = date('N', strtotime($lastDay['date_sql']));
                            for ($i = $lastDayIndex; $i < 7; $i++) echo '<div class="col"></div>';
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Agenda Timeline -->
        <div class="col-lg-4">
            <div class="modern-card h-100">
                <div class="p-4 border-bottom">
                    <h5 class="m-0 font-weight-bold" style="color: #2d3748;">Agenda Bulan Ini</h5>
                </div>
                <div class="timeline">
                    <?php if (empty($kalender)): ?>
                        <div class="text-center py-5 text-muted">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" style="opacity:0.5; margin-bottom:15px;">
                            <p>Tidak ada agenda di bulan ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($kalender as $row): ?>
                            <div class="timeline-item">
                                <div class="timeline-date-badge">
                                    <span class="timeline-day"><?= date('d', strtotime($row['tanggal'])) ?></span>
                                    <span class="timeline-month"><?= date('M', strtotime($row['tanggal'])) ?></span>
                                </div>
                                <div class="timeline-content" style="border-left-color: <?= $row['warna_kode'] ?? '#6c757d' ?>;">
                                    <div class="timeline-title"><?= esc($row['keterangan']) ?></div>
                                    <small class="text-muted d-block mb-1"><i class="far fa-clock mr-1"></i><?= date('l', strtotime($row['tanggal'])) ?></small>
                                    <span class="timeline-badge"
                                        style="background: <?= $row['warna_kode'] ?? '#6c757d' ?>15; color: <?= $row['warna_kode'] ?? '#6c757d' ?>;">
                                        <?= ucfirst(str_replace('_', ' ', $row['jenis_hari'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>