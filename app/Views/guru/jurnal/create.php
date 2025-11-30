<?= $this->extend('guru/layouts/template') ?>

<?= $this->section('content') ?>
<style>
/* Mobile Responsive Styles for Create Form */
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
    
    /* Table responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        font-size: 0.75rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
    }
    
    /* Alert styles */
    .alert {
        font-size: 0.875rem;
        padding: 0.75rem;
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
    
    /* List group */
    .list-group-item {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    
    /* Nav pills */
    .nav-pills .nav-link {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
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
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Tambah Jurnal Mengajar</h3>
            </div>
            <form action="<?= base_url('guru/jurnal/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Tanggal Mengajar</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $selected_tanggal ?? date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rombel_id">Rombel</label>
                                <select class="form-control select2" id="rombel_id" name="rombel_id" required>
                                    <option value="">Pilih Rombel</option>
                                    <?php foreach ($rombel as $r): ?>
                                        <option value="<?= $r['id'] ?>" <?= (isset($selected_rombel) && $selected_rombel == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rombel'] ?></option>
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
                                        <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_ke">Jam Ke</label>
                                <input type="text" class="form-control" id="jam_ke" name="jam_ke" placeholder="Contoh: 1-3" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="materi">Materi</label>
                        <textarea class="form-control" id="materi" name="materi" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_jam">Jumlah Jam</label>
                        <input type="number" class="form-control" id="jumlah_jam" name="jumlah_jam" min="1" max="10" required>
                    </div>
                    <!-- <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="jumlah_peserta">Jumlah Peserta Didik</label>
                            <div class="input-group">
                                <input type="number" name="jumlah_peserta" id="jumlah_peserta" 
                                    class="form-control form-control-lg" 
                                    value="<?= $total_siswa ?? 0 ?>" 
                                    readonly
                                    placeholder="Pilih kelas dan tanggal">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-users"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-muted" id="jumlah_peserta_help">
                                Jumlah siswa dalam rombel (Total Siswa)
                            </small>
                        </div>
                    </div> -->

                    <div id="attendance-warning" class="alert alert-warning" style="display:none;">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Absensi Harian Belum Diisi</h5>
                        <p>Anda belum mengisi absensi untuk kelas dan tanggal ini. Data kehadiran diperlukan untuk membuat jurnal.</p>
                        <hr>
                        <a href="#" id="btn-isi-absensi" class="btn btn-warning font-weight-bold text-dark">
                            <i class="fas fa-clipboard-check mr-2"></i> Isi Absensi Sekarang
                        </a>
                    </div>

                    <div id="attendance-success" class="alert alert-success" style="display:none;">
                        <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Data Absensi Tersedia</h5>
                        <p class="mb-0">
                            <span id="attendance-summary"></span>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Bukti Dokumen Pendukung (Gambar/PDF/Word/Excel)</label>
                        <div id="input-kamera" style="display:none">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input file-input-preview" id="bukti_dukung_cam" name="bukti_dukung" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" capture="environment">
                                <label class="custom-file-label" for="bukti_dukung_cam">Ambil foto atau pilih file</label>
                            </div>
                        </div>
                        <div id="input-file" style="display:none">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input file-input-preview" id="bukti_dukung" name="bukti_dukung" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                                <label class="custom-file-label" for="bukti_dukung">Pilih file</label>
                            </div>
                        </div>
                        
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
                        <select class="form-control" id="status" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Publish</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="btn-submit">Simpan</button>
                    <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Jurnal Terakhir</h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <?php if (!empty($recent_jurnal)): ?>
                        <?php foreach ($recent_jurnal as $jurnal): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('guru/jurnal/view/' . $jurnal['id']) ?>" class="nav-link">
                                    <i class="fas fa-book"></i> <?= $jurnal['nama_mapel'] ?>
                                    <span class="float-right text-muted"><?= $jurnal['tanggal'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-muted">
                                Belum ada jurnal.
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
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
// Function untuk memeriksa ketergantungan absensi harian
function checkAttendanceDependency() {
    console.log('Checking attendance dependency...');
    const rombelId = $('#rombel_id').val();
    const tanggal = $('#tanggal').val();
    
    const jumlahPesertaInput = document.getElementById('jumlah_peserta');
    const warningDiv = document.getElementById('attendance-warning');
    const successDiv = document.getElementById('attendance-success');
    const summarySpan = document.getElementById('attendance-summary');
    const btnIsiAbsensi = document.getElementById('btn-isi-absensi');
    const btnSubmit = document.getElementById('btn-submit');
    
    // Reset UI
    warningDiv.style.display = 'none';
    successDiv.style.display = 'none';
    btnSubmit.disabled = false;
    
    if (rombelId && tanggal) {
        // Update link isi absensi
        btnIsiAbsensi.href = `<?= base_url('guru/absensi/create') ?>?rombel_id=${rombelId}&tanggal=${tanggal}`;
        
        // Fetch data rekap absensi harian
        fetch(`<?= base_url('guru/jurnal/check-daily-attendance') ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                rombel_id: rombelId,
                tanggal: tanggal
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Received attendance data:', data);
            
            if (data.status === 'success' && data.data) {
                // Absensi ADA
                const hadir = data.data.total_hadir;
                const total = data.data.total_siswa;
                const percent = total > 0 ? Math.round((hadir / total) * 100) : 0;
                
                jumlahPesertaInput.value = hadir;
                
                successDiv.style.display = 'block';
                summarySpan.innerHTML = `<strong>${hadir}</strong> dari <strong>${total}</strong> siswa hadir (${percent}%).`;
                
                btnSubmit.disabled = false;
            } else if (data.status === 'warning' && data.data) {
                 // Absensi TIDAK ADA tapi data siswa ada
                 const total = data.data.total_siswa;
                 
                 // Tetap set jumlah peserta 0 karena belum absen
                 jumlahPesertaInput.value = 0;
                 
                 // Tampilkan warning dengan info jumlah siswa
                 warningDiv.style.display = 'block';
                 warningDiv.innerHTML = `
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Absensi Harian Belum Diisi</h5>
                    <p>Anda belum mengisi absensi untuk kelas dan tanggal ini. Terdapat <strong>${total}</strong> siswa di kelas ini.</p>
                    <hr>
                    <a href="${btnIsiAbsensi.href}" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fas fa-clipboard-check mr-2"></i> Isi Absensi Sekarang
                    </a>
                 `;
                 
                 btnSubmit.disabled = true;
            } else {
                // Absensi TIDAK ADA (status warning/error)
                jumlahPesertaInput.value = 0;
                
                warningDiv.style.display = 'block';
                btnSubmit.disabled = true; // Prevent submit if no attendance
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
            // On error, be safe and warn
            warningDiv.style.display = 'block';
            btnSubmit.disabled = true;
        });
    } else {
        jumlahPesertaInput.value = 0;
    }
}

// Event Listeners
$(document).ready(function() {
    // Use standard change event which Select2 triggers
    $('#rombel_id').on('change', function() {
        checkAttendanceDependency();
    });

    $('#tanggal').on('change', function() {
        checkAttendanceDependency();
    });
    
    // Initial check
    if ($('#rombel_id').val() && $('#tanggal').val()) {
        checkAttendanceDependency();
    }
    
    // Ensure Select2 changes trigger the event
    $('.select2').select2().on('change', function() {
        $(this).trigger('input');
    });
});
</script>

<script>
async function cekKamera() {
    const inputCam = document.getElementById('bukti_dukung_cam');
    const inputFile = document.getElementById('bukti_dukung');
    const divCam = document.getElementById('input-kamera');
    const divFile = document.getElementById('input-file');

    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const adaKamera = devices.some(d => d.kind === 'videoinput');
        
        if (adaKamera) {
            divCam.style.display = 'block';
            divFile.style.display = 'none';
            
            // Enable camera input, disable file input
            inputCam.disabled = false;
            inputFile.disabled = true;
        } else {
            divCam.style.display = 'none';
            divFile.style.display = 'block';
            
            // Enable file input, disable camera input
            inputFile.disabled = false;
            inputCam.disabled = true;
        }
    } catch (e) {
        console.error("Error checking camera:", e);
        divCam.style.display = 'none';
        divFile.style.display = 'block';
        
        // Fallback: Enable file input
        inputFile.disabled = false;
        inputCam.disabled = true;
    }
}
cekKamera();
</script>

<script>
// File Preview Functionality
$(document).ready(function() {
    // Handle file input change for both camera and file inputs
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
        // Clear file inputs
        $('#bukti_dukung').val('');
        $('#bukti_dukung_cam').val('');
        
        // Reset labels
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

<script>
async function cekKamera() {
    const inputCam = document.getElementById('bukti_dukung_cam');
    const inputFile = document.getElementById('bukti_dukung');
    const divCam = document.getElementById('input-kamera');
    const divFile = document.getElementById('input-file');

    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const adaKamera = devices.some(d => d.kind === 'videoinput');
        
        if (adaKamera) {
            divCam.style.display = 'block';
            divFile.style.display = 'none';
            
            // Enable camera input, disable file input
            inputCam.disabled = false;
            inputFile.disabled = true;
        } else {
            divCam.style.display = 'none';
            divFile.style.display = 'block';
            
            // Enable file input, disable camera input
            inputFile.disabled = false;
            inputCam.disabled = true;
        }
    } catch (e) {
        console.error("Error checking camera:", e);
        divCam.style.display = 'none';
        divFile.style.display = 'block';
        
        // Fallback: Enable file input
        inputFile.disabled = false;
        inputCam.disabled = true;
    }
}
cekKamera();
</script>

<script>
// File Preview Functionality
$(document).ready(function() {
    // Handle file input change for both camera and file inputs
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
        // Clear file inputs
        $('#bukti_dukung').val('');
        $('#bukti_dukung_cam').val('');
        
        // Reset labels
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