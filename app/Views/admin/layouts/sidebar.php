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
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/users') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'users') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    
                    <li class="nav-item has-treeview <?= (isset($active_menu) && in_array($active_menu, ['kelas', 'rombel', 'siswa', 'absensi', 'ruangan', 'pindah_kelas', 'mapel'])) ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= (isset($active_menu) && in_array($active_menu, ['kelas', 'rombel', 'siswa', 'absensi', 'ruangan', 'pindah_kelas', 'mapel'])) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-school"></i>
                            <p>
                                Manajemen Data
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('admin/ruangan') ?>"
                                   class="nav-link <?= (isset($active_menu) && $active_menu == 'ruangan') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ruangan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/rombel') ?>"
                                   class="nav-link <?= (isset($active_menu) && $active_menu == 'rombel') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manajemen Rombel</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/siswa') ?>"
                                   class="nav-link <?= (isset($active_menu) && $active_menu == 'siswa') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/absensi') ?>"
                                   class="nav-link <?= (isset($active_menu) && $active_menu == 'absensi') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Absensi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/pindah-kelas') ?>"
                                   class="nav-link <?= (isset($active_menu) && $active_menu == 'pindah_kelas') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pindah Kelas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/mapel') ?>"
                                   class="nav-link <?= (isset($active_menu) && $active_menu == 'mapel') ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Mata Pelajaran</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/monitoring') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'monitoring') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-search"></i>
                            <p>Monitoring Jurnal</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/laporan/statistik') ?>" 
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'statistik') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Statistik</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/laporan') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'laporan') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                    
                    <li class="nav-item text-danger">
                        <a href="<?= base_url('admin/laporan/export') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'export') ? 'active' : '' ?>">
                            <i class="nav-icon fa-solid fa-file-export"></i>
                            <p>Export</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('admin/settings') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'settings') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Pengaturan</p>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if (session()->get('role') == 'guru'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('guru/dashboard') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('guru/jurnal') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'jurnal') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Jurnal Mengajar</p>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if (session()->get('role') == 'kepala_sekolah'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('kepala_sekolah/dashboard') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('kepala_sekolah/laporan') ?>"
                           class="nav-link <?= (isset($active_menu) && $active_menu == 'laporan') ? 'active' : '' ?>">
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