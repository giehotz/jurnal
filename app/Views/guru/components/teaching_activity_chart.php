<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">Aktivitas Mengajar Bulanan</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <canvas id="teachingActivityChart" height="300"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data aktivitas mengajar per minggu
    const weeklyActivities = <?= json_encode($weekly_activities ?? []) ?>;
    
    if (Object.keys(weeklyActivities).length > 0) {
        const ctx = document.getElementById('teachingActivityChart').getContext('2d');
        
        // Siapkan data untuk chart
        const weeks = weeklyActivities.map(item => item.week);
        const counts = weeklyActivities.map(item => item.count);
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: weeks.map(week => `Minggu ${week}`),
                datasets: [{
                    label: 'Jumlah Jurnal',
                    data: counts,
                    borderColor: 'rgba(60,141,188,1)',
                    backgroundColor: 'rgba(60,141,188,0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    } else {
        // Tampilkan pesan jika tidak ada data
        document.getElementById('teachingActivityChart').closest('.card-body').innerHTML = 
            '<div class="alert alert-info">Belum ada data aktivitas mengajar bulan ini.</div>';
    }
});
</script>