<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Absensi Siswa</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/absensi') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/absensi/update/' . $absensi['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Siswa</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= esc($siswa['nama']) ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">NIS / NISN</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= esc($siswa['nis']) ?> / <?= esc($siswa['nisn']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= date('d F Y', strtotime($absensi['tanggal'])) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">Status Kehadiran</label>
                        <div class="col-sm-10">
                            <select name="status" id="status" class="form-control">
                                <option value="hadir" <?= ($absensi['status'] == 'H' || $absensi['status'] == 'hadir') ? 'selected' : '' ?>>Hadir</option>
                                <option value="izin" <?= ($absensi['status'] == 'I' || $absensi['status'] == 'izin') ? 'selected' : '' ?>>Izin</option>
                                <option value="sakit" <?= ($absensi['status'] == 'S' || $absensi['status'] == 'sakit') ? 'selected' : '' ?>>Sakit</option>
                                <option value="alfa" <?= ($absensi['status'] == 'A' || $absensi['status'] == 'alfa') ? 'selected' : '' ?>>Alpha</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= esc($absensi['keterangan']) ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>