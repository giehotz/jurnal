<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<style>
    #mobile-bottom-nav { display: none !important; }
</style>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('guru/absensi') ?>" class="w-8 h-8 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm mr-3 active:scale-95 transition-transform">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Export Data</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
        <h4 class="text-blue-800 font-bold text-sm mb-2">Informasi Export</h4>
        <ul class="list-disc list-inside text-xs text-blue-700 space-y-1">
            <li>Sistem otomatis menghitung <strong>Hari Efektif</strong> (Senin-Sabtu).</li>
            <li>Hari Minggu dan <strong>Libur Nasional</strong> tidak dihitung.</li>
            <li><strong>Alfa</strong> = Hari Efektif - (Hadir + Sakit + Izin).</li>
            <li>Perhitungan sampai hari ini jika export bulan berjalan.</li>
        </ul>
    </div>

    <form action="<?= base_url('guru/absensi/process_export') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Periode Export</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Bulan Awal</label>
                    <select name="start_month" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                        <option value="">Pilih</option>
                        <?php 
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        foreach ($months as $num => $name): ?>
                            <option value="<?= $num ?>"><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Bulan Akhir</label>
                    <select name="end_month" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                        <option value="">Pilih</option>
                        <?php foreach ($months as $num => $name): ?>
                            <option value="<?= $num ?>"><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-1">Kelas (Rombel)</label>
                <select name="rombel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($rombel as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Format File</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="radio" name="export_type" id="pdf" value="pdf" class="peer hidden" required>
                    <label for="pdf" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all">
                        <i class="fas fa-file-pdf text-2xl mb-2 text-red-500"></i>
                        <span class="text-xs font-bold">PDF</span>
                    </label>
                </div>
                <div>
                    <input type="radio" name="export_type" id="excel" value="excel" class="peer hidden" required>
                    <label for="excel" class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                        <i class="fas fa-file-excel text-2xl mb-2 text-green-500"></i>
                        <span class="text-xs font-bold">Excel</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 z-[60] flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="<?= base_url('guru/absensi') ?>" class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl text-center text-sm hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="flex-1 py-3 px-4 bg-blue-600 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-colors">
                Export Data
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
