/**
 * Guru Absensi Scripts - Chart.js v3+ Compatible
 * Handles mobile view toggling and chart initialization.
 */

var GuruAbsensi = {
    init: function (chartData) {
        this.initMobileView();
        if (chartData) {
            this.initChart(chartData);
        }
    },

    initMobileView: function () {
        // Function to toggle between desktop and mobile views
        function toggleMobileView() {
            const isMobile = window.innerWidth < 768;
            const tables = document.querySelectorAll('.table');

            tables.forEach(table => {
                if (isMobile) {
                    convertTableToMobile(table);
                } else {
                    convertTableToDesktop(table);
                }
            });
        }

        // Convert table to mobile view
        function convertTableToMobile(table) {
            if (table.classList.contains('table-mobile-responsive')) return;

            const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    cell.setAttribute('data-label', headers[index]);
                });
            });

            table.classList.add('table-mobile-responsive');
        }

        // Convert table to desktop view
        function convertTableToDesktop(table) {
            table.classList.remove('table-mobile-responsive');

            const cells = table.querySelectorAll('td[data-label]');
            cells.forEach(cell => {
                cell.removeAttribute('data-label');
            });
        }

        // Call on load and resize
        toggleMobileView();
        window.addEventListener('resize', toggleMobileView);
    },

    initChart: function (data) {
        var ctx = document.getElementById('absensiChart');
        if (!ctx) {
            console.warn('absensiChart canvas not found');
            return;
        }

        console.log('Initializing Absensi Chart with data:', data);

        // Check if data is valid
        if (!data.labels || !Array.isArray(data.labels) || data.labels.length === 0) {
            console.warn('No valid chart data available');
            return;
        }

        // Chart.js v3+ syntax
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        label: 'Hadir',
                        data: data.hadir || [],
                        backgroundColor: 'rgba(40, 167, 69, 0.8)', // green
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Izin',
                        data: data.izin || [],
                        backgroundColor: 'rgba(23, 162, 184, 0.8)', // blue
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Sakit',
                        data: data.sakit || [],
                        backgroundColor: 'rgba(255, 193, 7, 0.8)', // yellow
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Alfa',
                        data: data.alfa || [],
                        backgroundColor: 'rgba(220, 53, 69, 0.8)', // red
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { family: "'Outfit', sans-serif", size: 13 },
                        bodyFont: { family: "'Inter', sans-serif", size: 12 },
                        cornerRadius: 8,
                        padding: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            padding: 10
                        },
                        grid: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
};
