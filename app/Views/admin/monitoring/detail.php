<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detail Jurnal</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">Guru</th>
                        <td><?= esc($jurnal['nama_guru']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <th>Rombel</th>
                        <td><?= esc($jurnal['nama_kelas']) ?> (<?= esc($jurnal['kode_kelas']) ?>)</td>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <td><?= esc($jurnal['nama_mapel']) ?></td>
                    </tr>
                    <tr>
                        <th>Jam Ke</th>
                        <td><?= esc($jurnal['jam_ke']) ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah JP</th>
                        <td><?= esc($jurnal['jumlah_jam']) ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Peserta</th>
                        <td><?= esc($jurnal['jumlah_peserta']) ?></td>
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
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Kembali</a>
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
