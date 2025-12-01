<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0 text-gray-800">Detail QR Code</h1>
        <div>
            <a href="<?= base_url('guru/qrcode/edit/' . $url['id']) ?>" class="btn btn-warning shadow-sm mr-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="<?= base_url('guru/qrcode') ?>" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Preview QR Code</h6>
                </div>
                <div class="card-body text-center">
                    <?php if ($qrImage) : ?>
                        <img src="<?= $qrImage ?>" alt="QR Code" class="img-fluid mb-3 border p-2" style="max-width: 100%;">
                        <br>
                        <a href="<?= base_url('guru/qrcode/download/' . $url['id']) ?>" class="btn btn-success btn-lg mt-3">
                            <i class="fas fa-download mr-2"></i> Download PNG
                        </a>
                    <?php else : ?>
                        <div class="alert alert-warning">
                            Gagal memuat preview QR Code.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Detail</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%;">Nama Label</th>
                            <td>: <strong><?= esc($url['custom_name']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>URL Tujuan</th>
                            <td>: <a href="<?= esc($url['original_url']) ?>" target="_blank"><?= esc($url['original_url']) ?> <i class="fas fa-external-link-alt small"></i></a></td>
                        </tr>
                        <tr>
                            <th>Short Slug</th>
                            <td>: <span class="badge badge-secondary"><?= esc($url['short_slug']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>: <?= date('d F Y H:i', strtotime($url['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Ukuran</th>
                            <td>: <?= esc($settings['size']) ?> px</td>
                        </tr>
                        <tr>
                            <th>Warna QR</th>
                            <td>: <span class="badge" style="background-color: <?= esc($settings['qr_color']) ?>; color: #fff; text-shadow: 0 0 2px #000;"><?= esc($settings['qr_color']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Warna Background</th>
                            <td>: <span class="badge border" style="background-color: <?= esc($settings['bg_color']) ?>; color: #000;"><?= esc($settings['bg_color']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Logo</th>
                            <td>: <?= !empty($settings['logo_path']) ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-secondary">Tidak</span>' ?></td>
                        </tr>
                        <tr>
                            <th>Gaya Frame</th>
                            <td>: <?= esc(ucfirst($settings['frame_style'] ?? 'None')) ?></td>
                        </tr>
                        <tr>
                            <th>Label</th>
                            <td>: <?= !empty($settings['show_label']) ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-secondary">Tidak</span>' ?></td>
                        </tr>
                    </table>

                    <hr>
                    <h6 class="font-weight-bold text-secondary">Embed Code</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="embed-code" value='<img src="<?= base_url('guru/qrcode/download/' . $url['id']) ?>" alt="QR Code" width="200">' readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyEmbedCode()">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyEmbedCode() {
        var copyText = document.getElementById("embed-code");
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        document.execCommand("copy");
        alert("Embed code copied to clipboard!");
    }
</script>
<?= $this->endSection() ?>
