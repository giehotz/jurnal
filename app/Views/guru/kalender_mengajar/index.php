<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h4 class="mb-1 font-weight-bold text-primary">Kalender Mengajar</h4>
                        <p class="text-muted mb-0">
                            Tahun Ajaran: <span class="font-weight-bold text-dark"><?= esc($tahun_ajaran) ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="get" class="form-inline justify-content-md-end">
                            <input type="hidden" name="tahun" value="<?= $tahun ?>">
                            <input type="hidden" name="tahun_ajaran" value="<?= $tahun_ajaran ?>">

                            <!-- Semester Select -->
                            <div class="input-group mr-sm-2 mb-2 mb-sm-0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-graduation-cap text-primary"></i></span>
                                </div>
                                <select name="semester" class="custom-select border-0 bg-light text-primary font-weight-bold" onchange="this.form.submit()" style="box-shadow: none;">
                                    <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Semester Ganjil</option>
                                    <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Semester Genap</option>
                                </select>
                            </div>

                            <!-- Month Select -->
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0"><i class="far fa-calendar-alt text-primary"></i></span>
                                </div>
                                <select name="bulan" class="custom-select border-0 bg-light font-weight-bold" onchange="this.form.submit()" style="box-shadow: none;">
                                    <?php
                                    $months = [
                                        1 => 'Januari',
                                        2 => 'Februari',
                                        3 => 'Maret',
                                        4 => 'April',
                                        5 => 'Mei',
                                        6 => 'Juni',
                                        7 => 'Juli',
                                        8 => 'Agustus',
                                        9 => 'September',
                                        10 => 'Oktober',
                                        11 => 'November',
                                        12 => 'Desember'
                                    ];
                                    foreach ($months as $k => $v): ?>
                                        <option value="<?= $k ?>" <?= $bulan == $k ? 'selected' : '' ?>><?= $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Widgets -->
<div class="row mb-4">
    <div class="col-md-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $statistik['hari_efektif'] ?></h3>
                <p>Hari Efektif</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $statistik['ujian'] ?></h3>
                <p>Hari Ujian</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $statistik['libur_nasional'] ?></h3>
                <p>Libur Nasional</p>
            </div>
            <div class="icon">
                <i class="fas fa-flag"></i>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $statistik['event'] ?></h3>
                <p>Kegiatan</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-star"></i>
            </div>
        </div>
    </div>
    <!-- Add more stats if needed -->
</div>

