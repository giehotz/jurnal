<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
<!-- Style tambahan spesifik Kenaikan Kelas -->
<style>
    .panel-header {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .warning-box {
        background-color: #fce4e4;
        border: 1px solid #fcc2c3;
        color: #cc0033;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
    }

    .warning-title {
        font-weight: bold;
        font-size: 1.2rem;
        color: #a94442;
        margin-bottom: 10px;
    }

    .scrollable-table {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Kenaikan Kelas</h1>
                <p class="text-muted">Menu ini digunakan untuk menaikkan siswa dari tingkatan sebelumnya.</p>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- PANEL KIRI: Kelas Asal -->
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Tahun Ajaran Asal</strong> (Current)</h3>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tingkat</label>
                                    <select class="form-control" id="source_tingkat">
                                        <option value="">--Pilih--</option>
                                        <?php if (isset($tingkatList)): foreach ($tingkatList as $t): ?>
                                                <option value="<?= $t['tingkat'] ?>"><?= $t['tingkat'] ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select class="form-control" id="source_rombel" disabled>
                                        <option value="">--Pilih--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="scrollable-table mb-3">
                            <table class="table table-sm table-hover table-striped mb-0" id="table-source">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th style="width: 40px" class="text-center">
                                            <input type="checkbox" id="check-all-source">
                                        </th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>L/P</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No data available in table</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Showing <span id="source-count">0</span> entries</span>
                        </div>

                        <!-- Warning Box Kiri -->
                        <div class="warning-box">
                            <div class="warning-title">Perhatikan:</div>
                            <ul style="padding-left: 20px; margin-bottom: 0;">
                                <li>Silahkan pilih kelas asal</li>
                                <li>Silahkan klik pada siswa yang akan di naikkan</li>
                                <li>Silahkan pilih kelas tujuan</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            <!-- PANEL KANAN: Kelas Tujuan -->
            <div class="col-md-6">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Tahun Ajaran Tujuan</strong> (<?= esc($currentYear) ?>)</h3>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tingkat</label>
                                    <select class="form-control" id="target_tingkat">
                                        <option value="">--Pilih--</option>
                                        <?php if (isset($tingkatList)): foreach ($tingkatList as $t): ?>
                                                <option value="<?= $t['tingkat'] ?>"><?= $t['tingkat'] ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select class="form-control" id="target_rombel" disabled>
                                        <option value="">--Pilih--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Table Preview (Kosong Awalnya) -->
                        <div class="scrollable-table mb-3">
                            <table class="table table-sm table-hover table-striped mb-0" id="table-target">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th style="width: 40px">#</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>L/P</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Siswa yang dipindahkan akan muncul disini</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Target Count: <span id="target-count">0</span></span>
                        </div>

                        <!-- Warning Box Kanan -->
                        <div class="warning-box">
                            <div class="warning-title">Perhatikan:</div>
                            <ul style="padding-left: 20px; margin-bottom: 0;">
                                <li>Silahkan pilih kelas asal</li>
                                <li>Pastikan data siswa benar sebelum proses</li>
                                <li>Silahkan pilih kelas tujuan</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="row my-3">
            <div class="col-12 text-center">
                <button class="btn btn-lg btn-primary px-5" id="btn-process" disabled>
                    <i class="fas fa-level-up-alt"></i> Proses Kenaikan Kelas
                </button>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        // ----------------------------------------------------------------
        // SECTION: FETCH ROMBEL
        // ----------------------------------------------------------------
        function loadRombel(tingkat, targetSelectId) {
            if (!tingkat) {
                $(targetSelectId).html('<option value="">--Pilih--</option>').prop('disabled', true);
                return;
            }

            $.ajax({
                url: '<?= base_url('admin/kenaikan-kelas/get-rombel') ?>',
                type: 'POST',
                data: {
                    tingkat: tingkat
                },
                success: function(response) {
                    let options = '<option value="">--Pilih--</option>';
                    response.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nama_rombel}</option>`;
                    });
                    $(targetSelectId).html(options).prop('disabled', false);
                },
                error: function() {
                    alert('Gagal mengambil data kelas');
                }
            });
        }

        $('#source_tingkat').change(function() {
            loadRombel($(this).val(), '#source_rombel');
            $('#table-source tbody').html('<tr><td colspan="4" class="text-center text-muted py-4">Pilih kelas baru</td></tr>');
        });

        $('#target_tingkat').change(function() {
            loadRombel($(this).val(), '#target_rombel');
        });

        // ----------------------------------------------------------------
        // SECTION: FETCH SISWA
        // ----------------------------------------------------------------
        $('#source_rombel').change(function() {
            let rombelId = $(this).val();
            if (!rombelId) return;

            $.ajax({
                url: '<?= base_url('admin/kenaikan-kelas/get-siswa') ?>',
                type: 'POST',
                data: {
                    rombel_id: rombelId
                },
                success: function(response) {
                    let rows = '';
                    if (response.length === 0) {
                        rows = '<tr><td colspan="4" class="text-center text-muted py-4">Tidak ada siswa di kelas ini</td></tr>';
                    } else {
                        response.forEach(function(s) {
                            rows += `
                        <tr>
                            <td class="text-center"><input type="checkbox" class="check-item" value="${s.id}"></td>
                            <td>${s.nisn || '-'}</td>
                            <td>${s.nama}</td>
                            <td>${s.jenis_kelamin || '-'}</td>
                        </tr>`;
                        });
                    }
                    $('#table-source tbody').html(rows);
                    $('#source-count').text(response.length);
                    validateForm();
                }
            });
        });

        // ----------------------------------------------------------------
        // SECTION: CHECKBOX LOGIC
        // ----------------------------------------------------------------
        $('#check-all-source').change(function() {
            $('.check-item').prop('checked', $(this).prop('checked'));
            updateTargetPreview();
            validateForm();
        });

        $(document).on('change', '.check-item', function() {
            updateTargetPreview();
            validateForm();
        });

        // Move checked items to preview logic (Optional visual feedback)
        function updateTargetPreview() {
            // Logic ini opsional: apakah kita mau men-copy row ke tabel kanan? 
            // Sesuai gambar referensi mungkin tidak required, tapi bagus untuk UX.
            // Kita hitung saja jumlah terpilih dulu
            let count = $('.check-item:checked').length;
            $('#target-count').text(count);
        }

        // ----------------------------------------------------------------
        // SECTION: PROCESS
        // ----------------------------------------------------------------
        $('#target_rombel').change(function() {
            validateForm();
        });

        function validateForm() {
            let source = $('#source_rombel').val();
            let target = $('#target_rombel').val();
            let selected = $('.check-item:checked').length;

            if (source && target && selected > 0) {
                $('#btn-process').prop('disabled', false);
            } else {
                $('#btn-process').prop('disabled', true);
            }
        }

        $('#btn-process').click(function() {
            let sourceRombel = $('#source_rombel').val();
            let targetRombel = $('#target_rombel').val();
            let selectedIds = [];
            $('.check-item:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Pilih siswa terlebih dahulu!');
                return;
            }

            if (!confirm(`Yakin ingin menaikkan ${selectedIds.length} siswa terpilih ke kelas tujuan?`)) {
                return;
            }

            // Show loading state
            let originalBtn = $(this).html();
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            $.ajax({
                url: '<?= base_url('admin/kenaikan-kelas/process') ?>',
                type: 'POST',
                data: {
                    source_rombel_id: sourceRombel,
                    target_rombel_id: targetRombel,
                    student_ids: selectedIds
                },
                success: function(res) {
                    if (res.success) {
                        alert(res.message);
                        location.reload(); // Refresh page to clear state
                    } else {
                        alert('Gagal: ' + res.message);
                        $('#btn-process').html(originalBtn).prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem');
                    $('#btn-process').html(originalBtn).prop('disabled', false);
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>