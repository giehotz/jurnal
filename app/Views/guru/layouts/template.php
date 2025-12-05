<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Dashboard Guru') ?> - Jurnal Guru</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url('AdminLTE/dist/css/adminlte.min.css') ?>">

    <link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">

    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

        <?= $this->include('guru/layouts/header') ?>

        <?= $this->include('guru/layouts/sidebar') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= esc($title ?? 'Dashboard') ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item active"><?= esc($title ?? 'Dashboard') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>
        <?= $this->include('guru/layouts/footer') ?>

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Moment.js (Required for Tempusdominus) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="<?= base_url('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url('AdminLTE/dist/js/adminlte.min.js') ?>"></script>

        <!-- Tambahkan script untuk inisialisasi komponen AdminLTE -->
        <script>
            // Gunakan jQuery ready untuk memastikan semua library terload
            $(document).ready(function() {
                console.log('Initializing AdminLTE components...');

                // Inisialisasi layout AdminLTE
                try {
                    if (typeof $.fn.layout !== 'undefined') {
                        $('body').layout({
                            scroll: true,
                            fixedSidebar: true,
                            fixedNavbar: true
                        });
                        console.log('✓ Layout initialized');
                    }
                } catch(e) {
                    console.warn('Layout error:', e);
                }

                // Inisialisasi komponen treeview
                try {
                    if (typeof $.fn.Treeview !== 'undefined') {
                        $('[data-widget="treeview"]').Treeview();
                        console.log('✓ Treeview initialized');
                    }
                } catch(e) {
                    console.warn('Treeview error:', e);
                }

                // Inisialisasi PushMenu untuk sidebar collapse
                try {
                    if (typeof $.fn.PushMenu !== 'undefined') {
                        $('[data-widget="pushmenu"]').PushMenu();
                        console.log('✓ PushMenu initialized');
                    } else {
                        console.warn('⚠ PushMenu plugin not found');
                    }
                } catch(e) {
                    console.error('PushMenu error:', e);
                }

                // Inisialisasi ControlSidebar
                try {
                    if (typeof $.fn.ControlSidebar !== 'undefined') {
                        $('[data-widget="control-sidebar"]').ControlSidebar();
                        console.log('✓ ControlSidebar initialized');
                    }
                } catch(e) {
                    console.warn('ControlSidebar error:', e);
                }

                // Inisialisasi Dropdown
                try {
                    if (typeof $.fn.Dropdown !== 'undefined') {
                        $('[data-widget="dropdown"]').Dropdown();
                        console.log('✓ Dropdown initialized');
                    }
                } catch(e) {
                    console.warn('Dropdown error:', e);
                }

                // Inisialisasi tab Bootstrap 4
                try {
                    if (typeof $.fn.tab !== 'undefined') {
                        $('button[data-toggle="tab"]').on('shown.bs.tab', function(event) {
                            console.log('Tab activated:', $(this).attr('href'));
                        });
                        console.log('✓ Tab initialization set up');
                    }
                } catch(e) {
                    console.warn('Tab error:', e);
                }

                // Inisialisasi semua Tempusdominus DateTimePicker
                try {
                    if (typeof $.fn.datetimepicker !== 'undefined') {
                        $('[id$="_picker"]').datetimepicker({
                            format: 'DD/MM/YYYY',
                            locale: 'id',
                            allowInputToggle: true,
                            useCurrent: false
                        });
                        console.log('✓ DateTimePicker initialized');
                    }
                } catch(e) {
                    console.warn('DateTimePicker error:', e);
                }

                // Fallback: Tambah click handler untuk sidebar toggle jika PushMenu gagal
                try {
                    $('[data-widget="pushmenu"]').on('click', function(e) {
                        e.preventDefault();
                        $('body').toggleClass('sidebar-collapse');
                        console.log('Sidebar toggled via fallback');
                    });
                } catch(e) {
                    console.warn('Fallback PushMenu handler error:', e);
                }
            });

            // Alternative: Inisialisasi dengan DOMContentLoaded sebagai fallback
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('DOMContentLoaded fired');
                });
            }
        </script>

        <!-- Chart.js untuk grafik aktivitas mengajar -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <?= $this->renderSection('scripts') ?>

    </div>
</body>

</html>