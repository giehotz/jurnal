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
    <!-- AdminLTE App -->
    <script src="<?= base_url('AdminLTE/dist/js/adminlte.min.js') ?>"></script>

    <!-- Tambahkan script untuk inisialisasi komponen AdminLTE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi layout AdminLTE
            if (typeof $.fn.layout !== 'undefined') {
                $('body').layout({
                    scroll: true,
                    fixedSidebar: true,
                    fixedNavbar: true
                });
            }
            
            // Inisialisasi komponen treeview
            if (typeof $.fn.Treeview !== 'undefined') {
                $('[data-widget="treeview"]').Treeview();
            }
            
            // Inisialisasi komponen lainnya jika ada
            if (typeof $.fn.PushMenu !== 'undefined') {
                $('[data-widget="pushmenu"]').PushMenu();
            }
            
            if (typeof $.fn.ControlSidebar !== 'undefined') {
                $('[data-widget="control-sidebar"]').ControlSidebar();
            }
            
            if (typeof $.fn.Dropdown !== 'undefined') {
                $('[data-widget="dropdown"]').Dropdown();
            }
            
            // Inisialisasi tab Bootstrap 4
            if (typeof $.fn.tab !== 'undefined') {
                $('button[data-toggle="tab"]').on('shown.bs.tab', function (event) {
                    // Tab telah diaktifkan
                });
            }
        });
    </script>

    <!-- Chart.js untuk grafik aktivitas mengajar -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?= $this->renderSection('scripts') ?>

</div>
</body>
</html>