<!-- Calendar & Agenda Section -->
<div class="row">
    <!-- Calendar Widget -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
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
                <a href="?bulan=<?= $prevMonth ?>&tahun=<?= $prevYear ?>&semester=<?= $semester ?>&tahun_ajaran=<?= urlencode($tahun_ajaran) ?>" class="btn btn-sm btn-light">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="far fa-calendar-alt mr-2"></i>Kalender <?= $months[(int)$bulan] ?> <?= $tahun ?>
                </h6>
                <a href="?bulan=<?= $nextMonth ?>&tahun=<?= $nextYear ?>&semester=<?= $semester ?>&tahun_ajaran=<?= urlencode($tahun_ajaran) ?>" class="btn btn-sm btn-light">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="card-body p-3">
                <!-- Styling for Calendar -->
                <style>
                    .calendar-widget .calendar-header .col {
                        padding: 10px;
                        border-bottom: 2px solid #f0f0f0;
                        background: #fafafa;
                    }

                    .calendar-body .col {
                        border: 1px solid #f8f9fa;
                        min-height: 100px;
                        padding: 0;
                        position: relative;
                    }

                    .calendar-date {
                        height: 100%;
                        padding: 8px;
                        cursor: pointer;
                        transition: 0.2s;
                    }

                    .calendar-date:hover {
                        background: #f8f9fa;
                    }

                    .calendar-date.today {
                        background: #e3f2fd;
                    }

                    .calendar-date.is-holiday {
                        background: #ffebee;
                    }

                    .date-number {
                        font-weight: bold;
                        font-size: 1.1rem;
                    }

                    .event-marker {
                        height: 7px;
                        display: block;
                        margin-top: 3px;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .event-marker.libur_nasional {
                        background-color: #dc3545;
                    }

                    .event-marker.hari_efektif {
                        background-color: #28a745;
                    }

                    .event-marker.libur_sekolah {
                        background-color: #fd7e14;
                    }

                    .event-marker.ujian {
                        background-color: #ffc107;
                        color: #000;
                    }

                    .event-marker.event {
                        background-color: #17a2b8;
                    }

                    .event-marker.rapat {
                        background-color: #6f42c1;
                    }
                </style>

                <div class="calendar-widget">
                    <div class="calendar-header mb-2">
                        <div class="row text-center text-muted small font-weight-bold no-gutters">
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
                            <div class="row no-gutters">
                                <?php
                                // Fill empty days at start
                                if ($weekNumber == 1) {
                                    $firstDay = reset($daysInWeek);
                                    $dayIndex = date('N', strtotime($firstDay['date_sql']));
                                    for ($i = 1; $i < $dayIndex; $i++) echo '<div class="col"></div>';
                                }
                                ?>

                                <?php foreach ($daysInWeek as $day): ?>
                                    <?php
                                    $dateSql = $day['date_sql'];
                                    $events = $events_by_date[$dateSql] ?? [];
                                    $isToday = ($dateSql === date('Y-m-d'));

                                    // Check if any event is a holiday
                                    $isHolidayEvent = false;
                                    foreach ($events as $evt) {
                                        if (in_array($evt['jenis_hari'], ['libur_nasional', 'libur_sekolah'])) {
                                            $isHolidayEvent = true;
                                            break;
                                        }
                                    }

                                    // Make IsHoliday true if Sunday OR High Priority Event
                                    $isSunday = (date('N', strtotime($dateSql)) == 7);
                                    $isHoliday = $isSunday || $isHolidayEvent;

                                    $dayClass = 'calendar-date';
                                    if ($isToday) $dayClass .= ' today';
                                    if ($isHoliday) $dayClass .= ' is-holiday';
                                    ?>
                                    <div class="col">
                                        <div class="<?= $dayClass ?>">
                                            <span class="date-number <?= $isHoliday ? 'text-danger' : '' ?>"><?= $day['day_num'] ?></span>

                                            <!-- Events List in Cell -->
                                            <?php foreach ($events as $evt): ?>
                                                <div class="event-marker" style="background-color: <?= $evt['warna_kode'] ?>;" title="<?= esc($evt['keterangan']) ?>" data-toggle="tooltip"></div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <?php
                                // Fill empty days at end
                                $lastDay = end($daysInWeek);
                                $lastDayIndex = date('N', strtotime($lastDay['date_sql']));
                                for ($i = $lastDayIndex; $i < 7; $i++) echo '<div class="col"></div>';
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agenda List (Side) -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-gray-800">Daftar Agenda</h6>
            </div>
            <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                <?php if (empty($kalender)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p>Tidak ada agenda bulan ini</p>
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($kalender as $row): ?>
                            <li class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <?= date('d', strtotime($row['tanggal'])) ?> <?= $months[(int)date('m', strtotime($row['tanggal']))] ?>
                                    </h6>
                                    <span class="badge badge-pill 
                                        <?= ($row['jenis_hari'] == 'libur_nasional' || $row['jenis_hari'] == 'libur_sekolah') ? 'badge-danger' : (($row['jenis_hari'] == 'hari_efektif') ? 'badge-success' : 'badge-info') ?>">
                                        <?= ucfirst(str_replace('_', ' ', $row['jenis_hari'])) ?>
                                    </span>
                                </div>
                                <p class="mb-1 small"><?= esc($row['keterangan']) ?></p>
                                <small class="text-muted"><?= date('l', strtotime($row['tanggal'])) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>