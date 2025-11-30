<?= $this->include('templates/header') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Jurnal Mengajar</h2>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Edit Jurnal</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('guru/jurnal/update/' . $jurnal['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Mengajar</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= old('tanggal', $jurnal['tanggal']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="rombel_id" class="form-label">Kelas</label>
                                    <select class="form-control" id="rombel_id" name="rombel_id" required>
                                        <option value="">Pilih Kelas</option>
                                        <?php foreach ($rombel as $r): ?>
                                            <option value="<?= $r['id'] ?>" <?= old('rombel_id', $jurnal['rombel_id']) == $r['id'] ? 'selected' : '' ?>>
                                                <?= $r['nama_rombel'] ?> (<?= $r['kode_rombel'] ?>)
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
                                            <option value="<?= $m['id'] ?>" <?= old('mapel_id', $jurnal['mapel_id']) == $m['id'] ? 'selected' : '' ?>>
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
                                            <option value="<?= $i ?>" <?= old('jam_ke', $jurnal['jam_ke']) == $i ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <div id="jam-info" class="mt-2"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="materi" class="form-label">Materi Pembelajaran</label>
                            <textarea class="form-control" id="materi" name="materi" rows="3" 
                                      placeholder="Masukkan materi pembelajaran" required><?= old('materi', $jurnal['materi']) ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_jam" class="form-label">Jumlah JP</label>
                                    <input type="number" class="form-control" id="jumlah_jam" name="jumlah_jam" 
                                           value="<?= old('jumlah_jam', $jurnal['jumlah_jam']) ?>" min="1" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                    <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" 
                                           value="<?= old('jumlah_peserta', $jurnal['jumlah_peserta']) ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" 
                                      placeholder="Masukkan keterangan tambahan"><?= old('keterangan', $jurnal['keterangan']) ?></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="bukti_dukung" class="form-label">Bukti Dukung (Kosongkan jika tidak ingin mengganti)</label>
                            <input type="file" class="form-control" id="bukti_dukung" name="bukti_dukung" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Format: JPG, JPEG, PNG, PDF (Maks. 2MB)</div>
                            
                            <?php if (!empty($jurnal['bukti_dukung'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">File saat ini: <?= $jurnal['bukti_dukung'] ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="draft" <?= old('status', $jurnal['status']) == 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= old('status', $jurnal['status']) == 'published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Jurnal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Jurnal</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Kelas</strong></td>
                            <td><?= $jurnal['nama_kelas'] ?> (<?= $jurnal['kode_kelas'] ?>)</td>
                        </tr>
                        <tr>
                            <td><strong>Mata Pelajaran</strong></td>
                            <td><?= $jurnal['nama_mapel'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jam Ke</strong></td>
                            <td><?= $jurnal['jam_ke'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah JP</strong></td>
                            <td><?= $jurnal['jumlah_jam'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Peserta</strong></td>
                            <td><?= $jurnal['jumlah_peserta'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <span class="badge bg-<?= $jurnal['status'] == 'published' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($jurnal['status']) ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                    
                    <?php if (!empty($jurnal['bukti_dukung'])): ?>
                        <div class="mt-3">
                            <a href="<?= base_url('uploads/' . $jurnal['bukti_dukung']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-download"></i> Lihat Bukti Dukung
                            </a>
                        </div>
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
    
    // Simpan nilai jam_ke yang sudah ada saat halaman dimuat
    const existingJamKe = jamSelect.value;
    
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
                // Simpan nilai saat ini sebelum menghapus opsi
                const currentValue = jamSelect.value;
                
                // Bersihkan opsi yang ada
                jamSelect.innerHTML = '<option value="">Pilih Jam Ke</option>';
                
                // Tambahkan opsi yang tersedia
                data.available_hours.forEach(hour => {
                    const option = document.createElement('option');
                    option.value = hour;
                    option.textContent = hour;
                    jamSelect.appendChild(option);
                });
                
                // Kembalikan nilai yang sudah ada (jika tersedia dalam opsi baru)
                // Jika tidak, gunakan nilai existingJamKe (nilai asli saat load)
                if (data.available_hours.includes(parseInt(currentValue))) {
                    jamSelect.value = currentValue;
                } else if (data.available_hours.includes(parseInt(existingJamKe))) {
                    jamSelect.value = existingJamKe;
                } else if (data.next_hour) {
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
    } else if (existingJamKe) {
        // Jika tidak ada kelas/tanggal tapi ada nilai jam_ke, tetap tampilkan
        jamSelect.value = existingJamKe;
    }
});
</script>

<?= $this->include('templates/footer') ?>