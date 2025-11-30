<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pindah Kelas</h3>
                </div>
                <div class="card-body">
                    <p>Menu ini digunakan untuk memindahkan siswa ke kelas lain.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Left Panel (Source Class) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelas Asal</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="source_tingkat">Tingkat</label>
                                <select class="form-control" id="source_tingkat">
                                    <option value="">--Pilih--</option>
                                    <?php foreach ($tingkatList as $tingkat): ?>
                                        <option value="<?= $tingkat['tingkat'] ?>"><?= $tingkat['tingkat'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="source_kelas">Kelas</label>
                                <select class="form-control" id="source_kelas" disabled>
                                    <option value="">--Pilih--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                Show 
                                <select class="form-control form-control-sm d-inline w-auto">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select> 
                                entries
                            </div>
                            <div>
                                Search: <input type="search" class="form-control form-control-sm d-inline w-auto">
                            </div>
                        </div>
                        
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-source"></th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>L/P</th>
                                </tr>
                            </thead>
                            <tbody id="source-siswa-table">
                                <tr>
                                    <td colspan="4" class="text-center">No data available in table</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>Showing 0 to 0 of 0 entries</div>
                            <div>
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatikan:</h5>
                        <ul class="mb-0">
                            <li>Silahkan pilih kelas asal</li>
                            <li>Silahkan pilih kelas tujuan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel (Target Class) -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelas Tujuan</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_status_tingkat">Status Tingkat</label>
                                <select class="form-control" id="target_status_tingkat">
                                    <option value="beda" selected>Tingkat Beda</option>
                                    <option value="sama">Tingkat Sama</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 target-tingkat-container" style="display: none;">
                            <div class="form-group">
                                <label for="target_tingkat">Tingkat Tujuan</label>
                                <select class="form-control" id="target_tingkat">
                                    <option value="">--Pilih--</option>
                                    <?php foreach ($tingkatList as $tingkat): ?>
                                        <option value="<?= $tingkat['tingkat'] ?>"><?= $tingkat['tingkat'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 target-kelas-container">
                            <div class="form-group">
                                <label for="target_kelas">Kelas</label>
                                <select class="form-control" id="target_kelas" disabled>
                                    <option value="">--Pilih--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                Show 
                                <select class="form-control form-control-sm d-inline w-auto">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select> 
                                entries
                            </div>
                            <div>
                                Search: <input type="search" class="form-control form-control-sm d-inline w-auto">
                            </div>
                        </div>
                        
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all-target"></th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>L/P</th>
                                </tr>
                            </thead>
                            <tbody id="target-siswa-table">
                                <tr>
                                    <td colspan="4" class="text-center">No data available in table</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>Showing 0 to 0 of 0 entries</div>
                            <div>
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button id="move-students-btn" class="btn btn-primary" disabled>
                        <i class="fas fa-exchange-alt"></i> Pindahkan Siswa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk mengambil rombel berdasarkan tingkat
async function getRombelByTingkat(tingkat, targetElement) {
    if (!tingkat) {
        targetElement.innerHTML = '<option value="">--Pilih--</option>';
        targetElement.disabled = true;
        return;
    }
    
    try {
        const response = await fetch('<?= base_url("admin/pindah-kelas/get-rombel-by-tingkat") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: 'tingkat=' + encodeURIComponent(tingkat)
        });
        
        const data = await response.json();
        
        if (data.error) {
            console.error('Error:', data.error);
            return;
        }
        
        let options = '<option value="">--Pilih--</option>';
        data.forEach(rombel => {
            options += `<option value="${rombel.id}">${rombel.kode_rombel} - ${rombel.nama_rombel}</option>`;
        });
        
        targetElement.innerHTML = options;
        targetElement.disabled = false;
    } catch (error) {
        console.error('Error:', error);
    }
}

// Fungsi untuk mengambil siswa berdasarkan rombel
async function getSiswaByRombel(rombelId, targetTable) {
    if (!rombelId) {
        targetTable.innerHTML = '<tr><td colspan="4" class="text-center">No data available in table</td></tr>';
        return;
    }
    
    try {
        const response = await fetch('<?= base_url("admin/pindah-kelas/get-siswa-by-rombel") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: 'rombel_id=' + encodeURIComponent(rombelId)
        });
        
        const data = await response.json();
        
        if (data.error) {
            console.error('Error:', data.error);
            targetTable.innerHTML = '<tr><td colspan="4" class="text-center">Error loading data</td></tr>';
            return;
        }
        
        if (data.length === 0) {
            targetTable.innerHTML = '<tr><td colspan="4" class="text-center">No data available in table</td></tr>';
            return;
        }
        
        let rows = '';
        data.forEach(siswa => {
            const gender = siswa.jenis_kelamin === 'L' ? 'L' : 'P';
            rows += `
                <tr>
                    <td><input type="checkbox" class="student-checkbox" data-id="${siswa.id}"></td>
                    <td>${siswa.nisn || '-'}</td>
                    <td>${siswa.nama}</td>
                    <td>${gender}</td>
                </tr>
            `;
        });
        
        targetTable.innerHTML = rows;
    } catch (error) {
        console.error('Error:', error);
        targetTable.innerHTML = '<tr><td colspan="4" class="text-center">Error loading data</td></tr>';
    }
}

