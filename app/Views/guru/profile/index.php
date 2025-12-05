<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('styles') ?>
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 40px 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .profile-name {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        margin-bottom: 8px;
    }

    .profile-role {
        font-size: 14px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .profile-action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .profile-action-buttons .btn {
        flex: 1;
        min-width: 150px;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 15px;
        border-radius: 6px;
        border: 2px solid white;
        background: transparent;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .profile-action-buttons .btn:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .info-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
        word-break: break-all;
    }

    @media (max-width: 576px) {
        .profile-header {
            padding: 30px 20px;
            text-align: center;
        }

        .profile-name {
            font-size: 24px;
        }

        .profile-action-buttons {
            flex-direction: column;
        }

        .profile-action-buttons .btn {
            min-width: 100%;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Profile Header -->
    <div class="profile-header text-center">
        <?php 
        $profilePicture = $user['profile_picture'];
        if (!empty($profilePicture) && $profilePicture !== 'default.png'): ?>
            <img class="profile-avatar"
                 src="<?= base_url('uploads/profile_pictures/' . $profilePicture) ?>"
                 alt="<?= session()->get('nama') ?>">
        <?php else: ?>
            <img class="profile-avatar"
                 src="<?= base_url('uploads/profile_pictures/default.png') ?>"
                 alt="<?= session()->get('nama') ?>">
        <?php endif; ?>
        
        <h1 class="profile-name"><?= session()->get('nama') ?></h1>
        <p class="profile-role"><?= ucfirst(session()->get('role')) ?></p>

        <div class="profile-action-buttons">
            <a href="<?= base_url('guru/profile/edit') ?>" class="btn">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
            <a href="<?= base_url('guru/profile/change-password') ?>" class="btn">
                <i class="fas fa-key"></i> Ubah Password
            </a>
        </div>
    </div>

    <!-- Info Grid -->
    <div class="info-grid">
        <div class="info-card">
            <div class="info-label"><i class="fas fa-id-card"></i> NIP</div>
            <div class="info-value"><?= $user['nip'] ?? 'Belum diisi' ?></div>
        </div>

        <div class="info-card">
            <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
            <div class="info-value"><?= $user['email'] ?></div>
        </div>

        <div class="info-card">
            <div class="info-label"><i class="fas fa-phone"></i> No. Telepon</div>
            <div class="info-value"><?= $user['no_telepon'] ?? 'Belum diisi' ?></div>
        </div>

        <div class="info-card">
            <div class="info-label"><i class="fas fa-map-marker-alt"></i> Alamat</div>
            <div class="info-value"><?= $user['alamat'] ?? 'Belum diisi' ?></div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mb-4">
        <a href="<?= base_url('guru/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
<?= $this->endSection() ?>