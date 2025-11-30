<?= $this->include('templates/header') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Jurnal Mengajar</h2>
        <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Jurnal Mengajar</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('guru/jurnal/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Mengajar</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select class="form-control" id="kelas_id" name="kelas_id" required>
                                        <option value="">Pilih Kelas</option>
                                        <?php foreach ($kelas as $k): ?>
                                            <option value="<?= $k['id'] ?>" <?= old('kelas_id') == $k['id'] ? 'selected' : '' ?>>
                                                <?= $k['nama_kelas'] ?> (<?= $k['kode_kelas'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mapel_id" class="form-label">Mata Pelajaran</label>
                                    <select class="form-control" id="mapel_id" name="mapel_id" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        <?php foreach ($mapel as $m): ?>
                                            <option value="<?= $m['id'] ?>" <?= old('mapel_id') == $m['id'] ? 'selected' : '' ?>>
                                                <?= $m['nama_mapel'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jam_ke" class="form-label">Jam Ke</label>
                                    <select class="form-control" id="jam_ke" name="jam_ke" required>
                                        <option value="">Pilih Jam Ke</option>
                                        <!-- Options akan diisi dengan JavaScript -->
                                        <!-- Fallback statis 1-10 jika tidak ada data dari database -->
                                        <?php for ($i = 1; $i <= 10; $i++): ?>
                                            <option value="<?= $i ?>" <?= old('jam_ke') == $i ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <div id="jam-info" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="materi" class="form-label">Materi Pembelajaran</label>
                            <textarea class="form-control" id="materi" name="materi" rows="3" 
                                      placeholder="Masukkan materi pembelajaran" required><?= old('materi') ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_jam" class="form-label">Jumlah JP</label>
                                    <input type="number" class="form-control" id="jumlah_jam" name="jumlah_jam" 
                                           value="<?= old('jumlah_jam', 1) ?>" min="1" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                    <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" 
                                           value="<?= old('jumlah_peserta', 25) ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" 
                                      placeholder="Masukkan keterangan tambahan"><?= old('keterangan') ?></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="bukti_dukung" class="form-label">Bukti Dukung</label>
                            <input type="file" class="form-control" id="bukti_dukung" name="bukti_dukung" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Format: JPG, JPEG, PNG, PDF (Maks. 2MB)</div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="draft" <?= old('status') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= old('status') == 'published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Jurnal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jurnal Terakhir</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_jurnal)): ?>
                        <?php foreach ($recent_jurnal as $jurnal): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong><?= date('d M Y', strtotime($jurnal['tanggal'])) ?></strong>
                                    <span class="badge bg-<?= $jurnal['status'] == 'published' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($jurnal['status']) ?>
                                    </span>
                                </div>
                                <div class="small text-muted">
                                    <?= $jurnal['nama_kelas'] ?> | <?= $jurnal['nama_mapel'] ?>
                                </div>
                                <div class="small">
                                    <?= substr($jurnal['materi'], 0, 50) ?><?= strlen($jurnal['materi']) > 50 ? '...' : '' ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Belum ada jurnal terakhir.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.getElementById('kelas_id');
    const tanggalInput = document.getElementById('tanggal');
    const jamSelect = document.getElementById('jam_ke');
    const jamInfo = document.getElementById('jam-info');
    const jumlahJamInput = document.getElementById('jumlah_jam');
    
    // Fungsi untuk mengambil jam yang tersedia
    function getAvailableHours() {
        const kelasId = kelasSelect.value;
        const tanggal = tanggalInput.value;
        
        // Reset info
        jamInfo.innerHTML = '';
        
        if (!kelasId || !tanggal) {
            return;
        }
        
        // Kirim request AJAX
        fetch('<?= base_url('guru/jurnal/get-available-hours') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: new URLSearchParams({
                'kelas_id': kelasId,
                'tanggal': tanggal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Bersihkan opsi yang ada
                jamSelect.innerHTML = '<option value="">Pilih Jam Ke</option>';
                
                // Tambahkan opsi yang tersedia
                data.available_hours.forEach(hour => {
                    const option = document.createElement('option');
                    option.value = hour;
                    option.textContent = hour;
                    jamSelect.appendChild(option);
                });
                
                // Set nilai berikutnya sebagai default jika ada
                if (data.next_hour) {
                    jamSelect.value = data.next_hour;
                }
                
                // Tampilkan info jam terpakai
                if (data.used_hours.length > 0) {
                    jamInfo.innerHTML = `
                        <small class="text-muted">
                            Jam terpakai: ${data.used_hours.join(', ')}
                        </small>
                    `;
                } else {
                    jamInfo.innerHTML = `
                        <small class="text-muted">
                            Belum ada jam terpakai
                        </small>
                    `;
                }
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Event listener
    kelasSelect.addEventListener('change', getAvailableHours);
    tanggalInput.addEventListener('change', getAvailableHours);
    
    // Panggil saat halaman dimuat untuk mengecek nilai yang sudah ada
    if (kelasSelect.value && tanggalInput.value) {
        getAvailableHours();
    }
});
</script>

<?= $this->include('templates/footer') ?>