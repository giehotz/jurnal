<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kalender Mengajar' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }

        .day-active {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>

<body class="pb-20">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-6 pt-8 pb-10 rounded-b-[2rem] shadow-lg relative z-10">
        <div class="flex justify-between items-center text-white mb-4">
            <h1 class="text-xl font-bold">Kalender Mengajar</h1>
            <!-- Back Button or PDF Export -->
            <a href="<?= base_url('guru/kalender-mengajar/pdf?tahun_ajaran=' . urlencode($tahun_ajaran)) . '&semester=' . $semester ?>"
                target="_blank"
                class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-medium flex items-center gap-2 transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>

        <!-- Filters (Month & Year) -->
        <form action="" method="get" class="bg-white/10 backdrop-blur-md p-3 rounded-xl flex flex-wrap gap-2">
            <input type="hidden" name="tahun_ajaran" value="<?= $tahun_ajaran ?>">

            <select name="semester" onchange="this.form.submit()" class="basis-1/3 grow bg-white/90 text-gray-800 text-sm rounded-lg px-3 py-2 focus:outline-none">
                <option value="1" <?= $semester == 1 ? 'selected' : '' ?>>Ganjil</option>
                <option value="2" <?= $semester == 2 ? 'selected' : '' ?>>Genap</option>
            </select>

            <select name="bulan" onchange="this.form.submit()" class="flex-1 bg-white/90 text-gray-800 text-sm rounded-lg px-3 py-2 focus:outline-none">
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
                foreach ($months as $m => $name): ?>
                    <option value="<?= $m ?>" <?= $m == $bulan ? 'selected' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>

            <select name="tahun" onchange="this.form.submit()" class="w-24 bg-white/90 text-gray-800 text-sm rounded-lg px-3 py-2 focus:outline-none">
                <?php
                $currYear = date('Y');
                for ($y = $currYear - 1; $y <= $currYear + 1; $y++): ?>
                    <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <!-- Main Content -->
    <div class="px-5 -mt-6 relative z-20">

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-white p-3 rounded-xl card-shadow flex flex-col items-center">
                <span class="text-xs text-gray-500 font-medium">Hari Efektif</span>
                <span class="text-lg font-bold text-blue-600"><?= $statistik['hari_efektif'] ?></span>
            </div>
            <div class="bg-white p-3 rounded-xl card-shadow flex flex-col items-center">
                <span class="text-xs text-gray-500 font-medium">Libur Nasional</span>
                <span class="text-lg font-bold text-red-500"><?= $statistik['libur_nasional'] ?></span>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="bg-white rounded-2xl p-4 card-shadow mb-6">
            <!-- Headers -->
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-[10px] font-semibold text-gray-500">Sen</div>
                <div class="text-center text-[10px] font-semibold text-gray-500">Sel</div>
                <div class="text-center text-[10px] font-semibold text-gray-500">Rab</div>
                <div class="text-center text-[10px] font-semibold text-gray-500">Kam</div>
                <div class="text-center text-[10px] font-semibold text-gray-500">Jum</div>
                <div class="text-center text-[10px] font-semibold text-gray-500">Sab</div>
                <div class="text-center text-[10px] font-semibold text-red-500">Min</div>
            </div>

            <!-- Days -->
            <?php foreach ($weeks as $weekNumber => $daysInWeek): ?>
                <div class="grid grid-cols-7 gap-1 mb-1">
                    <?php
                    if ($weekNumber == 1) {
                        $firstDay = reset($daysInWeek);
                        $dayIndex = date('N', strtotime($firstDay['date_sql']));
                        for ($i = 1; $i < $dayIndex; $i++) echo '<div></div>';
                    }
                    ?>

                    <?php foreach ($daysInWeek as $day): ?>
                        <?php
                        $dateSql = $day['date_sql'];
                        $events = $events_by_date[$dateSql] ?? [];
                        // Logic Warna
                        $bgClass = '';
                        $textClass = 'text-gray-700';
                        $hasEvent = !empty($events);
                        $eventColor = $hasEvent ? ($events[0]['warna_kode'] ?? '#ccc') : '';
                        $isSunday = (date('N', strtotime($dateSql)) == 7);
                        if ($isSunday) $textClass = 'text-red-500';

                        // Custom style for event
                        $style = "";
                        if ($hasEvent && $eventColor) {
                            $style = "background-color: {$eventColor}; color: #fff;";
                            $textClass = ""; // Override
                        }
                        ?>
                        <div class="flex justify-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-medium <?= $textClass ?>" style="<?= $style ?>">
                                <?= $day['day_num'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php
                    $lastDay = end($daysInWeek);
                    $lastDayIndex = date('N', strtotime($lastDay['date_sql']));
                    for ($i = $lastDayIndex; $i < 7; $i++) echo '<div></div>';
                    ?>
                </div>
            <?php endforeach; ?>

            <!-- Legend -->
            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-3 text-[10px]">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-red-500"></span> Libur</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-green-500"></span> Event</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-yellow-400"></span> Ujian</div>
            </div>
        </div>

        <!-- Detailed Events List -->
        <h3 class="font-bold text-gray-800 text-sm mb-3">Agenda Bulan Ini</h3>
        <div class="space-y-3 pb-6">
            <?php
            $hasAgenda = false;
            foreach ($events_by_date as $date => $events):
                foreach ($events as $evt):
                    $hasAgenda = true;
                    $dayNum = date('d', strtotime($date));
                    $dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w', strtotime($date))];
            ?>
                    <div class="bg-white p-3 rounded-xl card-shadow flex gap-3 items-start border-l-4" style="border-color: <?= $evt['warna_kode'] ?? '#ccc' ?>">
                        <div class="text-center px-1">
                            <span class="block text-xl font-bold text-gray-800"><?= $dayNum ?></span>
                            <span class="block text-[10px] text-gray-500 uppercase"><?= substr($dayName, 0, 3) ?></span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-800"><?= esc($evt['keterangan']) ?></h4>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 mt-1 inline-block">
                                <?= ucfirst(str_replace('_', ' ', $evt['jenis_hari'])) ?>
                            </span>
                        </div>
                    </div>
                <?php
                endforeach;
            endforeach;

            if (!$hasAgenda):
                ?>
                <div class="text-center py-8 text-gray-400 text-xs">
                    <i class="far fa-calendar-times text-2xl mb-2"></i>
                    <p>Tidak ada agenda bulan ini</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?= $this->include('mobile/shared/partials/bottom_nav_guru') ?>

</body>

</html>