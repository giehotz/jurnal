/**
 * Monitoring Dashboard Charts
 * Handles the rendering of charts on the Admin Monitoring page.
 */

window.MonitoringCharts = {
    defaults: {
        fontFamily: "'Inter', sans-serif",
        fontColor: '#64748B',
        colors: {
            primary: '#0EA5E9',   // Sky 500
            success: '#10B981',   // Emerald 500
            warning: '#F59E0B',   // Amber 500
            danger: '#EF4444',    // Red 500
            info: '#8B5CF6',      // Violet 500
            secondary: '#64748B', // Slate 500
            dark: '#1E293B'       // Slate 800
        }
    },

    init: function (data) {
        console.log('MonitoringCharts init called with data:', data);

        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            return;
        }

        this.setupDefaults();

        if (data.dailyActivity) {
            this.initDailyActivityChart(data.dailyActivity);
        } else {
            console.warn('No dailyActivity data found');
        }

        if (data.studentAttendance) {
            this.initStudentAttendanceChart(data.studentAttendance);
        } else {
            console.warn('No studentAttendance data found');
        }

        if (data.classAttendance) {
            this.initClassAttendanceChart(data.classAttendance);
        } else {
            console.warn('No classAttendance data found');
        }

        if (data.monthlyTrend) {
            this.initMonthlyTrendChart(data.monthlyTrend);
        } else {
            console.warn('No monthlyTrend data found');
        }
    },

    setupDefaults: function () {
        // Check for Chart.js v2 syntax
        if (Chart.defaults.global) {
            Chart.defaults.global.defaultFontFamily = this.defaults.fontFamily;
            Chart.defaults.global.defaultFontColor = this.defaults.fontColor;
        } else {
            // Chart.js v3+ syntax
            Chart.defaults.font.family = this.defaults.fontFamily;
            Chart.defaults.color = this.defaults.fontColor;
        }
    },

    initDailyActivityChart: function (data) {
        const ctx = document.getElementById('dailyActivityChart');
        if (!ctx) return;

        console.log('Rendering Daily Activity Chart', data);

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: data.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                datasets: [
                    {
                        label: 'Jurnal',
                        data: data.map(item => item.jurnal),
                        borderColor: this.defaults.colors.primary,
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        pointBackgroundColor: this.defaults.colors.primary,
                        pointBorderColor: '#fff',
                        lineTension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Absensi',
                        data: data.map(item => item.absensi),
                        borderColor: this.defaults.colors.success,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        pointBackgroundColor: this.defaults.colors.success,
                        pointBorderColor: '#fff',
                        lineTension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20 } },
                tooltips: {
                    backgroundColor: this.defaults.colors.dark,
                    titleFontFamily: "'Outfit', sans-serif",
                    bodyFontFamily: "'Inter', sans-serif",
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                }
            }
        });
    },

    initStudentAttendanceChart: function (data) {
        const ctx = document.getElementById('studentAttendanceChart');
        if (!ctx) return;

        console.log('Rendering Student Attendance Chart', data);

        // Ensure data properties exist
        const totalHadir = parseInt(data.total_hadir) || 0;
        const totalSakit = parseInt(data.total_sakit) || 0;
        const totalIzin = parseInt(data.total_izin) || 0;
        const totalAlfa = parseInt(data.total_alfa) || 0;

        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                datasets: [{
                    data: [totalHadir, totalSakit, totalIzin, totalAlfa],
                    backgroundColor: [
                        this.defaults.colors.success,
                        this.defaults.colors.warning,
                        this.defaults.colors.primary,
                        this.defaults.colors.danger
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                cutoutPercentage: 70
            }
        });
    },

    initClassAttendanceChart: function (data) {
        const ctx = document.getElementById('classAttendanceChart');
        if (!ctx) return;

        console.log('Rendering Class Attendance Chart', data);

        let classLabels = [];
        let classPercentages = [];

        if (Array.isArray(data) && data.length > 0) {
            classLabels = data.map(item => item.nama_rombel);
            classPercentages = data.map(item => parseFloat(item.avg_persentase).toFixed(1));
        }

        const classColors = classPercentages.map(pct => {
            if (pct < 60) return this.defaults.colors.danger;
            if (pct < 80) return this.defaults.colors.warning;
            return this.defaults.colors.success;
        });

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [{
                    label: 'Persentase Kehadiran (%)',
                    data: classPercentages,
                    backgroundColor: classColors,
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 100,
                            callback: function (value) { return value + "%" },
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return tooltipItem.yLabel + '%';
                        }
                    },
                    backgroundColor: this.defaults.colors.dark,
                    titleFontFamily: "'Outfit', sans-serif",
                    bodyFontFamily: "'Inter', sans-serif",
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                },
                legend: { display: false }
            }
        });
    },

    initMonthlyTrendChart: function (data) {
        const ctx = document.getElementById('monthlyTrendChart');
        if (!ctx) return;

        console.log('Rendering Monthly Trend Chart', data);

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: data.map(item => item.month),
                datasets: [
                    {
                        label: 'Jurnal',
                        data: data.map(item => item.jurnal),
                        borderColor: this.defaults.colors.info,
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        pointBackgroundColor: this.defaults.colors.info,
                        fill: true,
                        lineTension: 0.3
                    },
                    {
                        label: 'Absensi',
                        data: data.map(item => item.absensi),
                        borderColor: this.defaults.colors.secondary,
                        backgroundColor: 'rgba(100, 116, 139, 0.1)',
                        pointBackgroundColor: this.defaults.colors.secondary,
                        fill: true,
                        lineTension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20 } }
            }
        });
    }
};
