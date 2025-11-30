<!DOCTYPE html>
<html>
<head>
    <title>Test Base URL</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="<?= base_url('AdminLTE/dist/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Base URL Test</h3>
                                </div>
                                <div class="card-body">
                                    <p>Base URL: <?= $base_url ?></p>
                                    <p>AdminLTE CSS: <?= $adminlte_css ?></p>
                                    <p>Font Awesome CSS: <?= $fontawesome_css ?></p>
                                    
                                    <h2>Testing CSS Links:</h2>
                                    <p>If this text is styled, then CSS is working correctly.</p>
                                    <button class="btn btn-primary">Primary Button</button>
                                    <button class="btn btn-success">Success Button</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url('AdminLTE/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('AdminLTE/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>