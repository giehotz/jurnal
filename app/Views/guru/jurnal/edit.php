<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<style>
/* Mobile Responsive Styles for Edit Form */
@media (max-width: 768px) {
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .col-md-8, .col-md-4, .col-md-6 {
        padding-left: 0;
        padding-right: 0;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .card-header h3 {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .card-footer {
        padding: 0.75rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .form-control {
        font-size: 0.875rem;
        padding: 0.5rem;
    }
    
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn:last-child {
        margin-bottom: 0;
    }
    
    /* Sidebar on mobile */
    .col-md-4 {
        margin-top: 1rem;
    }
    
    /* Select2 mobile */
    .select2-container {
        width: 100% !important;
    }
    
    .select2-selection {
        font-size: 0.875rem !important;
    }
    
    /* Summernote mobile */
    .note-editor {
        font-size: 0.875rem;
    }
    
    .note-toolbar {
        padding: 0.5rem;
    }
    
    /* Custom file input */
    .custom-file-label {
        font-size: 0.875rem;
    }
    
    /* Text muted */
    .text-muted {
        font-size: 0.875rem;
    }
    
    /* Icons */
    .fas {
        font-size: 0.875rem;
    }
}

    /* File preview container */
    #file-preview-container .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
    }
    
    #file-preview-container .card-header h5 {
        font-size: 0.875rem;
        margin: 0;
    }
    
    #file-preview-container .card-body {
        padding: 0.75rem;
    }
    
    #preview-img {
        max-height: 200px !important;
    }
}

@media (min-width: 769px) {
    .btn {
        width: auto;
    }
    
    #preview-img {
        max-height: 300px !important;
    }
}

/* File Preview Styles */
#file-preview-container {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#file-preview-container .card {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

#file-preview-container .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

#file-preview-container .close {
    padding: 0;
    background-color: transparent;
    border: 0;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    opacity: 0.5;
    cursor: pointer;
}

#file-preview-container .close:hover {
    opacity: 0.75;
}

