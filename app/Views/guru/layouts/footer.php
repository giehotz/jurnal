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
    // Patch ini harus dijalankan sebelum bootstrap.bundle.min.js dimuat
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

<!-- Initialize AdminLTE -->
<script>
    $(document).ready(function() {
        try {
            // Inisialisasi layout AdminLTE dengan opsi fixed
            if (typeof $.fn.layout !== 'undefined') {
                $('body').layout({
                    scroll: true,
                    fixedSidebar: true,
                    fixedNavbar: true
                });
            } else {
                console.warn('Layout plugin not loaded');
            }

            // Inisialisasi komponen treeview
            if (typeof $.fn.Treeview !== 'undefined') {
                $('[data-widget="treeview"]').Treeview();
            } else {
                console.warn('Treeview plugin not loaded');
            }

            // Inisialisasi komponen pushmenu (sidebar toggle)
            if (typeof $.fn.PushMenu !== 'undefined') {
                $('[data-widget="pushmenu"]').PushMenu();
            } else {
                console.warn('PushMenu plugin not loaded');
            }

            // Inisialisasi komponen control-sidebar
            if (typeof $.fn.ControlSidebar !== 'undefined') {
                $('[data-widget="control-sidebar"]').ControlSidebar();
            } else {
                console.warn('ControlSidebar plugin not loaded');
            }

            // Inisialisasi komponen dropdown
            if (typeof $.fn.Dropdown !== 'undefined') {
                $('[data-widget="dropdown"]').Dropdown();
            } else {
                console.warn('Dropdown plugin not loaded');
            }

            // Ensure Bootstrap dropdowns work properly
            if (typeof $.fn.dropdown !== 'undefined') {
                $('.dropdown-toggle').dropdown();
            } else {
                console.warn('Bootstrap dropdown not available');
            }

            // Add manual event handler for treeview menu items on mobile
            $('.nav-sidebar .nav-link').on('click', function(e) {
                var $this = $(this);
                var $parent = $this.parent();
                var $submenu = $this.next('.nav-treeview');

                // Only handle items with submenus
                if ($submenu.length > 0) {
                    e.preventDefault();

                    // On mobile devices, ensure menu opens properly
                    if ($(window).width() <= 768) {
                        // Close other open menus
                        $('.nav-item').not($parent).removeClass('menu-open');

                        // Toggle current menu
                        $parent.toggleClass('menu-open');
                    } else {
                        // On desktop, just toggle the menu
                        $parent.toggleClass('menu-open');
                    }
                }
            });

            // Handle touch events for mobile devices
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                $('.nav-sidebar .nav-link').each(function() {
                    var $this = $(this);
                    var $parent = $this.parent();
                    var $submenu = $this.next('.nav-treeview');

                    if ($submenu.length > 0) {
                        // Add touch-specific classes or attributes if needed
                        $this.addClass('touch-enabled');
                    }
                });
            }
        } catch (error) {
            console.error('Error initializing AdminLTE components:', error);
            // Send error to server for logging if possible
            try {
                $.post('/log-js-error', {
                    error: error.toString(),
                    file: 'footer.php',
                    line: error.lineNumber
                });
            } catch (loggingError) {
                console.warn('Failed to log error to server:', loggingError);
            }
        }
    });
</script>

<?= $this->renderSection('scripts') ?>
</body>

</html>