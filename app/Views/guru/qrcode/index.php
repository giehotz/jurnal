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
                                <h1 class="display-4 font-weight-bold text-dark ml-2" style="font-family: serif; line-height: 1;"><?= $i + 1 ?></h1>
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
