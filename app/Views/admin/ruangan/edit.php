<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Ruangan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/ruangan') ?>">Manajemen Ruangan</a></li>
                    <li class="breadcrumb-item active">Edit Ruangan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Ruangan</h3>
                    </div>
                    <form action="<?= base_url('admin/ruangan/update/' . $ruangan['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_ruangan">Nama Ruangan</label>
                                <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" value="<?= $ruangan['nama_ruangan'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="kapasitas">Kapasitas</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="<?= $ruangan['kapasitas'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis">Jenis Ruangan</label>
                                <select class="form-control" id="jenis" name="jenis">
                                    <option value="Kelas" <?= $ruangan['jenis'] == 'Kelas' ? 'selected' : '' ?>>Kelas</option>
                                    <option value="Lab Komputer" <?= $ruangan['jenis'] == 'Lab Komputer' ? 'selected' : '' ?>>Lab Komputer</option>
                                    <option value="Lab IPA" <?= $ruangan['jenis'] == 'Lab IPA' ? 'selected' : '' ?>>Lab IPA</option>
                                    <option value="Perpustakaan" <?= $ruangan['jenis'] == 'Perpustakaan' ? 'selected' : '' ?>>Perpustakaan</option>
                                    <option value="Aula" <?= $ruangan['jenis'] == 'Aula' ? 'selected' : '' ?>>Aula</option>
                                    <option value="Lainnya" <?= $ruangan['jenis'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= $ruangan['keterangan'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="<?= base_url('admin/ruangan') ?>" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
