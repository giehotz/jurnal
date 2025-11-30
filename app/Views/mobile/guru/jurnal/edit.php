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
        <h1 class="text-lg font-bold text-gray-800">Edit Jurnal</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($validation)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <ul class="list-disc list-inside text-sm text-red-700">
                <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('guru/jurnal/update/' . $jurnal['id']) ?>" method="POST" enctype="multipart/form-data" id="form-edit">
        <?= csrf_field() ?>
        
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6 space-y-4">
            <!-- Tanggal -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="<?= $jurnal['tanggal'] ?>" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
            </div>

            <!-- Kelas -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Kelas</label>
                <select name="rombel_id" id="rombel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($rombel as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= ($jurnal['rombel_id'] == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rombel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Mapel -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                    <option value="">Pilih Mapel</option>
                    <?php foreach ($mapel as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= ($jurnal['mapel_id'] == $m['id']) ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jam Ke & Jumlah Jam -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Jam Ke</label>
                    <input type="text" name="jam_ke" value="<?= $jurnal['jam_ke'] ?>" placeholder="Contoh: 1-2" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Jumlah Jam</label>
                    <input type="number" name="jumlah_jam" min="1" value="<?= $jurnal['jumlah_jam'] ?>" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                </div>
            </div>

            <!-- Jumlah Peserta -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Jumlah Peserta</label>
                <input type="number" name="jumlah_peserta" min="0" value="<?= $jurnal['jumlah_peserta'] ?>" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6 space-y-4">
            <!-- Materi -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Materi Pembelajaran</label>
                <textarea name="materi" rows="4" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Tuliskan materi pembelajaran..." required><?= esc($jurnal['materi']) ?></textarea>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Keterangan / Catatan</label>
                <textarea name="keterangan" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Catatan tambahan (opsional)"><?= esc($jurnal['keterangan']) ?></textarea>
            </div>

            <!-- Bukti Dukung -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Bukti Dukung (Foto/Dokumen)</label>
                <?php if ($jurnal['bukti_dukung']): ?>
                    <div class="mb-2 p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <i class="fas fa-file text-blue-500"></i>
                            <span class="text-xs text-gray-600 truncate"><?= $jurnal['bukti_dukung'] ?></span>
                        </div>
                        <a href="<?= base_url('uploads/' . $jurnal['bukti_dukung']) ?>" target="_blank" class="text-xs text-blue-600 font-bold hover:underline">Lihat</a>
                    </div>
                <?php endif; ?>
                <input type="file" name="bukti_dukung" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-400">Upload file baru untuk mengganti. Max: 5MB.</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2">Status Jurnal</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <input type="radio" name="status" id="status_published" value="published" class="peer hidden" <?= ($jurnal['status'] == 'published') ? 'checked' : '' ?>>
                        <label for="status_published" class="flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                            <i class="fas fa-check-circle text-lg mb-1"></i>
                            <span class="text-xs font-bold">Published</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="status" id="status_draft" value="draft" class="peer hidden" <?= ($jurnal['status'] == 'draft') ? 'checked' : '' ?>>
                        <label for="status_draft" class="flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:bg-yellow-50 peer-checked:border-yellow-500 peer-checked:text-yellow-700 transition-all">
                            <i class="fas fa-save text-lg mb-1"></i>
                            <span class="text-xs font-bold">Draft</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 z-[60] flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a href="<?= base_url('guru/jurnal') ?>" class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 font-bold rounded-xl text-center text-sm hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="flex-1 py-3 px-4 bg-blue-600 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-colors">
                Update Jurnal
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
