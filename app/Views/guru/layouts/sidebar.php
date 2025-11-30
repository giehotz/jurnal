<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('guru/dashboard') ?>" class="brand-link">
        <img src="<?= base_url('AdminLTE/dist/img/AdminLTELogo.png') ?>"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Jurnal Guru</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <?php 
                $profilePicture = session()->get('profile_picture');
                if (!empty($profilePicture) && $profilePicture !== 'default.png'): ?>
                    <img src="<?= base_url('uploads/profile_pictures/' . $profilePicture) ?>" class="img-circle elevation-2" alt="User Image">
                <?php else: ?>
                    <img src="<?= base_url('uploads/profile_pictures/default.png') ?>" class="img-circle elevation-2" alt="User Image">
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="<?= base_url('guru/profile') ?>" class="d-block"><?= esc(session()->get('nama') ?? 'Guru') ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('guru/dashboard') ?>"
                       class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Jurnal -->
                <li class="nav-item">
                    <a href="<?= base_url('guru/jurnal') ?>"
                       class="nav-link <?= ($active ?? '') === 'jurnal' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Jurnal Mengajar</p>
                    </a>
                </li>

                <!-- Absensi -->
                <li class="nav-item">
                    <a href="<?= base_url('guru/absensi') ?>"
                       class="nav-link <?= ($active ?? '') === 'absensi' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>Absensi Siswa</p>
                    </a>
                </li>

                <!-- Profile -->
                <li class="nav-item">
                    <a href="<?= base_url('guru/profile') ?>"
                       class="nav-link <?= ($active ?? '') === 'profile' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>

                 <li class="nav-item">
                    <a href="<?= base_url('auth/logout') ?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>