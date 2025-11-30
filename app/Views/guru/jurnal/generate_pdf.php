<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Generate Laporan PDF</h3>
            </div>
            <!-- Bagian PHP ini tidak diubah -->
            <form action="<?= base_url('guru/jurnal/export/pdf') ?>" method="post" target="_blank">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Placeholder untuk notifikasi kustom -->
                    <div id="custom-alert-placeholder"></div>

                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi</h5>
                        <p>Pilih bulan awal dan bulan akhir untuk menghasilkan laporan jurnal mengajar dalam format PDF.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulan_awal">Bulan Mulai:</label>
                                <select name="bulan_awal" class="form-control" id="bulan_awal">
                                    <option value="">Pilih Bulan Awal</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= (date('n') == $i) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulan_akhir">Bulan Selesai:</label>
                                <select name="bulan_akhir" class="form-control" id="bulan_akhir">
                                    <option value="">Pilih Bulan Akhir</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>" <?= (date('n') == $i) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tahun">Tahun:</label>
                                <select name="tahun" class="form-control" id="tahun">
                                    <option value="">Pilih Tahun</option>
                                    <?php for ($i = date('Y'); $i >= date('Y') - 2; $i--): ?>
                                        <option value="<?= $i ?>" <?= (date('Y') == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-info">
                                <h5><i class="fas fa-lightbulb"></i> Tips Penggunaan</h5>
                                <ul>
                                    <li>Pilih bulan mulai dan bulan selesai dengan tahun yang sesuai</li>
                                    <li>Laporan akan mencakup seluruh jurnal dari tanggal 1 bulan awal hingga akhir bulan akhir</li>
                                    <li>Jika tidak ada data, akan muncul pesan kesalahan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="generate-pdf-btn">
                        <i class="fas fa-file-pdf"></i> Generate PDF
                    </button>
                    <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Form submission handler
    $('form').on('submit', function() {
        var bulanAwal = $('#bulan_awal').val();
        var bulanAkhir = $('#bulan_akhir').val();
        var tahun = $('#tahun').val();
        
        if (!bulanAwal || !bulanAkhir || !tahun) {
            alert('Harap isi semua field terlebih dahulu.');
            return false;
        }
        
        if (parseInt(bulanAwal) > parseInt(bulanAkhir)) {
            alert('Bulan mulai tidak boleh lebih besar dari bulan selesai.');
            return false;
        }
        
        // Show loading message
        $('#generate-pdf-btn').html('<i class="fas fa-spinner fa-spin"></i> Sedang memproses...');
        $('#generate-pdf-btn').prop('disabled', true);
    });
});
</script>
<?= $this->endSection() ?>