#preview-img {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}
</style>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Jurnal Mengajar</h3>
            </div>
            <form action="<?= base_url('guru/jurnal/update/' . $jurnal['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Tanggal Mengajar</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= old('tanggal', $jurnal['tanggal']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rombel_id">Rombel</label>
                                <select class="form-control select2" id="rombel_id" name="rombel_id" required>
                                    <option value="">Pilih Rombel</option>
                                    <?php foreach ($rombel as $r): ?>
                                        <option value="<?= $r['id'] ?>" <?= ($r['id'] == $jurnal['rombel_id']) ? 'selected' : '' ?>><?= $r['nama_rombel'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mapel_id">Mata Pelajaran</label>
                                <select class="form-control select2" id="mapel_id" name="mapel_id" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    <?php foreach ($mapel as $m): ?>
                                        <option value="<?= $m['id'] ?>" <?= ($m['id'] == $jurnal['mapel_id']) ? 'selected' : '' ?>><?= $m['nama_mapel'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_ke">Jam Ke</label>
                                <input type="text" class="form-control" id="jam_ke" name="jam_ke" value="<?= old('jam_ke', $jurnal['jam_ke']) ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="materi">Materi Pembelajaran</label>
                        <textarea id="materi" name="materi" class="form-control" rows="3"><?= old('materi', $jurnal['materi']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_jam">Jumlah JP</label>
                                <input type="number" class="form-control" id="jumlah_jam" name="jumlah_jam" min="1" value="<?= old('jumlah_jam', $jurnal['jumlah_jam']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_peserta">Jumlah Peserta</label>
                                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="<?= old('jumlah_peserta', $jurnal['jumlah_peserta']) ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2"><?= old('keterangan', $jurnal['keterangan']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bukti_dukung">Bukti Dukung (kosongkan jika tidak diubah)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input file-input-preview" id="bukti_dukung" name="bukti_dukung" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                                <label class="custom-file-label" for="bukti_dukung">Pilih file</label>
                            </div>
                        </div>
                        <?php if ($jurnal['bukti_dukung']): ?>
                            <small class="form-text text-muted">File saat ini: <a href="<?= base_url('uploads/' . $jurnal['bukti_dukung']) ?>" target="_blank"><?= $jurnal['bukti_dukung'] ?></a></small>
                        <?php endif; ?>
                        
                        <!-- Preview Container -->
                        <div id="file-preview-container" style="display:none; margin-top: 1rem;">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Preview</h5>
                                    <button type="button" class="close" id="remove-preview" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="card-body text-center">
                                    <!-- Image Preview -->
                                    <div id="image-preview" style="display:none;">
                                        <img id="preview-img" src="" alt="Preview" class="img-fluid" style="max-height: 300px; border-radius: 0.25rem;">
                                    </div>
                                    <!-- File Info for non-images -->
                                    <div id="file-info" style="display:none;">
                                        <i class="fas fa-file fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-1"><strong id="file-name"></strong></p>
                                        <p class="text-muted mb-0"><span id="file-size"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="draft" <?= ($jurnal['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= ($jurnal['status'] == 'published') ? 'selected' : '' ?>>Published</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Detail Jurnal</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Materi</strong>
                <p class="text-muted">
                    <?= $jurnal['materi'] ?>
                </p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Keterangan</strong>
                <p class="text-muted"><?= $jurnal['keterangan'] ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<script src="<?= base_url('AdminLTE/plugins/select2/js/select2.full.min.js') ?>"></script>
<!-- Summernote -->
<link rel="stylesheet" href="<?= base_url('AdminLTE/plugins/summernote/summernote-bs4.min.css') ?>">
<script src="<?= base_url('AdminLTE/plugins/summernote/summernote-bs4.min.js') ?>"></script>

<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4'
    })

    //Summernote
    $('#materi').summernote()
});
</script>

<script>
// File Preview Functionality
$(document).ready(function() {
    // Handle file input change
    $('.file-input-preview').on('change', function(e) {
        const file = e.target.files[0];
        const label = $(this).next('.custom-file-label');
        
        if (file) {
            // Update label with filename
            label.text(file.name);
            
            // Show preview
            showFilePreview(file);
        } else {
            // Hide preview if no file selected
            hideFilePreview();
            label.text('Pilih file');
        }
    });
    
    // Remove preview button
    $('#remove-preview').on('click', function() {
        // Clear file input
        $('#bukti_dukung').val('');
        
        // Reset label
        $('.custom-file-label').text('Pilih file');
        
        // Hide preview
        hideFilePreview();
    });
});

function showFilePreview(file) {
    const previewContainer = document.getElementById('file-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const fileInfo = document.getElementById('file-info');
    const previewImg = document.getElementById('preview-img');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    // Check if file is an image
    if (file.type.startsWith('image/')) {
        // Show image preview
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
            fileInfo.style.display = 'none';
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    } else {
        // Show file info for non-image files
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        imagePreview.style.display = 'none';
        fileInfo.style.display = 'block';
        previewContainer.style.display = 'block';
        
        // Update icon based on file type
        const icon = fileInfo.querySelector('i');
        if (file.type.includes('pdf')) {
            icon.className = 'fas fa-file-pdf fa-3x mb-3 text-danger';
        } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
            icon.className = 'fas fa-file-word fa-3x mb-3 text-primary';
        } else if (file.type.includes('excel') || file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
            icon.className = 'fas fa-file-excel fa-3x mb-3 text-success';
        } else {
            icon.className = 'fas fa-file fa-3x mb-3 text-secondary';
        }
    }
}

function hideFilePreview() {
    const previewContainer = document.getElementById('file-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const fileInfo = document.getElementById('file-info');
    const previewImg = document.getElementById('preview-img');
    
    previewContainer.style.display = 'none';
    imagePreview.style.display = 'none';
    fileInfo.style.display = 'none';
    previewImg.src = '';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>

<?= $this->endSection() ?>