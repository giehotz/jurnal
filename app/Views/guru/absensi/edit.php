<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Absensi</h3>
                <div class="card-tools">
                    <a href="<?= base_url('guru/absensi') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
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

                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 150px;">Tanggal</th>
                                <td>: <?= date('d F Y', strtotime($jurnal['tanggal'])) ?></td>
                            </tr>
                            <tr>
                                <th>Nama Siswa</th>
                                <td>: <?= esc($siswa['nama']) ?></td>
                            </tr>
                            <tr>
                                <th>NIS</th>
                                <td>: <?= esc($siswa['nis']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <form action="<?= base_url('guru/absensi/update/' . $absensi['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label>Status Kehadiran</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" id="hadir" name="status" value="hadir" <?= ($absensi['status'] == 'hadir') ? 'checked' : '' ?>>
                                <label for="hadir" class="custom-control-label text-success">Hadir</label>
                            </div>
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" id="izin" name="status" value="izin" <?= ($absensi['status'] == 'izin') ? 'checked' : '' ?>>
                                <label for="izin" class="custom-control-label text-info">Izin</label>
                            </div>
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" id="sakit" name="status" value="sakit" <?= ($absensi['status'] == 'sakit') ? 'checked' : '' ?>>
                                <label for="sakit" class="custom-control-label text-warning">Sakit</label>
                            </div>
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" id="alfa" name="status" value="alfa" <?= ($absensi['status'] == 'alfa') ? 'checked' : '' ?>>
                                <label for="alfa" class="custom-control-label text-danger">Alfa</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika ada..."><?= esc($absensi['keterangan']) ?></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
