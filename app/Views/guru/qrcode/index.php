<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">QR Code Manager</h1>
        <a href="<?= base_url('guru/qrcode/create') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Generate QR Code
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($urls)) : ?>
            <?php foreach ($urls as $i => $url) : ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card shadow h-100 py-2" style="border-radius: 15px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h1 class="btn btn-primary  display-4 font-weight-bold text-white ml-2" style="font-family: 'Poppins', sans-serif; line-height: 1; "><?= $i + 1 ?></h1>
                                <div class="text-right small text-muted" style="font-size: 0.8rem;">
                                    <div>Dibuat Pada : <?= date('d M Y H:i', strtotime($url['created_at'])) ?></div>
                                    <div>Slug : <?= esc($url['short_slug']) ?></div>
                                </div>
                            </div>

                            <div class="text-center my-2">
                                <img src="<?= base_url('guru/qrcode/render/' . $url['id']) ?>?v=<?= strtotime($url['updated_at'] ?? $url['created_at']) ?>" alt="QR Code" class="img-fluid" style="max-height: 180px;">
                            </div>

                            <div class="text-center mb-3">
                                <h6 class="font-weight-bold text-uppercase text-dark mb-1"><?= esc($url['custom_name']) ?></h6>
                                <small class="text-muted d-block text-truncate px-3" title="<?= esc($url['original_url']) ?>"><?= esc($url['original_url']) ?></small>
                            </div>

                            <div class="d-flex justify-content-center mb-3">
                                <a href="<?= base_url('guru/qrcode/show/' . $url['id']) ?>" class="btn btn-info btn-sm mx-1" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('guru/qrcode/edit/' . $url['id']) ?>" class="btn btn-warning btn-sm mx-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('guru/qrcode/download/' . $url['id']) ?>" class="btn btn-success btn-sm mx-1" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="<?= base_url('guru/qrcode/delete/' . $url['id']) ?>" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>

                            <div class="px-3">
                                <a href="<?= esc($url['original_url']) ?>" target="_blank" class="btn btn-success btn-block rounded-pill font-weight-bold">Buka URL</a>
                                <button type="button" class="btn btn-primary btn-block rounded-pill font-weight-bold mt-2 btn-preview" data-url="<?= esc($url['original_url']) ?>">
                                    <i class="fas fa-eye mr-1"></i> Preview Dokumen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada QR Code yang dibuat. Silakan buat baru.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Modal Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 90vw;">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-file-alt mr-2"></i>Preview Dokumen
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 h-100 bg-light">
                <div class="d-flex justify-content-center align-items-center h-100" id="loadingPreview">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <iframe id="previewFrame" src="" style="width: 100%; height: 100%; border: none; display: none;" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn-preview').on('click', function() {
            var url = $(this).data('url');
            var title = $(this).closest('.card').find('h6').text();

            // Konversi URL Google Drive ke mode preview embed
            if (url.includes('drive.google.com') && url.includes('/view')) {
                url = url.replace('/view', '/preview');
            } else if (url.includes('drive.google.com') && !url.includes('/preview')) {
                // Attempt to force preview if it looks like a direct link not ending in preview
                // This covers format: drive.google.com/file/d/ID
                if (!url.endsWith('/preview')) {
                    // Check if it ends with a slash
                    if (url.endsWith('/')) {
                        url = url + 'preview';
                    } else {
                        url = url + '/preview';
                    }
                }
            }

            // Set title modal
            $('#previewModalLabel').html('<i class="fas fa-file-alt mr-2"></i>Preview: ' + title);

            // Show loading, hide frame
            $('#loadingPreview').show();
            $('#previewFrame').hide();
            $('#previewFrame').attr('src', url);

            $('#previewModal').modal('show');

            $('#previewFrame').on('load', function() {
                $('#loadingPreview').hide();
                $('#previewFrame').show();
            });
        });

        $('#previewModal').on('hidden.bs.modal', function() {
            $('#previewFrame').attr('src', '');
        });
    });
</script>
<?= $this->endSection() ?>