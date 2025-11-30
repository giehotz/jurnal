<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Upload Data Siswa</h3>
            </div>
            <form action="<?= base_url('admin/siswa/import') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php elseif (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="file">File Excel</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file" accept=".xls,.xlsx,.csv" required>
                                <label class="custom-file-label" for="file">Pilih file</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            File harus dalam format Excel (.xls, .xlsx) atau CSV. Maksimal ukuran file 2MB.
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Informasi Kolom</h5>
                        <p>File Excel harus memiliki kolom dengan urutan sebagai berikut:</p>
                        <ol>
                            <li><strong>NIS</strong> - Nomor Induk Siswa</li>
                            <li><strong>NISN</strong> - Nomor Induk Siswa Nasional</li>
                            <li><strong>Nama</strong> - Nama lengkap siswa</li>
                            <li><strong>Jenis Kelamin</strong> - L untuk Laki-laki, P untuk Perempuan</li>
                            <li><strong>Tempat Lahir</strong> - Tempat lahir siswa</li>
                            <li><strong>Tanggal Lahir</strong> - Format YYYY-MM-DD</li>
                            <li><strong>Rombel ID</strong> - ID rombel tempat siswa akan ditempatkan</li>
                        </ol>
                        <p>Baris pertama dianggap sebagai header dan akan dilewati.</p>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <a href="<?= base_url('admin/siswa') ?>" class="btn btn-secondary">Kembali</a>
                    <a href="<?= base_url('admin/siswa/download-template') ?>" class="btn btn-info float-right">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
</script>
<?= $this->endSection() ?>