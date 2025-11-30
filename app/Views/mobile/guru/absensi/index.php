<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<div class="p-4 max-w-md mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Absensi Siswa</h1>
            <p class="text-xs text-gray-500">Kelola kehadiran siswa</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('guru/absensi/create') ?>" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200 active:scale-95 transition-transform">
                <i class="fas fa-plus"></i>
            </a>
            <a href="<?= base_url('guru/absensi/export') ?>" class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center shadow-lg shadow-green-200 active:scale-95 transition-transform">
                <i class="fas fa-file-export"></i>
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700"><?= session()->getFlashdata('success') ?></p>
                </div>
            </div>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter mr-2 text-blue-600"></i>Filter Data
        </h3>
        <form action="<?= base_url('guru/absensi') ?>" method="GET">
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-500 mb-1">Periode</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $startDate ?>">
                    <span class="text-gray-400 text-xs">s/d</span>
                    <input type="date" name="end_date" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $endDate ?>">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
                <select name="rombel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($rombel as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= ($rombelId == $r['id']) ? 'selected' : '' ?>>
                            <?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center shadow-lg shadow-blue-200 transition-all">
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Grafik Absensi
        </h3>
        <div class="relative h-48">
            <canvas id="absensiChart"></canvas>
        </div>
    </div>

    <!-- Data List -->
    <h3 class="text-sm font-bold text-gray-800 mb-3 px-1">Rekapitulasi per Kelas</h3>
    
    <?php if (!empty($rekapKelas)): ?>
        <?php foreach ($rekapKelas as $kelas): ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm mb-4 border border-gray-100">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-50">
                    <span class="font-bold text-gray-800"><?= esc($kelas['nama_rombel']) ?></span>
                    <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5 rounded-full border border-gray-200">
                        <?= esc($kelas['jumlah_siswa']) ?> Siswa
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 flex items-center"><i class="fas fa-check-circle text-green-500 mr-1.5 text-xs"></i>Hadir</span>
                            <span class="font-bold text-gray-700"><?= esc($kelas['hadir']) ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 flex items-center"><i class="fas fa-info-circle text-blue-400 mr-1.5 text-xs"></i>Izin</span>
                            <span class="font-bold text-gray-700"><?= esc($kelas['izin']) ?></span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 flex items-center"><i class="fas fa-plus-circle text-yellow-400 mr-1.5 text-xs"></i>Sakit</span>
                            <span class="font-bold text-gray-700"><?= esc($kelas['sakit']) ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 flex items-center"><i class="fas fa-times-circle text-red-500 mr-1.5 text-xs"></i>Alfa</span>
                            <span class="font-bold text-gray-700"><?= esc($kelas['alfa']) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-2">
                    <a href="<?= base_url('guru/absensi/detail/' . $kelas['rombel_id'] . '?start_date=' . $startDate . '&end_date=' . $endDate) ?>" 
                       class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 focus:ring-2 focus:ring-blue-300 transition-colors">
                        Detail <i class="fas fa-arrow-right ml-1.5"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-10">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                <i class="fas fa-clipboard-list text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm">Tidak ada data rekapitulasi</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('absensiChart').getContext('2d');
    
    var hariLabels = <?= json_encode($rekapHarian['labels'] ?? []) ?>;
    var hadirData = <?= json_encode($rekapHarian['hadir'] ?? []) ?>;
    var izinData = <?= json_encode($rekapHarian['izin'] ?? []) ?>;
    var sakitData = <?= json_encode($rekapHarian['sakit'] ?? []) ?>;
    var alfaData = <?= json_encode($rekapHarian['alfa'] ?? []) ?>;
    
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#94a3b8';
    
    var absensiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hariLabels,
            datasets: [
                { label: 'Hadir', data: hadirData, backgroundColor: '#22c55e', borderRadius: 4 },
                { label: 'Izin', data: izinData, backgroundColor: '#3b82f6', borderRadius: 4 },
                { label: 'Sakit', data: sakitData, backgroundColor: '#eab308', borderRadius: 4 },
                { label: 'Alpha', data: alfaData, backgroundColor: '#ef4444', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 20,
                        font: { size: 11 }
                    }
                }
            },
            scales: {
                x: { 
                    stacked: true,
                    grid: { display: false }
                },
                y: { 
                    stacked: true, 
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: '#f1f5f9' },
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
