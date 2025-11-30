<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('guru/absensi?start_date=' . $startDate . '&end_date=' . $endDate . '&rombel_id=' . $rombel['id']) ?>" class="w-8 h-8 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm mr-3 active:scale-95 transition-transform">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Detail Absensi</h1>
            <p class="text-xs text-gray-500"><?= esc($rombel['nama_rombel']) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <div class="flex items-center mb-2">
            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
            <span class="font-bold text-gray-800 text-sm">Periode</span>
        </div>
        <p class="text-gray-500 text-xs pl-6">
            <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?>
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Riwayat Absensi</h3>
        <?php if (!empty($detailAbsensi)): ?>
            <div class="space-y-4">
                <?php foreach ($detailAbsensi as $d): ?>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                        <div class="flex-1 min-w-0 mr-3">
                            <div class="font-bold text-gray-800 text-sm truncate"><?= esc($d['nama_siswa']) ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="far fa-calendar mr-1"></i> <?= date('d/m/y', strtotime($d['tanggal'])) ?>
                                <?php if($d['keterangan']): ?>
                                    <span class="mx-1">•</span> <i class="far fa-comment-alt mr-1"></i> <?= esc($d['keterangan']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <?php 
                                $badgeClass = '';
                                $statusText = '';
                                switch($d['status']) {
                                    case 'hadir': $badgeClass = 'bg-green-100 text-green-700 border border-green-200'; $statusText = 'Hadir'; break;
                                    case 'sakit': $badgeClass = 'bg-yellow-100 text-yellow-700 border border-yellow-200'; $statusText = 'Sakit'; break;
                                    case 'izin': $badgeClass = 'bg-blue-100 text-blue-700 border border-blue-200'; $statusText = 'Izin'; break;
                                    case 'alfa': $badgeClass = 'bg-red-100 text-red-700 border border-red-200'; $statusText = 'Alfa'; break;
                                    default: $badgeClass = 'bg-gray-100 text-gray-700 border border-gray-200'; $statusText = $d['status'];
                                }
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $badgeClass ?>"><?= $statusText ?></span>
                            
                            <a href="<?= base_url('guru/absensi/edit/' . $d['id']) ?>" class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <p class="text-gray-400 text-xs">Tidak ada data absensi</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
