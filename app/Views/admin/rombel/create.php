<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Rombongan Belajar</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/rombel') ?>">Rombongan Belajar</a></li>
                    <li class="breadcrumb-item active">Tambah Rombongan Belajar</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Rombel</h3>
                    </div>
                    <form action="<?= base_url('admin/rombel/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <!-- Flashdata handled by SweetAlert -->

                            <?php if (validation_list_errors()): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= validation_list_errors() ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <!-- Informasi Utama -->
                            <h5 class="text-primary mb-3"><i class="fas fa-info-circle mr-1"></i> Informasi Utama</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_rombel">Nama Rombel <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_rombel" name="nama_rombel" placeholder="Contoh: Kelas 10 IPA 1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_rombel">Kode Rombel <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="kode_rombel" name="kode_rombel" placeholder="Contoh: KELAS-10-IPA-1" required>
                                        <small class="text-muted">Otomatis terisi dari Nama Rombel, bisa diedit manual.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tingkat">Tingkat Kelas <span class="text-danger">*</span></label>
                                        <select class="form-control" id="tingkat" name="tingkat" required>
                                            <option value="">Pilih Tingkat</option>
                                            <?php 
                                            $start = 1; $end = 12;
                                            if (isset($school_level)) {
                                                if ($school_level == 'SD/MI') {
                                                    $start = 1; $end = 6;
                                                } elseif ($school_level == 'SMP/MTs') {
                                                    $start = 7; $end = 9;
                                                } elseif ($school_level == 'SMA/MA') {
                                                    $start = 10; $end = 12;
                                                }
                                            }
                                            for ($i = $start; $i <= $end; $i++) : ?>
                                                <option value="<?= $i ?>">Kelas <?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jurusan">Jurusan</label>
                                        <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Contoh: IPA, IPS, Teknik Komputer (Opsional)">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Detail Akademik -->
                            <h5 class="text-primary mb-3 mt-4"><i class="fas fa-graduation-cap mr-1"></i> Detail Akademik</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun_ajaran">Tahun Ajaran <span class="text-danger">*</span></label>
                                        <select class="form-control" id="tahun_ajaran" name="tahun_ajaran" required>
                                            <option value="2025/2026 Ganjil" selected>2025/2026 Ganjil</option>
                                            <option value="2025/2026 Genap">2025/2026 Genap</option>
                                            <option value="2024/2025 Ganjil">2024/2025 Ganjil</option>
                                            <option value="2024/2025 Genap">2024/2025 Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kurikulum">Kurikulum</label>
                                        <select class="form-control" id="kurikulum" name="kurikulum">
                                            <option value="Kurikulum Merdeka">Kurikulum Merdeka</option>
                                            <option value="K13">K13</option>
                                            <option value="KTSP">KTSP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Detail Operasional -->
                            <h5 class="text-primary mb-3 mt-4"><i class="fas fa-cogs mr-1"></i> Detail Operasional</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="wali_kelas">Wali Kelas</label>
                                        <select class="form-control select2" id="wali_kelas" name="wali_kelas" style="width: 100%;">
                                            <option value="">Pilih Wali Kelas</option>
                                            <?php foreach ($teachers as $teacher): ?>
                                                <option value="<?= $teacher['id'] ?>"><?= $teacher['nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ruangan_id">Nama Ruangan</label>
                                        <select class="form-control select2" id="ruangan_id" name="ruangan_id" style="width: 100%;">
                                            <option value="">Pilih Ruangan</option>
                                            <?php if (isset($rooms) && !empty($rooms)): ?>
                                                <?php foreach ($rooms as $room): ?>
                                                    <option value="<?= $room['id'] ?>"><?= $room['nama_ruangan'] ?> (Kapasitas: <?= $room['kapasitas'] ?>)</option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="waktu_mengajar">Waktu Mengajar</label>
                                        <select class="form-control" id="waktu_mengajar" name="waktu_mengajar">
                                            <option value="Pagi">Pagi</option>
                                            <option value="Siang">Siang</option>
                                            <option value="Full Day">Full Day</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_rombel">Jenis Rombel</label>
                                        <select class="form-control" id="jenis_rombel" name="jenis_rombel">
                                            <option value="Reguler">Reguler</option>
                                            <option value="Eskul">Eskul</option>
                                            <option value="Matrikulasi">Matrikulasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden fields for compatibility if needed -->
                            <input type="hidden" name="semester" value="1">
                            <input type="hidden" name="kapasitas" value="30">

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="<?= base_url('admin/rombel') ?>" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<script src="<?= base_url('AdminLTE/plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4'
    })
    
    // Auto-generate kode rombel
    $('#nama_rombel').on('input', function() {
        var nama = $(this).val();
        var kode = nama.replace(/\s+/g, '-').toUpperCase();
        $('#kode_rombel').val(kode);
    });
});
</script>
<?= $this->endSection() ?>