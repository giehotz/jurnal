<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Export Laporan Jurnal</h3>
            </div>
            <div class="card-body">
                <p>Pilih rentang bulan untuk mengekspor laporan jurnal.</p>
                <form action="<?= base_url('admin/laporan/export') ?>" method="get">
                    <input type="hidden" name="type" value="jurnal">
                    <div class="form-group">
                        <label>Rentang Bulan:</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="start_month">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="end_month">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <select class="form-control" name="year">
                                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                        <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Format:</label>
                        <select class="form-control" name="format">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Export Jurnal</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Export Data Master</h3>
            </div>
            <div class="card-body">
                <p>Export data master (Guru, Kelas, Mapel) ke format Excel.</p>
                <a href="<?= base_url('admin/export/master/guru') ?>" class="btn btn-app bg-info">
                    <i class="fas fa-users"></i> Export Guru
                </a>
                <a href="<?= base_url('admin/export/master/kelas') ?>" class="btn btn-app bg-warning">
                    <i class="fas fa-school"></i> Export Kelas
                </a>
                <a href="<?= base_url('admin/export/master/mapel') ?>" class="btn btn-app bg-danger">
                    <i class="fas fa-book"></i> Export Mapel
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
