<style>
    /* Ensure user menu dropdown is visible */
    .user-menu .dropdown-menu {
        z-index: 1000;
        min-width: 280px;
    }

    .user-menu .dropdown-menu.show {
        display: block !important;
    }

    .user-menu .user-footer {
        padding: 10px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .user-menu .user-footer .btn-default {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #6c757d;
    }

    .user-menu .user-footer .btn-default:hover {
        background-color: #e9ecef;
    }

    /* Sidebar collapse styling */
    .sidebar-collapse .main-sidebar {
        margin-left: -260px;
    }

    .sidebar-collapse .content-wrapper,
    .sidebar-collapse .main-footer {
        margin-left: 0;
    }

    /* Smooth transitions for sidebar */
    .main-sidebar,
    .content-wrapper,
    .main-footer,
    .main-header {
        transition: margin-left 0.3s ease-in-out;
    }

    /* Ensure smooth sidebar transition */
    .main-sidebar {
        transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out;
    }

    .content-wrapper,
    .main-footer,
    .main-header {
        transition: margin 0.3s ease-in-out;
    }

    /* Pushmenu button styling */
    [data-widget="pushmenu"] {
        cursor: pointer;
        user-select: none;
    }

    [data-widget="pushmenu"]:hover {
        opacity: 0.8;
    }
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <?php
                $profilePicture = session()->get('profile_picture');
                if (!empty($profilePicture) && $profilePicture !== 'default.png'): ?>
                    <img src="<?= base_url('uploads/profile_pictures/' . $profilePicture) ?>" class="user-image img-circle elevation-2" alt="User Image">
                <?php else: ?>
                    <img src="<?= base_url('uploads/profile_pictures/default.png') ?>" class="user-image img-circle elevation-2" alt="User Image">
                <?php endif; ?>
                <span class="d-none d-md-inline"><?= session()->get('nama') ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <?php
                    $profilePicture = session()->get('profile_picture');
                    if (!empty($profilePicture) && $profilePicture !== 'default.png'): ?>
                        <img src="<?= base_url('uploads/profile_pictures/' . $profilePicture) ?>" class="img-circle elevation-2" alt="User Image">
                    <?php else: ?>
                        <img src="<?= base_url('uploads/profile_pictures/default.png') ?>" class="img-circle elevation-2" alt="User Image">
                    <?php endif; ?>
                    <p>
                        <?= session()->get('nama') ?>
                        <small><?= session()->get('email') ?></small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="<?= base_url('guru/profile') ?>" class="btn btn-default btn-flat">Profile</a>
                    <a href="<?= base_url('auth/logout') ?>" class="btn btn-default btn-flat float-right" id="logoutButton">Sign out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<script>
    // Tambahkan konfirmasi logout
    document.addEventListener('DOMContentLoaded', function() {
        var logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    window.location.href = '<?= base_url('auth/logout') ?>';
                }
            });
        }
    });

    // Ensure dropdown functionality works
    $(document).ready(function() {
        $('.user-menu .nav-link').on('click', function(e) {
            e.preventDefault();
            $('.user-menu .dropdown-menu').toggleClass('show');
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.user-menu').length) {
                $('.user-menu .dropdown-menu').removeClass('show');
            }
        });
    });
</script>