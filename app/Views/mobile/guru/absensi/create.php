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
        <h1 class="text-lg font-bold text-gray-800">Input Absensi</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('guru/absensi/store') ?>" method="POST" id="absensiForm">
        <?= csrf_field() ?>
        
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $selected_tanggal ?? date('Y-m-d') ?>" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-1">Kelas</label>
                <select name="rombel_id" id="rombel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($rombel as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= (isset($selected_rombel) && $selected_rombel == $r['id']) ? 'selected' : '' ?>><?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    <option value="">-- Pilih Mapel --</option>
                    <?php foreach ($mapel as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-0">
                <label class="block text-xs font-bold text-gray-500 mb-1">Jam Ke</label>
                <input type="number" name="jam_ke" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Contoh: 1-2">
            </div>
        </div>

        <div id="loading-message" style="display: none;" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-500 text-xs">Memuat siswa...</p>
        </div>

        <div id="absensi-section" style="display: none;">
            <h3 class="text-sm font-bold text-gray-500 mb-3 px-1">Daftar Siswa</h3>
            <div id="absensi-list">
                <!-- Student cards will be inserted here -->
            </div>
        </div>
        
        <div id="no-siswa-message" style="display: none;" class="bg-blue-50 text-blue-700 p-4 rounded-xl text-center text-sm">
            <i class="fas fa-info-circle mb-2 block text-xl"></i>
            Tidak ada siswa dalam kelas ini.
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 z-[60] flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="<?= base_url('guru/absensi') ?>" class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl text-center text-sm hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="flex-1 py-3 px-4 bg-blue-600 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-colors">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trigger change event if rombel is pre-selected
    const rombelSelect = document.getElementById('rombel_id');
    if (rombelSelect.value) {
        rombelSelect.dispatchEvent(new Event('change'));
    }
});

document.getElementById('rombel_id').addEventListener('change', function() {
    const rombelId = this.value;
    const absensiSection = document.getElementById('absensi-section');
    const absensiList = document.getElementById('absensi-list');
    const noSiswaMessage = document.getElementById('no-siswa-message');
    const loadingMessage = document.getElementById('loading-message');
    
    // Reset UI
    absensiList.innerHTML = '';
    absensiSection.style.display = 'none';
    noSiswaMessage.style.display = 'none';
    
    if (rombelId) {
        loadingMessage.style.display = 'block';

        fetch('<?= base_url("guru/absensi/get-siswa-by-rombel") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: 'rombel_id=' + rombelId
        })
        .then(response => response.json())
        .then(data => {
            loadingMessage.style.display = 'none';
            
            if (data.length > 0) {
                absensiSection.style.display = 'block';
                
                let html = '';
                data.forEach((siswa, index) => {
                    html += `
                        <div class="bg-white rounded-xl p-4 shadow-sm mb-3 border border-gray-100">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-50">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">${index + 1}</span>
                                    <span class="font-bold text-gray-800 text-sm">${siswa.siswa_nama}</span>
                                </div>
                                <span class="text-[10px] text-gray-500 bg-gray-50 px-2 py-1 rounded border border-gray-100">${siswa.siswa_nis}</span>
                            </div>
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <div>
                                    <input type="radio" id="hadir_${siswa.siswa_id}" name="absensi[${siswa.siswa_id}][status]" value="hadir" class="peer hidden" checked>
                                    <label for="hadir_${siswa.siswa_id}" class="block text-center py-2 border border-gray-200 rounded-lg text-xs font-medium cursor-pointer transition-all peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-500 hover:bg-gray-50">Hadir</label>
                                </div>
                                <div>
                                    <input type="radio" id="sakit_${siswa.siswa_id}" name="absensi[${siswa.siswa_id}][status]" value="sakit" class="peer hidden">
                                    <label for="sakit_${siswa.siswa_id}" class="block text-center py-2 border border-gray-200 rounded-lg text-xs font-medium cursor-pointer transition-all peer-checked:bg-yellow-100 peer-checked:text-yellow-700 peer-checked:border-yellow-500 hover:bg-gray-50">Sakit</label>
                                </div>
                                <div>
                                    <input type="radio" id="izin_${siswa.siswa_id}" name="absensi[${siswa.siswa_id}][status]" value="izin" class="peer hidden">
                                    <label for="izin_${siswa.siswa_id}" class="block text-center py-2 border border-gray-200 rounded-lg text-xs font-medium cursor-pointer transition-all peer-checked:bg-blue-100 peer-checked:text-blue-700 peer-checked:border-blue-500 hover:bg-gray-50">Izin</label>
                                </div>
                                <div>
                                    <input type="radio" id="alfa_${siswa.siswa_id}" name="absensi[${siswa.siswa_id}][status]" value="alfa" class="peer hidden">
                                    <label for="alfa_${siswa.siswa_id}" class="block text-center py-2 border border-gray-200 rounded-lg text-xs font-medium cursor-pointer transition-all peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-500 hover:bg-gray-50">Alfa</label>
                                </div>
                            </div>
                            <input type="text" name="absensi[${siswa.siswa_id}][keterangan]" class="w-full bg-gray-50 border-none rounded-lg py-2 px-3 text-xs focus:ring-2 focus:ring-blue-100 focus:bg-white transition-colors" placeholder="Catatan (opsional)...">
                        </div>
                    `;
                });
                absensiList.innerHTML = html;
            } else {
                noSiswaMessage.style.display = 'block';
            }
        })
        .catch(error => {
            loadingMessage.style.display = 'none';
            console.error('Error:', error);
            alert('Gagal memuat data siswa');
        });
    }
});
</script>
<?= $this->endSection() ?>
