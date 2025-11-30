<?= $this->include('templates/header') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Kelas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
                <i class="fas fa-plus"></i> Tambah Kelas
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="kelasTable">
                    <thead>
                        <tr>
                            <th>Kode Kelas</th>
                            <th>Nama Kelas</th>
                            <th>Fase</th>
                            <th>Wali Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <tr data-id="<?= $class['id'] ?>">
                                    <td class="editable" data-field="kode_kelas"><?= $class['kode_kelas'] ?></td>
                                    <td class="editable" data-field="nama_kelas"><?= $class['nama_kelas'] ?></td>
                                    <td class="editable" data-field="fase"><?= $class['fase'] ?></td>
                                    <td class="editable" data-field="wali_kelas" data-value="<?= $class['wali_kelas'] ?? '' ?>"><?= $class['wali_kelas_nama'] ?? 'Belum ditentukan' ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $class['id'] ?>">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data kelas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKelasModalLabel">Tambah Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="tambahKelasForm">
                    <div class="mb-3">
                        <label for="kode_kelas" class="form-label">Kode Kelas</label>
                        <input type="text" class="form-control" id="kode_kelas" name="kode_kelas" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fase" class="form-label">Fase</label>
                        <select class="form-select" id="fase" name="fase" required>
                            <option value="">Pilih Fase</option>
                            <option value="A">Fase A</option>
                            <option value="B">Fase B</option>
                            <option value="C">Fase C</option>
                            <option value="D">Fase D</option>
                            <option value="E">Fase E</option>
                            <option value="F">Fase F</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="wali_kelas" class="form-label">Wali Kelas (Opsional)</label>
                        <select class="form-select" id="wali_kelas" name="wali_kelas">
                            <option value="">Pilih Wali Kelas</option>
                            <?php if (!empty($teachers)): ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= $teacher['id'] ?>"><?= $teacher['nama'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanKelasBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Editable cells
    const editableCells = document.querySelectorAll('.editable');
    editableCells.forEach(cell => {
        cell.addEventListener('click', function() {
            const originalValue = this.innerText;
            const field = this.dataset.field;
            const id = this.closest('tr').dataset.id;
            
            // Buat input element
            let input;
            if (field === 'fase') {
                input = document.createElement('select');
                input.classList.add('form-select', 'form-select-sm');
                const phases = ['A', 'B', 'C', 'D', 'E', 'F'];
                phases.forEach(phase => {
                    const option = document.createElement('option');
                    option.value = phase;
                    option.text = 'Fase ' + phase;
                    if (originalValue === 'Fase ' + phase) {
                        option.selected = true;
                    }
                    input.appendChild(option);
                });
            } else if (field === 'wali_kelas') {
                input = document.createElement('select');
                input.classList.add('form-select', 'form-select-sm');
                
                // Tambahkan opsi kosong
                const emptyOption = document.createElement('option');
                emptyOption.value = '';
                emptyOption.text = 'Belum ditentukan';
                input.appendChild(emptyOption);
                
                // Tambahkan guru-guru
                <?php if (!empty($teachers)): ?>
                    <?php foreach ($teachers as $teacher): ?>
                        const option = document.createElement('option');
                        option.value = '<?= $teacher['id'] ?>';
                        option.text = '<?= $teacher['nama'] ?>';
                        if (this.dataset.value == '<?= $teacher['id'] ?>') {
                            option.selected = true;
                        }
                        input.appendChild(option);
                    <?php endforeach; ?>
                <?php endif; ?>
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.classList.add('form-control', 'form-control-sm');
                input.value = originalValue;
            }
            
            // Ganti konten sel dengan input
            this.innerHTML = '';
            this.appendChild(input);
            input.focus();
            
            // Simpan perubahan saat kehilangan fokus atau tekan Enter
            const saveChanges = () => {
                const newValue = input.value;
                if (newValue !== originalValue) {
                    // Kirim AJAX request untuk menyimpan perubahan
                    fetch('<?= base_url('admin/kelas/save') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            'id': id,
                            'field': field,
                            'value': newValue,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (field === 'wali_kelas') {
                                this.innerHTML = data.value || 'Belum ditentukan';
                            } else if (field === 'fase') {
                                this.innerHTML = 'Fase ' + newValue;
                            } else {
                                this.innerHTML = newValue;
                            }
                        } else {
                            alert('Error: ' + data.message);
                            this.innerHTML = originalValue;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.innerHTML = originalValue;
                    });
                } else {
                    if (field === 'wali_kelas') {
                        this.innerHTML = this.dataset.value ? 
                            <?= json_encode(array_column($teachers ?? [], 'nama', 'id')) ?>[this.dataset.value] || 'Belum ditentukan' : 
                            'Belum ditentukan';
                    } else {
                        this.innerHTML = originalValue;
                    }
                }
            };
            
            input.addEventListener('blur', saveChanges);
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    saveChanges();
                }
            });
        });
    });
    
    // Tombol simpan kelas baru
    document.getElementById('simpanKelasBtn').addEventListener('click', function() {
        const form = document.getElementById('tambahKelasForm');
        const formData = new FormData(form);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        fetch('<?= base_url('admin/kelas/create') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                if (data.errors) {
                    let errorMsg = '';
                    for (const [key, value] of Object.entries(data.errors)) {
                        errorMsg += value + '\n';
                    }
                    alert(errorMsg);
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    });
    
    // Tombol hapus kelas
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
                fetch(`<?= base_url('admin/kelas/delete') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }
        });
    });
});
</script>

<?= $this->include('templates/footer') ?>