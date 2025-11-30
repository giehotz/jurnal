<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Kolom Kiri: Detail Rombel -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Detail Informasi Rombel
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <tbody>
                            <tr>
                                <th style="width: 30%; padding-left: 20px;">Kode Rombel</th>
                                <td><strong><?= esc($rombel['kode_rombel']) ?></strong></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Nama Rombel</th>
                                <td><?= esc($rombel['nama_rombel']) ?></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Tingkat</th>
                                <td><?= esc($rombel['tingkat'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Jurusan</th>
                                <td><?= esc($rombel['jurusan'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Wali Kelas</th>
                                <td>
                                    <i class="fas fa-user-tie text-muted mr-1"></i>
                                    <?= esc($rombel['wali_kelas_nama'] ?? 'Belum ditentukan') ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Tahun Ajaran</th>
                                <td><?= esc($rombel['tahun_ajaran']) ?></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Semester</th>
                                <td><?= esc($rombel['semester'] == '1' ? '1 (Ganjil)' : ($rombel['semester'] == '2' ? '2 (Genap)' : '-')) ?></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Kapasitas</th>
                                <td><?= esc($rombel['kapasitas'] ?? '30') ?> Siswa</td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Status</th>
                                <td>
                                    <?php if ($rombel['is_active']): ?>
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fas fa-times"></i> Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Dibuat pada</th>
                                <td class="text-muted small"><?= esc($rombel['created_at']) ?></td>
                            </tr>
                            <tr>
                                <th style="padding-left: 20px;">Terakhir diupdate</th>
                                <td class="text-muted small"><?= esc($rombel['updated_at']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('admin/rombel/edit/' . $rombel['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Rombel
                    </a>
                    <a href="<?= base_url('admin/rombel/assign-students/' . $rombel['id']) ?>" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Kelola Siswa
                    </a>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-file-upload"></i> Upload Siswa
                    </button>
                    <a href="<?= base_url('admin/rombel') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal Upload -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Data Siswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('admin/rombel/preview-upload/' . $rombel['id']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>1. Download Template</label>
                                <p>Silakan download template Excel terlebih dahulu.</p>
                                <a href="<?= base_url('admin/rombel/download-template') ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download"></i> Download Template
                                </a>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>2. Upload File Excel</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_excel" name="file_excel" accept=".xls, .xlsx" required>
                                    <label class="custom-file-label" for="file_excel">Pilih file...</label>
                                </div>
                                <small class="text-muted">Format: .xls, .xlsx</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Preview & Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script untuk Custom File Input -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Menampilkan nama file yang dipilih
                document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                    var fileName = document.getElementById("file_excel").files[0].name;
                    var nextSibling = e.target.nextElementSibling;
                    nextSibling.innerText = fileName;
                });
            });
        </script>

        <!-- Kolom Kanan: Statistik -->
        <div class="col-md-4">
            <!-- Menggunakan Info Box agar lebih elegan -->
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Siswa</span>
                    <span class="info-box-number"><?= $jumlah_siswa ?> / <?= esc($rombel['kapasitas'] ?? '30') ?></span>
                    <div class="progress">
                        <!-- Menghitung persentase kapasitas bar -->
                        <?php 
                            $kapasitas = $rombel['kapasitas'] ?? 30;
                            $persen = ($kapasitas > 0) ? ($jumlah_siswa / $kapasitas) * 100 : 0; 
                        ?>
                        <div class="progress-bar" style="width: <?= $persen ?>%"></div>
                    </div>
                    <span class="progress-description">
                        Siswa Terdaftar dalam Rombel
                    </span>
                </div>
            </div>

            <!-- Card Tambahan untuk Shortcut (Opsional) -->
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Informasi Tambahan</h3>
                </div>
                <div class="card-body">
                   <p class="text-muted">Pastikan kuota rombel mencukupi sebelum menambahkan siswa baru.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Siswa</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- Tambah table-responsive agar tidak error di mobile -->
                <div class="card-body table-responsive p-0">
                    <?php if (!empty($siswa_list)): ?>
                        <table class="table table-hover text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%" class="text-center">No</th>
                                    <th style="width: 15%" class="text-center">NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th style="width: 15%" class="text-center">Jenis Kelamin</th>
                                    <th style="width: 10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($siswa_list as $siswa): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $no++ ?></td>
                                    <td class="text-center align-middle"><span class="badge badge-light"><?= esc($siswa['nis']) ?></span></td>
                                    <td class="align-middle">
                                        <span class="font-weight-bold"><?= esc($siswa['nama']) ?></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if($siswa['jenis_kelamin'] == 'L'): ?>
                                            <span class="badge badge-primary">Laki-laki</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Perempuan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="#" class="btn btn-default btn-sm shadow-sm" title="Lihat Detail Siswa">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>Tidak ada data siswa dalam rombel ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>