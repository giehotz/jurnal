<?= $this->extend('admin/layouts/adminlte') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $stats['total_jurnal'] ?? 0 ?></h3>
                <p>Total Jurnal</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $stats['jurnal_published'] ?? 0 ?></h3>
                <p>Jurnal Published</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $stats['jurnal_draft'] ?? 0 ?></h3>
                <p>Jurnal Draft</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $stats['total_guru'] ?? 0 ?></h3>
                <p>Total Guru</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- DONUT CHART -->
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Jurnal per Status</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <!-- BAR CHART -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Jurnal per Bulan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="monthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('AdminLTE/plugins/chart.js/Chart.min.js') ?>"></script>
<script>
$(function () {
    // Variabel untuk menyimpan instance chart
    var statusChartInstance = null;
    var monthlyChartInstance = null;

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart status
    function initStatusChart() {
        // Hancurkan chart jika sudah ada
        if (statusChartInstance) {
            statusChartInstance.destroy();
        }

        var statusData = {
          labels: ['Published', 'Draft'],
          datasets: [
            {
              data: [<?= $stats['jurnal_published'] ?? 0 ?>, <?= $stats['jurnal_draft'] ?? 0 ?>],
              backgroundColor : ['#00a65a', '#f39c12'],
            }
          ]
        }
        var pieChartCanvas = $('#statusChart').get(0).getContext('2d')
        var pieData = statusData;
        var pieOptions = {
          maintainAspectRatio : false,
          responsive : true,
        }
        statusChartInstance = new Chart(pieChartCanvas, {
          type: 'doughnut',
          data: pieData,
          options: pieOptions
        })
    }

    // Fungsi untuk menginisialisasi atau menginisialisasi ulang chart bulanan
    function initMonthlyChart() {
        // Hancurkan chart jika sudah ada
        if (monthlyChartInstance) {
            monthlyChartInstance.destroy();
        }

        var barChartCanvas = $('#monthlyChart').get(0).getContext('2d')
        var barChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
                {
                    label: 'Jumlah Jurnal',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    data: [<?= implode(',', $jurnal_per_bulan ?? array_fill(0, 12, 0)) ?>]
                }
            ]
        }
        var barChartOptions = {
          responsive: true,
          maintainAspectRatio: false,
          datasetFill: false
        }

        monthlyChartInstance = new Chart(barChartCanvas, {
          type: 'bar',
          data: barChartData,
          options: barChartOptions
        })
    }

    // Inisialisasi semua chart saat halaman dimuat
    initStatusChart();
    initMonthlyChart();

    // Event listener untuk card status chart
    $('#statusChart').closest('.card').on('expanded.lte.cardwidget', function() {
        initStatusChart();
    });

    // Event listener untuk card monthly chart
    $('#monthlyChart').closest('.card').on('expanded.lte.cardwidget', function() {
        initMonthlyChart();
    });
})
</script>
<?= $this->endSection() ?>