<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard Admin') ?> - Jurnal Guru</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?= base_url('vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link text-danger"
                   href="<?= base_url('auth/logout') ?>"
                   onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= base_url('admin/dashboard') ?>" class="brand-link">
            <img src="<?= base_url('uploads/logos/logo.png') ?>" alt="Logo Sekolah" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
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
                        <img src="<?= base_url('uploads/profile_pictures/' . $profilePicture) ?>" 
                             class="img-circle elevation-2" alt="User Image">
                    <?php else: ?>
                        <img src="<?= base_url('uploads/profile_pictures/default.png') ?>" 
                             class="img-circle elevation-2" alt="User Image">
                    <?php endif; ?>
                </div>
                <div class="info">
                    <a href="<?= base_url('admin/profile') ?>" class="d-block">
                        <?= esc(session()->get('nama') ?? 'Administrator') ?>
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <?php if (in_array(session()->get('role'), ['admin', 'super_admin'])): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/dashboard') ?>"
                               class="nav-link <?= (service('uri')->getPath() == 'admin/dashboard') ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('admin/user-management') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/user-management') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>User Management</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('admin/kelas') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/kelas') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-school"></i>
                                <p>Kelas</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('admin/mapel') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/mapel') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Mata Pelajaran</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('admin/monitoring') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/monitoring') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-search"></i>
                                <p>Monitoring Jurnal</p>
                            </a>
                        </li>
                               <li class="nav-item">
                        <a href="<?= base_url('admin/laporan/statistik') ?>" class="nav-link <?= (isset($active_menu) && $active_menu == 'settings') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Statistik</p>
                        </a>
                         </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/laporan') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Laporan</p>
                            </a>
                        </li>
                          <li class="nav-item">
                            <a href="<?= base_url('admin/siswa') ?>"
                               class="nav-link <?= ($active ?? '') === 'siswa' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manajemen Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item text-danger">
                            <a href="<?= base_url('admin/laporan/export') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/laporan/export') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fa-solid fa-file-export"></i>
                                <p>Export</p>
                            </a>
                        </li>
                          <li class="nav-item">
                            <a href="<?= base_url('admin/siswa') ?>"
                               class="nav-link <?= ($active ?? '') === 'siswa' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manajemen Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/settings') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'admin/settings') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Pengaturan</p>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (session()->get('role') == 'guru'): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('guru/dashboard') ?>"
                               class="nav-link <?= (service('uri')->getPath() == 'guru/dashboard') ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('guru/jurnal') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'guru/jurnal') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Jurnal Mengajar</p>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (session()->get('role') == 'kepala_sekolah'): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('kepala_sekolah/dashboard') ?>"
                               class="nav-link <?= (service('uri')->getPath() == 'kepala_sekolah/dashboard') ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= base_url('kepala_sekolah/laporan') ?>"
                               class="nav-link <?= (strpos(service('uri')->getPath(), 'kepala_sekolah/laporan') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Laporan</p>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= esc($title ?? 'Dashboard') ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active"><?= esc($title ?? 'Dashboard') ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Hak Cipta &copy; <?= date('Y') ?> <a href="<?= base_url() ?>">Jurnal Guru</a>.</strong>
        Hak cipta dilindungi.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versi</b> 1.0.0
        </div>
    </footer>

</div>
<!-- ./wrapper -->

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js') ?>"></script>
<script src="<?= base_url('vendor/almasaeed2010/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
