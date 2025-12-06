<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-calendar-alt mr-2"></i>Manajemen Kalender Pendidikan
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/kalender-pendidikan/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Manual
                    </a>
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal">
                        <i class="fas fa-file-excel mr-1"></i> Import Excel
                    </button>
                </div>
            </div>
            <div class="card-body">

                <?php
                $request = service('request');
                $viewParam = $request->getGet('view');
                $activeTab = ($viewParam === 'calendar') ? 'calendar' : 'list';
                ?>

                <!-- Alerts -->
                <?php if (isset($hasUnpublishedChanges) && $hasUnpublishedChanges): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Perubahan Belum Dipublish!</strong> Ada perubahan data kalender master yang belum disinkronisasi ke tampilan guru.
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Publish Status Section -->
                <?php if (!empty($kalender)): ?>
                    <div class="mb-4 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted font-weight-bold">Status Publikasi:</span>
                            <span class="ml-2 badge badge-info">
                                Terakhir: <?= (isset($latestPublish['published_at'])) ? date('d M Y H:i', strtotime($latestPublish['published_at'])) : 'Belum pernah' ?>
                            </span>
                        </div>
                        <form action="<?= base_url('admin/kalender-pendidikan/publish') ?>" method="post" class="m-0">
                            <input type="hidden" name="tahun_ajaran" value="<?= esc($tahun_ajaran) ?>">
                            <input type="hidden" name="semester" value="<?= esc($semester) ?>">
                            <button type="submit" class="btn btn-success font-weight-bold" onclick="return confirm('Apakah Anda yakin ingin mempublish kalender ini ke semua guru? Data di view guru akan di-reset sesuai master.')">
                                <i class="fas fa-check-circle mr-1"></i> Publish Sekarang
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs mb-3" id="kaldikTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'list' ? 'active' : '' ?>" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="<?= $activeTab === 'list' ? 'true' : 'false' ?>">
                            <i class="fas fa-list mr-1"></i> List Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'calendar' ? 'active' : '' ?>" id="calendar-tab" data-toggle="tab" href="#calendar" role="tab" aria-controls="calendar" aria-selected="<?= $activeTab === 'calendar' ? 'true' : 'false' ?>">
                            <i class="far fa-calendar-alt mr-1"></i> Kalender Visual
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="kaldikTabContent">
                    <!-- TAB LIST -->
                    <div class="tab-pane fade <?= $activeTab === 'list' ? 'show active' : '' ?>" id="list" role="tabpanel" aria-labelledby="list-tab">

                        <!-- List Filter -->
                        <form action="" method="get" class="mb-4">
                            <div class="form-row align-items-end">
                                <div class="col-md-3">
                                    <label>Tahun Ajaran</label>
                                    <input type="text" name="tahun_ajaran" class="form-control" value="<?= esc($tahun_ajaran) ?>" placeholder="2024/2025" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label>Semester</label>
                                    <select name="semester" class="form-control">
                                        <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Ganjil (1)</option>
                                        <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Genap (2)</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary btn-block">
                                        <i class="fas fa-filter mr-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Bulk Delete & Table -->
                        <form action="<?= base_url('admin/kalender-pendidikan/delete-bulk') ?>" method="post" id="bulkDeleteForm">
                            <div class="mb-2">
                                <button type="submit" class="btn btn-danger btn-sm" id="btnDeleteBulk" onclick="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')" disabled>
                                    <i class="fas fa-trash mr-1"></i> Hapus Terpilih
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="kaldikTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%" class="text-center">
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th width="5%">No</th>
                                            <th width="20%">Tanggal</th>
                                            <th width="15%">Jenis Hari</th>
                                            <th>Keterangan</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($kalender)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Data kalender belum tersedia untuk periode ini.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php $i = 1;
                                            foreach ($kalender as $row): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="checkItem">
                                                    </td>
                                                    <td class="text-center"><?= $i++ ?></td>
                                                    <td>
                                                        <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                                        <br>
                                                        <small class="text-muted"><?= date('l', strtotime($row['tanggal'])) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge" style="background-color: <?= $row['warna_kode'] ?? '#ccc' ?>; color: #fff;">
                                                            <?= ucfirst(str_replace('_', ' ', $row['jenis_hari'])) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= esc($row['keterangan']) ?></td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('admin/kalender-pendidikan/edit/' . $row['id']) ?>" class="btn btn-warning btn-xs" title="Edit">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        <a href="<?= base_url('admin/kalender-pendidikan/delete/' . $row['id']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <!-- TAB CALENDAR -->
                    <div class="tab-pane fade <?= $activeTab === 'calendar' ? 'show active' : '' ?>" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                        <!-- Calendar Styles -->
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
                        </style>

                        <!-- Month Navigator -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h4 class="mb-1 font-weight-bold text-primary">Kalender Bulan: <?= date('F', mktime(0, 0, 0, $bulan, 10)) ?> <?= $tahun ?></h4>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <form action="" method="get" class="form-inline justify-content-md-end">
                                    <input type="hidden" name="tahun_ajaran" value="<?= $tahun_ajaran ?>">
                                    <input type="hidden" name="semester" value="<?= $semester ?>">
                                    <input type="hidden" name="tahun" value="<?= $tahun ?>">
                                    <input type="hidden" name="view" value="calendar">

                                    <select name="bulan" class="custom-select mr-2" onchange="this.form.submit()">
                                        <?php
                                        $monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                                        foreach ($monthNames as $k => $v): ?>
                                            <option value="<?= $k ?>" <?= $bulan == $k ? 'selected' : '' ?>><?= $v ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <select name="tahun" class="custom-select" onchange="this.form.submit()">
                                        <?php for ($y = date('Y') - 2; $y <= date('Y') + 2; $y++): ?>
                                            <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <!-- Statistics Widgets -->
                        <div class="row mb-4">
                            <?php
                            $statItems = [
                                'mari_efektif' => ['label' => 'Hari Efektif', 'icon' => 'fas fa-check-circle', 'bg' => 'bg-success', 'key' => 'hari_efektif'],
                                'ujian' => ['label' => 'Hari Ujian', 'icon' => 'fas fa-edit', 'bg' => 'bg-warning', 'key' => 'ujian'],
                                'libur_nasional' => ['label' => 'Libur Nasional', 'icon' => 'fas fa-flag', 'bg' => 'bg-danger', 'key' => 'libur_nasional'],
                                'event' => ['label' => 'Kegiatan', 'icon' => 'fas fa-calendar-star', 'bg' => 'bg-info', 'key' => 'event']
                            ];
                            foreach ($statItems as $item):
                            ?>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="small-box <?= $item['bg'] ?>">
                                        <div class="inner">
                                            <h3><?= $statistik[$item['key']] ?? 0 ?></h3>
                                            <p><?= $item['label'] ?></p>
                                        </div>
                                        <div class="icon"><i class="<?= $item['icon'] ?>"></i></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white">
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
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="?bulan=<?= $prevMonth ?>&tahun=<?= $prevYear ?>&semester=<?= $semester ?>&tahun_ajaran=<?= urlencode($tahun_ajaran) ?>&view=calendar" class="btn btn-sm btn-light"><i class="fas fa-chevron-left"></i></a>
                                            <h6 class="m-0 font-weight-bold text-primary">Kalender <?= $monthNames[(int)$bulan] ?> <?= $tahun ?></h6>
                                            <a href="?bulan=<?= $nextMonth ?>&tahun=<?= $nextYear ?>&semester=<?= $semester ?>&tahun_ajaran=<?= urlencode($tahun_ajaran) ?>&view=calendar" class="btn btn-sm btn-light"><i class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="calendar-widget">
                                            <div class="calendar-header">
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
                                                        if ($weekNumber == 1) {
                                                            $firstDay = reset($daysInWeek);
                                                            $dayIndex = date('N', strtotime($firstDay['date_sql']));
                                                            for ($i = 1; $i < $dayIndex; $i++) echo '<div class="col"></div>';
                                                        }
                                                        ?>
                                                        <?php foreach ($daysInWeek as $day):
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

                                                            $dayClass = 'calendar-date';
                                                            if ($isToday) $dayClass .= ' today';
                                                            if ($isHoliday) $dayClass .= ' is-holiday';
                                                        ?>
                                                            <div class="col">
                                                                <div class="<?= $dayClass ?>">
                                                                    <span class="date-number <?= $isHoliday ? 'text-danger' : '' ?>"><?= $day['day_num'] ?></span>
                                                                    <?php foreach ($events as $evt): ?>
                                                                        <div class="event-marker" style="background-color: <?= $evt['warna_kode'] ?? '#ccc' ?>;" title="<?= esc($evt['keterangan']) ?>" data-toggle="tooltip"></div>
                                                                    <?php endforeach; ?>
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
                                </div>
                            </div>

                            <!-- Agenda List Side -->
                            <div class="col-md-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="m-0 font-weight-bold text-gray-800">Agenda Bulan Ini</h6>
                                    </div>
                                    <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                                        <?php if (empty($events_by_date)): ?>
                                            <div class="p-4 text-center text-muted">
                                                <p>Tidak ada agenda.</p>
                                            </div>
                                        <?php else: ?>
                                            <ul class="list-group list-group-flush">
                                                <?php
                                                // Sort dates
                                                ksort($events_by_date);
                                                foreach ($events_by_date as $date => $evts):
                                                    foreach ($evts as $evt):
                                                ?>
                                                        <li class="list-group-item">
                                                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                                <h6 class="mb-0 font-weight-bold text-primary"><?= date('d M', strtotime($date)) ?></h6>
                                                                <span class="badge" style="background-color: <?= $evt['warna_kode'] ?? '#ccc' ?>; color: white;">
                                                                    <?= ucfirst(str_replace('_', ' ', $evt['jenis_hari'])) ?>
                                                                </span>
                                                            </div>
                                                            <p class="mb-1 small"><?= esc($evt['keterangan']) ?></p>
                                                            <small class="text-muted"><?= date('l', strtotime($date)) ?></small>
                                                        </li>
                                                <?php endforeach;
                                                endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Import -->
                <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="importModalLabel">Import Kalender Excel</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="<?= base_url('admin/kalender-pendidikan/import-excel') ?>" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tahun Ajaran</label>
                                        <input type="text" name="tahun_ajaran" class="form-control" value="<?= esc($tahun_ajaran) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select name="semester" class="form-control">
                                            <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Ganjil (1)</option>
                                            <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Genap (2)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>File Excel (.xlsx, .xls)</label>
                                        <a href="<?= base_url('admin/kalender-pendidikan/download-template') ?>" class="btn btn-sm btn-info float-right mb-2">
                                            <i class="fas fa-download mr-1"></i> Download Template
                                        </a>
                                        <input type="file" name="excel_file" class="form-control-file" accept=".xlsx, .xls" required>
                                    </div>
                                    <div class="alert alert-info small">
                                        Pastikan format excel sesuai template: Tanggal (Y-m-d), Jenis Hari, Keterangan.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Upload & Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?= $this->endSection() ?>

                <?= $this->section('scripts') ?>
                <script>
                    // Activate Datatable if needed
                    // $(document).ready(function() {
                    //     $('#kaldikTable').DataTable();
                    // });

                    document.addEventListener('DOMContentLoaded', function() {
                        const checkAll = document.getElementById('checkAll');
                        const checkItems = document.querySelectorAll('.checkItem');
                        const btnDeleteBulk = document.getElementById('btnDeleteBulk');

                        function toggleButton() {
                            let anyChecked = false;
                            checkItems.forEach(item => {
                                if (item.checked) anyChecked = true;
                            });
                            btnDeleteBulk.disabled = !anyChecked;
                        }

                        if (checkAll) {
                            checkAll.addEventListener('change', function() {
                                checkItems.forEach(item => {
                                    item.checked = this.checked;
                                });
                                toggleButton();
                            });
                        }

                        checkItems.forEach(item => {
                            item.addEventListener('change', function() {
                                toggleButton();
                                // Optional: Uncheck "checkAll" if one item is unchecked
                                if (!this.checked) {
                                    checkAll.checked = false;
                                }
                            });
                        });
                    });
                </script>
                <?= $this->endSection() ?>