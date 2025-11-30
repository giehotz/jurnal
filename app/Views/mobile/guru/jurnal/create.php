<?= $this->extend('mobile/layouts/template') ?>

<?= $this->section('content') ?>
<div class="p-4 max-w-md mx-auto pb-24">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('guru/jurnal') ?>" class="w-8 h-8 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-sm mr-3 active:scale-95 transition-transform">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Tambah Jurnal</h1>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-r-lg">
            <p class="text-sm text-green-700"><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('guru/jurnal/store') ?>" method="POST" enctype="multipart/form-data" id="form-create">
        <?= csrf_field() ?>
        
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6 space-y-4">
            <!-- Tanggal -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Tanggal Mengajar</label>
                <input type="date" name="tanggal" id="tanggal" value="<?= $selected_tanggal ?? date('Y-m-d') ?>" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
            </div>

            <!-- Kelas -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Kelas</label>
                <select name="rombel_id" id="rombel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($rombel as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= (isset($selected_rombel) && $selected_rombel == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rombel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Mapel -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                    <option value="">Pilih Mapel</option>
                    <?php foreach ($mapel as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jam Ke & Jumlah Jam -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Jam Ke</label>
                    <input type="text" name="jam_ke" id="jam_ke" placeholder="Contoh: 1-2" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Jumlah Jam</label>
                    <input type="number" name="jumlah_jam" id="jumlah_jam" min="1" value="2" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" required>
                </div>
            </div>
            
            <!-- Hidden Jumlah Peserta (Auto-filled) -->
            <input type="hidden" name="jumlah_peserta" id="jumlah_peserta" value="0">
        </div>

        <!-- Attendance Alerts -->
        <div id="attendance-warning" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Absensi Belum Diisi</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p id="warning-text">Anda belum mengisi absensi untuk kelas dan tanggal ini. Data kehadiran diperlukan untuk membuat jurnal.</p>
                    </div>
                    <div class="mt-4">
                        <a href="#" id="btn-isi-absensi" class="text-sm font-medium text-yellow-800 hover:text-yellow-600 underline">
                            Isi Absensi Sekarang <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="attendance-success" class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Data Absensi Tersedia</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p id="attendance-summary"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm mb-6 space-y-4">
            <!-- Materi -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Materi Pembelajaran</label>
                <textarea name="materi" id="materi" rows="4" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Tuliskan materi pembelajaran..." required></textarea>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Keterangan / Catatan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Catatan tambahan (opsional)"></textarea>
            </div>

            <!-- Bukti Dukung -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Bukti Dukung (Foto/Dokumen)</label>
                
                <!-- Camera Input -->
                <div id="input-kamera" class="hidden mb-2">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-camera text-2xl text-gray-400 mb-2"></i>
                            <p class="text-xs text-gray-500">Ambil Foto</p>
                        </div>
                        <input type="file" name="bukti_dukung" id="bukti_dukung_cam" accept="image/*" capture="environment" class="hidden file-input-preview">
                    </label>
                </div>

                <!-- File Input -->
                <div id="input-file" class="hidden mb-2">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                            <p class="text-xs text-gray-500">Pilih File</p>
                        </div>
                        <input type="file" name="bukti_dukung" id="bukti_dukung" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx" class="hidden file-input-preview">
                    </label>
                </div>

                <!-- Preview Container -->
                <div id="file-preview-container" class="hidden mt-3 bg-gray-50 rounded-xl border border-gray-200 p-3 relative">
                    <button type="button" id="remove-preview" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                    
                    <div class="text-center">
                        <!-- Image Preview -->
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow-sm">
                        </div>
                        <!-- File Info -->
                        <div id="file-info" class="hidden py-4">
                            <i id="file-icon" class="fas fa-file text-4xl text-gray-400 mb-2"></i>
                            <p id="file-name" class="text-sm font-medium text-gray-700 truncate px-4"></p>
                            <p id="file-size" class="text-xs text-gray-500"></p>
                        </div>
                    </div>
                </div>
                
                <p class="mt-2 text-xs text-gray-400">Max: 5MB. Format: JPG, PNG, PDF, Word, Excel</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2">Status Jurnal</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <input type="radio" name="status" id="status_published" value="published" class="peer hidden" checked>
                        <label for="status_published" class="flex flex-col items-center justify-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                            <i class="fas fa-check-circle text-lg mb-1"></i>
                            <span class="text-xs font-bold">Published</span>
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="status" id="status_draft" value="draft" class="peer hidden">
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
            <button type="submit" id="btn-submit" class="flex-1 py-3 px-4 bg-blue-600 text-white font-bold rounded-xl text-center text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-colors">
                Simpan Jurnal
            </button>
        </div>
    </form>
</div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 z-[70] hidden bg-white/80 backdrop-blur-sm flex items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-r-transparent mb-3"></div>
            <p class="text-gray-600 font-medium text-sm">Memproses...</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const rombelSelect = document.getElementById('rombel_id');
        const tanggalInput = document.getElementById('tanggal');
        const loadingOverlay = document.getElementById('loading-overlay');
        const submitBtn = document.getElementById('btn-submit');
        const jumlahPesertaInput = document.getElementById('jumlah_peserta');
        
        const warningDiv = document.getElementById('attendance-warning');
        const successDiv = document.getElementById('attendance-success');
        const summarySpan = document.getElementById('attendance-summary');
        const btnIsiAbsensi = document.getElementById('btn-isi-absensi');
        const warningText = document.getElementById('warning-text');

        // Attendance Check Function
        function checkAbsensi() {
            const rombelId = rombelSelect.value;
            const tanggal = tanggalInput.value;
            
            // Reset UI
            warningDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            submitBtn.disabled = false;

            if (rombelId && tanggal) {
                // Update link isi absensi
                btnIsiAbsensi.href = `<?= base_url('guru/absensi/create') ?>?rombel_id=${rombelId}&tanggal=${tanggal}`;
                
                // Show loading
                loadingOverlay.classList.remove('hidden');
                loadingOverlay.querySelector('p').textContent = 'Memeriksa Absensi...';
                submitBtn.disabled = true;

                fetch('<?= base_url("guru/jurnal/check-daily-attendance") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({
                        rombel_id: rombelId,
                        tanggal: tanggal
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loadingOverlay.classList.add('hidden');
                    
                    if (data.status === 'success' && data.data) {
                        // Absensi ADA
                        const hadir = data.data.total_hadir;
                        const total = data.data.total_siswa;
                        const percent = total > 0 ? Math.round((hadir / total) * 100) : 0;
                        
                        jumlahPesertaInput.value = hadir;
                        
                        successDiv.classList.remove('hidden');
                        summarySpan.innerHTML = `<strong>${hadir}</strong> dari <strong>${total}</strong> siswa hadir (${percent}%).`;
                        
                        submitBtn.disabled = false;
                    } else if (data.status === 'warning' && data.data) {
                         // Absensi TIDAK ADA tapi data siswa ada
                         const total = data.data.total_siswa;
                         
                         jumlahPesertaInput.value = 0;
                         
                         warningDiv.classList.remove('hidden');
                         warningText.innerHTML = `Anda belum mengisi absensi untuk kelas dan tanggal ini. Terdapat <strong>${total}</strong> siswa di kelas ini.`;
                         
                         submitBtn.disabled = true;
                    } else {
                        // Absensi TIDAK ADA (generic)
                        jumlahPesertaInput.value = 0;
                        warningDiv.classList.remove('hidden');
                        warningText.innerHTML = `Anda belum mengisi absensi untuk kelas dan tanggal ini. Data kehadiran diperlukan untuk membuat jurnal.`;
                        submitBtn.disabled = true;
                    }
                })
                .catch(err => {
                    console.error(err);
                    loadingOverlay.classList.add('hidden');
                    // Show warning on error to be safe
                    warningDiv.classList.remove('hidden');
                    submitBtn.disabled = true;
                });
            } else {
                jumlahPesertaInput.value = 0;
            }
        }

        // Event Listeners for Attendance
        rombelSelect.addEventListener('change', checkAbsensi);
        tanggalInput.addEventListener('change', checkAbsensi);
        
        // Initial Check
        if (rombelSelect.value && tanggalInput.value) {
            checkAbsensi();
        }

        // Camera/File Input Logic
        async function cekKamera() {
            const divCam = document.getElementById('input-kamera');
            const divFile = document.getElementById('input-file');
            const inputCam = document.getElementById('bukti_dukung_cam');
            const inputFile = document.getElementById('bukti_dukung');

            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const adaKamera = devices.some(d => d.kind === 'videoinput');
                
                if (adaKamera) {
                    divCam.classList.remove('hidden');
                    divFile.classList.add('hidden');
                    inputCam.disabled = false;
                    inputFile.disabled = true;
                } else {
                    divCam.classList.add('hidden');
                    divFile.classList.remove('hidden');
                    inputFile.disabled = false;
                    inputCam.disabled = true;
                }
            } catch (e) {
                console.error("Error checking camera:", e);
                divCam.classList.add('hidden');
                divFile.classList.remove('hidden');
                inputFile.disabled = false;
                inputCam.disabled = true;
            }
        }
        cekKamera();

        // File Preview Logic
        const fileInputs = document.querySelectorAll('.file-input-preview');
        const previewContainer = document.getElementById('file-preview-container');
        const imagePreview = document.getElementById('image-preview');
        const fileInfo = document.getElementById('file-info');
        const previewImg = document.getElementById('preview-img');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const fileIcon = document.getElementById('file-icon');
        const removePreviewBtn = document.getElementById('remove-preview');

        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    showFilePreview(file);
                } else {
                    hideFilePreview();
                }
            });
        });

        removePreviewBtn.addEventListener('click', function() {
            fileInputs.forEach(input => input.value = '');
            hideFilePreview();
        });

        function showFilePreview(file) {
            previewContainer.classList.remove('hidden');
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    fileInfo.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                
                imagePreview.classList.add('hidden');
                fileInfo.classList.remove('hidden');
                
                // Icon logic
                fileIcon.className = 'fas fa-file text-4xl mb-2';
                if (file.type.includes('pdf')) {
                    fileIcon.classList.add('text-red-500');
                    fileIcon.classList.remove('text-gray-400');
                } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                    fileIcon.classList.add('text-blue-500');
                    fileIcon.classList.remove('text-gray-400');
                } else if (file.type.includes('excel') || file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
                    fileIcon.classList.add('text-green-500');
                    fileIcon.classList.remove('text-gray-400');
                } else {
                    fileIcon.classList.add('text-gray-400');
                }
            }
        }

        function hideFilePreview() {
            previewContainer.classList.add('hidden');
            imagePreview.classList.add('hidden');
            fileInfo.classList.add('hidden');
            previewImg.src = '';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>
<?= $this->endSection() ?>