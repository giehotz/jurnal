<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Allowed Routes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/autoroute') ?>">Auto-Route</a></li>
                    <li class="breadcrumb-item active">Allowed Routes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Allowed Routes</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRouteModal">
                                <i class="fas fa-plus"></i> Add Route
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Module</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allowed_routes as $route) : ?>
                                    <tr>
                                        <td><?= $route['id'] ?></td>
                                        <td><code><?= esc($route['module']) ?></code></td>
                                        <td><span class="badge badge-info"><?= esc($route['role']) ?></span></td>
                                        <td>
                                            <?php if ($route['enabled']) : ?>
                                                <span class="badge badge-success">Enabled</span>
                                            <?php else : ?>
                                                <span class="badge badge-secondary">Disabled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/autoroute/toggle-allowed/' . $route['id']) ?>" 
                                               class="btn btn-sm btn-warning" 
                                               onclick="return confirm('Toggle this route?')">
                                                <i class="fas fa-toggle-on"></i>
                                            </a>
                                            <a href="<?= base_url('admin/autoroute/delete-allowed/' . $route['id']) ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Delete this route?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Route Modal -->
<div class="modal fade" id="addRouteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/autoroute/add-allowed') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add Allowed Route</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="module">Module</label>
                        <input type="text" class="form-control" id="module" name="module" required 
                               placeholder="e.g., siswa, guru, kelas">
                        <small class="form-text text-muted">The module/controller name (lowercase)</small>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
