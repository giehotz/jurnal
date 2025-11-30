<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Detail Kelas</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">Kode Kelas</th>
                        <td><?= esc($class['kode_kelas']) ?></td>
                    </tr>
                    <tr>
                        <th>Nama Kelas</th>
                        <td><?= esc($class['nama_kelas']) ?></td>
                    </tr>
                    <tr>
                        <th>Tingkat</th>
                        <td><?= esc($class['tingkat'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Wali Kelas</th>
                        <td><?= esc($class['wali_kelas_nama'] ?? 'Belum ditentukan') ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat pada</th>
                        <td><?= esc($class['created_at']) ?></td>
                    </tr>
                    <tr>
                        <th>Terakhir diupdate</th>
                        <td><?= esc($class['updated_at']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('admin/kelas/edit/' . $class['id']) ?>" class="btn btn-primary">Edit</a>
                <a href="<?= base_url('admin/kelas') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Siswa di Kelas Ini</h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users"></i> Jumlah Siswa
                            <span class="badge bg-primary float-right">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>