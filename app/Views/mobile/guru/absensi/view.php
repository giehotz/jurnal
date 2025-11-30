<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('guru/absensi') ?>" class="w-8 h-8 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm mr-3 active:scale-95 transition-transform">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Detail Harian</h1>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-gray-50 p-3 rounded-xl">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Tanggal</span>
                <span class="block text-sm font-bold text-gray-800"><?= date('d M Y', strtotime($jurnal['tanggal'])) ?></span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Jam Ke</span>
                <span class="block text-sm font-bold text-gray-800"><?= $jurnal['jam_ke'] ?? '-' ?></span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl col-span-2">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Kelas</span>
                <span class="block text-sm font-bold text-gray-800"><?= esc($rombel['nama_rombel']) ?></span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl col-span-2">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Mata Pelajaran</span>
                <span class="block text-sm font-bold text-gray-800"><?= esc($mapel['nama_mapel']) ?></span>
            </div>
        </div>
        
        <div class="bg-white border border-gray-100 p-3 rounded-xl">
            <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Materi</span>
            <span class="block text-sm text-gray-700"><?= esc($jurnal['materi']) ?></span>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-2 mb-6">
        <div class="bg-green-500 p-3 rounded-xl text-center text-white shadow-sm shadow-green-200">
            <span class="block text-xl font-bold leading-none mb-1"><?= $stats['hadir'] ?></span>
            <span class="text-[10px] opacity-90">Hadir</span>
        </div>
        <div class="bg-blue-500 p-3 rounded-xl text-center text-white shadow-sm shadow-blue-200">
            <span class="block text-xl font-bold leading-none mb-1"><?= $stats['izin'] ?></span>
            <span class="text-[10px] opacity-90">Izin</span>
        </div>
        <div class="bg-yellow-500 p-3 rounded-xl text-center text-white shadow-sm shadow-yellow-200">
            <span class="block text-xl font-bold leading-none mb-1"><?= $stats['sakit'] ?></span>
            <span class="text-[10px] opacity-90">Sakit</span>
        </div>
        <div class="bg-red-500 p-3 rounded-xl text-center text-white shadow-sm shadow-red-200">
            <span class="block text-xl font-bold leading-none mb-1"><?= $stats['alfa'] ?></span>
            <span class="text-[10px] opacity-90">Alfa</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Daftar Siswa</h3>
        <?php if (!empty($absensi)): ?>
            <div class="space-y-4">
                <?php foreach ($absensi as $item): ?>
                    <?php
                    $siswaModel = new \App\Models\SiswaModel();
                    $siswa = $siswaModel->find($item['siswa_id']);
                    
                    $badgeClass = '';
                    $statusText = '';
                    switch ($item['status']) {
                        case 'hadir': $badgeClass = 'bg-green-100 text-green-700 border border-green-200'; $statusText = 'Hadir'; break;
                        case 'izin': $badgeClass = 'bg-blue-100 text-blue-700 border border-blue-200'; $statusText = 'Izin'; break;
                        case 'sakit': $badgeClass = 'bg-yellow-100 text-yellow-700 border border-yellow-200'; $statusText = 'Sakit'; break;
                        case 'alfa': $badgeClass = 'bg-red-100 text-red-700 border border-red-200'; $statusText = 'Alfa'; break;
                    }
                    ?>
                    <div class="flex items-center border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                        <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-500 text-sm mr-3">
                            <?= substr($siswa['nama'], 0, 1) ?>
                        </div>
                        <div class="flex-1 min-w-0 mr-3">
                            <div class="font-bold text-gray-800 text-sm truncate"><?= esc($siswa['nama']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($siswa['nis']) ?></div>
                            <?php if($item['keterangan']): ?>
                                <div class="text-[10px] text-gray-400 mt-1">
                                    <i class="fas fa-comment-alt mr-1"></i> <?= esc($item['keterangan']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $badgeClass ?>"><?= $statusText ?></span>
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
