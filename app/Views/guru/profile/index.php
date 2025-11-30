<?= $this->extend('guru/layouts/template') ?>
<style> 
.profile-user-img {
    height: 100px;
    width: 100px;
    object-fit: cover;
    border-radius: 50%; /* Pastikan ini ada (biasanya sudah ada di kelas img-circle) */
}
</style>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <?php 
                    $profilePicture = $user['profile_picture'];
                    if (!empty($profilePicture) && $profilePicture !== 'default.png'): ?>
                        <img class="profile-user-img img-fluid img-circle"
                             src="<?= base_url('uploads/profile_pictures/' . $profilePicture) ?>"
                             alt="User profile picture">
                    <?php else: ?>
                        <img class="profile-user-img img-fluid img-circle"
                             src="<?= base_url('uploads/profile_pictures/default.png') ?>"
                             alt="User profile picture">
                    <?php endif; ?>
                </div>

                <h3 class="profile-username text-center"><?= session()->get('nama') ?></h3>

                <p class="text-muted text-center"><?= ucfirst(session()->get('role')) ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>NIP</b> <a class="float-right"><?= $user['nip'] ?? 'N/A' ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right"><?= $user['email'] ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <form class="form-horizontal" action="<?= base_url('guru/profile/update') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputName" name="nama" value="<?= old('nama', $user['nama']) ?>" placeholder="Nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputNIP" class="col-sm-2 col-form-label">NIP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputNIP" name="nip" value="<?= old('nip', $user['nip']) ?>" placeholder="NIP">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" name="email" value="<?= old('email', $user['email']) ?>" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-sm-2 col-form-label">No. Telepon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputPhone" name="no_telepon" value="<?= old('no_telepon', $user['no_telepon']) ?>" placeholder="No. Telepon">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputAddress" name="alamat" placeholder="Alamat"><?= old('alamat', $user['alamat']) ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFoto" class="col-sm-2 col-form-label">Foto Profil</label>
                                <div class="col-sm-10">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputFoto" name="profile_picture" accept="image/*">
                                        <label class="custom-file-label" for="inputFoto">Pilih file</label>
                                    </div>
                                    <?php if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png'): ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url('uploads/profile_pictures/' . $user['profile_picture']) ?>" alt="Current profile picture" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<?= $this->endSection() ?>