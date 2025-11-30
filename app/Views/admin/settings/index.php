<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-12">
            <h1 class="m-0 text-dark font-weight-bold h3">Pengaturan Akun</h1>
            <p class="text-muted">Kelola informasi profil dan akun Anda</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card card-primary card-outline shadow-sm border-0 h-100">
                <div class="card-body box-profile d-flex flex-column">
                    <div class="text-center mb-4 mt-3">
                        <?php 
                        $profilePicture = session()->get('profile_picture');
                        $avatarUrl = (!empty($profilePicture) && $profilePicture !== 'default.png') 
                            ? base_url('uploads/profile_pictures/' . $profilePicture) 
                            : base_url('uploads/profile_pictures/default.png');
                        ?>
                        <div class="position-relative d-inline-block">
                            <img class="profile-user-img img-fluid img-circle shadow-sm"
                                 src="<?= $avatarUrl ?>"
                                 alt="User profile picture"
                                 style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <div class="position-absolute bg-success rounded-circle border border-white" 
                                 style="width: 20px; height: 20px; bottom: 10px; right: 10px;"></div>
                        </div>
                    </div>

                    <h3 class="profile-username text-center font-weight-bold mb-1"><?= session()->get('nama') ?></h3>
                    <p class="text-muted text-center mb-4 badge badge-light mx-auto d-table px-3 py-2"><?= strtoupper(session()->get('role')) ?></p>

                    <div class="w-100 mt-auto">
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                <span class="text-muted"><i class="fas fa-id-card mr-2 text-primary"></i> NIP</span>
                                <span class="font-weight-medium"><?= session()->get('nip') ?? '-' ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                <span class="text-muted"><i class="fas fa-envelope mr-2 text-primary"></i> Email</span>
                                <span class="font-weight-medium text-truncate" style="max-width: 180px;"><?= session()->get('email') ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                <span class="text-muted"><i class="fas fa-phone mr-2 text-primary"></i> Telepon</span>
                                <span class="font-weight-medium"><?= session()->get('no_telepon') ?? '-' ?></span>
                            </li>
                        </ul>
                        
                        <?php if(session()->get('role') === 'admin' || session()->get('role') === 'super_admin'): ?>
                        <a href="<?= base_url('admin/settings/settingapps') ?>" class="btn btn-outline-primary btn-block py-2 shadow-sm rounded-pill">
                            <i class="fas fa-cogs mr-2"></i> Pengaturan Aplikasi
                        </a>
                        <?php endif; ?>
                        <?php if(session()->get('role') === 'admin' || session()->get('role') === 'super_admin'): ?>
                        <a href="<?= base_url('admin/autoroute') ?>" class="btn btn-outline-primary btn-block py-2 shadow-sm rounded-pill">
                            <i class="fas fa-cogs mr-2"></i> Autoroute
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 p-4 pb-0">
                    <h3 class="card-title font-weight-bold text-primary"><i class="fas fa-user-edit mr-2"></i> Edit Profil</h3>
                </div>
                <div class="card-body p-4">
                     <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form class="form-horizontal mt-3" action="<?= base_url('admin/settings/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="form-group row align-items-center">
                            <label for="inputName" class="col-sm-3 col-form-label text-muted font-weight-medium">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light border-0 h-auto py-2" id="inputName" name="nama" value="<?= old('nama', session()->get('nama')) ?>" placeholder="Nama Lengkap">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="inputNIP" class="col-sm-3 col-form-label text-muted font-weight-medium">NIP</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-id-badge text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light border-0 h-auto py-2" id="inputNIP" name="nip" value="<?= old('nip', session()->get('nip')) ?>" placeholder="Nomor Induk Pegawai">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="inputEmail" class="col-sm-3 col-form-label text-muted font-weight-medium">Email</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" class="form-control bg-light border-0 h-auto py-2" id="inputEmail" name="email" value="<?= old('email', session()->get('email')) ?>" placeholder="Email Address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="inputPhone" class="col-sm-3 col-form-label text-muted font-weight-medium">No. Telepon</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-phone text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control bg-light border-0 h-auto py-2" id="inputPhone" name="no_telepon" value="<?= old('no_telepon', session()->get('no_telepon')) ?>" placeholder="Nomor Telepon">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputAddress" class="col-sm-3 col-form-label text-muted font-weight-medium">Alamat</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                    </div>
                                    <textarea class="form-control bg-light border-0" id="inputAddress" name="alamat" rows="3" placeholder="Alamat Lengkap"><?= old('alamat', session()->get('alamat')) ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row align-items-center">
                            <label for="inputFoto" class="col-sm-3 col-form-label text-muted font-weight-medium">Foto Profil</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputFoto" name="profile_picture" accept="image/*">
                                    <label class="custom-file-label bg-light border-0" for="inputFoto">Pilih file foto...</label>
                                </div>
                                <small class="form-text text-muted mt-2"><i class="fas fa-info-circle mr-1"></i> Format: JPG, PNG. Maksimal 2MB.</small>
                            </div>
                        </div>

                        <div class="form-group row mt-5">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm rounded-pill font-weight-bold">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-user-img {
        transition: transform 0.3s ease;
    }
    .profile-user-img:hover {
        transform: scale(1.05);
    }
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
    }
    .input-group-text {
        border-radius: 0.25rem 0 0 0.25rem;
    }
    .custom-file-label::after {
        background-color: #e9ecef;
        color: #495057;
    }
</style>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>