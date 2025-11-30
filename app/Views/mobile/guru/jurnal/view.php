<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<style>
    #mobile-bottom-nav { display: none !important; }
</style>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('guru/jurnal') ?>" class="w-8 h-8 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm mr-3 active:scale-95 transition-transform">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Detail Jurnal</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-green-700"><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <span class="text-xs font-bold text-gray-400 block mb-1">Tanggal</span>
                <h2 class="text-lg font-bold text-gray-800"><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></h2>
            </div>
            <?php if ($jurnal['status'] == 'published'): ?>
                <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-bold">Published</span>
            <?php else: ?>
                <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-xs font-bold">Draft</span>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-gray-50 p-3 rounded-xl">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Kelas</span>
                <span class="block text-sm font-bold text-gray-800"><?= esc($jurnal['nama_kelas']) ?></span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Jam Ke</span>
                <span class="block text-sm font-bold text-gray-800"><?= $jurnal['jam_ke'] ?></span>
            </div>
            <div class="bg-gray-50 p-3 rounded-xl col-span-2">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Mata Pelajaran</span>
                <span class="block text-sm font-bold text-gray-800"><?= esc($jurnal['nama_mapel']) ?></span>
            </div>
        </div>

        <div class="mb-4">
            <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-2">Materi Pembelajaran</span>
            <div class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">
                <?= nl2br(esc($jurnal['materi'])) ?>
            </div>
        </div>

        <?php if (!empty($jurnal['keterangan'])): ?>
            <div class="mb-4">
                <span class="block text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-2">Keterangan / Catatan</span>
                <div class="text-sm text-gray-600 italic bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                    <?= nl2br(esc($jurnal['keterangan'])) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 gap-3">
            <div class="bg-blue-50 p-3 rounded-xl text-center">
                <span class="block text-2xl font-bold text-blue-600 mb-1"><?= $jurnal['jumlah_jam'] ?></span>
                <span class="text-[10px] uppercase tracking-wider font-semibold text-blue-400">Jam Pelajaran</span>
            </div>
            <div class="bg-purple-50 p-3 rounded-xl text-center">
                <span class="block text-2xl font-bold text-purple-600 mb-1"><?= $jurnal['jumlah_peserta'] ?></span>
                <span class="text-[10px] uppercase tracking-wider font-semibold text-purple-400">Peserta Hadir</span>
            </div>
        </div>
    </div>
    
    <!-- Absensi List (if available) -->
    <?php if (!empty($absensi)): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">Daftar Absensi</h3>
        <div class="space-y-3">
            <?php foreach ($absensi as $a): ?>
                <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 mr-3">
                            <?= substr($a['nama_siswa'], 0, 1) ?>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-800"><?= esc($a['nama_siswa']) ?></div>
                            <div class="text-[10px] text-gray-400"><?= esc($a['nis']) ?></div>
                        </div>
                    </div>
                    <?php
                    $badgeClass = '';
                    switch ($a['status']) {
                        case 'hadir': $badgeClass = 'bg-green-100 text-green-700'; break;
                        case 'izin': $badgeClass = 'bg-blue-100 text-blue-700'; break;
                        case 'sakit': $badgeClass = 'bg-yellow-100 text-yellow-700'; break;
                        case 'alfa': $badgeClass = 'bg-red-100 text-red-700'; break;
                    }
                    ?>
                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold <?= $badgeClass ?> uppercase"><?= $a['status'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <?php if (!isset($is_wali_kelas) || !$is_wali_kelas || (isset($jurnal['user_id']) && $jurnal['user_id'] == session()->get('user_id'))): ?>
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 z-[60] flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="<?= base_url('guru/jurnal/edit/' . $jurnal['id']) ?>" class="flex-1 py-3 px-4 bg-yellow-500 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-yellow-200 hover:bg-yellow-600 transition-colors">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="<?= base_url('guru/jurnal/delete/' . $jurnal['id']) ?>" class="flex-1 py-3 px-4 bg-red-500 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-red-200 hover:bg-red-600 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">
                <i class="fas fa-trash mr-2"></i> Hapus
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
