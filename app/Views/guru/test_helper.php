<?= $this->include('templates/header') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Test Helper Tanggal Indonesia</h2>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Contoh Penggunaan Helper Tanggal</h5>
        </div>
        <div class="card-body">
            <p><strong>Tanggal Asli (format database):</strong> <?= $tanggal_asli ?></p>
            <p><strong>Tanggal Terformat (Indonesia):</strong> <?= $tanggal_terformat ?></p>
            
            <hr>
            
            <h5>Contoh Penggunaan Lain:</h5>
            <?php 
            // Memuat helper (jika belum dimuat di controller)
            helper('tanggal');
            ?>
            
            <p>Tanggal sekarang: <?= format_tanggal_indonesia(date('Y-m-d H:i:s')) ?></p>
            <p>Tanggal spesifik: <?= format_tanggal_indonesia('2025-12-25 08:00:00') ?></p>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>