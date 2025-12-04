<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pindah-kelas.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="pindah-kelas-page container-fluid">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="m-0 text-dark font-weight-bold" style="font-family: 'Outfit', sans-serif; font-size: 1.75rem;">Pindah Kelas</h1>
            <p class="text-muted mb-0">Kelola perpindahan siswa antar kelas dengan mudah.</p>
        </div>

    </div>

    <div class="row">
        <!-- Left Panel (Source Class) -->
        <div class="col-md-5">
            <div class="custom-card h-100">
                <div class="custom-card-header">
                    <div class="d-flex align-items-center">
                        <div class="step-indicator">1</div>
                        <h3 class="custom-card-title">Kelas Asal</h3>
                    </div>
                </div>
                <div class="custom-card-body">
                    <!-- Filters -->
                    <div class="custom-form-group mb-4">
                        <label for="source_tingkat">Tingkat</label>
                        <select class="custom-form-control" id="source_tingkat">
                            <option value="">-- Pilih Tingkat --</option>
                            <?php foreach ($tingkatList as $tingkat): ?>
                                <option value="<?= $tingkat['tingkat'] ?>"><?= $tingkat['tingkat'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="custom-form-group mb-4">
                        <label for="source_kelas">Kelas</label>
                        <select class="custom-form-control" id="source_kelas" disabled>
                            <option value="">-- Pilih Kelas --</option>
                        </select>
                    </div>

                    <!-- Table Section -->
                    <div class="custom-table-container">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                            <div class="font-weight-bold text-secondary small text-uppercase" style="letter-spacing: 0.05em;">Daftar Siswa</div>
                            <div class="custom-badge badge-primary-soft"><span id="source-count">0</span> siswa</div>
                        </div>

                        <div class="table-responsive custom-scrollbar" style="max-height: 450px; overflow-y: auto;">
                            <table class="custom-table">
                                <thead class="sticky-top bg-white" style="z-index: 10;">
                                    <tr>
                                        <th width="50" class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select-all-source">
                                                <label class="custom-control-label" for="select-all-source"></label>
                                            </div>
                                        </th>
                                        <th>Siswa</th>
                                        <th width="50" class="text-center">L/P</th>
                                    </tr>
                                </thead>
                                <tbody id="source-siswa-table">
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                            </div>
                                            <h6 class="text-dark font-weight-bold mb-1">Pilih Kelas Asal</h6>
                                            <p class="text-muted small mb-0">Silahkan pilih tingkat dan kelas terlebih dahulu</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Action Area (Desktop) -->
        <div class="col-md-2 d-flex flex-column align-items-center justify-content-center py-3">
            <div class="d-none d-md-flex flex-column align-items-center text-center">
                <div class="bg-white p-3 rounded-circle shadow-md mb-3 text-primary d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border: 1px solid #E2E8F0;">
                    <i class="fas fa-arrow-right fa-lg"></i>
                </div>
                <span class="custom-badge badge-info-soft">Pindahkan Ke</span>
            </div>
            <div class="d-md-none text-center my-4">
                <div class="bg-white p-3 rounded-circle shadow-md text-primary d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border: 1px solid #E2E8F0;">
                    <i class="fas fa-arrow-down fa-lg"></i>
                </div>
            </div>
        </div>

        <!-- Right Panel (Target Class) -->
        <div class="col-md-5">
            <div class="custom-card h-100">
                <div class="custom-card-header">
                    <div class="d-flex align-items-center">
                        <div class="step-indicator">2</div>
                        <h3 class="custom-card-title">Kelas Tujuan</h3>
                    </div>
                </div>
                <div class="custom-card-body">
                    <!-- Filters -->
                    <div class="custom-form-group mb-4">
                        <label for="target_status_tingkat">Status Tingkat</label>
                        <select class="custom-form-control" id="target_status_tingkat">
                            <option value="beda" selected>Tingkat Beda</option>
                            <option value="sama">Tingkat Sama</option>
                        </select>
                    </div>
                    <div class="target-tingkat-container mb-4">
                        <div class="custom-form-group">
                            <label for="target_tingkat">Tingkat Tujuan</label>
                            <select class="custom-form-control" id="target_tingkat">
                                <option value="">-- Pilih Tingkat --</option>
                                <?php foreach ($tingkatList as $tingkat): ?>
                                    <option value="<?= $tingkat['tingkat'] ?>"><?= $tingkat['tingkat'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="target-kelas-container mb-4">
                        <div class="custom-form-group">
                            <label for="target_kelas">Kelas Tujuan</label>
                            <select class="custom-form-control" id="target_kelas" disabled>
                                <option value="">-- Pilih Kelas --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="custom-table-container">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                            <div class="font-weight-bold text-secondary small text-uppercase" style="letter-spacing: 0.05em;">Daftar Siswa</div>
                            <div class="custom-badge badge-primary-soft"><span id="target-count">0</span> siswa</div>
                        </div>

                        <div class="table-responsive custom-scrollbar" style="max-height: 450px; overflow-y: auto;">
                            <table class="custom-table">
                                <thead class="sticky-top bg-white" style="z-index: 10;">
                                    <tr>
                                        <th width="50" class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select-all-target">
                                                <label class="custom-control-label" for="select-all-target"></label>
                                            </div>
                                        </th>
                                        <th>Siswa</th>
                                        <th width="50" class="text-center">L/P</th>
                                    </tr>
                                </thead>
                                <tbody id="target-siswa-table">
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-users fa-2x"></i>
                                            </div>
                                            <h6 class="text-dark font-weight-bold mb-1">Pilih Kelas Tujuan</h6>
                                            <p class="text-muted small mb-0">Daftar siswa akan muncul di sini</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Bar -->
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="custom-card border-0 shadow-lg bg-white">
                <div class="custom-card-body p-4 text-center">
                    <button id="move-students-btn" class="custom-btn custom-btn-primary btn-lg px-5" disabled>
                        <i class="fas fa-exchange-alt mr-2"></i> Proses Perpindahan Siswa
                    </button>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="fas fa-info-circle mr-1 text-info"></i>
                        Pastikan data yang dipilih sudah benar sebelum memproses.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Helper function to toggle row highlight
    function toggleRowHighlight(checkbox) {
        const row = checkbox.closest('tr');
        if (checkbox.checked) {
            row.classList.add('selected-row');
        } else {
            row.classList.remove('selected-row');
        }
    }

    // Fungsi untuk mengambil rombel berdasarkan tingkat
    async function getRombelByTingkat(tingkat, targetElement) {
        if (!tingkat) {
            targetElement.innerHTML = '<option value="">-- Pilih Kelas --</option>';
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

            let options = '<option value="">-- Pilih Kelas --</option>';
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
    async function getSiswaByRombel(rombelId, targetTable, countElementId) {
        if (!rombelId) {
            targetTable.innerHTML = `
            <tr>
                <td colspan="3" class="text-center py-5">
                    <div class="empty-state-icon"><i class="fas fa-inbox fa-2x"></i></div>
                    <h6 class="text-dark font-weight-bold mb-1">Tidak Ada Data</h6>
                    <p class="text-muted small mb-0">Silahkan pilih kelas lain</p>
                </td>
            </tr>`;
            if (countElementId) document.getElementById(countElementId).innerText = '0';
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
                targetTable.innerHTML = '<tr><td colspan="3" class="text-center text-danger py-4">Error loading data</td></tr>';
                return;
            }

            if (countElementId) document.getElementById(countElementId).innerText = data.length;

            if (data.length === 0) {
                targetTable.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center py-5">
                        <div class="empty-state-icon"><i class="fas fa-user-slash fa-2x"></i></div>
                        <h6 class="text-dark font-weight-bold mb-1">Kelas Kosong</h6>
                        <p class="text-muted small mb-0">Belum ada siswa di kelas ini</p>
                    </td>
                </tr>`;
                return;
            }

            let rows = '';
            data.forEach(siswa => {
                const genderBadge = siswa.jenis_kelamin === 'L' ?
                    '<span class="custom-badge badge-info-soft" title="Laki-laki">L</span>' :
                    '<span class="custom-badge badge-warning-soft" title="Perempuan">P</span>';

                rows += `
                <tr>
                    <td class="text-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input student-checkbox" id="cb-${siswa.id}" data-id="${siswa.id}">
                            <label class="custom-control-label" for="cb-${siswa.id}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">${siswa.nama}</span>
                            <span class="text-muted small" style="font-family: monospace;">${siswa.nisn || '-'}</span>
                        </div>
                    </td>
                    <td class="text-center">${genderBadge}</td>
                </tr>
            `;
            });

            targetTable.innerHTML = rows;

            // Add event listeners to new checkboxes
            targetTable.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    toggleRowHighlight(this);
                });
            });

        } catch (error) {
            console.error('Error:', error);
            targetTable.innerHTML = '<tr><td colspan="3" class="text-center text-danger py-4">Error loading data</td></tr>';
        }
    }

    // Event listener untuk tingkat kelas asal
    document.getElementById('source_tingkat').addEventListener('change', function() {
        const tingkat = this.value;
        const kelasSelect = document.getElementById('source_kelas');
        getRombelByTingkat(tingkat, kelasSelect);

        // Kosongkan tabel siswa
        document.getElementById('source-siswa-table').innerHTML = `
        <tr>
            <td colspan="3" class="text-center py-5">
                <div class="empty-state-icon"><i class="fas fa-chalkboard-teacher fa-2x"></i></div>
                <h6 class="text-dark font-weight-bold mb-1">Pilih Kelas Asal</h6>
                <p class="text-muted small mb-0">Silahkan pilih kelas untuk menampilkan siswa</p>
            </td>
        </tr>`;
        document.getElementById('source-count').innerText = '0';
    });

    // Event listener untuk rombel kelas asal
    document.getElementById('source_kelas').addEventListener('change', function() {
        const rombelId = this.value;
        const tableBody = document.getElementById('source-siswa-table');
        getSiswaByRombel(rombelId, tableBody, 'source-count');

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
        kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
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
        document.getElementById('target-siswa-table').innerHTML = `
        <tr>
            <td colspan="3" class="text-center py-5">
                <div class="empty-state-icon"><i class="fas fa-users fa-2x"></i></div>
                <h6 class="text-dark font-weight-bold mb-1">Pilih Kelas Tujuan</h6>
                <p class="text-muted small mb-0">Daftar siswa akan muncul di sini</p>
            </td>
        </tr>`;
        document.getElementById('target-count').innerText = '0';
    });

    // Event listener untuk tingkat kelas tujuan
    document.getElementById('target_tingkat').addEventListener('change', function() {
        const tingkat = this.value;
        const kelasSelect = document.getElementById('target_kelas');
        getRombelByTingkat(tingkat, kelasSelect);

        // Kosongkan tabel siswa
        document.getElementById('target-siswa-table').innerHTML = `
        <tr>
            <td colspan="3" class="text-center py-5">
                <div class="empty-state-icon"><i class="fas fa-users fa-2x"></i></div>
                <h6 class="text-dark font-weight-bold mb-1">Pilih Kelas Tujuan</h6>
                <p class="text-muted small mb-0">Daftar siswa akan muncul di sini</p>
            </td>
        </tr>`;
        document.getElementById('target-count').innerText = '0';
    });

    // Event listener untuk rombel kelas tujuan
    document.getElementById('target_kelas').addEventListener('change', function() {
        const rombelId = this.value;
        const tableBody = document.getElementById('target-siswa-table');
        getSiswaByRombel(rombelId, tableBody, 'target-count');

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
                getSiswaByRombel(sourceRombelId, document.getElementById('source-siswa-table'), 'source-count');
                getSiswaByRombel(targetRombelId, document.getElementById('target-siswa-table'), 'target-count');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memindahkan siswa.');
        }
    });

    // Event listener untuk select all checkbox
    document.getElementById('select-all-source').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('#source-siswa-table .student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            toggleRowHighlight(cb);
        });
    });

    document.getElementById('select-all-target').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('#target-siswa-table .student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            toggleRowHighlight(cb);
        });
    });
</script>
<?= $this->endSection() ?>