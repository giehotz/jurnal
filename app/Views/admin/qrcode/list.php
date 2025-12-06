<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/custom-admin.css') ?>">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    .qr-preview-sm {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 2px;
        background: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="custom-card shadow-sm">
            <div class="custom-card-header d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-emerald">Daftar QR Code User</h6>
                <a href="<?= base_url('admin/qrcode/settings') ?>" class="btn btn-sm btn-light border">
                    <i class="fas fa-cog mr-1"></i> Pengaturan Global
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="qrTable" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">User</th>
                                <th width="25%">Label / URL</th>
                                <th width="15%">Preview</th>
                                <th width="15%">Tanggal Dibuat</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($entries)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada QR Code yang dibuat.</td>
                                </tr>
                            <?php else: ?>
                                <?php $i = 1;
                                foreach ($entries as $entry): ?>
                                    <tr>
                                        <td class="text-center align-middle"><?= $i++ ?></td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <?php
                                                $photo = $entry['profile_picture'] ? 'uploads/profile/' . $entry['profile_picture'] : 'assets/img/default-profile.png';
                                                if (!file_exists(FCPATH . $photo)) {
                                                    $photo = 'assets/img/default-profile.png';
                                                }
                                                ?>
                                                <img src="<?= base_url($photo) ?>" alt="User" class="user-avatar mr-3">
                                                <div>
                                                    <div class="font-weight-bold text-dark"><?= esc($entry['user_name']) ?></div>
                                                    <div class="small text-muted"><?= ucfirst(esc($entry['user_role'])) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-primary mb-1"><?= esc($entry['custom_name']) ?></div>
                                            <div class="small text-muted text-truncate" style="max-width: 200px;" title="<?= esc($entry['original_url']) ?>">
                                                <i class="fas fa-link mr-1"></i> <?= esc($entry['original_url']) ?>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <span class="badge badge-light border">Slug: <?= esc($entry['short_slug']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <img src="<?= base_url('admin/qrcode/render/' . $entry['id']) ?>" alt="QR" class="qr-preview-sm" loading="lazy">
                                        </td>
                                        <td class="align-middle">
                                            <div class="small text-secondary">
                                                <i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y', strtotime($entry['created_at'])) ?>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="far fa-clock mr-1"></i> <?= date('H:i', strtotime($entry['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info view-qr-btn"
                                                    data-id="<?= $entry['id'] ?>"
                                                    data-name="<?= esc($entry['custom_name']) ?>"
                                                    data-url="<?= esc($entry['original_url']) ?>"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="<?= base_url('admin/qrcode/download/' . $entry['id']) ?>" class="btn btn-sm btn-success" title="Download PNG">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal View QR -->
<div class="modal fade" id="viewQRModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-emerald" id="viewQRTitle">Detail QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center pt-4">
                <img id="modalQRImage" src="" class="img-fluid mb-3 shadow-sm border rounded p-2" style="max-height: 300px;">
                <h5 id="modalQRName" class="font-weight-bold text-dark mb-1"></h5>
                <p id="modalQRUrl" class="text-muted small text-break mb-4"></p>

                <div class="d-flex justify-content-center">
                    <a id="modalDownloadBtn" href="#" class="btn btn-success px-4 rounded-pill">
                        <i class="fas fa-download mr-2"></i> Download Image
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#qrTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "order": [
                [4, "desc"]
            ] // Sort by date created desc
        });

        // View QR Handler
        $('.view-qr-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = $(this).data('url');
            const renderUrl = '<?= base_url('admin/qrcode/render') ?>/' + id;
            const downloadUrl = '<?= base_url('admin/qrcode/download') ?>/' + id;

            $('#modalQRImage').attr('src', renderUrl);
            $('#modalQRName').text(name);
            $('#modalQRUrl').text(url);
            $('#modalDownloadBtn').attr('href', downloadUrl);

            $('#viewQRModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>