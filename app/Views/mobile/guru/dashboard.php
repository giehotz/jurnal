<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Jurnal Guru - Mobile Dashboard' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>">

    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }

        .nav-active {
            color: #2563eb;
        }

        .nav-inactive {
            color: #94a3b8;
        }

        /* Gradient backgrounds for icons */
        .bg-icon-blue {
            background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
        }

        .bg-icon-green {
            background: linear-gradient(135deg, #4ade80 0%, #16a34a 100%);
        }

        .bg-icon-red {
            background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
        }

        .bg-icon-yellow {
            background: linear-gradient(135deg, #facc15 0%, #ca8a04 100%);
        }

        .bg-icon-purple {
            background: linear-gradient(135deg, #c084fc 0%, #9333ea 100%);
        }

        .bg-icon-teal {
            background: linear-gradient(135deg, #2dd4bf 0%, #0d9488 100%);
        }

        .bg-icon-orange {
            background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
        }

        .bg-icon-gray {
            background: linear-gradient(135deg, #94a3b8 0%, #475569 100%);
        }
    </style>
</head>
<body>
    <div class="min-h-screen pb-20 relative">
        <!-- Header Section -->
        <div class="relative h-44 w-full">
            <!-- Background Elements (Overflow Hidden) - Z-Index 0 to stay behind Main Content (Z-30) -->
            <div class="absolute inset-0 overflow-hidden rounded-b-[2.5rem] bg-white shadow-sm z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-indigo-800 opacity-100"></div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-black/10 to-transparent"></div>
            </div>

            <!-- User Profile & Greeting (Overflow Visible) - Z-Index 50 to stay ABOVE Main Content (Z-30) -->
            <div class="relative z-50 p-6 pt-8 text-white flex justify-between items-center">
                <div>
                    <?php
                    $hour = date('H');
                    $greeting = 'Selamat Malam';
                    if ($hour >= 4 && $hour < 11) { $greeting = 'Selamat Pagi'; }
                    elseif ($hour >= 11 && $hour < 15) { $greeting = 'Selamat Siang'; }
                    elseif ($hour >= 15 && $hour < 18) { $greeting = 'Selamat Sore'; }
                    ?>
                    <p class="text-blue-100 text-xs font-medium mb-1"><?= $greeting ?>,</p>
                    <h1 class="text-xl font-bold leading-tight"><?= session()->get('nama') ?? 'Guru' ?></h1>
                    <div class="mt-1 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-[10px] opacity-80">Online</span>
                    </div>
                </div>
                
                <!-- Profile Picture -->
                <div class="relative">
                    <button id="profileButton" onclick="toggleProfileMenu()" class="w-12 h-12 rounded-full border-2 border-white/30 overflow-hidden shadow-lg bg-white focus:outline-none transition-transform active:scale-95">
                        <?php
                        $profilePicture = session()->get('profile_picture');
                        // Use local default if no picture
                        $avatarUrl = base_url('uploads/profile_pictures/default.png'); 
                        if (!empty($profilePicture) && $profilePicture !== 'default.png') {
                            $avatarUrl = base_url('uploads/profile_pictures/' . $profilePicture);
                        }
                        ?>
                        <img src="<?= $avatarUrl ?>" alt="Profile" class="w-full h-full object-cover" onerror="this.src='<?= base_url('AdminLTE/dist/img/user2-160x160.jpg') ?>'">
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100 transform origin-top-right transition-all">
                        <div class="px-4 py-2 border-b border-gray-50">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Akun Saya</p>
                            <p class="text-xs font-bold text-gray-800 truncate mt-0.5"><?= session()->get('nama') ?></p>
                        </div>
                        <a href="<?= base_url('guru/profile') ?>" class="flex items-center px-4 py-3 text-xs font-medium text-gray-700 hover:bg-blue-50 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-[10px]"></i>
                            </div>
                            Edit Profil
                        </a>
                        <div class="border-t border-gray-50 my-1"></div>
                        <a href="<?= base_url('auth/logout') ?>" class="flex items-center px-4 py-3 text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-red-50 text-red-600 flex items-center justify-center mr-3">
                                <i class="fas fa-sign-out-alt text-[10px]"></i>
                            </div>
                            Keluar Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 -mt-10 relative z-30">
            
            <!-- SECTION 2: Menu Cepat -->
            <div class="bg-white rounded-2xl p-5 card-shadow mb-6">
                <h3 class="text-gray-800 font-bold text-sm mb-4">Menu Cepat</h3>
                <div class="grid grid-cols-4 gap-4">
                    <!-- 1. Absensi -->
                    <a href="<?= base_url('guru/absensi') ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-2xl bg-icon-blue text-white flex items-center justify-center text-lg shadow-lg shadow-blue-100 transform transition group-hover:scale-105 group-active:scale-95">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Absensi</span>
                    </a>

                    <!-- 2. Jurnal -->
                    <a href="<?= base_url('guru/jurnal') ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-2xl bg-icon-orange text-white flex items-center justify-center text-lg shadow-lg shadow-orange-100 transform transition group-hover:scale-105 group-active:scale-95">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Jurnal</span>
                    </a>

                    <!-- 3. Profil Saya -->
                    <a href="<?= base_url('guru/profile') ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-2xl bg-icon-green text-white flex items-center justify-center text-lg shadow-lg shadow-green-100 transform transition group-hover:scale-105 group-active:scale-95">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Profil</span>
                    </a>

                    <!-- 4. QR Code -->
                    <a href="<?= base_url('guru/qrcode') ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 rounded-2xl bg-icon-purple text-white flex items-center justify-center text-lg shadow-lg shadow-purple-100 transform transition group-hover:scale-105 group-active:scale-95">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">QR Code</span>
                    </a>
                </div>
            </div>

            <!-- SECTION 3: Grafik Aktivitas -->
            <div class="bg-white rounded-2xl p-5 card-shadow mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-gray-800 font-bold text-sm">Aktivitas Mengajar</h3>
                </div>
                <div class="h-40 relative w-full">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- SECTION 4: Kelas yang Diajar -->
            <div class="mb-6">
                <h3 class="text-gray-800 font-bold text-sm mb-3 px-1">Kelas yang Diajar</h3>
                <div class="bg-white rounded-2xl p-1 card-shadow">
                    <?php if (!empty($kelas_diajar)): ?>
                        <?php foreach ($kelas_diajar as $kelas): ?>
                            <div class="p-3 border-b border-gray-100 flex gap-3 items-center hover:bg-gray-50 transition">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-xs font-bold border border-blue-100">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-gray-800 truncate"><?= esc($kelas['nama_rombel']) ?></h4>
                                    <p class="text-[10px] text-gray-500 truncate"><?= esc($kelas['kode_rombel']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-gray-500 text-xs">Belum ada data kelas.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SECTION 5: Calendar Widget -->
            <div class="mb-4">
                <h3 class="text-gray-800 font-bold text-sm mb-3 px-1">
                    <i class="far fa-calendar-alt mr-1 text-blue-600"></i>Kalender Jurnal - <?= date('F Y') ?>
                </h3>
                <div class="bg-white rounded-2xl p-4 card-shadow">
                    <?php
                    helper('tanggal');
                    $weeks = get_dates_by_week($current_month, $current_year);
                    $today = date('Y-m-d');
                    ?>
                    
                    <div class="calendar-widget">
                        <!-- Calendar Header (Days of Week) -->
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center text-[10px] font-semibold text-gray-500">Sen</div>
                            <div class="text-center text-[10px] font-semibold text-gray-500">Sel</div>
                            <div class="text-center text-[10px] font-semibold text-gray-500">Rab</div>
                            <div class="text-center text-[10px] font-semibold text-gray-500">Kam</div>
                            <div class="text-center text-[10px] font-semibold text-gray-500">Jum</div>
                            <div class="text-center text-[10px] font-semibold text-gray-500">Sab</div>
                            <div class="text-center text-[10px] font-semibold text-gray-500">Min</div>
                        </div>
                        
                        <!-- Calendar Body -->
                        <div class="calendar-body">
                            <?php foreach ($weeks as $weekNumber => $daysInWeek): ?>
                                <div class="grid grid-cols-7 gap-1 mb-1">
                                    <?php 
                                    // Fill empty days at start of first week
                                    if ($weekNumber == 1) {
                                        $firstDay = reset($daysInWeek);
                                        $dayIndex = date('N', strtotime($firstDay['date_sql']));
                                        for ($i = 1; $i < $dayIndex; $i++) {
                                            echo '<div></div>';
                                        }
                                    }
                                    ?>
                                    
                                    <?php foreach ($daysInWeek as $day): ?>
                                        <?php 
                                        $dateSql = $day['date_sql'];
                                        $hasJournal = isset($jurnal_by_date[$dateSql]);
                                        $isToday = ($dateSql === $today);
                                        $journalCount = $hasJournal ? $jurnal_by_date[$dateSql] : 0;
                                        
                                        // Cek Libur & Minggu
                                        $isHoliday = isset($holidays[$dateSql]);
                                        $holidayName = $isHoliday ? $holidays[$dateSql] : '';
                                        $isSunday = (date('N', strtotime($dateSql)) == 7);
                                        
                                        $dayClass = 'w-9 h-9 flex flex-col items-center justify-center rounded-full text-[11px] relative transition';
                                        
                                        // Styling Logic
                                        if ($isToday) {
                                            $dayClass .= ' bg-green-100 text-green-700 font-bold';
                                        } elseif ($hasJournal) {
                                            $dayClass .= ' font-bold text-blue-600';
                                        } elseif ($isHoliday || $isSunday) {
                                            $dayClass .= ' text-red-500 font-medium';
                                        } else {
                                            $dayClass .= ' text-gray-600';
                                        }
                                        
                                        // Tooltip
                                        $tooltipParts = [];
                                        if ($isToday) $tooltipParts[] = 'Hari Ini';
                                        if ($isHoliday) $tooltipParts[] = $holidayName;
                                        if ($hasJournal) $tooltipParts[] = $journalCount . ' Jurnal';
                                        $tooltip = implode(', ', $tooltipParts);
                                        if (empty($tooltip)) $tooltip = date('d M Y', strtotime($dateSql));
                                        ?>
                                        <div class="flex justify-center">
                                            <div class="<?= $dayClass ?>" title="<?= esc($tooltip) ?>">
                                                <span><?= $day['day_num'] ?></span>
                                                <?php if ($hasJournal): ?>
                                                    <span class="absolute bottom-1 w-1 h-1 bg-blue-600 rounded-full <?= $isToday ? 'bg-green-600' : '' ?>"></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <?php 
                                    // Fill empty days at end of last week
                                    $lastDay = end($daysInWeek);
                                    $lastDayIndex = date('N', strtotime($lastDay['date_sql']));
                                    for ($i = $lastDayIndex; $i < 7; $i++) {
                                        echo '<div></div>';
                                    }
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div class="mt-4 pt-3 border-t border-gray-100 flex gap-4 text-[10px] flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span class="text-gray-600">Ada Jurnal</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-600"></span>
                            <span class="text-gray-600">Hari Ini</span>
                        </div>
                        <!-- <div class="flex items-center gap-1.5">
                            <span class="text-red-500 font-bold text-xs">12</span>
                            <span class="text-gray-600">Libur</span>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Navigation -->
        <?= $this->include('mobile/shared/partials/bottom_nav_guru') ?>

    </div>

    <!-- Script Chart -->
    <script>
        // Profile Menu Toggle
        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('profileMenu');
            const button = document.getElementById('profileButton');
            
            if (!button.contains(event.target) && !menu.contains(event.target) && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });

        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // Prepare data from PHP
        const weeklyActivities = <?= json_encode($weeklyActivities ?? []) ?>;
        const labels = weeklyActivities.map(item => 'Minggu ' + item.week);
        const data = weeklyActivities.map(item => item.count);

        const ctxActivity = document.getElementById('activityChart').getContext('2d');
        new Chart(ctxActivity, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jurnal',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(18, 80, 4, 0.99)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { display: false, beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
</body>

</html>