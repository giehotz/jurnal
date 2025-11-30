<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>


<div class="content">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Auto-Route Activity Logs</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/autoroute/clear-logs') ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to clear all logs?')">
                                <i class="fas fa-trash"></i> Clear All Logs
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>IP</th>
                                    <th>URI</th>
                                    <th>Controller</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logs)) : ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No logs found</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($logs as $log) : ?>
                                        <tr>
                                            <td><?= $log['id'] ?></td>
                                            <td><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></td>
                                            <td><?= $log['user_id'] ?? 'N/A' ?></td>
                                            <td><span class="badge badge-info"><?= esc($log['role']) ?></span></td>
                                            <td><small><?= esc($log['ip']) ?></small></td>
                                            <td><code><?= esc($log['uri']) ?></code></td>
                                            <td><small><?= esc($log['controller']) ?></small></td>
                                            <td><code><?= esc($log['method']) ?></code></td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'generated' => 'success',
                                                    'blocked' => 'danger',
                                                    'ignored' => 'warning'
                                                ];
                                                $class = $statusClass[$log['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $class ?>"><?= esc($log['status']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!empty($logs)) : ?>
                        <div class="card-footer clearfix">
                            <?= $pager->links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
