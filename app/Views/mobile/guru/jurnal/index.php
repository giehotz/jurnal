<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Jurnal Mengajar</h1>
        <div class="flex gap-2">
            <a href="<?= base_url('guru/jurnal/generate-pdf') ?>" class="w-9 h-9 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm active:scale-95 transition-transform">
                <i class="fas fa-file-pdf text-red-500"></i>
            </a>
        </div>
    </div>

    <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Anda adalah wali kelas <strong><?= esc($kelas_perwalian['nama_rombel']) ?></strong>.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-green-700"><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <!-- Search/Filter (Optional - can be expanded later) -->
    <!-- <div class="mb-4">
        <div class="relative">
            <input type="text" placeholder="Cari jurnal..." class="w-full pl-10 pr-4 py-3 rounded-xl border-none bg-white shadow-sm text-sm focus:ring-2 focus:ring-blue-500">
            <div class="absolute left-3 top-3 text-gray-400">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div> -->

    <!-- Jurnal List -->
    <div class="space-y-4">
        <?php if (!empty($jurnals)): ?>
            <?php foreach ($jurnals as $j): ?>
                <div class="bg-white rounded-2xl p-4 shadow-sm active:scale-[0.99] transition-transform">
                    <div class="flex justify-between items-start mb-3 border-b border-gray-50 pb-3">
                        <div>
                            <span class="text-xs font-bold text-gray-400 block mb-1"><?= date('d M Y', strtotime($j['tanggal'])) ?></span>
                            <h3 class="font-bold text-gray-800 text-sm"><?= esc($j['nama_mapel']) ?></h3>
                        </div>
                        <?php if ($j['status'] == 'published'): ?>
                            <span class="px-2 py-1 rounded-lg bg-green-100 text-green-700 text-[10px] font-bold">Published</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-[10px] font-bold">Draft</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="block text-[10px] text-gray-400">Kelas</span>
                            <span class="block text-xs font-bold text-gray-700"><?= esc($j['nama_kelas']) ?></span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded-lg">
                            <span class="block text-[10px] text-gray-400">Jam Ke</span>
                            <span class="block text-xs font-bold text-gray-700"><?= $j['jam_ke'] ?></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-gray-600 line-clamp-2"><?= esc($j['materi']) ?></p>
                    </div>

                    <?php if (isset($is_wali_kelas) && $is_wali_kelas): ?>
                        <div class="mb-3 flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="text-xs text-gray-500"><?= $j['nama_guru'] ?? 'Anda' ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex gap-2">
                        <a href="<?= base_url('guru/jurnal/view/' . $j['id']) ?>" class="flex-1 py-2 bg-blue-50 text-blue-600 rounded-xl text-center text-xs font-bold hover:bg-blue-100 transition-colors">
                            Detail
                        </a>
                        <?php if (!isset($is_wali_kelas) || !$is_wali_kelas || (isset($j['user_id']) && $j['user_id'] == session()->get('user_id'))): ?>
                            <a href="<?= base_url('guru/jurnal/edit/' . $j['id']) ?>" class="w-9 h-9 flex items-center justify-center bg-yellow-50 text-yellow-600 rounded-xl hover:bg-yellow-100 transition-colors">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <a href="<?= base_url('guru/jurnal/delete/' . $j['id']) ?>" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors" onclick="return confirm('Hapus jurnal ini?')">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fas fa-book-open text-2xl"></i>
                </div>
                <h3 class="text-gray-800 font-bold mb-1">Belum ada Jurnal</h3>
                <p class="text-gray-500 text-sm">Mulai buat jurnal mengajar Anda hari ini</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Floating Action Button -->
    <a href="<?= base_url('guru/jurnal/create') ?>" class="fixed bottom-24 right-4 w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg shadow-blue-300 flex items-center justify-center active:scale-90 transition-transform z-40">
        <i class="fas fa-plus text-xl"></i>
    </a>
</div>
<?= $this->endSection() ?>
