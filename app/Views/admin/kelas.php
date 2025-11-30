<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <h1>Daftar Kelas</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Kelas</th>
                                <th>Nama Kelas</th>
                                <th>Fase</th>
                                <th>Wali Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($classes) && is_array($classes)): ?>
                                <?php foreach ($classes as $class): ?>
                                    <tr>
                                        <td><?= esc($class['kode_kelas']) ?></td>
                                        <td><?= esc($class['nama_kelas']) ?></td>
                                        <td><?= esc($class['fase']) ?></td>
                                        <td><?= isset($class['wali_kelas']) ? esc($class['wali_kelas']) : 'Belum ditentukan' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data kelas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2>Tambah Kelas</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="add_class">
                    
                    <div class="mb-3">
                        <label for="kode_kelas" class="form-label">Kode Kelas:</label>
                        <input type="text" class="form-control" id="kode_kelas" name="kode_kelas" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas:</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fase" class="form-label">Fase:</label>
                        <select class="form-select" id="fase" name="fase" required>
                            <option value="">Pilih Fase</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="wali_kelas" class="form-label">Wali Kelas:</label>
                        <select class="form-select" id="wali_kelas" name="wali_kelas">
                            <option value="">Tidak ada</option>
                            <?php if (isset($teachers) && is_array($teachers)): ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= esc($teacher['id']) ?>"><?= esc($teacher['nama']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Simpan Kelas</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>