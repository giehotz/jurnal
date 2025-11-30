<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Toggle Card -->
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Auto-Route Generator Control</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="autoRouteToggle">Generator Status</label>
                            <div class="custom-control custom-switch custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="autoRouteToggle" 
                                    <?= $auto_route_enabled ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="autoRouteToggle">
                                    <span id="toggleLabel"><?= $auto_route_enabled ? 'Enabled' : 'Disabled' ?></span>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                When enabled, the system will automatically generate controllers, methods, and routes for valid requests.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Generated</span>
                                        <span class="info-box-number"><?= $stats['generated'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Blocked</span>
                                        <span class="info-box-number"><?= $stats['blocked'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-eye-slash"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Ignored</span>
                                        <span class="info-box-number"><?= $stats['ignored'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-route"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Allowed Routes</span>
                                        <span class="info-box-number"><?= $stats['total_allowed_routes'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <a href="<?= base_url('admin/autoroute/allowed') ?>" class="btn btn-primary">
                            <i class="fas fa-list"></i> Manage Allowed Routes
                        </a>
                        <a href="<?= base_url('admin/autoroute/logs') ?>" class="btn btn-info">
                            <i class="fas fa-history"></i> View Activity Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('autoRouteToggle').addEventListener('change', function() {
    const enabled = this.checked ? 1 : 0;
    const label = document.getElementById('toggleLabel');
    
    fetch('<?= base_url('admin/autoroute/update-toggle') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'enabled=' + enabled
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            label.textContent = enabled ? 'Enabled' : 'Disabled';
            // Show toast notification
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Success',
                body: data.message
            });
        } else {
            // Revert toggle
            this.checked = !this.checked;
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        this.checked = !this.checked;
    });
});
</script>
<?= $this->endSection() ?>
