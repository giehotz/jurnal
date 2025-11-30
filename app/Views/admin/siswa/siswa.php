<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Siswa</h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/siswa/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                    <a href="<?= base_url('admin/siswa/upload') ?>" class="btn btn-danger btn-sm ml-2">
                        <i class="fas fa-upload"></i> Upload Siswa
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="tingkat">Tingkat</label>
                        <select id="tingkat" class="form-control">
                            <option value="">Semua Tingkat</option>
                            <?php if (isset($school_level)): ?>
                                <?php if ($school_level == 'SD/MI'): ?>
                                    <option value="1" <?= ($filter_tingkat == '1') ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= ($filter_tingkat == '2') ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= ($filter_tingkat == '3') ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= ($filter_tingkat == '4') ? 'selected' : '' ?>>4</option>
                                    <option value="5" <?= ($filter_tingkat == '5') ? 'selected' : '' ?>>5</option>
                                    <option value="6" <?= ($filter_tingkat == '6') ? 'selected' : '' ?>>6</option>
                                <?php elseif ($school_level == 'SMP/MTs'): ?>
                                    <option value="7" <?= ($filter_tingkat == '7') ? 'selected' : '' ?>>7</option>
                                    <option value="8" <?= ($filter_tingkat == '8') ? 'selected' : '' ?>>8</option>
                                    <option value="9" <?= ($filter_tingkat == '9') ? 'selected' : '' ?>>9</option>
                                <?php else: // SMA/MA ?>
                                    <option value="10" <?= ($filter_tingkat == '10') ? 'selected' : '' ?>>10</option>
                                    <option value="11" <?= ($filter_tingkat == '11') ? 'selected' : '' ?>>11</option>
                                    <option value="12" <?= ($filter_tingkat == '12') ? 'selected' : '' ?>>12</option>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Fallback jika school_level tidak tersedia -->
                                <option value="10" <?= ($filter_tingkat == '10') ? 'selected' : '' ?>>10</option>
                                <option value="11" <?= ($filter_tingkat == '11') ? 'selected' : '' ?>>11</option>
                                <option value="12" <?= ($filter_tingkat == '12') ? 'selected' : '' ?>>12</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="kelas">Kelas</label>
                        <select id="kelas" class="form-control">
                            <option value="">Semua Kelas</option>
                            <?php foreach ($rombel as $r): ?>
                                <option value="<?= $r['id'] ?>" data-tingkat="<?= $r['tingkat'] ?>" <?= ($filter_kelas == $r['id']) ? 'selected' : '' ?>>
                                    <?= $r['kode_rombel'] ?> - <?= $r['nama_rombel'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="show_entries">Tampilkan</label>
                        <select id="show_entries" class="form-control">
                            <option value="30" <?= ($per_page == '30') ? 'selected' : '' ?>>30 data</option>
                            <option value="60" <?= ($per_page == '60') ? 'selected' : '' ?>>60 data</option>
                            <option value="90" <?= ($per_page == '90') ? 'selected' : '' ?>>90 data</option>
                            <option value="-1" <?= ($per_page == '-1') ? 'selected' : '' ?>>Semua data</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search">Pencarian</label>
                        <form method="GET" class="form-inline" action="<?= base_url('admin/siswa') ?>">
                            <!-- Hidden inputs to persist filters -->
                            <?php if($filter_tingkat): ?><input type="hidden" name="tingkat" value="<?= esc($filter_tingkat) ?>"><?php endif; ?>
                            <?php if($filter_kelas): ?><input type="hidden" name="kelas" value="<?= esc($filter_kelas) ?>"><?php endif; ?>
                            <?php if($per_page): ?><input type="hidden" name="per_page" value="<?= esc($per_page) ?>"><?php endif; ?>
                            
                            <div class="input-group w-100">
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Nama, NIS, NISN..." value="<?= esc($search ?? '') ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php if (isset($search) && !empty($search)): ?>
                    <div class="alert alert-info">
                        Hasil pencarian untuk: <strong><?= esc($search) ?></strong>
                        <a href="<?= base_url('admin/siswa') ?>" class="float-right">Tampilkan Semua</a>
                    </div>
                <?php endif; ?>
                
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>L/P</th>
                            <th>TTL</th>
                            <th>Kelas</th>        <!-- Kolom 7 -->
                            <th>Tingkat</th>      <!-- Kolom 8 -->
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php $no = 1 + (($pager->getCurrentPage('siswa') - 1) * $per_page); ?>
                            <?php foreach ($students as $s): ?>
                                <?php if($s['is_active'] == 1): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if (!empty($s['foto']) && file_exists('uploads/profile_pictures/' . $s['foto'])): ?>
                                                <img src="<?= base_url('uploads/profile_pictures/' . $s['foto']) ?>" alt="Foto" class="img-circle elevation-2" width="40" height="40">
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/profile_pictures/default.png') ?>" alt="Foto" class="img-circle elevation-2" width="40" height="40">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($s['nis'] ?? '-') ?></td>
                                        <td><?= esc($s['nisn'] ?? '-') ?></td>
                                        <td><?= esc($s['nama']) ?></td>
                                        <td><?= esc($s['jenis_kelamin']) ?></td>
                                        <td>
                                            <?php if (!empty($s['tempat_lahir']) && !empty($s['tanggal_lahir'])): ?>
                                                <?= esc($s['tempat_lahir']) ?>, <?= date('d M Y', strtotime($s['tanggal_lahir'])) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($s['nama_rombel'] ?? '-') ?></td>
                                        <td><?= esc($s['tingkat'] ?? '-') ?></td>
                                        <td>
                                            <?php if (isset($s['password']) && !empty($s['password'])): ?>
                                                <?= esc($s['password']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/siswa/edit/' . $s['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('admin/siswa/delete/' . $s['id']) ?>" 
                                               class="btn btn-danger btn-sm" 
                                               title="Hapus"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Semua data terkait akan ikut terhapus.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">
                                    <?php if ($filter_kelas): ?>
                                        Pada kelas ini siswa belum ada
                                    <?php else: ?>
                                        Tidak ada data siswa
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="row mt-3">
                    <div class="col-12">
                        <?= $pager->links('siswa', 'default_full') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Inisialisasi DataTable (Hanya untuk styling dan export buttons, matikan fitur search/paging bawaan)
    var table = $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"],
        "paging": false,
        "searching": false,
        "info": false,
        "ordering": true // Masih boleh sorting kolom di halaman ini
    });

    table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
    // 2. Filter Server-side
    function applyFilter() {
        var tingkat = document.getElementById('tingkat').value;
        var kelas = document.getElementById('kelas').value;
        var perPage = document.getElementById('show_entries').value;
        var search = document.getElementById('search').value;
        
        var url = new URL(window.location.href);
        
        if (tingkat) url.searchParams.set('tingkat', tingkat);
        else url.searchParams.delete('tingkat');
        
        if (kelas) url.searchParams.set('kelas', kelas);
        else url.searchParams.delete('kelas');
        
        if (perPage) url.searchParams.set('per_page', perPage);
        
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        // Reset page ke 1 saat filter berubah
        url.searchParams.delete('page_siswa');
        
        window.location.href = url.toString();
    }
    
    // Event listeners
    document.getElementById('tingkat').addEventListener('change', function() {
        // Reset kelas jika tingkat berubah
        document.getElementById('kelas').value = ''; 
        applyFilter();
    });
    
    document.getElementById('kelas').addEventListener('change', applyFilter);
    document.getElementById('show_entries').addEventListener('change', applyFilter);
    
    // 3. Update dropdown kelas berdasarkan tingkat (Visual only)
    var currentTingkat = "<?= $filter_tingkat ?>";
    updateKelasDropdown(currentTingkat);
    
    function updateKelasDropdown(tingkat) {
        var $kelas = $('#kelas');
        
        if (tingkat) {
            $kelas.find('option').each(function() {
                var optTingkat = $(this).data('tingkat');
                // Tampilkan option kosong atau yang sesuai tingkat
                if ($(this).val() === '' || optTingkat == tingkat) {
                    $(this).show().prop('disabled', false);
                } else {
                    $(this).hide().prop('disabled', true);
                }
            });
        } else {
            $kelas.find('option').show().prop('disabled', false);
        }
    }
});
</script>
<?= $this->endSection() ?>