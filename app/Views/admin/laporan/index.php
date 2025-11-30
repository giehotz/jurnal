<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<!-- 
  Kita tambahkan sedikit CSS kustom di sini untuk mereplikasi
  tampilan "btn-app" agar lebih menarik di Bootstrap 5
-->
<style>
    .btn-app-bs {
        width: 110px;
        height: 90px;
        transition: all 0.2s ease-in-out;
        color: #495057;
    }

    .btn-app-bs:hover {
        background-color: #f8f9fa;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        color: #0d6efd;
    }

    .btn-app-bs i {
        font-size: 2.2rem;
        /* Ukuran ikon besar */
        margin-bottom: 0.25rem;
    }

    .btn-app-bs span {
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Menyesuaikan ikon footer card stats */
    .stat-card-icon {
        font-size: 3.5rem;
        opacity: 0.3;
    }
</style>

<!-- Baris 1: Stat Boxes (Dikonversi ke Card Bootstrap 5) -->
<!-- 
  g-4 adalah gutter (spasi) 4, menggantikan margin manual
-->
<div class="row g-4">
    <!-- Total Jurnal -->
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-info shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $stats['total_jurnal'] ?? 0 ?></h3>
                    <p class="mb-0">Total Jurnal</p>
                </div>
                <i class="ion ion-bag stat-card-icon"></i>
            </div>
            <a href="<?= base_url('admin/monitoring') ?>" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center py-2">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    
    <!-- Total Guru -->
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $stats['total_guru'] ?? 0 ?></h3>
                    <p class="mb-0">Total Guru</p>
                </div>
                <i class="ion ion-stats-bars stat-card-icon"></i>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center py-2">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <!-- Baris 4: Generate Laporan -->

    <!-- Total Kelas -->
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-warning shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $stats['total_kelas'] ?? 0 ?></h3>
                    <p class="mb-0">Total Kelas</p>
                </div>
                <i class="ion ion-person-add stat-card-icon"></i>
            </div>
            <a href="<?= base_url('admin/kelas') ?>" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center py-2">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    
    <!-- Total Mapel -->
    <div class="col-lg-3 col-6">
        <div class="card text-white bg-danger shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $stats['total_mapel'] ?? 0 ?></h3>
                    <p class="mb-0">Total Mapel</p>
                </div>
                <i class="ion ion-pie-graph stat-card-icon"></i>
            </div>
            <a href="<?= base_url('admin/mapel') ?>" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center py-2">
                <span>More info</span>
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- Akhir Baris 1 -->

<!-- Baris 2: Charts (Elemen Canvas yang Hilang) -->
<!-- 
  Script Anda mencari #jurnalGuruChart dan #jurnalMapelChart,
  kita tambahkan di sini.
-->
  <!-- 
  Memperbaiki centering:
  - justify-content-center di .row
  - col-lg-10 (atau ukuran lain) untuk membungkus card
  - card-body dibuat flex untuk menata tombol
-->
<div class="row justify-content-left ">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h5 class="card-title mb-0">Generate Laporan</h5>
            </div>
            <div class="card-body d-flex flex-wrap justify-content-center align-items-center gap-3 py-4">
                <!-- 
                  Konversi:
                  btn btn-app -> btn-app-bs (custom)
                  Menggunakan flex-column untuk menata ikon di atas teks
                -->
                <a href="<?= base_url('admin/laporan/guru') ?>" class="btn-app-bs d-flex flex-column justify-content-center align-items-center p-2 border rounded-3 text-decoration-none">
                    <i class="fas fa-users text-primary"></i>
                    <span>Laporan Guru</span>
                </a>
                <a href="<?= base_url('admin/laporan/jurnal') ?>" class="btn-app-bs d-flex flex-column justify-content-center align-items-center p-2 border rounded-3 text-decoration-none">
                    <i class="fas fa-file-alt text-info"></i>
                    <span>Laporan Jurnal</span>
                </a>
                <a href="<?= base_url('admin/laporan/statistik') ?>" class="btn-app-bs d-flex flex-column justify-content-center align-items-center p-2 border rounded-3 text-decoration-none">
                    <i class="fas fa-chart-pie text-warning"></i>
                    <span>Statistik</span>
                </a>
                <a href="<?= base_url('admin/laporan/export') ?>" class="btn-app-bs d-flex flex-column justify-content-center align-items-center p-2 border rounded-3 text-decoration-none">
                    <i class="fas fa-file-excel text-success"></i>
                    <span>Export</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Baris 4 -->
<div class="row g-4 mt-2">
    <!-- Jurnal Guru Chart -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Jurnal per Guru</h5>
            </div>
            <div class="card-body">
                <canvas id="jurnalGuruChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Jurnal Mapel Chart -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Jurnal per Mapel</h5>
            </div>
            <div class="card-body">
                <canvas id="jurnalMapelChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Baris 2 -->


<!-- Baris 3: Data Tables -->
<div class="row g-4 mt-2">
    <!-- Tabel Jurnal per Guru -->
    <div class="col-md-6">
        <!-- 
          Konversi:
          card-outline card-danger -> card border-danger
          card-tools -> menggunakan data-bs-toggle untuk collapse
        -->
        <div class="card border-danger shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-danger">Data Jurnal per Guru</h5>
                <button class="btn btn-tool btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuruTable" aria-expanded="true" aria-controls="collapseGuruTable">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- Wrapper untuk Collapse -->
            <div class="collapse show" id="collapseGuruTable">
                <!-- p-0 pada card-body dihapus agar tabel tidak menempel ke border -->
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nama Guru</th>
                                <th>Jumlah Jurnal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jurnal_per_guru)): ?>
                                <?php foreach ($jurnal_per_guru as $index => $item): ?>
                                    <tr>
                                        <td><?= $index + 1 ?>.</td>
                                        <td><?= esc($item['guru_name']) ?></td>
                                        <td>
                                            <!-- badge bg-danger adalah class yg sama di BS5 -->
                                            <span class="badge bg-danger"><?= $item['jurnal_count'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Jurnal per Mapel -->
    <div class="col-md-6">
        <div class="card border-success shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-success">Data Jurnal per Mapel</h5>
                <button class="btn btn-tool btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMapelTable" aria-expanded="true" aria-controls="collapseMapelTable">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <div class="collapse show" id="collapseMapelTable">
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Mata Pelajaran</th>
                                <th>Jumlah Jurnal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jurnal_per_mapel)): ?>
                                <?php foreach ($jurnal_per_mapel as $index => $item): ?>
                                    <tr>
                                        <td><?= $index + 1 ?>.</td>
                                        <td><?= esc($item['mapel_name']) ?></td>
                                        <td>
                                            <span class="badge bg-success"><?= $item['jurnal_count'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Baris 3 -->


<!-- 
  Script Chart.js Anda
  (Tidak perlu diubah, karena sudah menggunakan ID Canvas)
-->
<script src="<?= base_url('AdminLTE/plugins/chart.js/Chart.min.js') ?>"></script>
<script>
$(function () {
    // Pastikan data ada sebelum mencoba membuat chart
    <?php 
    $guruNames = [];
    $guruCounts = [];
    if (!empty($jurnal_per_guru)) {
        foreach ($jurnal_per_guru as $item) {
            $guruNames[] = $item['guru_name'];
            $guruCounts[] = $item['jurnal_count'];
        }
    }
    ?>
    var guruNames = <?= json_encode($guruNames) ?>;
    var guruCounts = <?= json_encode($guruCounts) ?>;
    
    if (guruNames.length > 0) {
        var donutData = {
          labels: guruNames,
          datasets: [
            {
              data: guruCounts,
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997'],
            }
          ]
        }
        var pieChartCanvas = $('#jurnalGuruChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
          maintainAspectRatio : false,
          responsive : true,
        }
        new Chart(pieChartCanvas, {
          type: 'pie',
          data: pieData,
          options: pieOptions
        })
    }

    //-------------
    //- JURNAL PER MAPEL -
    //-------------
    <?php 
    $mapelNames = [];
    $mapelCounts = [];
    if (!empty($jurnal_per_mapel)) {
        foreach ($jurnal_per_mapel as $item) {
            $mapelNames[] = $item['mapel_name'];
            $mapelCounts[] = $item['jurnal_count'];
        }
    }
    ?>
    var mapelNames = <?= json_encode($mapelNames) ?>;
    var mapelCounts = <?= json_encode($mapelCounts) ?>;
    
    if (mapelNames.length > 0) {
        var barChartCanvas = $('#jurnalMapelChart').get(0).getContext('2d')
        var barChartData = {
            labels: mapelNames,
            datasets: [
                {
                    label: 'Jumlah Jurnal',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    data: mapelCounts
                }
            ]
        }
        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        new Chart(barChartCanvas, {
          type: 'bar',
          data: barChartData,
          options: barChartOptions
        })
    }
})
</script>
<?= $this->endSection() ?>