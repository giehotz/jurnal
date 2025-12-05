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

        // Hitung total untuk tren line chart
        var totalData = data.hadir.map((val, idx) => {
            return (val || 0) + (data.izin[idx] || 0) + (data.sakit[idx] || 0) + (data.alfa[idx] || 0);
        });

        // Chart.js v3+ syntax - Kombinasi Bar dan Line Chart
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        type: 'bar',
                        label: 'Hadir',
                        data: data.hadir || [],
                        backgroundColor: 'rgba(40, 167, 69, 0.8)', // green
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.6,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'Izin',
                        data: data.izin || [],
                        backgroundColor: 'rgba(23, 162, 184, 0.8)', // blue
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.6,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'Sakit',
                        data: data.sakit || [],
                        backgroundColor: 'rgba(255, 193, 7, 0.8)', // yellow
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.6,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'Alfa',
                        data: data.alfa || [],
                        backgroundColor: 'rgba(220, 53, 69, 0.8)', // red
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.6,
                        yAxisID: 'y'
                    },
                    {
                        type: 'line',
                        label: 'Total Siswa Diinput',
                        data: totalData,
                        borderColor: 'rgba(156, 39, 176, 1)', // purple
                        backgroundColor: 'rgba(156, 39, 176, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(156, 39, 176, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: window.innerWidth < 768 ? 10 : 15,
                            font: { size: window.innerWidth < 768 ? 10 : 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { family: "'Outfit', sans-serif", size: window.innerWidth < 768 ? 11 : 13 },
                        bodyFont: { family: "'Inter', sans-serif", size: window.innerWidth < 768 ? 10 : 12 },
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            afterLabel: function(context) {
                                if (context.datasetIndex < 4) {
                                    var hadir = data.hadir[context.dataIndex] || 0;
                                    var izin = data.izin[context.dataIndex] || 0;
                                    var sakit = data.sakit[context.dataIndex] || 0;
                                    var alfa = data.alfa[context.dataIndex] || 0;
                                    var total = hadir + izin + sakit + alfa;
                                    return 'Total: ' + total;
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: window.innerWidth >= 768,
                            text: 'Jumlah Siswa (Bar)',
                            font: { size: window.innerWidth < 768 ? 9 : 11, weight: 'bold' }
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            padding: 10,
                            font: { size: window.innerWidth < 768 ? 9 : 11 }
                        },
                        grid: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: window.innerWidth >= 768,
                            text: 'Total Diinput (Line)',
                            font: { size: window.innerWidth < 768 ? 9 : 11, weight: 'bold' }
                        },
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            font: { size: window.innerWidth < 768 ? 9 : 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: window.innerWidth < 768 ? 8 : 11 }
                        }
                    }
                }
            }
        });
    }
};
