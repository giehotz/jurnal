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

/* Ensure smooth sidebar transition */
.main-sidebar {
    transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out;
}

.content-wrapper,
.main-footer,
.main-header {
    transition: margin 0.3s ease-in-out;
}
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!--
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
        -->
    </ul>

    <!-- SEARCH FORM -->
    <!--
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <!--
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        -->
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
                <!-- Menu Body -->
                <!--
                <li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                    </div>
                </li>
                -->
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="<?= base_url('guru/profile') ?>" class="btn btn-default btn-flat">Profile</a>
                    <a href="<?= base_url('auth/logout') ?>" class="btn btn-default btn-flat float-right" id="logoutButton">Sign out</a>
                </li>
            </ul>
        </li>
        <!--
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
        -->
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
    
    // Manual toggle for sidebar
    $('[data-widget="pushmenu"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
    });
});
</script>