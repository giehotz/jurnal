<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Export Data Absensi</h3>
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
                
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Informasi Export</h5>
                    <ul class="mb-0 pl-3">
                        <li>Sistem otomatis menghitung <strong>Hari Efektif</strong> (Senin-Sabtu) dalam periode yang dipilih.</li>
                        <li>Hari Minggu dan <strong>Hari Libur Nasional</strong> (via API) otomatis tidak dihitung.</li>
                        <li><strong>Alfa</strong> dihitung otomatis: <code>Hari Efektif - (Hadir + Sakit + Izin)</code>.</li>
                        <li>Jika export dilakukan di tengah bulan, perhitungan hanya sampai tanggal hari ini.</li>
                    </ul>
                </div>

                <form action="<?= base_url('guru/absensi/process_export') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_month">Bulan Awal</label>
                                <select name="start_month" id="start_month" class="form-control" required>
                                    <option value="">Pilih Bulan Awal</option>
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
                                <small class="form-text text-muted">Pilih bulan awal untuk periode export data</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_month">Bulan Akhir</label>
                                <select name="end_month" id="end_month" class="form-control" required>
                                    <option value="">Pilih Bulan Akhir</option>
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
                                <small class="form-text text-muted">Pilih bulan akhir untuk periode export data</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rombel_id">Kelas (Rombel)</label>
                                <select name="rombel_id" id="rombel_id" class="form-control">
                                    <option value="">Semua Kelas</option>
                                    <?php foreach ($rombel as $r): ?>
                                        <option value="<?= $r['id'] ?>"><?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Jenis Export</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="export_type" id="pdf" value="pdf" required>
                                    <label class="form-check-label" for="pdf">
                                        PDF
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="export_type" id="excel" value="excel" required>
                                    <label class="form-check-label" for="excel">
                                        Excel
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-export"></i> Export Data
                    </button>
                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
