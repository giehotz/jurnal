<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
            </div>
            <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" value="<?= old('nip', $user['nip'] ?? '') ?>" placeholder="Masukkan NIP">
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama', $user['nama']) ?>" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required placeholder="Masukkan email">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="guru" <?= ($user['role'] == 'guru') ? 'selected' : '' ?>>Guru</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="super_admin" <?= ($user['role'] == 'super_admin') ? 'selected' : '' ?>>Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <select class="form-control" id="is_active" name="is_active" required>
                            <option value="1" <?= ($user['is_active']) ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= (!$user['is_active']) ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru (opsional)</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Petunjuk</h3>
            </div>
            <div class="card-body">
                <ul>
                    <li>Isi semua field yang bertanda *</li>
                    <li>Email harus unik dan belum terdaftar</li>
                    <li>NIP harus unik untuk setiap guru</li>
                    <li>Role menentukan hak akses user</li>
                    <li>User dengan status non-aktif tidak dapat login</li>
                </ul>
            </div>
        </div>
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Reset Password</h3>
            </div>
            <div class="card-body">
                <p>Untuk mereset password user, klik tombol berikut. Password akan direset ke default '12345678'.</p>
                <a href="<?= base_url('admin/users/reset_password/' . $user['id']) ?>" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin mereset password user ini?')">
                    <i class="fas fa-key"></i> Reset Password
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
