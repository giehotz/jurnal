<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Jurnal Guru - Mobile Dashboard' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

<body class="flex justify-center min-h-screen items-center bg-gray-200">

    <!-- Mobile Container -->
    <div
        class="w-full max-w-[420px] h-[850px] bg-gray-50 relative shadow-2xl overflow-hidden flex flex-col border-x border-gray-300 rounded-xl">

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto no-scrollbar pb-20">

            <!-- Header / Top Decoration -->
            <div class="relative h-44 w-full overflow-hidden rounded-b-[2.5rem] bg-white shadow-sm z-10">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-indigo-800 opacity-100"></div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-black/10 to-transparent"></div>

                <!-- User Profile & Greeting -->
                <div class="relative z-20 p-6 pt-8 text-white flex justify-between items-center">
                    <div>
                        <?php
                        // Determine greeting based on time
                        $hour = date('H');
                        $greeting = 'Selamat Malam'; // Default: night (00:00-03:59)
                        
                        if ($hour >= 4 && $hour < 11) {
                            $greeting = 'Selamat Pagi'; // Morning: 04:00-10:59
                        } elseif ($hour >= 11 && $hour < 15) {
                            $greeting = 'Selamat Siang'; // Afternoon: 11:00-14:59
                        } elseif ($hour >= 15 && $hour < 18) {
                            $greeting = 'Selamat Sore'; // Evening: 15:00-17:59
                        } elseif ($hour >= 18) {
                            $greeting = 'Selamat Malam'; // Night: 18:00-23:59
                        }
                        ?>
                        <p class="text-blue-100 text-xs font-medium mb-1"><?= $greeting ?>,</p>
                        <h1 class="text-xl font-bold leading-tight"><?= session()->get('nama') ?? 'Administrator' ?></h1>
                        <div class="mt-1 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-[10px] opacity-80">Online</span>
                        </div>
                    </div>
                    <div class="relative">
                        <button id="profileButton" onclick="toggleProfileMenu()" class="w-12 h-12 rounded-full border-2 border-white/30 overflow-hidden shadow-lg bg-white focus:outline-none transition-transform active:scale-95">
                            <?php
                            $profilePicture = session()->get('profile_picture');
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode(session()->get('nama') ?? 'Admin Guru') . '&background=random&color=fff';

                            if (!empty($profilePicture) && $profilePicture !== 'default.png') {
                                $avatarUrl = base_url('uploads/profile_pictures/' . $profilePicture);
                            }
                            ?>
                            <img src="<?= $avatarUrl ?>" alt="Profile" class="w-full h-full object-cover">
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100 transform origin-top-right transition-all">
                            <div class="px-4 py-2 border-b border-gray-50">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Akun Saya</p>
                                <p class="text-xs font-bold text-gray-800 truncate mt-0.5"><?= session()->get('nama') ?></p>
                            </div>
                            <a href="<?= base_url('admin/profile') ?>" class="flex items-center px-4 py-3 text-xs font-medium text-gray-700 hover:bg-blue-50 transition-colors">
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

            <!-- Main Content Area -->
            <div class="px-5 -mt-12 relative z-20">

                <!-- SECTION 1: Ringkasan Data (Stats Horizontal) -->
                <div class="bg-white rounded-2xl p-4 card-shadow mb-6 flex justify-between items-center">
                    <div class="text-center flex-1 border-r border-gray-100">
                        <span class="block text-lg font-bold text-blue-600"><?= $total_guru ?? 0 ?></span>
                        <span class="text-[10px] text-gray-400">Guru</span>
                    </div>
                    <div class="text-center flex-1 border-r border-gray-100">
                        <span class="block text-lg font-bold text-green-600"><?= $total_kelas ?? 0 ?></span>
                        <span class="text-[10px] text-gray-400">Kelas</span>
                    </div>
                    <div class="text-center flex-1">
                        <span class="block text-lg font-bold text-orange-500"><?= $jurnal_bulan_ini ?? 0 ?></span>
                        <span class="text-[10px] text-gray-400">Jurnal</span>
                    </div>
                </div>

                <!-- SECTION 2: MENU UTAMA (Transformasi Link Sidebar menjadi Icon) -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3 px-1">
                        <h3 class="text-gray-800 font-bold text-sm">Menu Aplikasi</h3>
                    </div>

                    <div class="grid grid-cols-4 gap-y-6 gap-x-2 bg-white p-5 rounded-2xl card-shadow">
                        <!-- 1. User Management -->
                        <a href="<?= base_url('admin/users') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-blue text-white flex items-center justify-center text-lg shadow-lg shadow-blue-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Users</span>
                        </a>

                        <!-- 2. Rombel (Kelas) -->
                        <a href="<?= base_url('admin/rombel') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-orange text-white flex items-center justify-center text-lg shadow-lg shadow-orange-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Rombel</span>
                        </a>

                        <!-- 3. Data Siswa -->
                        <a href="<?= base_url('admin/siswa') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-green text-white flex items-center justify-center text-lg shadow-lg shadow-green-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Siswa</span>
                        </a>

                        <!-- 4. Absensi -->
                        <a href="<?= base_url('admin/absensi') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-red text-white flex items-center justify-center text-lg shadow-lg shadow-red-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Absensi</span>
                        </a>

                        <!-- 5. Mata Pelajaran -->
                        <a href="<?= base_url('admin/mapel') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-yellow text-white flex items-center justify-center text-lg shadow-lg shadow-yellow-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Mapel</span>
                        </a>

                        <!-- 6. Monitoring -->
                        <a href="<?= base_url('admin/monitoring') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-teal text-white flex items-center justify-center text-lg shadow-lg shadow-teal-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-search"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Monitor</span>
                        </a>

                        <!-- 7. Laporan -->
                        <a href="<?= base_url('admin/laporan') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-purple text-white flex items-center justify-center text-lg shadow-lg shadow-purple-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Laporan</span>
                        </a>

                        <!-- 8. Pengaturan -->
                        <a href="<?= base_url('admin/settings') ?>" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-icon-gray text-white flex items-center justify-center text-lg shadow-lg shadow-gray-100 transform transition group-hover:scale-105 group-active:scale-95">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span class="text-[10px] text-center font-medium text-gray-600 leading-tight">Setting</span>
                        </a>
                    </div>
                </div>

                <!-- SECTION 3: Grafik & Statistik -->
                <div class="bg-white rounded-2xl p-5 card-shadow mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-gray-800 font-bold text-sm">Keaktifan Guru</h3>
                        <button class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-md font-medium">Lihat
                            Detail</button>
                    </div>
                    <div class="h-40 relative w-full">
                        <canvas id="guruChart"></canvas>
                    </div>
                </div>

                <!-- SECTION 4: Recent Jurnal -->
                <div class="mb-4">
                    <h3 class="text-gray-800 font-bold text-sm mb-3 px-1">Update Terbaru</h3>
                    <div class="bg-white rounded-2xl p-1 card-shadow">
                        <?php if (!empty($recent_jurnals)): ?>
                            <?php foreach ($recent_jurnals as $jurnal): ?>
                                <!-- List Item -->
                                <div class="p-3 border-b border-gray-100 flex gap-3 items-center hover:bg-gray-50 transition">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-xs font-bold border border-blue-100">
                                        <?= substr($jurnal['nama_guru'], 0, 2) ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-gray-800 truncate"><?= $jurnal['nama_guru'] ?></h4>
                                        <p class="text-[10px] text-gray-500 truncate"><?= $jurnal['nama_mapel'] ?> • <?= $jurnal['nama_rombel'] ?></p>
                                    </div>
                                    <span
                                        class="text-[10px] <?= $jurnal['status'] == 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?> px-2 py-0.5 rounded-full font-medium"><?= ucfirst($jurnal['status']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4 text-center text-gray-500 text-xs">Belum ada data jurnal terbaru.</div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Navigation -->
        <div
            class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[420px] bg-white border-t border-gray-100 px-6 py-2 pb-4 flex justify-between items-end z-[100] rounded-t-2xl shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.03)]">
            <a href="<?= base_url('admin/dashboard') ?>" class="flex flex-col items-center gap-1 w-14 nav-active">
                <i class="fas fa-home text-lg"></i>
                <span class="text-[9px] font-medium">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 w-14 nav-inactive hover:text-blue-500">
                <i class="fas fa-chart-pie text-lg"></i>
                <span class="text-[9px] font-medium">Stats</span>
            </a>
            <div class="relative -top-5">
                <button
                    class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center shadow-xl shadow-blue-200 text-white hover:scale-105 transition">
                    <i class="fas fa-plus text-lg"></i>
                </button>
            </div>
            <a href="#" class="flex flex-col items-center gap-1 w-14 nav-inactive hover:text-blue-500">
                <i class="fas fa-bell text-lg"></i>
                <span class="text-[9px] font-medium">Notif</span>
            </a>
            <a href="<?= base_url('admin/profile') ?>" class="flex flex-col items-center gap-1 w-14 nav-inactive hover:text-blue-500">
                <i class="fas fa-user text-lg"></i>
                <span class="text-[9px] font-medium">Profil</span>
            </a>
        </div>

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
        const guruAktifData = <?= json_encode($guru_aktif_data ?? []) ?>;
        const labels = guruAktifData.map(item => item.nama.split(' ')[0]); // Take first name only
        const data = guruAktifData.map(item => item.jumlah_jurnal);

        const ctxGuru = document.getElementById('guruChart').getContext('2d');
        new Chart(ctxGuru, {
            type: 'bar',
            data: {
                labels: labels.length > 0 ? labels : ['No Data'],
                datasets: [{
                    label: 'Jurnal',
                    data: data.length > 0 ? data : [0],
                    backgroundColor: ['#3b82f6', '#f97316', '#22c55e', '#ef4444', '#eab308'],
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { display: false, beginAtZero: true }
                }
            }
        });
    </script>
</body>

</html>