// Event listener untuk tingkat kelas asal
document.getElementById('source_tingkat').addEventListener('change', function() {
    const tingkat = this.value;
    const kelasSelect = document.getElementById('source_kelas');
    getRombelByTingkat(tingkat, kelasSelect);
    
    // Kosongkan tabel siswa
    document.getElementById('source-siswa-table').innerHTML = '<tr><td colspan="4" class="text-center">No data available in table</td></tr>';
});

// Event listener untuk rombel kelas asal
document.getElementById('source_kelas').addEventListener('change', function() {
    const rombelId = this.value;
    const tableBody = document.getElementById('source-siswa-table');
    getSiswaByRombel(rombelId, tableBody);
    
    // Cek apakah kelas asal dan tujuan sudah dipilih
    checkMoveButtonStatus();
});

// Event listener untuk status tingkat kelas tujuan
document.getElementById('target_status_tingkat').addEventListener('change', function() {
    const status = this.value;
    const tingkatSelect = document.getElementById('source_tingkat');
    const targetTingkatContainer = document.querySelector('.target-tingkat-container');
    const targetKelasContainer = document.querySelector('.target-kelas-container');
    const kelasSelect = document.getElementById('target_kelas');
    const targetTingkatSelect = document.getElementById('target_tingkat');
    
    // Reset pilihan kelas tujuan
    kelasSelect.innerHTML = '<option value="">--Pilih--</option>';
    kelasSelect.disabled = true;
    
    if (status === 'sama') {
        // Sembunyikan dropdown tingkat tujuan
        targetTingkatContainer.style.display = 'none';
        
        // Jika ada tingkat asal, ambil rombel dengan tingkat yang sama
        if (tingkatSelect.value) {
            getRombelByTingkat(tingkatSelect.value, kelasSelect);
        }
    } else if (status === 'beda') {
        // Tampilkan dropdown tingkat tujuan
        targetTingkatContainer.style.display = 'block';
        
        // Reset pilihan tingkat tujuan
        targetTingkatSelect.value = '';
    }
    
    // Kosongkan tabel siswa
    document.getElementById('target-siswa-table').innerHTML = '<tr><td colspan="4" class="text-center">No data available in table</td></tr>';
});

// Event listener untuk tingkat kelas tujuan
document.getElementById('target_tingkat').addEventListener('change', function() {
    const tingkat = this.value;
    const kelasSelect = document.getElementById('target_kelas');
    getRombelByTingkat(tingkat, kelasSelect);
    
    // Kosongkan tabel siswa
    document.getElementById('target-siswa-table').innerHTML = '<tr><td colspan="4" class="text-center">No data available in table</td></tr>';
});

// Event listener untuk rombel kelas tujuan
document.getElementById('target_kelas').addEventListener('change', function() {
    const rombelId = this.value;
    const tableBody = document.getElementById('target-siswa-table');
    getSiswaByRombel(rombelId, tableBody);
    
    // Cek apakah kelas asal dan tujuan sudah dipilih
    checkMoveButtonStatus();
});

// Fungsi untuk mengecek status tombol pindah
function checkMoveButtonStatus() {
    const sourceKelas = document.getElementById('source_kelas').value;
    const targetKelas = document.getElementById('target_kelas').value;
    const moveButton = document.getElementById('move-students-btn');
    
    moveButton.disabled = !(sourceKelas && targetKelas);
}

// Event listener untuk tombol pindah siswa
document.getElementById('move-students-btn').addEventListener('click', async function() {
    const sourceRombelId = document.getElementById('source_kelas').value;
    const targetRombelId = document.getElementById('target_kelas').value;
    
    // Ambil ID siswa yang dipilih
    const selectedCheckboxes = document.querySelectorAll('#source-siswa-table .student-checkbox:checked');
    const studentIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.id);
    
    if (studentIds.length === 0) {
        alert('Silakan pilih minimal satu siswa untuk dipindahkan.');
        return;
    }
    
    if (!confirm(`Apakah Anda yakin ingin memindahkan ${studentIds.length} siswa ke kelas tujuan?`)) {
        return;
    }
    
    try {
        const response = await fetch('<?= base_url("admin/pindah-kelas/move-students") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: `source_rombel_id=${encodeURIComponent(sourceRombelId)}&target_rombel_id=${encodeURIComponent(targetRombelId)}&student_ids[]=${studentIds.join('&student_ids[]=')}`
        });
        
        const data = await response.json();
        
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }
        
        if (data.success) {
            alert(data.message);
            
            // Refresh data di kedua tabel
            getSiswaByRombel(sourceRombelId, document.getElementById('source-siswa-table'));
            getSiswaByRombel(targetRombelId, document.getElementById('target-siswa-table'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memindahkan siswa.');
    }
});

// Event listener untuk select all checkbox
document.getElementById('select-all-source').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('#source-siswa-table .student-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

document.getElementById('select-all-target').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('#target-siswa-table .student-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
<?= $this->endSection() ?>