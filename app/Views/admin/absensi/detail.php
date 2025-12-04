<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/custom-admin.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="m-0" style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main);">Detail Absensi</h1>
                <p class="text-muted m-0">Kelas <?= esc($rombel['nama_rombel']) ?></p>
            </div>
            <div>
                <a href="<?= base_url('admin/absensi') ?>" class="custom-btn custom-btn-secondary custom-btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;">
                <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('warning') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Info Card -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="custom-info-box">
                    <div class="custom-info-icon bg-emerald-soft">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="custom-info-content">
                        <span class="custom-info-label">Kelas Aktif</span>
                        <span class="custom-info-value"><?= esc($rombel['kode_rombel']) ?> - <?= esc($rombel['nama_rombel']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Card -->
        <div class="custom-card">
            <div class="custom-card-body">
                <form action="" method="GET" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="filter_date" class="form-label text-muted small font-weight-bold">TANGGAL</label>
                            <input type="date" name="filter_date" id="filter_date" class="custom-form-control w-100" value="<?= esc($filterDate) ?>" max="<?= date('Y-m-d') ?>" onchange="document.getElementById('filterForm').submit()">
                        </div>
                        <div class="col-md-4">
                            <label for="rombel_select" class="form-label text-muted small font-weight-bold">PILIH KELAS LAIN</label>
                            <select id="rombel_select" class="custom-form-control w-100" onchange="window.location.href='<?= base_url('admin/absensi/detail/') ?>' + this.value + '?filter_date=' + document.getElementById('filter_date').value">
                                <?php foreach ($rombelList as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= ($r['id'] == $rombel['id']) ? 'selected' : '' ?>>
                                        <?= esc($r['nama_rombel']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 rounded bg-slate-soft text-center">
                                <span class="text-muted small">Terpilih: </span>
                                <span class="font-weight-bold"><?= date('d F Y', strtotime($filterDate)) ?></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Alert Hari Libur -->
        <?php if ($isHoliday): ?>
            <div class="alert alert-danger d-flex align-items-center" style="border-radius: var(--radius-md); background-color: #FEE2E2; color: #991B1B; border: 1px solid #FECACA;">
                <i class="fas fa-ban fa-2x mr-3"></i>
                <div>
                    <h5 class="m-0 font-weight-bold">Hari Libur!</h5>
                    <p class="m-0">Tanggal <?= date('d F Y', strtotime($filterDate)) ?> adalah hari libur: <strong><?= esc($holidayName) ?></strong>. Tidak dapat mengisi absensi.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tabel Absensi -->
        <?php if (!$isHoliday): ?>
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Daftar Kehadiran Siswa</h3>
                </div>
                <div class="custom-card-body p-0">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="10%">NIS</th>
                                    <th width="25%">Nama Siswa</th>
                                    <th width="15%">Status</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($detailAbsensi)): ?>
                                    <?php $no = 1; foreach ($detailAbsensi as $d): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                                            <td><span class="font-weight-bold text-muted"><?= esc($d['nis']) ?></span></td>
                                            <td><span class="font-weight-bold"><?= esc($d['nama_siswa']) ?></span></td>
                                            <td>
                                                <?php 
                                                    switch($d['status']) {
                                                        case 'hadir':
                                                            echo '<span class="custom-badge badge-success-soft">Hadir</span>';
                                                            break;
                                                        case 'sakit':
                                                            echo '<span class="custom-badge badge-warning-soft">Sakit</span>';
                                                            break;
                                                        case 'izin':
                                                            echo '<span class="custom-badge badge-info-soft">Izin</span>';
                                                            break;
                                                        case 'alfa':
                                                            echo '<span class="custom-badge badge-danger-soft">Alfa</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="custom-badge bg-slate-soft">' . esc($d['status']) . '</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?= esc($d['keterangan']) ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/absensi/edit/' . $d['id']) ?>" class="custom-btn custom-btn-secondary custom-btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <img src="https://illustrations.popsy.co/gray/question-mark.svg" alt="No Data" style="width: 120px; opacity: 0.5;">
                                            <p class="text-muted mt-3">Tidak ada data absensi pada tanggal ini.</p>
                                            <a href="<?= base_url('admin/absensi/create?rombel_id=' . $rombel['id'] . '&tanggal=' . $filterDate) ?>" class="custom-btn custom-btn-primary mt-2">
                                                <i class="fas fa-plus"></i> Isi Absensi Sekarang
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>