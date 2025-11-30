<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('guru/absensi') ?>" class="w-8 h-8 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm mr-3 active:scale-95 transition-transform">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Edit Absensi</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-50">
            <span class="text-gray-500 text-xs font-medium">Tanggal</span>
            <span class="font-bold text-gray-800 text-sm"><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></span>
        </div>
        <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-50">
            <span class="text-gray-500 text-xs font-medium">Nama Siswa</span>
            <span class="font-bold text-gray-800 text-sm"><?= esc($siswa['nama']) ?></span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500 text-xs font-medium">NIS</span>
            <span class="font-bold text-gray-800 text-sm"><?= esc($siswa['nis']) ?></span>
        </div>
    </div>

    <form action="<?= base_url('guru/absensi/update/' . $absensi['id']) ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Status Kehadiran</h3>
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div>
                    <input type="radio" id="hadir" name="status" value="hadir" class="peer hidden" <?= ($absensi['status'] == 'hadir') ? 'checked' : '' ?>>
                    <label for="hadir" class="block text-center py-3 border border-gray-200 rounded-xl font-medium cursor-pointer transition-all peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-500 hover:bg-gray-50 shadow-sm">
                        <i class="fas fa-check-circle mb-1 block text-lg"></i>
                        <span class="text-sm">Hadir</span>
                    </label>
                </div>
                <div>
                    <input type="radio" id="sakit" name="status" value="sakit" class="peer hidden" <?= ($absensi['status'] == 'sakit') ? 'checked' : '' ?>>
                    <label for="sakit" class="block text-center py-3 border border-gray-200 rounded-xl font-medium cursor-pointer transition-all peer-checked:bg-yellow-100 peer-checked:text-yellow-700 peer-checked:border-yellow-500 hover:bg-gray-50 shadow-sm">
                        <i class="fas fa-plus-circle mb-1 block text-lg"></i>
                        <span class="text-sm">Sakit</span>
                    </label>
                </div>
                <div>
                    <input type="radio" id="izin" name="status" value="izin" class="peer hidden" <?= ($absensi['status'] == 'izin') ? 'checked' : '' ?>>
                    <label for="izin" class="block text-center py-3 border border-gray-200 rounded-xl font-medium cursor-pointer transition-all peer-checked:bg-blue-100 peer-checked:text-blue-700 peer-checked:border-blue-500 hover:bg-gray-50 shadow-sm">
                        <i class="fas fa-info-circle mb-1 block text-lg"></i>
                        <span class="text-sm">Izin</span>
                    </label>
                </div>
                <div>
                    <input type="radio" id="alfa" name="status" value="alfa" class="peer hidden" <?= ($absensi['status'] == 'alfa') ? 'checked' : '' ?>>
                    <label for="alfa" class="block text-center py-3 border border-gray-200 rounded-xl font-medium cursor-pointer transition-all peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-500 hover:bg-gray-50 shadow-sm">
                        <i class="fas fa-times-circle mb-1 block text-lg"></i>
                        <span class="text-sm">Alfa</span>
                    </label>
                </div>
            </div>
            
            <div class="mb-0">
                <label class="block text-xs font-bold text-gray-500 mb-2">Keterangan</label>
                <textarea name="keterangan" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" rows="3" placeholder="Tambahkan keterangan..."><?= esc($absensi['keterangan']) ?></textarea>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 z-50 flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="<?= base_url('guru/absensi') ?>" class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl text-center text-sm hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="flex-1 py-3 px-4 bg-blue-600 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
