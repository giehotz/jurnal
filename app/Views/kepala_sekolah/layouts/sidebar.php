<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('kepala_sekolah/dashboard') ?>" class="brand-link">
        <img src="<?= base_url('AdminLTE/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Jurnal Guru</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
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
                <a href="#" class="d-block"><?= session()->get('nama') ?? 'Kepala Sekolah' ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= base_url('kepala_sekolah/dashboard') ?>" class="nav-link <?= (isset($active) && $active == 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('kepala_sekolah/laporan') ?>" class="nav-link <?= (isset($active) && $active == 'laporan') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Laporan & Statistik
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('kepala_sekolah/monitoring') ?>" class="nav-link <?= (isset($active) && $active == 'monitoring') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-desktop"></i>
                        <p>
                            Monitor Jurnal
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('kepala_sekolah/profile') ?>" class="nav-link <?= (isset($active) && $active == 'profile') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>