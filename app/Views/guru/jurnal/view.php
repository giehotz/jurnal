<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<style>
/* Mobile Responsive Styles for View Page */
@media (max-width: 768px) {
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .col-md-8, .col-md-4 {
        padding-left: 0;
        padding-right: 0;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .card-header h3 {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .card-footer {
        padding: 0.75rem;
    }
    
    /* Table styles */
    .table {
        font-size: 0.875rem;
    }
    
    .table th {
        width: 100px;
        font-size: 0.75rem;
        padding: 0.5rem;
    }
    
    .table td {
        font-size: 0.875rem;
        padding: 0.5rem;
    }
    
    /* Badge */
    .badge {
        font-size: 0.75rem;
    }
    
    /* Buttons */
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn:last-child {
        margin-bottom: 0;
    }
    
    /* Content sections */
    h4 {
        font-size: 1rem;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    
    hr {
        margin: 1rem 0;
    }
    
    /* Image responsive */
    .img-fluid {
        max-width: 100%;
        height: auto;
    }
    
    /* Sidebar on mobile */
    .col-md-4 {
        margin-top: 1rem;
    }
    
    .btn-block {
        width: 100%;
    }
}

@media (min-width: 769px) {
    .btn {
        width: auto;
    }
}
</style>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detail Jurnal Mengajar</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">Tanggal</th>
                        <td><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <th>Rombel</th> <!-- Diubah dari Kelas -->
                        <td><?= esc($jurnal['nama_kelas']) ?> (<?= esc($jurnal['kode_kelas']) ?>)</td> <!-- Tidak perlu diubah karena field sama -->
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td><?= $jurnal['nama_mapel'] ?></td>
                    </tr>
                    <tr>
                        <th>Jam Ke</th>
                        <td><?= $jurnal['jam_ke'] ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah JP</th>
                        <td><?= $jurnal['jumlah_jam'] ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Peserta</th>
                        <td><?= $jurnal['jumlah_peserta'] ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($jurnal['status'] == 'published'): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <hr>
                <h4>Materi Pembelajaran</h4>
                <div><?= $jurnal['materi'] ?></div>
                <hr>
                <h4>Keterangan</h4>
                <p><?= $jurnal['keterangan'] ?></p>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('guru/jurnal/edit/' . $jurnal['id']) ?>" class="btn btn-warning">Edit</a>
                <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Bukti Dukung</h3>
            </div>
            <div class="card-body">
                <?php if ($jurnal['bukti_dukung']): ?>
                    <a href="<?= base_url('uploads/' . $jurnal['bukti_dukung']) ?>" target="_blank">
                        <img src="<?= base_url('uploads/' . $jurnal['bukti_dukung']) ?>" class="img-fluid">
                    </a>
                    <a href="<?= base_url('uploads/' . $jurnal['bukti_dukung']) ?>" target="_blank" class="btn btn-primary btn-block mt-2">Lihat File</a>
                <?php else: ?>
                    <p class="text-center">Tidak ada bukti dukung.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
