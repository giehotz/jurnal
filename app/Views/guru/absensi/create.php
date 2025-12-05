<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>

<style>
    /* Kustomisasi CSS untuk Mobile View */
    @media (max-width: 768px) {

        /* Sembunyikan header tabel di mobile */
        .table-mobile thead {
            display: none;
        }

        /* Ubah baris tabel menjadi Card */
        .table-mobile tbody tr {
            display: block;
            margin-bottom: 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 15px;
        }

        /* Hilangkan border default tabel */
        .table-mobile td {
            display: block;
            border: none;
            padding: 5px 0;
            text-align: left;
            width: 100%;
        }

        /* Styling khusus untuk Nama Siswa di mobile agar menonjol */
        .student-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px !important;
            margin-bottom: 8px;
        }

        /* Penyesuaian tombol absensi agar mudah disentuh */
        .attendance-options .btn-group {
            display: flex;
            width: 100%;
        }

        .attendance-options .btn {
            flex: 1;
            padding: 8px 2px;
            /* Padding lebih kecil di sisi */
            font-size: 0.9rem;
        }
    }

    /* Styling umum untuk tombol radio yang terlihat seperti tombol biasa */
    .btn-check-hidden {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Input Absensi Harian</h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/absensi/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <div class="input-group date" id="tanggal_picker" data-target-input="nearest">
                                    <input type="text" name="tanggal_display" class="form-control form-control-lg datetimepicker-input" data-target="#tanggal_picker" value="<?= date('d/m/Y') ?>" required />
                                    <div class="input-group-append" data-target="#tanggal_picker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                    </div>
                                </div>
                                <input type="hidden" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="rombel_id">Kelas</label>
                                <select name="rombel_id" id="rombel_id" class="form-control form-control-lg" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($rombel as $r): ?>
                                        <option value="<?= $r['id'] ?>"><?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="mapel_id">Mata Pelajaran</label>
                                <select name="mapel_id" id="mapel_id" class="form-control form-control-lg" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    <?php foreach ($mapel as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= $m['kode_mapel'] ?> - <?= $m['nama_mapel'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="jam_ke">Jam Ke</label>
                                <input type="number" name="jam_ke" id="jam_ke" class="form-control form-control-lg" min="1" max="12" placeholder="Start" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="jumlah_jam">Jumlah Jam</label>
                                <input type="number" name="jumlah_jam" id="jumlah_jam" class="form-control form-control-lg" min="1" max="12" placeholder="Durasi" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div id="absensi-section" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Daftar Siswa</h5>
                                    <small class="text-muted d-block d-md-none">Scroll ke bawah untuk simpan</small>
                                </div>

                                <!-- Tambahkan class table-mobile untuk trigger CSS responsif -->
                                <table class="table table-hover table-mobile">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 10%">NIS</th>
                                            <th style="width: 30%">Nama Siswa</th>
                                            <th style="width: 30%" class="text-center">Status Kehadiran</th>
                                            <th style="width: 25%">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="absensi-body">
                                        <!-- Data siswa akan diisi dengan AJAX -->
                                    </tbody>
                                </table>

                                <div class="form-group mt-4 pb-5 pb-md-0">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-md-inline">
                                        <i class="fas fa-save mr-1"></i> Simpan Absensi
                                    </button>
                                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary btn-lg btn-block btn-md-inline mt-2 mt-md-0">Batal</a>
                                </div>
                            </div>

                            <div id="loading-message" style="display: none;" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Memuat data siswa...</p>
                            </div>

                            <div id="no-siswa-message" style="display: none;" class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i> Tidak ada siswa dalam kelas ini.
                            </div>

                            <div id="error-message" style="display: none;" class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan saat mengambil data siswa.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                // Initialize DateTimePicker for this page
                $(document).ready(function() {
                    // Initialize date picker with specific configuration
                    $('#tanggal_picker').datetimepicker({
                        format: 'DD/MM/YYYY',
                        locale: 'id',
                        allowInputToggle: true,
                        useCurrent: false
                    });

                    // Handle date change event
                    $('#tanggal_picker').on('change.datetimepicker', function(e) {
                        if (e.date) {
                            // Update hidden input with Y-m-d format
                            const selectedDate = e.date.format('YYYY-MM-DD');
                            document.getElementById('tanggal').value = selectedDate;
                            console.log('Date selected:', selectedDate);
                        }
                    });

                    // Fallback: Also handle direct input changes
                    document.querySelector('[data-target="#tanggal_picker"]').addEventListener('change', function() {
                        const displayValue = document.querySelector('[name="tanggal_display"]').value;
                        if (displayValue) {
                            try {
                                const parts = displayValue.split('/');
                                if (parts.length === 3) {
                                    const tanggal = parts[2] + '-' + parts[1] + '-' + parts[0];
                                    document.getElementById('tanggal').value = tanggal;
                                    console.log('Date updated from input:', tanggal);
                                }
                            } catch(e) {
                                console.error('Error parsing date:', e);
                            }
                        }
                    });
                });

                document.getElementById('rombel_id').addEventListener('change', function() {
                    const rombelId = this.value;
                    const absensiSection = document.getElementById('absensi-section');
                    const absensiBody = document.getElementById('absensi-body');
                    const noSiswaMessage = document.getElementById('no-siswa-message');
                    const errorMessage = document.getElementById('error-message');
                    const loadingMessage = document.getElementById('loading-message');

                    // Reset UI
                    absensiBody.innerHTML = '';
                    absensiSection.style.display = 'none';
                    noSiswaMessage.style.display = 'none';
                    errorMessage.style.display = 'none';

                    if (rombelId) {
                        loadingMessage.style.display = 'block';

                        // Fetch siswa berdasarkan rombel
                        fetch('<?= base_url("guru/absensi/get-siswa-by-rombel") ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                                },
                                body: 'rombel_id=' + rombelId
                            })
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                loadingMessage.style.display = 'none';

                                if (data.error) {
                                    errorMessage.style.display = 'block';
                                    errorMessage.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.error;
                                    console.error('Error dari server:', data.error);
                                    return;
                                }

                                if (data.length > 0) {
                                    absensiSection.style.display = 'block';

                                    data.forEach((siswa, index) => {
                                        // UX Improvement: Menggunakan Button Group (Radio) daripada Select Option
                                        // Ini jauh lebih cepat di mobile (sekali tap vs tap-scroll-tap)
                                        const row = `
                                        <tr>
                                            <td class="d-none d-md-table-cell">${index + 1}</td>
                                            <td class="d-none d-md-table-cell">${siswa.siswa_nis}</td>
                                            <td class="student-name">
                                                <span class="d-md-none badge badge-light mr-1">${siswa.siswa_nis}</span>
                                                ${siswa.siswa_nama}
                                            </td>
                                            <td class="attendance-options">
                                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                    <label class="btn btn-outline-success active">
                                                        <input type="radio" name="absensi[${siswa.siswa_id}][status]" value="hadir" checked> 
                                                        <span class="d-none d-sm-inline">Hadir</span><span class="d-inline d-sm-none">H</span>
                                                    </label>
                                                    <label class="btn btn-outline-warning">
                                                        <input type="radio" name="absensi[${siswa.siswa_id}][status]" value="sakit"> 
                                                        <span class="d-none d-sm-inline">Sakit</span><span class="d-inline d-sm-none">S</span>
                                                    </label>
                                                    <label class="btn btn-outline-info">
                                                        <input type="radio" name="absensi[${siswa.siswa_id}][status]" value="izin"> 
                                                        <span class="d-none d-sm-inline">Izin</span><span class="d-inline d-sm-none">I</span>
                                                    </label>
                                                    <label class="btn btn-outline-danger">
                                                        <input type="radio" name="absensi[${siswa.siswa_id}][status]" value="alfa"> 
                                                        <span class="d-none d-sm-inline">Alfa</span><span class="d-inline d-sm-none">A</span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="absensi[${siswa.siswa_id}][keterangan]" class="form-control" placeholder="Ket. (Opsional)">
                                            </td>
                                        </tr>
                                    `;
                                        absensiBody.innerHTML += row;
                                    });
                                } else {
                                    noSiswaMessage.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                loadingMessage.style.display = 'none';
                                console.error('Error:', error);
                                errorMessage.style.display = 'block';
                            });
                    }
                });
            </script>
            <?= $this->endSection() ?>