<!-- /.content-wrapper -->
       <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">Jurnal Guru</a>.</strong>
            MIN 2 Tanggamus.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?= base_url('AdminLTE/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= base_url('AdminLTE/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- ChartJS -->
    <script src="<?= base_url('AdminLTE/plugins/chart.js/Chart.min.js') ?>"></script>
    <!-- Sparkline -->
    <script src="<?= base_url('AdminLTE/plugins/sparklines/sparkline.js') ?>"></script>
    <!-- JQVMap -->
    <script src="<?= base_url('AdminLTE/plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
    <script src="<?= base_url('AdminLTE/plugins/jqvmap/maps/jquery.vmap.usa.js') ?>"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?= base_url('AdminLTE/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
    <!-- daterangepicker -->
    <script src="<?= base_url('AdminLTE/plugins/moment/moment.min.js') ?>"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?= base_url('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
    <!-- Summernote -->
    <script src="<?= base_url('AdminLTE/plugins/summernote/summernote-bs4.min.js') ?>"></script>
    <!-- overlayScrollbars -->
    <script src="<?= base_url('AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('AdminLTE/dist/js/adminlte.js') ?>"></script>
    <!-- AdminLTE for demo purposes -->

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?= base_url('AdminLTE/dist/js/pages/dashboard.js') ?>"></script>
    
    <!-- Initialize AdminLTE -->
    <script>
        $(document).ready(function() {
            // Inisialisasi layout AdminLTE dengan opsi fixed
            $('body').layout({
                scroll: true,
                fixedSidebar: true,
                fixedNavbar: true
            });
            
            // Inisialisasi komponen treeview
            $('[data-widget="treeview"]').Treeview();
            
            // Inisialisasi komponen lainnya jika ada
            $('[data-widget="pushmenu"]').PushMenu();
            $('[data-widget="control-sidebar"]').ControlSidebar();
            $('[data-widget="dropdown"]').Dropdown();
        });
    </script>
</body>
</html>